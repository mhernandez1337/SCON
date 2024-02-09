<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <title>Dynamic Table Template</title>
</head>
<body>
<script>
    "use strict";
    $(document).ready(function(){
        //Make the table wrapper variable accessible by all functions.
        var container = document.getElementsByClassName('container');
        var tableWrapper = document.getElementById('fac-table-wrapper');
        var loader = document.getElementsByClassName('loader');
        let url = ``;

        loaderSwitch(1);
        //HTTP call to the API to pull the court location data
        fetch(url)
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
        <div id="dynamic-table-wrapper"></div>
    </div>
    
</body>
</html>