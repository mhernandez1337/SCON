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
            let advanceOpinionsURL = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/AdvanceOpinions`;
            let advanceOpinionsEvents;

            var loader = document.getElementsByClassName('loader');
            var listingContainer = document.getElementById('adv-opinion-event-listing-wrapper');
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

            initializeAOEvents = function() {
                
                //Create the container that will hold all the events
                var divEvent = document.createElement('div');
                divEvent.setAttribute('class', 'multicolumn-boxes-item__contents-text');

                //Create the ul item
                var ulEvent = document.createElement('ul');
                ulEvent.setAttribute('class', 'multicolumn-boxes-list');

                console.log('test');
                //Sort the events by most recent. 

                for(let i = 0; i < 4 ; i++){

                    //Create the individual li item
                    var liEvent = document.createElement('li');
                    liEvent.setAttribute('class', 'multicolumn-boxes-list-item');

                    //Create the title
                    var title = document.createElement('a');
                    title.setAttribute('class', 'multicolumn-boxes-list-link');
                    title.setAttribute('href', advanceOpinionsEvents[i].caseurl);
                    title.setAttribute('target', '_blank');

                    //Create the time element
                    var time = document.createElement('span');
                    time.setAttribute('class', 'date');

                    title.innerHTML = advanceOpinionsEvents[i].caseTitle;
                    time.innerHTML = moment(advanceOpinionsEvents[i].date).format('MM/DD/YYYY');

                    liEvent.appendChild(title);
                    liEvent.appendChild(time);
                    ulEvent.appendChild(liEvent)
                    
                    
                }
                divEvent.appendChild(ulEvent);
                listingContainer.appendChild(divEvent);
                // console.log("Monthly Events: ", advanceOpinionsEvents);
                loaderSwitch(0);
            }
            fetchAOEvents = function() {
                // loader[0].classList.remove('hide-div');
                // calendar[0].classList.add('add-opacity');
                loaderSwitch(1);
                fetch(advanceOpinionsURL)
                .then((response) => response.json())
                .then((data) => {
                    advanceOpinionsEvents = data;
                    // console.log("Advance Opinions", advanceOpinionsEvents[0].caseurl);
                    initializeAOEvents();
                });
            }
            fetchAOEvents();
        });

    </script>
    <body>
        <div class="event-listing-container">
                <div class="loader hide-div"></div>
                <div class="multicolumn-boxes-item__contents" id="adv-opinion-event-listing-wrapper"></div>
        </div>
    </body>
</html>