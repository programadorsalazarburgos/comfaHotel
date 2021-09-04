
// jQuery Gantt Chart
// ==================

// Basic usage:

//      $(".selector").gantt({
//          source: "ajax/data.json",
//          resizable: true,
//          draggable: true,
//          scale: "weeks",
//          minScale: "weeks",
//          maxScale: "months",
//          onItemClick: function(data) {
//              alert("Item clicked - show some details");
//          },
//          onAddClick: function(dt, rowId) {
//              alert("Empty space clicked - add an item!");
//          },
//          onRender: function() {
//              console.log("chart rendered");
//          }
//      });

//
/*jshint shadow:true, unused:false, laxbreak:true, evil:true*/
/*globals jQuery, alert*/
(function($) {

    "use strict";

    $.fn.gantt = function(options) {

        var scales = ["hours", "days", "weeks", "months"];
        //Default settings
        var settings = {
            source: null,
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            dow: ["S", "M", "T", "W", "T", "F", "S"],
            startPos: new Date(),
            waitText: "Please wait...",
            onItemClick: function(data) { return; },
            onAddClick: function(data) { return; },
            onRender: function() { return; },
            scrollToToday: true,
            resizable: false,
            draggable: false,
            onResize: function(data) { return; },
            onDrop: function(data) { return; },
            onDragAdd: function(data) { return; },
            onMouseEnter: function(data) { return; },
            onMouseLeave: function() { return; },
            weektext: "Week",
            dateStart: null,
            dateEnd: null,
            timeOffset: 1
        };

        // Mouse coordinates for drawing on data panel
        var rectpos = {
            startX: 0,
            startY: 0,
            endX: 0,
            endY: 0,
            down: false
        };

        var rect = $("<div>").addClass("rect");


        var clW = 96;
        var clH = 28;

        // custom selector `:findday` used to match on specified day in ms.
        //
        // The selector is passed a date in ms and elements are added to the
        // selection filter if the element date matches, as determined by the
        // id attribute containing a parsable date in ms.
        $.extend($.expr[":"], {
            findday: function(a, i, m) {
                var cd = new Date(parseInt(m[3], 10));
                var id = $(a).attr("id");
                id = id ? id : "";
                var si = id.indexOf("-") + 1;
                var ed = new Date(parseInt(id.substring(si, id.length), 10));
                cd = new Date(cd.getFullYear(), cd.getMonth(), cd.getDate());
                ed = new Date(ed.getFullYear(), ed.getMonth(), ed.getDate());
                return cd.getTime() === ed.getTime();
            }
        });
        // custom selector `:findmonth` used to match on specified month in ms.
        $.extend($.expr[":"], {
            findmonth: function(a, i, m) {
                var cd = new Date(parseInt(m[3], 10));
                cd = cd.getFullYear() + "-" + cd.getMonth();
                var id = $(a).attr("id");
                id = id ? id : "";
                var si = id.indexOf("-") + 1;
                var ed = id.substring(si, id.length);
                return cd === ed;
            }
        });

        // Date prototype helpers
        // ======================

        // `getWeekId` returns a string in the form of 'dh-YYYY-WW', where WW is
        // the week # for the year.
        // It is used to add an id to the week divs
        Date.prototype.getWeekId = function() {
            var y = this.getFullYear();
            var w = this.getDayForWeek().getWeekOfYear();
            var m = this.getMonth();
            if (m === 11 && w === 1) {
                y++;
            }
            return 'dh-' + y + "-" + w;
        };

        // `getDayForWeek` returns the Date object for the starting date of
        // the week # for the year
        Date.prototype.getDayForWeek = function() {
            var df = new Date(this.valueOf());
            df.setDate(df.getDate() - df.getDay());
            var dt = new Date(this.valueOf());
            dt.setDate(dt.getDate() + (6 - dt.getDay()));
            if ((df.getMonth() === dt.getMonth()) || (df.getMonth() !== dt.getMonth() && dt.getDate() >= 4)) {
                return new Date(dt.setDate(dt.getDate() - 3));
            } else {
                return new Date(df.setDate(df.getDate() + 3));
            }
        };

        // `getWeekOfYear` returns the week number for the year
        Date.prototype.getWeekOfYear = function(dowOffset) {
            //return $.datepicker.iso8601Week(this);

            dowOffset = typeof (dowOffset) == 'int' ? dowOffset : 1; //default dowOffset to zero
            var newYear = new Date(this.getFullYear(), 0, 1);
            var day = newYear.getDay() - dowOffset; //the day of week the year begins on
            day = (day >= 0 ? day : day + 7);
            var daynum = Math.floor((this.getTime() - newYear.getTime() - (this.getTimezoneOffset() - newYear.getTimezoneOffset()) * 60000) / 86400000) + 1;
            var weeknum;
            //if the year starts before the middle of a week
            if (day < 4) {
                weeknum = Math.floor((daynum + day - 1) / 7) + 1;
                if (weeknum > 52) {
                    var nYear = new Date(this.getFullYear() + 1, 0, 1);
                    var nday = nYear.getDay() - dowOffset;
                    nday = nday >= 0 ? nday : nday + 7;
                    /*if the next year starts before the middle of
                    the week, it is week #1 of that year*/
                    weeknum = nday < 4 ? 1 : 53;
                }
            }
            else {
                weeknum = Math.floor((daynum + day - 1) / 7);
            }
            return weeknum;
        };

        // `getDayOfYear` returns the day number for the year
        Date.prototype.getDayOfYear = function() {
            var fd = new Date(this.getFullYear(), 0, 0);
            var sd = new Date(this.getFullYear(), this.getMonth(), this.getDate());
            return Math.ceil((sd - fd) / 86400000);
        };

        // `getRepDate` returns the seconds since the epoch for a given date
        // depending on the active scale
        Date.prototype.genRepDate = function() {
            return this.getTime();
        };

        // Grid management
        // ===============

        // Core object is responsible for navigation and rendering
        var core = {
            positionFromPoint: function(panel, x, y, cellWidth, cellHeight, increment) {
                var panelOffset = panel.offset();

                var cellX = x - panelOffset.left;
                var cellY = y - panelOffset.top;

                var cellX = Math.floor(cellX / cellWidth);
                var cellY = Math.floor(cellY / cellHeight);

                if (increment) { cellX += 1; cellY += 1; }

                cellX = cellX * cellWidth;
                cellY = cellY * cellHeight;

                return { cellX: cellX, cellY: cellY }
            },

            // Return the element whose topmost point lies under the given point
            // Normalizes for IE
            elementFromPoint: function(x, y) {

                var pageXOff = (window.pageXOffset !== undefined) ? window.pageXOffset : $(document).scrollLeft();
                var pageYOff = (window.pageYOffset !== undefined) ? window.pageYOffset : $(document).scrollTop();

                x -= pageXOff;
                y -= pageYOff;

                return document.elementFromPoint(x, y);
            },

            elementFromDrag: function(elmX, elmWidth, cellWidth) {
                var corrY;
                var rowIndex = 1; // row with index 1 is row where he wave days and they have attribute with date

                var rowPanelRow = $(".fn-gantt .rightPanel .dataPanel .rowPanel .row:nth-child(2)");

                // shift x coord by half
                elmX -= clW / 2;

                elmX = parseInt(elmX);

                var colStart = rowPanelRow.find("div[offset=" + elmX + "]");
                var colEnd = rowPanelRow.find("div[offset=" + (elmX + (elmWidth + 1)) + "]");

                return { colStart: colStart, colEnd: colEnd };
            },

            barIntersects: function(bar) {
                var intersects = false;

                // get position and size of current element
                var meOffset = bar.offset();
                var x1 = meOffset.left;
                var x2 = x1 + bar.width();
                var y1 = meOffset.top;
                var y2 = y1 + bar.height();

                // loop each item and check if it intersects
                $.each(bar.parent().find(".bar"), function(i, item) {
                    // don't check for me own
                    if ($(item).data("dataObj") != bar.data("dataObj")) {
                        var item = $(item);
                        var offset = item.offset();

                        var ax1 = offset.left;
                        var ax2 = ax1 + item.width();
                        var ay1 = offset.top;
                        var ay2 = ay1 + item.height();

                        // check if item is inside rectangle (intersects)
                        if (ax1 <= x2 && x1 <= ax2 && ay1 <= y2 && y1 <= ay2) {
                            // it intersects, stop all and revert action
                            intersects = true;
                            return false;
                        }
                    }
                });

                return intersects;
            },

            // **Create the chart**
            create: function(element) {

                // Initialize data with a json object or fetch via an xhr
                // request depending on `settings.source`
                if (typeof settings.source !== "string") {
                    element.data = settings.source;
                    core.init(element);
                } else {
                    $.getJSON(settings.source, function(jsData) {
                        element.data = jsData;
                        core.init(element);
                    });
                }
            },

            // **Setup the initial view**
            // Here we calculate the number of rows, pages and visible start
            // and end dates once the data is ready
            init: function(element) {
                element.rowsNum = element.data.length;
                element.pageCount = 1;
                element.rowsOnLastPage = element.rowsNum;

                // get min required max date - we use this to fill calendar if data is not enough
                var minDays = Math.floor(($(element).width() - 240) / clW) - 2;

                element.dateStart = tools.getMinDate(element, settings.dateStart);
                element.dateEnd = tools.getMaxDate(element, settings.dateEnd);

                //console.log(element.dateStart);
                //console.log(element.dateEnd);

                var minDaysDef = Math.floor((element.dateEnd - element.dateStart) / 86400000);

                // if we would show less days than they fit inside - 3 is padding from original start date
                if (minDaysDef < minDays) {
                    element.dateEnd = new Date(element.dateStart.getTime());
                    element.dateEnd.setDate(element.dateEnd.getDate() + minDays);
                }

                /* core.render(element); */
                core.waitToggle(element, true, function() { core.render(element); });

            },

            // **Render the grid**
            render: function(element) {
                var content = $('<div class="fn-content"/>');
                var $leftPanel = core.leftPanel(element);
                content.append($leftPanel);
                var $rightPanel = core.rightPanel(element, $leftPanel);
                var mLeft, hPos;

                content.append($rightPanel);

                var $dataPanel = $rightPanel.find(".dataPanel");

                // $dataPanel.append($leftPanel);

                element.gantt = $('<div class="fn-gantt" />').append(content);

                $(element).html(element.gantt);

                core.markNow(element);
                core.fillData(element, $dataPanel, $leftPanel);

                $dataPanel.addClass("cf");

                $rightPanel.scroll(function() {
                    var leftWrapper = $(this).parent().find(".leftPanelWrapper");
                    var rowPanel = $(this).find(".rowPanel");
                    leftWrapper.css("margin-top", $(this).scrollTop() * -1);
                    rowPanel.css("top", $(this).scrollTop());
                    $("#ganttruler").css("top", $(this).scrollTop() + 84);
                });

                // adjust grid
                var firstDateDay = element.dateStart.getDay();
                var dayOffset = (firstDateDay >= 0 ? firstDateDay : firstDateDay + 7);
                dayOffset = (dayOffset * -96) + 48;
                $(".dataPanel").css("background-position", dayOffset + "px 28px");

                core.waitToggle(element, false);
                settings.onRender();
            },

            // Create and return the left panel with labels
            leftPanel: function(element) {
                /* Left panel */
                var ganttLeftPanel = $('<div class="leftPanel"/>');
                var leftPanelWrapper = $('<div class="leftPanelWrapper cf" />');

                var panelSpacerHeight = clH * element.headerRows;
                var panelSpacer = $('<div class="row spacer"/>').css("height", (panelSpacerHeight - 1) + "px");
                leftPanelWrapper.append(panelSpacer).css("padding-top", panelSpacerHeight);


                var entries = [];
                $.each(element.data, function(i, entry) {
                    entries.push('<div class="row name row' + i + (entry.desc ? '' : ' fn-wide') + '" id="rowheader' + i + '" offset="' + i * clH + '">');
                    entries.push('<span class="fn-label' + (entry.cssClass ? ' ' + entry.cssClass : '') + '">' + entry.name + '</span>');
                    entries.push('</div>');

                    if (entry.desc) {
                        entries.push('<div class="row desc row' + i + ' " id="RowdId_' + i + '" data-id="' + entry.id + '">');
                        entries.push('<span class="fn-label' + (entry.cssClass ? ' ' + entry.cssClass : '') + '"><span title="' + entry.VacState + '" class="block back' + entry.VacClr + '">&nbsp;</span><span title="' + entry.HKState + '" class="block back' + entry.HKClr + '">&nbsp;</span>&nbsp;' + entry.desc + '</span>');
                        entries.push('</div>');
                    }
                });
                leftPanelWrapper.append(entries.join(""));
                return ganttLeftPanel.append(leftPanelWrapper);
            },

            // Create and return the data panel element
            dataPanel: function(element, width, height) {
                var dataPanel = $('<div class="dataPanel" style="width: ' + width + 'px; height: ' + height + 'px"/>');

                // Handle click events and dispatch to registered `onAddClick`
                // function
                dataPanel.click(function(e) {

                    e.stopPropagation();
                    var corrX, corrY;
                    var leftpanel = $(element).find(".fn-gantt .leftPanel");
                    var datapanel = $(element).find(".fn-gantt .dataPanel");

                    corrY = clH * 3;

                    // Find column where click occurred
                    var col = core.elementFromPoint(e.pageX, datapanel.offset().top + corrY);

                    // if we did find column
                    if (!col == null) {
                        // Was the label clicked directly?
                        if (col.className === "fn-label") {
                            col = $(col.parentNode);
                        } else {
                            col = $(col);
                        }

                        var dt = col.attr("repdate");
                        // Find row where click occurred
                        var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, e.pageY);
                        // Was the lable clicked directly?
                        if (row.className.indexOf("fn-label") === 0) {
                            row = $(row.parentNode);
                        } else {
                            row = $(row);
                        }
                        var rowId = row.data().id;

                        // Dispatch user registered function with the DateTime in ms
                        // and the id if the clicked object is a row
                        settings.onAddClick(dt, rowId);
                    }
                });

                // attach required events for drag-to-draw rectangle
                dataPanel.mousedown(function(e) {
                    rectpos.startX = e.pageX;
                    rectpos.startY = e.pageY;
                    rectpos.down = true;
                    dataPanel.append(rect)

                    // find actual position
                    var pos1 = core.positionFromPoint(dataPanel, rectpos.startX, rectpos.startY, clW, clH);

                    // set rect to actual position
                    rect.css("margin-top", pos1.cellY + 2);
                    rect.css("margin-left", pos1.cellX + (clW / 2));
                    rect.css("width", clW);
                    rect.text("");

                    e.preventDefault();

                })

                dataPanel.mousemove(function(e) {
                    if (rectpos.down) {
                        e.preventDefault();

                        rectpos.endX = e.pageX;
                        rectpos.endY = e.pageY;

                        var pos1 = core.positionFromPoint(dataPanel, rectpos.startX, rectpos.startY, clW, clH);
                        var pos2 = core.positionFromPoint(dataPanel, rectpos.endX, rectpos.endY, clW, clH, true);

                        var rectWidth = pos2.cellX - pos1.cellX;
                        rect.css("width", rectWidth);

                        var valueS = parseInt(rect.css('margin-left'));
                        var valueE = parseInt(rect.css('margin-top'));

                        // get back dates from object
                        var result = core.elementFromDrag(valueS, parseInt(rect.width() - 1), clW);

                        // change data object
                        var newDataObj = {
                            from: new Date(parseFloat(result.colStart.attr("repdate"))),
                            to: new Date(parseFloat(result.colEnd.attr("repdate")))
                        }

                        var rectfrom = "";
                        var rectto = "";

                        if (rectWidth <= 48) {
                            rectfrom = newDataObj.from.getDate() + ".";
                            rectto = "";
                        } else if (rectWidth <= 96) {
                            rectfrom = newDataObj.from.getDate() + ". - ";
                            rectto = newDataObj.to.getDate() + ".";
                        } else {
                            rectfrom = newDataObj.from.getDate() + "." + newDataObj.from.getMonth() + ". - ";
                            rectto = newDataObj.to.getDate() + "." + newDataObj.from.getMonth() + ".  (" + rectWidth / 96 + ")";
                        }

                        rect.text(rectfrom + rectto);

                    }
                })

                dataPanel.mouseup(function(e) {
                    if (rectpos.down) {

                        rectpos.down = false;

                        var valueS = parseInt(rect.css('margin-left'));
                        var valueE = parseInt(rect.css('margin-top'));

                        // get back dates from object
                        var result = core.elementFromDrag(valueS, parseInt(rect.width() - 1), clW);

                        // Find row where click occurred
                        var leftpanel = $(".fn-gantt .leftPanel");
                        var epageY = leftpanel.offset().top + (dataPanel.offset().top - 96) + valueE;
                        var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, epageY);
                        // Was the lable clicked directly?
                        if (row.className.indexOf("fn-label") === 0) {
                            row = $(row.parentNode);
                        } else {
                            row = $(row);
                        }
                        var rowId = row.data().id;

                        // change data object
                        var newDataObj = {
                            from: "\/Date(" + result.colStart.attr("repdate") + ")",
                            to: "\/Date(" + result.colEnd.attr("repdate") + ")",
                            rowId: rowId
                        }

                        // raise event - with dates and room id
                        settings.onDragAdd(newDataObj, e);

                        // remove drawn rectangle
                        rect.remove();
                    }
                })


                return dataPanel;
            },

            // Creates and return the right panel containing the year/week/day
            // header
            rightPanel: function(element, leftPanel) {
                var range = null;
                // Days of the week have a class of one of
                // `sn` (Saturday), `sa` (Sunday), or `wd` (Weekday)
                var dowClass = [" sn", " wd", " wd", " wd", " wd", " wd", " sa"];
                var gridDowClass = [" sn", "", "", "", "", "", " sa"];

                var yearArr = ['<div class="row"/>'];
                var daysInYear = 0;

                var monthArr = ['<div class="row"/>'];
                var daysInMonth = 0;

                var weekArr = ['<div class="row"/>'];
                var daysInWeek = 0;

                var dayArr = [];

                var dowArr = [];


                var today = new Date();
                today = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                var holidays = settings.holidays ? settings.holidays.join() : '';

                // Setup the headings
                // **Days (default)**
                range = tools.parseDateRange(element.dateStart, element.dateEnd);

                var cellSize = clW;

                var year = range[0].getFullYear();
                var month = range[0].getMonth();
                var week = range[0].getWeekOfYear();
                var weekmonth = range[0].getMonth();
                var day = range[0];

                for (var i = 0; i < range.length; i++) {
                    var rday = range[i];

                    // Fill years
                    if (rday.getFullYear() !== year) {
                        yearArr.push(
								('<div class="row header year" style="width:'
									+ cellSize * daysInYear
									+ 'px;"><div class="fn-label">'
									+ year
									+ '</div></div>'));
                        year = rday.getFullYear();
                        daysInYear = 0;
                    }
                    daysInYear++;


                    // Fill months
                    if (rday.getMonth() !== month) {
                        monthArr.push(
								('<div class="row header month" style="width:'
								   + (cellSize * daysInMonth)
								   + 'px;"><div class="fn-label">'
								   + settings.months[month]
								   + ', ' + year
								   + '</div></div>'));
                        month = rday.getMonth();
                        daysInMonth = 0;
                    }
                    daysInMonth++;

                    // Fill weeks
                    if (rday.getWeekOfYear() !== week) {
                        weekArr.push(
								('<div class="row header year" style="width:'
									+ cellSize * daysInWeek
									+ 'px;"><div class="fn-label">'
								    + settings.months[weekmonth]
								    + ', ' + year + ', '
									+ settings.weektext + ' ' + week
									+ '</div></div>'));
                        week = rday.getWeekOfYear();
                        weekmonth = rday.getMonth();
                        daysInWeek = 0;
                    }
                    daysInWeek++;

                    var getDay = rday.getDay();
                    var day_class = dowClass[getDay];
                    if (holidays.indexOf((new Date(rday.getFullYear(), rday.getMonth(), rday.getDate())).getTime()) > -1) {
                        day_class = "holiday";
                    }

                    var dayF = (rday.getDate() < 10) ? "0" + rday.getDate() : rday.getDate();
                    var monthF = ((month + 1) < 10) ? "0" + (month + 1) : (month + 1);

                    dayArr.push('<div onclick="drawCalendar(' + rday.getDate() + ', ' + (rday.getMonth() + 1) + ', ' + rday.getFullYear() + ')" class="row date ' + day_class + '" '
								+ ' id="dh-' + tools.genId(rday.getTime()) + '" offset="' + (i * cellSize) + '" repdate="' + rday.genRepDate() + '"> '
								+ ' <div class="fn-label" id="date_' + dayF + '_' + monthF + '">' + dayF + '.' + monthF + '</div></div>');
                    dowArr.push('<div onclick="drawCalendar(' + rday.getDate() + ', ' + (rday.getMonth() + 1) + ', ' + rday.getFullYear() + ')" class="row day ' + day_class + '" '
								+ ' id="dw-' + tools.genId(rday.getTime()) + '"  repdate="' + rday.genRepDate() + '"> '
								+ ' <div class="fn-label" id="day_' + dayF + '_' + monthF + '">' + settings.dow[getDay] + '</div></div>');
                } //for


                // Last year
                yearArr.push(
						'<div class="row header year" style="width: '
						+ cellSize * daysInYear + 'px;"><div class="fn-label">'
						+ year
						+ '</div></div>');

                // Last month
                monthArr.push(
						'<div class="row header month" style="width: '
						+ cellSize * daysInMonth + 'px"><div class="fn-label">'
						+ settings.months[month]
                        + ', ' + year
						+ '</div></div>');

                // Last week
                weekArr.push(
						'<div class="row header year" style="width: '
						+ cellSize * daysInWeek + 'px;"><div class="fn-label">'
				        + settings.months[weekmonth]
				        + ', ' + year + ', '
					    + settings.weektext + ' ' + week
						+ '</div></div>');

                // yearArr = weekArr;



                var dataPanel = core.dataPanel(element, range.length * cellSize, (settings.source.length + 3) * clH);

                var rowPanel = $('<div class="rowPanel"></div>');
                // set large z-index to be above bars
                var maxZIndex = 0;
                $.each(settings.source, function(i, itm) {
                    $.each(itm.values, function(j, val) {
                        maxZIndex += 1;
                    });
                });
                //rowPanel.css("z-index", (maxZIndex * 10) + 1);
                rowPanel.css("z-index", maxZIndex + 1);
                // var rightPanel = $('<div class="rightPanel"></div>');

                // Append panel elements
                // rowPanel.append($('<div class="row" style="margin-left:0px"/>').html(yearArr.join("")));
                // rowPanel.append($('<div class="row" style="margin-left:0px"/>').html(monthArr.join("")));
                rowPanel.append($('<div class="row" style="margin-left:0px"/>').html(weekArr.join("")));
                rowPanel.append($('<div class="row" style="margin-left:0px"/>').html(dayArr.join("")));
                rowPanel.append($('<div class="row" style="margin-left:0px"/>').html(dowArr.join("")));


                dataPanel.append(rowPanel);
                dataPanel.append("<div id=\"ganttruler\">&nbsp;<\/div>");

                return $('<div class="rightPanel"></div>').append(dataPanel);
            },

            // **Progress Bar**
            // Return an element representing a progress of position within
            // the entire chart
            createProgressBar: function(days, cls, desc, label, dataObj) {
                var cellWidth = clW;

                var barMarg = tools.getProgressBarMargin() || 0;
                var commentsclass = (dataObj.comments) ? " fn-comments" : "";
                var customLabel = (dataObj.customLabel) ? " style=\"background-color:" + dataObj.customLabel + "\"" : "";

                var bar = $('<div class="bar"' + customLabel + '><div class="fn-label' + commentsclass + '">' + label + '</div></div>')
                        .addClass(cls)
                        .css({
                            width: ((cellWidth * days) - barMarg) + 5
                        })
                        .data("dataObj", dataObj);

                var isGray = bar.hasClass("ganttGray");
                var isTrans = bar.hasClass("ganttTrans");
                var isFlat = bar.hasClass("ganttFlat");

                // skip processing bars that are not real bars
                if (isTrans) {
                    bar.mousedown(function(e) {
                        e.stopPropagation();
                    });
                    bar.click(function(e) {
                        e.stopPropagation();
                    });
                    bar.mouseenter(function(e) {
                        e.stopPropagation();
                    });
                    bar.mouseleave(function(e) {
                        e.stopPropagation();
                    });

                    return bar;
                }

                if (desc) {
                    bar
                      .mouseover(function(e) {
                          var hint = $('<div class="fn-gantt-hint" />').html(desc);
                          $("body").append(hint);
                          hint.css("left", e.pageX);
                          hint.css("top", e.pageY);
                          hint.show();
                      })
                      .mouseout(function() {
                          $(".fn-gantt-hint").remove();
                      })
                      .mousemove(function(e) {
                          $(".fn-gantt-hint").css("left", e.pageX);
                          $(".fn-gantt-hint").css("top", e.pageY + 15);
                      });
                }

                bar.mousedown(function(e) {
                    e.stopPropagation();

                    // hide tooltip if it's shown - or even exists
                    $("#toolinfo").hide();

                    // clear timer
                    if (timer) { clearTimeout(timer); timer = null; }
                });

                bar.click(function(e) {
                    e.stopPropagation();

                    // Find row where click occurred
                    var leftpanel = $(".fn-gantt .leftPanel");
                    var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, e.pageY);
                    // Was the lable clicked directly?
                    if (row.className.indexOf("fn-label") === 0) { row = $(row.parentNode); } else { row = $(row); }
                    var rowId = row.data().id;

                    var newDataObj = $(this).data("dataObj");
                    newDataObj.rowId = rowId;

                    settings.onItemClick(newDataObj, e);
                });

                bar.mouseenter(function(e) {
                    var eX = e.pageX; var eY = e.pageY;

                    e.stopPropagation();

                    // Find row where click occurred
                    var leftpanel = $(".fn-gantt .leftPanel");
                    var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, e.pageY);
                    // Was the lable clicked directly?
                    if (row.className.indexOf("fn-label") === 0) { row = $(row.parentNode); } else { row = $(row); }
                    var rowId = row.data().id;

                    var newDataObj = $(this).data("dataObj");
                    newDataObj.rowId = rowId;

                    e.pageX = eX - $(".dataPanel").offset().left;
                    e.pageY = eY - $(".dataPanel").offset().top;

                    settings.onMouseEnter(newDataObj, e);
                });

                bar.mouseleave(function(e) {
                    e.stopPropagation();

                    settings.onMouseLeave(e);
                });

                if (settings.resizable && !isGray && !isTrans && !isFlat) {
                    bar.resizable({
                        grid: [cellWidth, cellWidth],
                        handles: "e",
                        start: function(event, ui) {

                            $(this).data("startx", $(this).offset().left);
                            $(this).data("starty", $(this).offset().top);
                            $(this).data("marginx", $(this).css('margin-left'));
                            $(this).data("marginy", $(this).css('margin-top'));
                        },
                        stop: function(event, ui) {
                            // check if we intersect
                            var intersects = core.barIntersects(bar);

                            if (core.isItemMoved(settings.source, $(this).data("dataObj"))) intersects = true;

                            if (intersects) {
                                $(this).css(ui.originalSize);

                                // return back to initial position
                                $(this).css('margin-left', parseInt($(this).data("marginx")));
                                $(this).css('left', '');

                                $(this).css('margin-top', parseInt($(this).data("marginy")));
                                $(this).css('top', '');

                            } else {
                                var change = $(this).offset().left - $(this).data("startx");
                                var value = parseInt($(this).css('margin-left')) + change;
                                $(this).css('margin-left', value);
                                $(this).css('left', '');

                                // get back dates from object
                                var result = core.elementFromDrag(value, parseInt($(this).width()), cellWidth);

                                // Find row where click occurred
                                var leftpanel = $(".fn-gantt .leftPanel");
                                var dataPanel = $(".fn-gantt .dataPanel");
                                var epageY = leftpanel.offset().top + (dataPanel.offset().top - 96) + parseInt($(this).css("margin-top"));
                                var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, epageY);
                                // Was the lable clicked directly?
                                if (row.className.indexOf("fn-label") === 0) {
                                    row = $(row.parentNode);
                                } else {
                                    row = $(row);
                                }
                                var rowId = row.data().id;


                                // change data object
                                var newDataObj = $(this).data("dataObj");
                                newDataObj.from = "\/Date(" + result.colStart.attr("repdate") + ")";
                                newDataObj.to = "\/Date(" + result.colEnd.attr("repdate") + ")";
                                newDataObj.rowId = rowId;

                                $(this).data("dataObj", newDataObj);

                                settings.onResize($(this).data("dataObj"), ui);
                            }

                        }
                    });
                }

                if (settings.draggable && !isGray && !isTrans && !isFlat) {
                    bar.draggable({
                        grid: [clW, clH],
                        revert: function(event) {
                            var intersects = core.barIntersects(bar);

                            // mark taht element was reverted, needed for stop event
                            bar.data("reverted", intersects);

                            return intersects;
                        },
                        start: function(event, ui) {
                            if (core.isItemMoved(settings.source, $(this).data("dataObj"))) return false;

                            //if () return false;
                            $(this).data("startx", $(this).offset().left);
                            $(this).data("starty", $(this).offset().top);
                            $(this).data("marginx", $(this).css('margin-left'));
                            $(this).data("marginy", $(this).css('margin-top'));
                            $(this).data("reverted", false);
                        },
                        stop: function(event, ui) {
                            if (bar.data("reverted") == true) {
                                // return back to initial position
                                $(this).css('margin-left', parseInt($(this).data("marginx")));
                                $(this).css('left', '');

                                $(this).css('margin-top', parseInt($(this).data("marginy")));
                                $(this).css('top', '');
                            } else {

                                var changeS = $(this).offset().left - $(this).data("startx");
                                var valueS = parseInt($(this).css('margin-left')) + changeS;
                                $(this).css('margin-left', valueS);
                                $(this).css('left', '');

                                var changeE = $(this).offset().top - $(this).data("starty");
                                var valueE = parseInt($(this).css('margin-top')) + changeE;
                                $(this).css('margin-top', valueE);
                                $(this).css('top', '');

                                // get back dates from object
                                var result = core.elementFromDrag(valueS, parseInt($(this).width()), cellWidth);

                                // Find row where click occurred
                                var leftpanel = $(".fn-gantt .leftPanel");
                                var dataPanel = $(".fn-gantt .dataPanel");
                                var epageY = leftpanel.offset().top + (dataPanel.offset().top - 96) + parseInt($(this).css("margin-top"));
                                var row = core.elementFromPoint(leftpanel.offset().left + leftpanel.width() - 10, epageY);
                                // Was the lable clicked directly?
                                if (row.className.indexOf("fn-label") === 0) {
                                    row = $(row.parentNode);
                                } else {
                                    row = $(row);
                                }
                                var rowId = row.data().id;

                                // change data object
                                var newDataObj = $(this).data("dataObj");
                                newDataObj.from = "\/Date(" + result.colStart.attr("repdate") + ")";
                                newDataObj.to = "\/Date(" + result.colEnd.attr("repdate") + ")";
                                newDataObj.rowId = rowId;

                                $(this).data("dataObj", newDataObj);

                                // raise event after droping object
                                settings.onDrop($(this).data("dataObj"), ui);
                            }
                        }
                    });
                }


                return bar;
            },

            // Remove the `wd` (weekday) class and add `today` class to the
            // current day/week/month (depending on the current scale)
            markNow: function(element) {
                var cdd = new Date();
                var dayF = (cdd.getDate() < 10) ? "0" + cdd.getDate() : cdd.getDate();
                var monthF = ((cdd.getMonth() + 1) < 10) ? "0" + (cdd.getMonth() + 1) : (cdd.getMonth() + 1);

                var dateElement = $("#date_" + dayF + "_" + monthF);
                var dayElement = $("#day_" + dayF + "_" + monthF);

                if ($("#day_" + dayF + "_" + monthF).length) {
                    var leftToday = dayElement.position().left;

                    dayElement.parent().removeClass("wd").addClass("today");
                    dateElement.parent().removeClass("wd").addClass("today");

                    $("#ganttruler").css("left", leftToday + 48);
                }

                /*
                var addHours = cdd.getTimezoneOffset() / 60; // (settings.timeOffset * -1);
                cdd.setHours(cdd.getHours() + addHours + settings.timeOffset);
                //console.log(addHours + " - " + cdd);
                //var cd = Date.parse(new Date());
                var cd = Date.parse(cdd);
                cd = (Math.floor(cd / 36400000) * 36400000);
                var todayElm = $(element).find(':findday("' + cd + '")');
                // console.log(todayElm.length);
                var todayPos = 0;
                if (todayElm.length != 0) todayPos = todayElm.removeClass('wd').addClass('today').position().left;
                $("#ganttruler").css("left", leftToday + 48);
                */
            },

            // **Fill the Chart**
            // Parse the data and fill the data panel
            fillData: function(element, datapanel, leftpanel) {
                var invertColor = function(colStr) {
                    try {
                        colStr = colStr.replace("rgb(", "").replace(")", "");
                        var rgbArr = colStr.split(",");
                        var R = parseInt(rgbArr[0], 10);
                        var G = parseInt(rgbArr[1], 10);
                        var B = parseInt(rgbArr[2], 10);
                        var gray = Math.round((255 - (0.299 * R + 0.587 * G + 0.114 * B)) * 0.9, 1);
                        return "rgb(" + gray + ", " + gray + ", " + gray + ")";
                    } catch (err) {
                        return "";
                    }
                };
                // Loop through the values of each data element and set a row
                $.each(element.data, function(i, entry) {
                    $.each(entry.values, function(j, day) {
                        var _bar = null;

                        // **Days**
                        var dFrom = tools.genId(tools.dateDeserialize(day.from).getTime());
                        var dTo = tools.genId(tools.dateDeserialize(day.to).getTime());

                        // var dFromEx = parseFloat(day.from.replace("/Date(", "").replace(")/", ""));
                        // var dToEx = parseFloat(day.to.replace("/Date(", "").replace(")/", ""));

                        var from = $(element).find("#dh-" + dFrom);
                        var cFrom = from.attr("offset");

                        var dl = Math.floor(((dTo / 1000) - (dFrom / 1000)) / 86400) + 1;
                        dl = day.nights;

                        _bar = core.createProgressBar(
									dl,
									day.customClass ? day.customClass : "",
									day.desc ? day.desc : "",
									day.label ? day.label : "",
									day ? day : null,
									day.customLabel ? day.customLabel : ""
							);

                        // find row
                        var topEl = $(element).find("#rowheader" + i);

                        var top = clH * 3 + 2 + parseInt(topEl.attr("offset"), 10);
                        if (_bar.hasClass("ganttTrans")) {
                            top = top - 2;
                            _bar.css({ 'margin-top': top, 'margin-left': Math.floor(cFrom) });
                        } else if (_bar.hasClass("ganttFlat")) {
                            top = top - 2;
                            _bar.css({ 'margin-top': top, 'margin-left': Math.floor(cFrom) + (clW / 2) });
                        } else {
                            _bar.css({ 'margin-top': top, 'margin-left': Math.floor(cFrom) + (clW / 2) });
                        }

                        datapanel.append(_bar);

                    });
                });
            },

            // check if any element is moved, don't allow further changes until move is confirmed or cancelled
            isItemMoved: function(element, itm) {
                var result = false;

                $.each(element, function(i, entry) {
                    $.each(entry.values, function(i, item) {
                        if (item.Moved) {
                            if (item != itm) {
                                result = true;
                                return true;
                            }
                        }
                    });
                });

                return result;
            },

            // Reposition data labels
            repositionLabel: function(element) {
                setTimeout(function() {
                    var $dataPanel;
                    if (!element) {
                        $dataPanel = $(".fn-gantt .rightPanel .dataPanel");
                    } else {
                        var $rightPanel = $(element).find(".fn-gantt .rightPanel");
                        $dataPanel = $rightPanel.find(".dataPanel");
                    }
                }, 500);
            },

            // waitToggle
            waitToggle: function(element, show, fn) {
                if (show) {
                    var eo = $(element).offset();
                    var ew = $(element).outerWidth();
                    var eh = $(element).outerHeight();

                    if (!element.loader) {
                        element.loader = $('<div class="fn-gantt-loader" style="position: absolute; top: ' + eo.top + 'px; left: ' + eo.left + 'px; width: ' + ew + 'px; height: ' + eh + 'px;">'
                        + '<div class="fn-gantt-loader-spinner"><span>' + settings.waitText + '</span></div></div>');
                    }
                    $("body").append(element.loader);
                    setTimeout(fn, 100);

                } else {
                    if (element.loader) {
                        element.loader.remove();
                    }
                    element.loader = null;
                }
            }
        };

        // Utility functions
        // =================
        var tools = {

            // Return the maximum available date in data depending on the scale
            getMaxDate: function(element, def) {
                var maxDate = null;
                $.each(element.data, function(i, entry) {
                    $.each(entry.values, function(i, date) {
                        var dsDate = tools.dateDeserialize(date.to);
                        maxDate = maxDate < dsDate ? dsDate : maxDate;
                    });
                });

                // comment this ?!? we usually get wierd results if far in future
                maxDate = null;

                if (maxDate == null) { maxDate = def; }

                // console.log(maxDate);

                if (maxDate == null) {
                    var today = new Date();
                    maxDate = new Date(today.setDate(today.getDate() + 30));
                }

                maxDate.setHours(0);
                // maxDate.setDate(maxDate.getDate() + 3);
                maxDate.setDate(maxDate.getDate() + 1);

                return maxDate;
            },

            // Return the minimum available date in data depending on the scale
            getMinDate: function(element, def) {
                var minDate = null;
                $.each(element.data, function(i, entry) {
                    $.each(entry.values, function(i, date) {
                        var dsDate = tools.dateDeserialize(date.from);
                        minDate = minDate > dsDate || minDate === null ? dsDate : minDate;
                    });
                });

                // comment this ?!? we usually get wierd results if far in future
                if (minDate > def) minDate = null;

                if (minDate == null) { minDate = def; }
                if (minDate == null) { minDate = new Date(); }

                // uncoomment this, it's related to above minDate = null;
                // if (minDate > new Date()) { minDate = new Date(); }

                minDate.setHours(0);
                // minDate.setDate(minDate.getDate() - 3);
                minDate.setDate(minDate.getDate());

                return minDate;
            },
            daylightSavingAdjust: function(date) {
                if (!date) return null;
                date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
                return date;
            },

            // Return an array of Date objects between `from` and `to`
            parseDateRange: function(from, to) {
                var current = new Date(from.getTime());
                var end = new Date(to.getTime());
                var ret = [];
                var i = 0;
                do {
                    // ret[i++] = tools.daylightSavingAdjust(new Date(current.getTime()));
                    ret[i++] = new Date(current.getTime());
                    current.setDate(current.getDate() + 1);
                } while (current.getTime() <= to.getTime());
                return ret;

            },

            // Return an array of Date objects between `from` and `to`,
            // scaled hourly
            parseTimeRange: function(from, to, scaleStep) {
                var current = new Date(from);
                var end = new Date(to);
                var ret = [];
                var i = 0;
                do {
                    ret[i] = new Date(current.getTime());
                    current.setHours(current.getHours() + scaleStep);
                    current.setHours(Math.floor((current.getHours()) / scaleStep) * scaleStep);

                    if (current.getDay() !== ret[i].getDay()) {
                        current.setHours(0);
                    }

                    i++;
                } while (current.getTime() <= to.getTime());
                return ret;
            },

            // Return an array of Date objects between a range of months
            // between `from` and `to`
            parseMonthsRange: function(from, to) {

                var current = new Date(from);
                var end = new Date(to);

                var ret = [];
                var i = 0;
                do {
                    ret[i++] = new Date(current.getFullYear(), current.getMonth(), 1);
                    current.setMonth(current.getMonth() + 1);
                } while (current.getTime() <= to.getTime());

                return ret;
            },

            // Deserialize a date from a string
            dateDeserialize: function(dateStr) {
                var floatmilliseconds = parseFloat(dateStr.replace("/Date(", "").replace(")/", ""));


                var date = parseInt(floatmilliseconds, 10);
                return new Date(date);

                var dt = new Date(floatmilliseconds);
                return new Date(tools.toUTC(dt));
                // console.log(dt);
                // console.log(floatmilliseconds);
                // console.log(tools.toUTC(dt));

                return new Date(floatmilliseconds);

                /*
                // maknuo jer je bilo sporo
                var date = eval("new" + dateStr.replace(/\//g, " "));
                return date;
                */
                // maknuo jer ne radimo sa vremenskim zonama ?!?
                //return new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes());
            },

            // Generate an id for a date
            genId: function(ticks) {
                var t = new Date(ticks);
                return (new Date(t.getFullYear(), t.getMonth(), t.getDate())).getTime();
            },
            // Get the current size of the rigth panel
            getRightPanelSize: function() {
                $("body").append(
                    $('<div style="display: none; position: absolute;" class="fn-gantt" id="measureCellWidth"><div class="rightPanel"></div></div>')
                );
                var ret = $("#measureCellWidth .rightPanel").height();
                $("#measureCellWidth").empty().remove();
                return ret;
            },
            //converts local time to UTC (Universal Time)
            toUTC: function(date) {
                return Date.UTC(
                    date.getFullYear()
                    , date.getMonth()
                    , date.getDate()
                    , date.getHours()
                    , date.getMinutes()
                    , date.getSeconds()
                    , date.getMilliseconds()
                );
            },


            // Get the current margin size of the progress bar
            _getProgressBarMargin: null,
            getProgressBarMargin: function() {
                if (!tools._getProgressBarMargin) {
                    $("body").append(
                        $('<div style="display: none; position: absolute;" id="measureBarWidth" ><div class="fn-gantt"><div class="rightPanel"><div class="dataPanel"><div class="row day"><div class="bar" /></div></div></div></div></div>')
                    );
                    tools._getProgressBarMargin = parseInt($("#measureBarWidth .fn-gantt .rightPanel .day .bar").css("margin-left").replace("px", ""), 10);
                    tools._getProgressBarMargin += parseInt($("#measureBarWidth .fn-gantt .rightPanel .day .bar").css("margin-right").replace("px", ""), 10);
                    $("#measureBarWidth").empty().remove();
                }
                return tools._getProgressBarMargin;
            }
        };


        this.each(function() {
            /**
            * Extend options with default values
            */
            if (options) {
                $.extend(settings, options);
            }

            this.data = null;        // Received data
            this.pageNum = 0;        // Current page number
            this.pageCount = 0;      // Available pages count
            this.rowsOnLastPage = 0; // How many rows on last page
            this.rowsNum = 0;        // Number of total rows
            this.hPosition = 0;      // Current position on diagram (Horizontal)
            this.dateStart = null;
            this.dateEnd = null;
            this.scrollClicked = false;
            this.scaleOldWidth = null;
            this.headerRows = null;

            this.headerRows = 3; this.scaleStep = 13;

            this.gantt = null;
            this.loader = null;

            core.create(this);

        });

    };
})(jQuery);