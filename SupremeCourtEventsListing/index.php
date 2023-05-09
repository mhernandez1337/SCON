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
        <title>Supreme Court Events Listing</title>
    </head>
    <style>
        .event-listing-title {
            color: #19779a;
            text-decoration: underline;
            background-color: transparent;
            color: #19779a;
            display: block;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5rem;
            padding: 0;
            font-family: Muli,Helvetica,Arial,sans-serif;
        }
    </style>
    <script>
        
        $(document).ready(function() {
            let oralArgEventsURL = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/OralArguments`;
            let supremeCourtEventsURL = `https://nvcourts.gov/_designs/components/custom_components/scn_calendar/tools/events_ds.json?node=1796`;
            let adktHearingsURL = `https://nvcourts.gov/_designs/components/custom_components/scn_calendar/tools/events_ds.json?node=1798`;
            let supremeCourtEvents;
            let adktHearings;
            let oralArgEvents;
            var loader = document.getElementsByClassName('loader');
            var listingContainer = document.getElementById('supreme-event-listing-wrapper');
            //url: 'https://nvcourts.gov/supreme/arguments/upcoming_oral_argument_synopses',
            loaderSwitch = function(turnOffOn){
                if (turnOffOn) {
                    loader[0].classList.remove('hide-div');
                    // calendar[0].classList.add('add-opacity');

                }else {
                    loader[0].classList.add('hide-div');
                    // calendar[0].classList.remove('add-opacity');
                }
            }

            initializeEvents = function() {
                var eventsListing = [6];
                var monthEvents = [];
                var currentDate = moment(new Date()).format('MM/DD/YYYY');
                var maxDate = moment(currentDate).add(1, 'months').format('MM/DD/YYYY');
                
                //Create the container that will hold all the events
                var divEvent = document.createElement('div');
                divEvent.setAttribute('class', 'multicolumn-boxes-item__contents-text');

                //Create the ul item
                var ulEvent = document.createElement('ul');
                ulEvent.setAttribute('class', 'multicolumn-boxes-list');



                for(let i = 0; i < oralArgEvents.length; i++){
                    //Grab the first two characters of the duration string, which is the length of the event (30 mins turns into 30(integer))
                    var duration = parseInt((oralArgEvents[i].duration).substr(0,2));
                    //Returns a moment object and our end time is attribute '_d'
                    var start = moment(oralArgEvents[i].start).format('MM/DD/YYYY');
                    if(start > currentDate && start < maxDate){
                        monthEvents.push(oralArgEvents[i]);
                    }
                }
                for(let i = 0; i < supremeCourtEvents.length; i++){
                    var start = moment(supremeCourtEvents[i].start).format('MM/DD/YYYY');
                    if(start > currentDate && start < maxDate){
                        monthEvents.push(supremeCourtEvents[i]);
                    }
                }
                for(let i = 0; i < adktHearings.length; i++){
                    var start = moment(adktHearings[i].start).format('MM/DD/YYYY');
                    if(start > currentDate && start < maxDate){
                        monthEvents.push(adktHearings[i]);
                    }
                }

                //Sort the events by most recent. 
                var eventLength;
                (monthEvents.length > 4) ? eventLength = 4 : eventLength = monthEvents.length;
                monthEvents.sort((a,b) => new Date(a.start).getTime() - new Date(b.start).getTime())
                for(let i = 0; i < eventLength; i++){

                    //Create the individual li item
                    var liEvent = document.createElement('li');
                    liEvent.setAttribute('class', 'multicolumn-boxes-list-item');

                    //Create the title
                    var title = document.createElement('a');
                    title.setAttribute('class', 'multicolumn-boxes-list-link');
                    (monthEvents[i].url) ? title.setAttribute('href', monthEvents[i].url) : title.setAttribute('href', 'https://nvcourts.gov/supreme/arguments/upcoming_oral_argument_synopses');

                    //Create the time element
                    var time = document.createElement('span');
                    time.setAttribute('class', 'date');

                    title.innerHTML = monthEvents[i].title;
                    time.innerHTML = moment(monthEvents[i].start).format('MM/DD/YYYY hh:mm A');

                    liEvent.appendChild(title);
                    liEvent.appendChild(time);
                    ulEvent.appendChild(liEvent)
                    
                    
                }
                divEvent.appendChild(ulEvent);
                listingContainer.appendChild(divEvent);
                console.log("Monthly Events: ", monthEvents);
                loaderSwitch(0);
            }
            
            async function fetchEvents() {
                // loader[0].classList.remove('hide-div');
                // calendar[0].classList.add('add-opacity');
                loaderSwitch(1);
                await fetch(oralArgEventsURL)
                .then((response) => response.json())
                .then((data) => {
                    oralArgEvents = data;
                    // console.log("Oral Argument Events:", oralArgEvents);
                });
                await fetch(supremeCourtEventsURL)
                .then((response) => response.json())
                .then((data) => {
                    supremeCourtEvents = data;
                    // console.log("Supreme Court Events:", supremeCourtEvents);
                });
                await fetch(adktHearingsURL)
                .then((response) => response.json())
                .then((data) => {
                    adktHearings = data;
                    // console.log("ADKT Hearings:", adktHearings);
                    
                });
                initializeEvents();
            }
            fetchEvents();
        });

    </script>
    <body>
        <div class="event-listing-container">
                <div class="loader hide-div"></div>
                <div class="multicolumn-boxes-item__contents" id="supreme-event-listing-wrapper"></div>
        </div>
    </body>
</html>