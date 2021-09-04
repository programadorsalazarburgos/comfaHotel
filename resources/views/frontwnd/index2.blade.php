<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Vue.js Scheduler: Build a Reservation Application in 5 Minutes</title>

    <link href="css/index2.css" rel="stylesheet" type="text/css">
    <link href="css/icons/style.css" rel="stylesheet" type="text/css">

    <!-- DayPilot library -->
    <script src="css/js/daypilot/daypilot-all.min.js"></script>

    <!-- Vue.js -->
    <script src="https://unpkg.com/vue"></script>

</head>
<body>
<div class="header">
    <h1><a href='https://code.daypilot.org/69423/vue-js-scheduler-build-a-reservation-application-in-5-minut'>Vue.js Scheduler: Build a Reservation Application in 5 Minutes</a></h1>
    <div><a href="https://javascript.daypilot.org/">DayPilot for JavaScript</a> - AJAX Calendar/Scheduling Widgets for JavaScript/HTML5/jQuery/AngularJS
    </div>
</div>

<div class="main">

    <div id="scheduler-app">
        <scheduler id="dp" :config="initConfig" ref="scheduler"></scheduler>
    </div>

</div>

<script>
    Vue.component('scheduler', {
        props: ['id', 'config'],
        template: '<div :id="id"></div>',
        mounted: function () {
            this.control = new DayPilot.Scheduler(this.id, this.config).init();
        }
    });

    var app = new Vue({
        el: '#scheduler-app',
        data: {
            initConfig: {
                timeHeaders: [{"groupBy": "Month"}, {"groupBy": "Day", "format": "d"}],
                scale: "Day",
                days: DayPilot.Date.today().daysInMonth(),
                startDate: DayPilot.Date.today().firstDayOfMonth(),
                timeRangeSelectedHandling: "Enabled",
                eventHeight: 40,
                onTimeRangeSelected: function (args) {
                    var dp = this;
                    DayPilot.Modal.prompt("Create a new event:", "Event 1").then(function (modal) {
                        dp.clearSelection();
                        if (!modal.result) {
                            return;
                        }
                        dp.events.add(new DayPilot.Event({
                            start: args.start,
                            end: args.end,
                            id: DayPilot.guid(),
                            resource: args.resource,
                            text: modal.result
                        }));
                    });
                },
                eventMoveHandling: "Update",
                onEventMoved: function (args) {
                    alert('El evento ha sido movido');
                    let me = this;
                    this.message("Event moved");
                },
                eventResizeHandling: "Update",
                onEventResized: function (args) {
                    this.message("Event resized");
                },
                eventClickHandling: "Enabled",
                onEventClicked: function (args) {
                    this.message("Event clicked");
                },
                eventHoverHandling: "Disabled",
                treeEnabled: true,
                onBeforeEventRender: function(args) {
                    args.data.barColor = args.data.color;
                    args.data.areas = [
                        { top: 6, right: 2, icon: "icon-triangle-down", visibility: "Hover", action: "ContextMenu", style: "font-size: 12px; background-color: #f9f9f9; border: 1px solid #ccc; padding: 2px 2px 0px 2px; cursor:pointer;"}
                    ];
                },
                contextMenu: new DayPilot.Menu({
                    items: [
                        {
                            text: "Delete",
                            onClick: function(args) {
                                var e = args.source;
                                app.scheduler.events.remove(e);
                                app.scheduler.message("Deleted.");
                            }
                        },
                        {
                            text: "-"
                        },                        {
                            text: "Blue",
                            icon: "icon icon-blue",
                            color: "#1155cc",
                            onClick: function(args) { app.updateColor(args.source, args.item.color); }
                        },
                        {
                            text: "Green",
                            icon: "icon icon-green",
                            color: "#6aa84f",
                            onClick: function(args) { app.updateColor(args.source, args.item.color); }
                        },
                        {
                            text: "Yellow",
                            icon: "icon icon-yellow",
                            color: "#f1c232",
                            onClick: function(args) { app.updateColor(args.source, args.item.color); }
                        },
                        {
                            text: "Red",
                            icon: "icon icon-red",
                            color: "#cc0000",
                            onClick: function(args) { app.updateColor(args.source, args.item.color); }
                        },

                    ]
                })
            }
        },
        computed: {
            // DayPilot.Scheduler object
            // https://api.daypilot.org/daypilot-scheduler-class/
            scheduler: function () {
                return this.$refs.scheduler.control;
            }
        },
        methods: {
            loadReservations: function () {
                // placeholder for an AJAX call
                var data = [
                    {
                        id: 1,
                        resource: "R1",
                        start: DayPilot.Date.today().firstDayOfMonth().addDays(3),
                        end: DayPilot.Date.today().firstDayOfMonth().addDays(7),
                        text: "Event 1",
                        barColor: "#cc0000"
                    },
                    {
                        id: 2,
                        resource: "R2",
                        start: DayPilot.Date.today().firstDayOfMonth().addDays(5),
                        end: DayPilot.Date.today().firstDayOfMonth().addDays(10),
                        text: "Event 2",
                        barColor: "#38761d"
                    }
                ];
                this.scheduler.update({events: data});
            },
            loadResources: function () {
                // placeholder for an AJAX call
                var data = [
                    {
                        name: "Group A", id: "GA", expanded: true, children: [
                        {name: "Resource 1", id: "R1"},
                        {name: "Resource 2", id: "R2"}
                    ]
                    },
                    {
                        name: "Group B", id: "GB", expanded: true, children: [
                        {name: "Resource 3", id: "R3"},
                        {name: "Resource 4", id: "R4"}
                    ]
                    }
                ];
                this.scheduler.update({resources: data});
            },
            updateColor: function(e, color) {
                var dp = this.scheduler;
                e.data.color = color;
                dp.events.update(e);
                dp.message("Color updated");
            }
        },
        mounted: function () {
            this.loadResources();
            this.loadReservations();
        }
    });

</script>
</body>
</html>
