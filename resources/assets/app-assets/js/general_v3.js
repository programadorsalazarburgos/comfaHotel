// holds current page instances
var page_inst = null;

// current locations
var loc_menu = "frontoffice";
var loc_submenu = "roomschart.aspx";
var loc_tab = null;

// noteice texts
var nT = "Changes applied successfully.";
var nF = "Error applying changes.";

// whole menu user has access to
var menu = null;

// general tag object, we use to send predefined filters when we switch menu, etc...
var tag = null;

/* stuff that is always run */
$(function() {
    // load menu only if we are not on login form
    if (window.location.pathname.indexOf("login.html") == -1) {
        // load menu
        requestFunction("general", "generatemenu", "", function(result) {
            menu = result; // sore results for later use
            drawMenu(); // draw all subitems for current menu
            switchSubMenu(loc_menu, loc_submenu); // call switch to - to select submenu and load it's action
        });

        // load employee data
        requestFunction("general", "currentuser", "", function(result) {
            $("#EmployeeName").text(result.Name);
        });

        $(window).resize(function() {
            $(".jqmWindow").center();
            $(".jqmWindowLight").center();
        });
    }
});
/* used for submiting form to .Net web service */
var submitProgress = false;
function submitForm(serviceName, methodName, form, callback) 
{
    if (submitProgress == false)
    {
        submitProgress = true;
        var formData = form2object(form);

        //             data: '{item: \"' + encodeURIComponent($.toJSON(formData)) + '\"}',
        $.ajax({
            type: "POST",
            data: '{\"item\": ' + JSON.stringify(formData) + '}',
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            url: serviceName + ".asmx/" + methodName,
            success:
                    function(data)
                    {
                        submitProgress = false;

                        // if we have redirect then we expect integer which is also id
                        if (callback != null)
                        {
                            if (data.d == 0)
                            {
                                showNotice(false);
                            } else if (data.d == true || data.d == false) {
                                // TODO: maybe we should not show status, if we have callback - as in original
                                showNotice(data.d);
                                callback(data.d);
                            } else 
                            {
                                callback(data.d);
                            }
                        } else
                        {
                            showNotice(data.d);
                        }
                    },
            error:
                    function()
                    {
                        submitProgress = false;

                        showNotice(false);
                    }
        });
    }

    return false;
}


/* used for sending requests to .Net web service */
function requestFunction(service, name, data, callback)
{
    var dataPre = (jQuery.type(data) === "string") ? "{" + data + "}" : JSON.stringify(data);

    $.ajax({
        type: "POST",
        data: dataPre,
        dataType: "json",
        contentType: "application/json; charset=utf-8",
        url: service + ".asmx/" + name,
        success:
        /* execute callback function if it exists */
            function(data) { if (callback != null) { callback(data.d); } },
        error: function(xhr, status, error) {
            if (xhr.status == 401) {
                // alert("login");
                window.location.href = "login.html";
            }
            else if (xhr.status == 403) {
                alert("access denied");
            }
            else {
                // parse exception to json object
                var exception = JSON.parse(xhr.responseText);
                
                // if exception exists
                if (exception) {
                    if (exception.Message == "unauthenticated") {
                        window.location.href = "login.html";
                    }
                } else {
                    alert("Error\n-----\n" + xhr.status + '\n' + xhr.responseText);
                }
            }
        }
    });
};

// used for searching json objects
function getObjects(obj, key, val)
{
    var objects = [];
    for (var i in obj)
    {
        if (!obj.hasOwnProperty(i)) continue;
        if (typeof obj[i] == 'object')
        {
            objects = objects.concat(getObjects(obj[i], key, val));
        } else if (i == key && obj[key] == val)
        {
            objects.push(obj);
        }
    }
    return objects;
}        

// handles menu generation
function drawMenu()
{
    // clear all menues
    $("#mainmenu").html("");

    // loop each menu item
    $.each(menu, function(i, item)
    {
        // add all menues that have sub menues
        if (item.SubItems.length != 0)
        {
            var itemHTML = "";
            if (loc_menu == item.ID)
            {
                itemHTML = "<a href=\"#\" class=\"accent\" onclick=\"switchMenu(\'" + item.ID + "\', \'" + item.SubItems[0].ID + "\')\">" + item.Text + "</a>";
            }
            else
            {
                itemHTML = "<a href=\"#\" onclick=\"switchMenu(\'" + item.ID + "\', \'" + item.SubItems[0].ID + "\')\">" + item.Text + "</a>";
            }
            $("#mainmenu").append(itemHTML);

            if (loc_menu == item.ID)
            {
                drawSubMenu(item);
            }
        }
    })

    // insert hotel name
    $("#mainmenu").append("<span class=\"floatright white\">" + txt_hotel + "&nbsp;<\/span>");
}

function drawSubMenu(mainMenu)
{
    // clear all submenues
    $("#submenu").html("");

    // loop each menu item
    $.each(mainMenu.SubItems, function(i, item) {
        var alertHTML = "";
        if (item.Alerts != 0) { alertHTML = "<span>" + item.Alerts + "</span>" }

        if (item.ID.substring(0, 6) == "https:" || item.ID.substring(0, 5) == "http:") {
            itemHTML = "<a href=\"" + item.ID + "\" target=\"_blank\">" +
                "<img src=\"icons/" + item.Icon + "\" />" + alertHTML +
                "<br />" + item.Text + "</a>";
        } else {
            itemHTML = "<a href=\"#\" id=\"" + mainMenu.ID + "_" + item.ID.replace(".", "_").replace("?", "_").replace("=", "_") + "\" onclick=\"switchSubMenu(\'" + mainMenu.ID + "\', \'" + item.ID + "\')\">" +
                "<img src=\"icons/" + item.Icon + "\" />" + alertHTML +
                "<br />" + item.Text + "</a>";
        }

        $("#submenu").append(itemHTML);
    })
}

function switchMenu(mainMenu, subMenu)
{
    // clean up last instance
    if (page_inst) page_inst();
    
    loc_menu = mainMenu;
    loc_submenu = subMenu;
    
    drawMenu(mainMenu, subMenu);

    // selected current submenu
    switchSubMenu(loc_menu, loc_submenu);
}

function switchSubMenu(mainMenu, subMenu)
{
    // clean up last instance
    if (page_inst) page_inst();

    // store current location of submenu
    loc_submenu = subMenu;

    // construct full path to know where to change instance
    var fullID = mainMenu + "_" + subMenu.replace(".", "_").replace("?", "_").replace("=", "_");
    
    // remove old selection
    $("#submenu a.selected").removeClass("selected");

    // apply selection to new item
    $("#" + fullID).addClass("selected");

    // make sure to stop old circular animation
    if (animatedCounter) { animatedCounter.stop(true, false); animatedCounter = null; }

    // remove timed functions interval
    if (timerHalfMinute) clearInterval(timerHalfMinute);
    

    // call page action
    $("#page").load("templates/" + loc_menu + "/" + loc_submenu);
}

function populateCombo(item, data)
{
    var records = [];

    $.each(data, function(i)
    {
        records[i] = "<option value=\"" + this.value + "\">" + this.text + "<\/option>";
    });

    item.html(records.join(''));
}

function showPreview()
{
    if ($("#rightstatic").css("display") == "none")
    {
        $("#pagewrap").css("padding-right", "40%");
        $("#rightstatic").css("width", "40%");
        $("#rightstatic").show();
    }
}

function hidePreview()
{
    $("#pagewrap").css("padding-right", "0%");
    $("#rightstatic").css("width", "40%");
    $("#rightstatic").hide();
}



// JQUERY CENTER FUNCTION
jQuery.fn.center = function(absolute)
{
    return this.each(function()
    {
        var t = jQuery(this);
        t.css({ position: absolute ? 'absolute' : 'fixed', left: '50%', top: '50%' }).css({ marginLeft: '-' + (t.outerWidth() / 2) + 'px', marginTop: '-' + (t.outerHeight() / 2) + 'px' });
        if (absolute)
        {
            t.css({ marginTop: parseInt(t.css('marginTop'), 10) + jQuery(window).scrollTop(), marginLeft: parseInt(t.css('marginLeft'), 10) + jQuery(window).scrollLeft() });
        }
    });
};

// timed functions
var timerHalfMinute = null;

// Circular progress bar
var animatedCounter = null; // holds always reference to counter

$.fn.circular = function(options, callback) {
    var settings = $.extend({
        range: null,
        counter: this,
        thick: 5.0,
        radius: 20,
        circleShade: "#f0f0f0",
        circleColor: "#87CEEB",
        fontColor: "#87CEEB",
        font: "12pt Calibri",
        animate: true,
        repeatAnimation: false,
        animateFrom: 0,
        animteTo: 100,
        duration: 30000,
        percText: false
    }, options);


    // SVG stuff
    var range = settings.range;
    var bg = settings.counter;
    var ctx = bg[0].getContext("2d");
    var imd = null;
    var circ = Math.PI * 2;
    var quart = Math.PI / 2;
    var width = bg.width();
    var height = bg.height();

    ctx.beginPath();
    ctx.lineCap = "square";
    ctx.font = settings.font;
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillStyle = settings.fontColor;
    ctx.closePath();
    ctx.fill();
    ctx.lineWidth = settings.thick;

    imd = ctx.getImageData(0, 0, width, height);

    var draw = function(current) {
        ctx.putImageData(imd, 0, 0);

        // shade
        ctx.strokeStyle = settings.circleShade;
        ctx.beginPath();
        ctx.arc(width / 2, height / 2, settings.radius, -(quart), ((circ)) - quart, false);
        ctx.stroke();

        // circle
        ctx.strokeStyle = settings.circleColor;
        ctx.beginPath();
        ctx.arc(width / 2, height / 2, settings.radius, -(quart), ((circ) * current) - quart, false);
        ctx.stroke();

        // text
        var txt = parseInt(current * 100);
        if (settings.percText) {
            ctx.fillText(range.val() + " %", width / 2, height / 2);
        } else {
            ctx.fillText(range.val(), width / 2, height / 2);
        }
    }

    // set default value
    if (settings.repeatAnimation) range.val(settings.animateFrom);

    $(range).change(function() {
        if (settings.animateFrom > settings.animateTo) {
            draw(range.val() / settings.animateFrom);
        } else {
            draw(range.val() / settings.animateTo);
        }
    });

    var countdown = function(to) {
        var oldValue = -1;
        if (!settings.repeatAnimation) $(range).val(0);
        $(range).animate({ value: to }, {
            duration: settings.duration,
            easing: "linear",
            step: function() {
                if (oldValue != Math.ceil(this.value)) {
                    oldValue = Math.ceil(this.value);
                    $(range).val(Math.ceil(this.value)).trigger("change");
                }
            },
            complete: function() {
                // Animation complete, restart it.
                if (settings.repeatAnimation) {
                    $(range).val(to).trigger("change");
                    range.val(settings.animateFrom);
                    countdown(settings.animateTo);
                }
                if (typeof callback == 'function') { // make sure the callback is a function
                    callback.call(this); // brings the scope to the callback
                }
            }
        });
    }

    var reset = function() {
        $(range).val(settings.animateFrom);
    }

    if (settings.animate) {
        if (settings.repeatAnimation) {
            countdown(settings.animateTo);
        } else {
            countdown(settings.range.val());
        }
    } else {
        draw(range.val() / settings.animateFrom);
    }

    return range;
};

$.fn.circularEx = function(options, callback) {
    var settings = $.extend({
        range: null,
        counter: this,
        thick: 5.0,
        radius: 20,
        circleShade: "#f0f0f0",
        circleColor: "#87CEEB",
        fontColor: "#87CEEB",
        font: "12pt Calibri",
        animate: true,
        animateFrom: 0,
        animteTo: 100,
        duration: 30000,
        percText: false
    }, options);


    // SVG stuff
    var range = settings.range;
    var bg = settings.counter;
    var ctx = bg[0].getContext("2d");
    var imd = null;
    var circ = Math.PI * 2;
    var quart = Math.PI / 2;
    var width = bg.width();
    var height = bg.height();

    ctx.beginPath();
    ctx.lineCap = "square";
    ctx.font = settings.font;
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillStyle = settings.fontColor;
    ctx.closePath();
    ctx.fill();
    ctx.lineWidth = settings.thick;

    imd = ctx.getImageData(0, 0, width, height);

    var draw = function(current) {
        ctx.putImageData(imd, 0, 0);

        // shade
        ctx.strokeStyle = settings.circleShade;
        ctx.beginPath();
        ctx.arc(width / 2, height / 2, settings.radius, -(quart), ((circ)) - quart, false);
        ctx.stroke();

        // circle
        ctx.strokeStyle = settings.circleColor;
        ctx.beginPath();
        ctx.arc(width / 2, height / 2, settings.radius, -(quart), ((circ) * current) - quart, false);
        ctx.stroke();

        // text
        var txt = parseInt(current * 100);
        if (settings.percText) {
            ctx.fillText(range.val() + " %", width / 2, height / 2);
        } else {
            ctx.fillText(range.val(), width / 2, height / 2);
        }
    }

    $(range).change(function() {
        draw(range.val() / 100);
    });

    var reset = function() { $(range).val(0); }

    draw(range.val() / 100);

    return range;
};

/// GET MAX ZINDEX
jQuery.fn.maxZIndex = function()
{
    var maxZ = Math.max.apply(null, $.map($('div').not(".ui-datepicker, .ui-dialog, .dlgjq"),
        function(e, n){
            // if ($(e).css('position') == 'absolute' || $(e).css('position') == 'fixed')
                return parseInt($(e).css('z-index')) || 1;
            }
        )
    );

    return maxZ;
};

/* capitalise function */
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

/* remove item from object array */
Array.prototype.remove = function(name, value) {
    var rest = $.grep(this, function(item) {
        return (item[name] !== value); // <- You may or may not want strict equality
    });

    this.length = 0;
    this.push.apply(this, rest);
};


// shows success and error notifications
function showNotice(result) {
    var container = $('<div id="noticeBarContainer"></div>').addClass("noticeBarContainer").addClass("smaller");
    if (result == true) {
        container.html('<span class="noticeBar noticeBarGreen">' + nT + '.</span');
    }
    else {
        container.html('<span class="noticeBar noticeBarRed">' + nF + '.</span');
    }

    $('body').append(container);

    // scroll down notice
    $('#noticeBarContainer').animate({ top: '0' }, 400,
                function() {
                    // scroll up notice after down animation is complete
                    $('#noticeBarContainer').animate({ top: '-50px' }, 1000,
                    function() {
                        // remove notice container
                        $('#noticeBarContainer').remove();
                        submitProgress = false;
                    }
                );
                }
    ).delay(1000);
}

// shows and hide custom notice
function showNoticeStatic(content, className, autoClose) {
    className = className || "noticeBarGreen";
    autoClose = autoClose || false;
    var container = $('<div id="noticeBarContainer"></div>').addClass("noticeBarContainer").addClass("smaller");
    container.html('<span id="noticeBarValue" class="noticeBar ' + className + '">' + content + '</span');
    $('body').append(container);
    // scroll down notice
    if (!autoClose) {
        $('#noticeBarContainer').animate({ top: '0' }, 400);
    } else {
        $('#noticeBarContainer').animate({ top: '0' }, 400,
                    function() {
                        // scroll up notice after down animation is complete
                        $('#noticeBarContainer').animate({ top: '-50px' }, 1000,
                        function() {
                            // remove notice container
                            $('#noticeBarContainer').remove();
                            submitProgress = false;
                        }
                    );
                    }
                ).delay(3000);
    }
}

function alertNoticeStatic(content) {
    $('#noticeBarValue').text(content);
    $('#noticeBarValue').addClass('noticeBarRed');
    $('#noticeBarValue').removeClass('noticeBarGreen');
}

function hideNoticeStatic() {
    $('#noticeBarContainer').animate({ top: '-50px' }, 1000,
        function() {
            // remove notice container
            $('#noticeBarContainer').remove();
        }
    );
}

function HtmlEncode(inputStr) {
    var value = inputStr;
    value = value.replace(/</gi, "&lt;");
    value = value.replace(/>/gi, "&gt;");
    value = value.replace(/&##39;/gi, "'");
    value = value.replace(/&quot;/gi, '"');
    value = value.replace(/&amp;/gi, '&');
    return value;
}

function parseDecimal(val) { return parseFloat(val.replace(",", ".")); }
function parseDecimalFixed(val) { return parseFloat(val.replace(",", ".")).toFixed(2); }

// general setting for how many fraction digits we have - this we override later
var def_numberOfFractionDigits = 2;
var def_fractionSeperator = ",";
var def_groupSeperator = ".";

String.prototype.toDecimal = function() {
    // change all chars to dot (.)
    var str = this.trim().replace(/[^\d.-]/g, '.');

    // count of dots in string - leave only last one
    var pun = str.split(".").length - 1;


    // replace dot's until we are all out of them - except last one, that one we leave
    if (pun > 1) {
        for (i = 0; i < pun - 1; i++) {
            str = str.replace(".", "");
        }
    }

    // parse to float, to have real number
    return parseFloat(str)
}

String.prototype.toDecimalFixed = function(fractionDigits) {
    fractionDigits = typeof fractionDigits !== 'undefined' ? fractionDigits : def_numberOfFractionDigits;
    return this.toDecimal().toFixed(fractionDigits);
}

Number.prototype.toLocale = function(fractionSeperator, groupSeperator, groupSeperatorLen) {
    var sx = ("" + this.toFixed(def_numberOfFractionDigits)).split("."), s = "", i, j;
    fractionSeperator || (fractionSeperator = def_fractionSeperator); // default seperator
    groupSeperator || (groupSeperator = def_groupSeperator); // default thousand seperator
    groupSeperatorLen || (groupSeperatorLen = 3); // default thousand seperator length

    var minus; minus = false;
    if (sx[0].length != 0 && sx[0].charAt(0) == "-") {
        minus = true;
        sx[0] = sx[0].substring(1);
    };

    i = sx[0].length;
    while (i > groupSeperatorLen) {
        j = i - groupSeperatorLen;
        s = groupSeperator + sx[0].slice(j, i) + s;
        i = j;
    }
    s = sx[0].slice(0, i) + s;
    if (minus) s = "-" + s;
    sx[0] = s;

    if (sx.length == 1) sx.push("");
    while (sx[1].length < def_numberOfFractionDigits) {
        sx[1] += "0";
    }

    sx[1] = sx[1].substring(0, def_numberOfFractionDigits);

    if (def_numberOfFractionDigits == 0) {
        return sx[0]
    } else {
        return sx.join(fractionSeperator)
    }
}

/* currency convert value */

function currConvertValue(elm) {
    $("#converterfromval").val($("#" + $(elm).data("ref")).val());

    $("#currencyconverter").dialog({
        dialogClass: "small",
        width: 400,
        height: 150,
        resizable: false,
        open: function(event, ui) { $(".ui-dialog").css("zIndex", 999998); }
    });


    $("#converterfrom").html("");
    $("#converterto").html("");

    var records = [];

    $.each(currencies, function(idx, itm) {
        records[idx] = "<option val2=\"" + itm.value + "\" value=\"" + itm.code + "\">" + itm.name + "</option>";
    });

    $("#converterfrom").html(records.join(''));
    $("#converterto").html(records.join(''));

    $("#converterfrom").val(curr1);
    $("#converterto").val(curr2);

    convertCurrency();

    return false;
}

var cal_year, cal_month, cal_day;

function refreshCalendar() {
    drawCalendar(cal_day, cal_month, cal_year);
}

function drawCalendar(day, month, year, skipredraw) {
    cal_year = year; cal_month = month; cal_day = day;
    
    if (typeof skipredraw === 'undefined') { skipredraw = false; }    

    if (!skipredraw) {
        $("#CurrentScheduleYear").text(year);
        var counter = 1;
        var FebNumberOfDays = 28;

        var dateNow = new Date(year, month - 1, day, 0, 0, 0, 0);  //new Date();

        var nextMonth = month + 1; //+1; //Used to match up the current month with the correct start date.
        var prevMonth = month - 1;

        //Determing if February (28,or 29)  
        if (month == 2){
            if ( (year % 100 != 0) && (year % 4 == 0) || (year % 400 == 0)) FebNumberOfDays = 29;
        }

        var dayPerMonth = [31, FebNumberOfDays, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        var htmlContent = "";

        var nextDate = new Date(year, month - 1, 1, 0, 0, 0, 0);
        nextDate.setMonth(nextDate.getMonth());

        var prevMonth = new Date(year, month - 1, 1, 0, 0, 0, 0);
        prevMonth.setMonth(prevMonth.getMonth() - 1);

        var nextMonth = new Date(year, month - 1, 1, 0, 0, 0, 0);
        nextMonth.setMonth(nextMonth.getMonth() + 1);

        var weekdays = (nextDate.getDay() == 0) ? 6 : nextDate.getDay() - 1;
        var weekdays2 = weekdays;
        var numOfDays = dayPerMonth[month - 1];

        var iterator = 0;

        cal_days_abbr

        // abbr month names
        htmlContent += "<div class=\"cf aligncenter\">";
        while (iterator < 12) {
            if ((month - 1) == iterator) {
                htmlContent += "<div class='floatleft month bold' onclick='drawCalendar(1," + (iterator + 1) + "," + year + ")'>" + cal_months_abbr[iterator].toUpperCase() + "</div>";
            } else {
                htmlContent += "<div class='floatleft month' onclick='drawCalendar(1," + (iterator + 1) + "," + year + ")'>" + cal_months_abbr[iterator].toUpperCase() + "</div>";
            }
            iterator++;
        }
        htmlContent += "</div>";


        // abbr day names
        iterator = 0;
        while (iterator < 7) {
            var currDayIdx = (dateNow.getDay() == 0) ? 6 : dateNow.getDay() - 1;
            if (currDayIdx == iterator) {
                htmlContent += "<div class='floatleft dayname bold'>" + cal_days_abbr[iterator].toUpperCase() + "</div>";
            } else {
                htmlContent += "<div class='floatleft dayname'>" + cal_days_abbr[iterator].toUpperCase() + "</div>";
            }
            iterator++;
        }
        htmlContent += "<br />";
        
     
        // this leave a white space for days of pervious month.
        while (weekdays > 0) {
            var max_days = dayPerMonth[prevMonth.getMonth()] + 1;
            htmlContent += "<div class='floatleft fade calPrevMonth day' id='cal_prev_day_" + (max_days - weekdays) + "_" + (prevMonth.getMonth() + 1) + "' onclick=\"drawCalendar(" + (max_days - weekdays) + "," + (prevMonth.getMonth() + 1) + "," + prevMonth.getFullYear() + ")\">" + (max_days - weekdays) + "</div>";

            // used in next loop.
             weekdays--;
        }

        // loop to build the calander body.
        var todayDate = new Date(new Date().getFullYear(),new Date().getMonth(), new Date().getDate());
        while (counter <= numOfDays){

             // When to start new line.
            if (weekdays2 > 6){
                weekdays2 = 0;
                htmlContent += "<br />";
            }



            // if counter is current day.
            // highlight current day using the CSS defined in header.
            var currentDate = new Date(year, dateNow.getMonth(), counter);
            if (currentDate.getDate() == todayDate.getDate() && currentDate.getMonth() == todayDate.getMonth() && currentDate.getFullYear() == currentDate.getFullYear()) {
                htmlContent += "<div class=\"floatleft white day\" id=\"cal_prev_day_" + counter + "_" + month + "\" onclick=\"drawCalendar(" + counter + "," + month + "," + year + ")\"><span class=\"today\">" + counter + "</span><\/div>";
            } else if (counter == day) {
                htmlContent += "<div class=\"floatleft day\" id=\"cal_prev_day_" + counter + "_" + month + "\" onclick=\"drawCalendar(" + counter + "," + month + "," + year + ")\"><span class=\"current\">" + counter + "</span><\/div>";
            }else if (weekdays2 == 6) {
                htmlContent += "<div class=\"floatleft accent day\" id=\"cal_prev_day_" + counter + "_" + month + "\" onclick=\"drawCalendar(" + counter + "," + month + "," + year + ")\">" + counter + "<\/div>";
            } else {
                htmlContent += "<div class=\"floatleft day\" id=\"cal_prev_day_" + counter + "_" + month + "\" onclick=\"drawCalendar(" + counter + "," + month + "," + year + ")\">" + counter + "</div>";
            }
            
            weekdays2++;
            counter++;
        }

        // this leave a white space for days of next month.
        weekdays = 1;
        while (weekdays2 < 7) {
            htmlContent += "<div class='floatleft fade day calPrevMonth' id='cal_prev_day_" + weekdays + "_" + (nextMonth.getMonth() + 1) + "' onclick=\"drawCalendar(" + weekdays + "," + (nextMonth.getMonth() + 1) + "," + nextMonth.getFullYear() + ")\">" + weekdays + "</div>";

            // used in next loop.
            weekdays++;
            weekdays2++;
        }
    }

    // weather forecast and events and reminders, call before active reservations because they are slow
    requestFunction("frontoffice", "ReadWeatherForecastEventsDay", { year: year, month: month, day: day }, function(data) {
        $("#CalendarDayOfWeek").text(data.DayOfWeek);
        $("#CalendarDayFull").text(data.DayFull);

        $.each(data.EventsDots, function(i, dot) {
            $("#cal_prev_day_" + dot.Day + "_" + dot.Month).addClass("dot");
        });

        // loop all forecasts and draw them on interface
        $("#weatherForecasts").html("");

        var forecast = "";
        var forecasts = "<div class=\"cf\"><br /><br />";
        $.each(data.WeatherForecast, function(i, day) {
            if (i == 0) {
                $("#CurrentWeatherCity").text(day.City);
                forecast += "<div class=\"cf\" title=\"" + day.DateTakenString + "\">" +
                        "<div class=\"floatleft width33 aligncenter\">" +
                            "<i class=\"wi " + day.CssClass + " accent xxlarge\"></i>" +
                        "</div>" +
                        "<div class=\"floatleft width33 alignright\">" +
                            "<span class=\"xlarge\">" + day.DayTemp + " °C</span>" +
                            "<br />" +
                            "<span class=\"large\">" + day.NightTemp + " °C</span>" +
                        "</div>" +
                        "<div class=\"floatleft width33 alignright\">" +
                            "<span>" + day.RainingPossibility + " %</span>&nbsp;<i class=\"wi wi-sprinkles large aligncenter\" style=\"width:1em\"></i>" +
                            "<br />" +
                            "<span>" + day.WindSpeed + " km/h</span>&nbsp;<i class=\"wi wi-strong-wind large\" style=\"width:1em\"></i>" +
                        "</div>" +
                    "</div>";
            } else {
                forecasts += "<div class=\"floatleft width25 cf\" title=\"" + day.DateTakenString + "\">" +
                            "<div class=\"splittwo\">" +
                                "<i class=\"wi " + day.CssClass + " accent xlarge lh2\"></i>" +
                            "</div>" +
                            "<div class=\"splittwo\">" +
                                "<span class=\"bold\">" + day.DayTemp + "°C</span><span><br />" + day.NightTemp + "°C</span>" +
                            "</div>" +
                        "</div>";
            }
        });

        forecasts += "</div>";

        $("#weatherForecasts").html(forecast + forecasts);

        $("#eventsList").html("");
        var eventsrecords = "";
        $.each(data.Events, function(i, event) {
            eventsrecords += "<div class=\"cf white paddTop eventEntry\" onclick=\"loadeventform(" + event.ID + ")\">" +
                                "<div class=\"floatleft width40 xfade alignright\">" + event.FormatedDate + "<br />" + event.Employee + "</div>" +
                                "<div class=\"floatleft width60\"><div class=\"padd larger\">" + event.Text + "</div></div>" +
                            "</div>";
        });

        $("#eventsList").html(eventsrecords);
    })

    // show form
    $('#dlgSchedule').jqm({ modal: true, toTop: true });
    $('#dlgSchedule').css("height", "90%");
    $('#dlgSchedule').jqmShow();
    $('#dlgSchedule').center();
    

    $("#scheduleCalendar").html(htmlContent);

    return false;
}

/* global event handler */
    function loadeventform(id) {
        // once data is loaded we will fill form with it
        requestFunction("frontoffice", "event", { id: id }, function(data) {
            // fill form with json data
            js2form(document.getElementById("eventForm"), data);

            // show form
            $('#dlgEvent').jqm({ modal: true, toTop: true });
            $('#dlgEvent').css("height", "90%");
            $('#dlgEvent').jqmShow();
            $('#dlgEvent').center();

            $("#eventForm input[name=StartDateString]").datepicker("setDate", new Date(cal_year, cal_month - 1, cal_day));
            $("#eventForm input[name=EndDateString]").datepicker("setDate", new Date(cal_year, cal_month - 1, cal_day));
        });
    }

    // handle submit event
    function eventupdate(form) {
        var validator = $("#eventForm").validate({ meta: "validate" });
        
        if ($(form).valid()) {
            if ($("#eventForm input[name=EventID]").val() == "0") {
                submitForm('frontoffice', 'EventAdd', form, function(data) {
                    if (data) { refreshCalendar(); $('#dlgEvent').jqmHide() };
                    // TODO: Refresh calendarlist();
                })
            } else {
                submitForm('frontoffice', 'EventUpdate', form, function(data) {
                    if (data) { refreshCalendar(); $('#dlgEvent').jqmHide() };
                })
            }
        };

        // don't actually submit form
        return false;
    }

    // handle event delete
    function eventdelete(id) {
        // once data is loaded we will fill form with it
        requestFunction("frontoffice", "EventDelete", { id: id }, function(data) {
            if (data) { refreshCalendar(); $('#dlgEvent').jqmHide() };
        });
    }



/* 
* a backwards compatable implementation of postMessage
* by Josh Fraser (joshfraser.com)
* released under the Apache 2.0 license.  
*/

// everything is wrapped in the XD function to reduce namespace collisions
var XD = function() {

    var interval_id,
    last_hash,
    cache_bust = 1,
    attached_callback,
    window = this;

    return {
        postMessage: function(message, target_url, target) {

            if (!target_url) {
                return;
            }

            target = target || parent;  // default to parent

            if (window['postMessage']) {
                // the browser supports window.postMessage, so call it with a targetOrigin set appropriately, based on the target_url parameter.
                target['postMessage'](message, target_url.replace(/([^:]+:\/\/[^\/]+).*/, '$1'));

            } else if (target_url) {
                // the browser does not support window.postMessage, so set the location of the target to target_url#message. A bit ugly, but it works! A cache bust parameter is added to ensure that repeat messages trigger the callback.
                target.location = target_url.replace(/#.*$/, '') + '#' + (+new Date) + (cache_bust++) + '&' + message;
            }
        },

        receiveMessage: function(callback, source_origin) {

            // browser supports window.postMessage
            if (window['postMessage']) {
                // bind the callback to the actual event associated with window.postMessage
                if (callback) {
                    attached_callback = function(e) {
                        if ((typeof source_origin === 'string' && e.origin !== source_origin)
                        || (Object.prototype.toString.call(source_origin) === "[object Function]" && source_origin(e.origin) === !1)) {
                            return !1;
                        }
                        callback(e);
                    };
                }
                if (window['addEventListener']) {
                    window[callback ? 'addEventListener' : 'removeEventListener']('message', attached_callback, !1);
                } else {
                    window[callback ? 'attachEvent' : 'detachEvent']('onmessage', attached_callback);
                }
            } else {
                // a polling loop is started & callback is called whenever the location.hash changes
                interval_id && clearInterval(interval_id);
                interval_id = null;

                if (callback) {
                    interval_id = setInterval(function() {
                        var hash = document.location.hash,
                        re = /^#?\d+&/;
                        if (hash !== last_hash && re.test(hash)) {
                            last_hash = hash;
                            callback({ data: hash.replace(re, '') });
                        }
                    }, 100);
                }
            }
        }
    };
} ();