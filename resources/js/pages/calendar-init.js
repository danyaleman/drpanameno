/*
 Template Name: Doctorly - Hospital & Clinic Management Laravel System
 Author: Lndinghub(Themesbrand)
 File: Calendar Init
 */


 !function ($) {
    "use strict";
    var CalendarPage = function () { };

    CalendarPage.prototype.init = function () {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        /*  className colors
    
         className: default(transparent), important(red), chill(pink), success(green), info(blue)
    
         */


        /* initialize the external events
         -----------------------------------------------------------------*/

        

        /* initialize the calendar
         -----------------------------------------------------------------*/
         var calendarEl = document.getElementById('calendar');

        var SITEURL = "{{url('/')}}"
        var calendar = new FullCalendar.Calendar(calendarEl, {
            editable: true,
            droppable: true,
            selectable: true,
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            weekNumbers: true,
            headerToolbar: {
                left: 'prev,next today',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                center: 'title',
            },
            // firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
            longPressDelay: 1,
            events: SITEURL + "/cal-appointment-show",
            // displayEventTime: true,
            dayMaxEventRows: true, // for all non-TimeGrid views
            // allDaySlot: false,
            views: {
                timeGrid: {
                    dayMaxEventRows: 5 // adjust to 6 only for timeGridWeek/timeGridDay
                }
            },
            select: function (date) {
                var start = date.start;
                var dt = moment(start).format('YYYY-MM-DD');
                $('#selected_date').html(moment(start).format('YYYY-MM-DD'));
                $('#appointment_list').hide();
                $('#new_list').show();
                $.ajax({
                    method: 'get',
                    url: aplist_url,
                    data: { date: dt },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status == 'error') {
                            $('#new_list').html('<h6>' + response.message + moment(start).format('YYYY-MM-DD') + '</h6>');
                        } else {
                            var t = 1;
                            var data = response.appointments;
                            var list = '<table class="table table-bordered dt-responsive nowrap datatable" style="border-collapse: collapse; border-spacing: 0; width: 100%;"><thead class="thead-light"><tr><th>Sr.No</th>';
                            if (response.role == 'doctor') {
                                list += '<th>Patient Name</th>';
                                list += '<th>Patient Number</th>';
                            } else if (response.role == 'patient') {
                                list += '<th>Doctor Name</th>';
                                list += '<th>Doctor Number</th>';
                            } else {
                                list += '<th>Patient Name</th><th>Doctor Name</th>';
                                list += '<th>Patient Number</th>';
                            }

                            list += '<th>Time</th></tr></thead><tbody>';
                            if (response.role == 'receptionist') {

                                $.each(data, function (i, appointments) {
                                    let DFirst_name = appointments.doctor.user.first_name;
                                    let DLast_name = appointments.doctor.user.last_name;
                                    let PFirst_name = appointments.patient.first_name;
                                    let PLast_name = appointments.patient.last_name;
                                    let from = appointments.time_slot.from;
                                    let to = appointments.time_slot.to;
                                    let mobile = appointments.patient.mobile
                                    list += "<tr><td>" + t + "</td><td>" + DFirst_name + "&nbsp;" + DLast_name + "</td><td>" + PFirst_name + "&nbsp;" + PLast_name + "</td><td>" + mobile + "</td><td>" + from + " to " + to +
                                        "</td></td>";
                                    t++;
                                });

                            } else if (response.role == 'patient') {
                                $.each(data, function (i, appointments) {
                                    let first_name = appointments.doctor.user.first_name;
                                    let last_name = appointments.doctor.user.last_name;
                                    let from = appointments.time_slot.from;
                                    let to = appointments.time_slot.to;
                                    let mobile = appointments.doctor.user.mobile
                                    list += "<tr><td>" + t + "</td><td>" + first_name + "&nbsp;" + last_name + "</td><td>" + mobile + "</td><td>" + from + " to " + to +
                                        "</td></td>";
                                    t++;
                                });

                            } else if (response.role == 'doctor') {
                                $.each(data, function (i, appointments) {
                                    let first_name = appointments.patient.first_name;
                                    let last_name = appointments.patient.last_name;
                                    let from = appointments.time_slot.from;
                                    let to = appointments.time_slot.to;
                                    let mobile = appointments.patient.mobile
                                    list += "<tr><td>" + t + "</td><td>" + first_name + "&nbsp;" + last_name + "</td><td>" + mobile + "</td><td>" + from + " to " + to +
                                        "</td></td>";
                                    t++;
                                });

                            }
                            list += "</tbody></table>";

                            $('#new_list').html(list);
                        }
                    },
                    error: function () {
                        console.log('Errors...Something went wrong!!!!');
                    }
                });
            },
            events: function (date, callback) {
                var start = moment(date.start).format('YYYY-MM-DD')
                var end = moment(date.end).format('YYYY-MM-DD')
                $.ajax({
                    type: "get",
                    url: "/cal-appointment-show",
                    data: {
                        start: start,
                        end: end,
                        title: 'appointment',
                    },
                    success: function (response) {
                        var appEvents = [];
                        $(response.appointments).each(function (key, value) {
                            if (value.total_appointment == 1) {
                                var badge = value.total_appointment + ' Appointment'
                            } else if (value.total_appointment > 1) {
                                var badge = value.total_appointment + ' Appointments'
                            }
                            appEvents.push({
                                title: badge,
                                start: value.appointment_date,
                                end: value.appointment_date,
                                className: 'bg-success text-white',
                            });
                        });
                        callback(appEvents);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            },
        });
        calendar.render();
    }
    //init
    $.CalendarPage = new CalendarPage, $.CalendarPage.Constructor = CalendarPage
}(window.jQuery),

    //initializing 
    function ($) {
        "use strict";
        $.CalendarPage.init()
    }(window.jQuery);