<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://nvcourts.gov/__data/assets/css_file/0013/40225/calendar-styles.css">
        <!-- <link rel="stylesheet" href="calendar-styles.css"> -->
        <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
        <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/index.global.min.js'></script>
        <title>Supreme Court Calendar</title>
    </head>
    <script>
        
        $(document).ready(function() {
            let oralArgEventsURL = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/OralArguments`;
            let supremeCourtEventsURL = `https://nvcourts.gov/_designs/components/custom_components/scn_calendar/tools/events_ds.json?node=1796`;
            let adktHearingsURL = `https://nvcourts.gov/_designs/components/custom_components/scn_calendar/tools/events_ds.json?node=1798`;
            let supremeCourtEvents;
            let adktHearings;
            let oralArgEvents;
            var loader = document.getElementsByClassName('loader');
            var calendar = document.getElementsByClassName('calendar-object');
            var calendarContainer = document.getElementsByClassName('calendar-container');
            loaderSwitch = function(turnOffOn){
                if (turnOffOn) {
                    loader[0].classList.remove('hide-div');
                    calendar[0].classList.add('add-opacity');

                }else {
                    loader[0].classList.add('hide-div');
                    calendar[0].classList.remove('add-opacity');
                }
            }
            // Calendar plugin docs: https://fullcalendar.io/docs
            initializeCalendar = function() {
                var calendarEvents = [];
                for(let i = 0; i < oralArgEvents.length; i++){
                    //Grab the first two characters of the duration string, which is the length of the event (30 mins turns into 30(integer))
                    var duration = parseInt((oralArgEvents[i].duration).substr(0,2));
                    //Returns a moment object and our end time is attribute '_d'
                    var endTime = moment(oralArgEvents[i].start).add(duration, 'minutes')._d;
                    // console.log("endTime: ", endTime);
                    var tempEvent = {
                        id: oralArgEvents[i].id,
                        title: oralArgEvents[i].title,
                        start: oralArgEvents[i].start,
                        end: endTime,
                        url: 'https://nvcourts.gov/supreme/arguments/upcoming_oral_argument_synopses',
                        eventColor: '#cd4a35',
                        color: '#cd4a35'
                    };
                    // console.log("Event: ", tempEvent);
                    calendarEvents.push(tempEvent);

                }
                for(let i = 0; i < supremeCourtEvents.length; i++){
                    var tempEvent = {
                        id: supremeCourtEvents[i].id,
                        title: supremeCourtEvents[i].title,
                        start: supremeCourtEvents[i].start,
                        end: supremeCourtEvents[i].end,
                        allDay: supremeCourtEvents[i].allDay,
                        url: supremeCourtEvents[i].url,
                        backgroundColor: '#20639f',
                        borderColor: '#20639f'
                    };
                    // console.log("Event: ", tempEvent);
                    calendarEvents.push(tempEvent);
                }
                for(let i = 0; i < adktHearings.length; i++){
                    var tempEvent = {
                        id: adktHearings[i].id,
                        title: adktHearings[i].title,
                        start: adktHearings[i].start,
                        end: adktHearings[i].end,
                        allDay: adktHearings[i].allDay,
                        url: adktHearings[i].url,
                        color: '#0a7299'
                    };
                    // console.log("Event: ", tempEvent);
                    calendarEvents.push(tempEvent);
                }
                var calendarEl = document.getElementById('calendar-object');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        events: calendarEvents,
                        headerToolbar: {
                            left: 'prev,next,today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay' // user can switch between the two
                        },
                        buttonText: {
                            today: 'Today',
                            month: 'Month',
                            week: 'Week',
                            day: 'Day',
                        }
                    });
                    console.log("Calendar: ", calendar);
                    calendarContainer[0].classList.add('height-auto');
                    calendar.render();
                    loaderSwitch(0);
            }
            fetchEvents = function() {
                // loader[0].classList.remove('hide-div');
                // calendar[0].classList.add('add-opacity');
                loaderSwitch(1);
                fetch(oralArgEventsURL)
                .then((response) => response.json())
                .then((data) => {
                    oralArgEvents = data;
                    // console.log("Oral Argument Events:", oralArgEvents);
                    fetch(supremeCourtEventsURL)
                    .then((response) => response.json())
                    .then((data) => {
                        supremeCourtEvents = data;
                        // console.log("Supreme Court Events:", supremeCourtEvents);
                        fetch(adktHearingsURL)
                        .then((response) => response.json())
                        .then((data) => {
                            adktHearings = data;
                            // console.log("ADKT Hearings:", adktHearings);
                            initializeCalendar();
                        });
                    });
                });
            }
            fetchEvents();
        });

    </script>
    <body>
        <div class="calendar-container">
                <div class="loader hide-div"></div>
                <div id='calendar-object' class="calendar-object"></div>
        </div>
    </body>
</html>