<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link rel="stylesheet" href="../styles.css"> -->
    <link rel="stylesheet" href="https://nvcourts.gov/__data/assets/css_file/0037/39988/styles.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <title>MapBox Plugin</title>
</head>
<body>
<style>
    a {
        cursor: pointer;
    }

    .container {
        position: relative;
    }

    .height-fit-content {
        height: fit-content !important;
    }

    .height-350{
        height: 350px;
    }

</style>
<script>
    "use strict";
    $(document).ready(function(){
        //Make the table wrapper variable accessible by all functions.
        var container = document.getElementsByClassName('container');
        var tableWrapper = document.getElementById('fac-table-wrapper');
        var loader = document.getElementsByClassName('loader');
        // let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Mapbox`;
        let url = `https://localhost:7019/api/Mapbox`;
        //Declare an array that will hold all the court data.
        var mapMarkers = [];
        //Set the Supreme Court product key
        mapboxgl.accessToken = 'pk.eyJ1IjoibnZ3ZWJtYXN0ZXIiLCJhIjoiY2llbHVhYm1uMDA1Mjh6a3Vhd2lsMWNoZiJ9.v7cPy88Cahy3gWdezyhBog';
        //Initialzie the mapbox plugin and it's initial variables. 
        const map = new mapboxgl.Map({
            container: "scnmap",
            zoom: 4.9,
            center: [-117.004395, 38.702659],
            style: "mapbox://styles/mapbox/streets-v11"
        });
        loaderSwitch(1);
        //HTTP call to the API to pull the court location data
        fetch(url, {
                method: 'GET',
                headers: {
                    'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                },
            })
        .then((response) => {
            //If the HTTP call is successful, move to the next then() function.
            if(response.ok){
                return response.json();
            }
            //If there's an error with the HTTP call, an error will be thrown. See the catch() function
            throw new Error(response.status + '. Please contact the administrator.');
        })
        //Succesfully API call
        .then((data) => {
            //Iterate through the data. 
            console.log("Data:", data);
            for(var i = 0; i < data.length; i++){
                //If statement will be removed once I can update the API
                if(data[i].courtid != 37){
                    //The tempAddressLink variable is used to dynamically create a Google Map link of each court.
                    var tempAddressLink;
                    tempAddressLink = "https://www.google.com/maps/place/" + data[i].street.replaceAll(" ", "+") + "+" + data[i].city.replaceAll(" ", "+") + "+" + data[i].state + "+" + data[i].zip;
                    //We create a temp object to hold each courts' data. 
                    var tempCourt = {
                        "name": data[i].courtname,
                        "lat": data[i].latitude,
                        "lng": data[i].longitude,
                        "address": data[i].street + ", " + data[i].city + ", " + data[i].state + " " + data[i].zip,
                        "phone": data[i].phone,
                        "website": (data[i].website) ? data[i].website : "",
                        "courtId": data[i].courtId,
                        "addressLink": tempAddressLink 
                    }
                }
                
                //Add the newly created tempCourt object to the mapMarkers array that was declared at the beginning of the code. 
                mapMarkers.push(tempCourt);
            }
            
            //Iterate through the newly populated mapMarkers array, which is comprised of each courts' data. We'll create a markerHtml object that will add use to assign as a popup when a marker is selected on the map. The popup displays the court's name, address, phone number, wesbite, and the Google Map link.
            mapMarkers.forEach( (m) => {
                // Define Marker markup
                let markerHtml = ``;
                // If the court doesn't have a website, the markerHtml object will not include website hyperlink.
                if(m.website == "" || !m.website){
                    markerHtml = `
                        <strong>${m.name}</strong><br/>
                        <a href='${m.addressLink}' target='_blank'>${m.address}</a><br/>
                        <a href="tel:${m.phone}">${m.phone}</a>
                    `;
                }else{
                    markerHtml = `
                        <strong>${m.name}</strong><br/>
                        <a href='${m.addressLink}' target='_blank'>${m.address}</a><br/>
                        <a href="tel:${m.phone}">${m.phone}</a><br/>
                        <a href='${m.website}' target='_blank'>Website</a>
                    `;
                }
                //Create and add the court marker by assigning the court's latitude and longitude points along with the respective markerHtml object. 
                let marker = new mapboxgl.Marker()
                    .setLngLat( [m.lng,m.lat] )
                    .setPopup(
                        new mapboxgl.Popup().setHTML(markerHtml)
                    )
                    .addTo(map);
            });
            
            //Turn off the loader.
            loaderSwitch(0);
        })
        .catch((e) => {
            //Write the error in the console.
            console.log(e);
            loaderSwitch(0);
        });
        // console.log("Data: ", tempData.length);
        
        //Loader swtich added for UI/UX purposes to show the user that the data is being loaded.
        function loaderSwitch (turnOffOn){
            if (turnOffOn) {
                loader[0].classList.remove('hide-div');
                container[0].classList.add('height-350');
                container[0].classList.remove('height-fit-content');
            }else {
                loader[0].classList.add('hide-div');
                container[0].classList.add('height-fit-content');
                container[0].classList.remove('height-350');
            }
        }
    });
</script>
    <div class="container">
        <div id="map-wrapper">
            <div class="loader hide-div"></div>
            <div id="scnmap" style="height: 450px; width: 100%;"></div>
        </div>
    </div>
    
</body>
</html>