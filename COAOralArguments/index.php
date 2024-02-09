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
        <title>Court of Appeals Calendar</title>
    </head>
    <script>
        
        $(document).ready(function() {
            let coaOralArgEventsURL = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/COAOralArguments`;
            let coaEventsURL = `https://nvcourts.gov/_designs/components/custom_components/scn_calendar/tools/events_ds.json?node=1797`;
            let coaEvents;
            let adktHearings;
            let coaOralArgEvents;
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
                for(let i = 0; i < coaOralArgEvents.length; i++){
                    //Grab the first two characters of the duration string, which is the length of the event (30 mins turns into 30(integer))
                    var duration = parseInt((coaOralArgEvents[i].duration).substr(0,2));
                    //Returns a moment object and our end time is attribute '_d'
                    var endTime = moment(coaOralArgEvents[i].start).add(duration, 'minutes')._d;
                    // console.log("endTime: ", endTime);
                    var tempEvent = {
                        id: coaOralArgEvents[i].id,
                        title: coaOralArgEvents[i].title,
                        start: coaOralArgEvents[i].start,
                        end: endTime,
                        url: 'https://nvcourts.gov/supreme/arguments/court_of_appeals_upcoming_oral_argument_synopses',
                        eventColor: '#cd4a35',
                        color: '#cd4a35'
                    };
                    calendarEvents.push(tempEvent);
                }
                for(let i = 0; i < coaEvents.length; i++){
                    var tempEvent = {
                        id: coaEvents[i].id,
                        title: coaEvents[i].title,
                        start: coaEvents[i].start,
                        end: coaEvents[i].end,
                        allDay: coaEvents[i].allDay,
                        url: coaEvents[i].url,
                        backgroundColor: '#20639f',
                        borderColor: '#20639f'
                    };
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
                    calendarContainer[0].classList.add('height-auto');
                    calendar.render();
                    loaderSwitch(0);
            }
            fetchEvents = function() {
                loaderSwitch(1);
                fetch(coaOralArgEventsURL, {
                    method: 'GET',
                    headers: {
                        'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    coaOralArgEvents = data;
                    // console.log("Oral Argument Events:", coaOralArgEvents);
                    fetch(coaEventsURL)
                    .then((response) => response.json())
                    .then((data) => {
                        coaEvents = data;
                        // console.log("Supreme Court Events:", coaEvents);
                            initializeCalendar();
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