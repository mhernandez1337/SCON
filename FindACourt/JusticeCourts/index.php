<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link rel="stylesheet" href="../../styles.css"> -->
    <link rel="stylesheet" href="https://nvcourts.gov/__data/assets/css_file/0037/39988/styles.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <title>Justice Courts</title>
</head>
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

        //courtType variables is used hold the type_id of the court type. In production, we'll locate the end of the url path and determine if the page should be rendering District, Municipal, or Municipal Courts. Additionally, when the court type is determined, the type ID of that court type is saved in the courtType variable, which is used in the HTTP call to the API.
        let courtType = 0;
        let urlString = window.location.pathname.split("/").pop();
        urlString = urlString.replace('_', ' ');
        urlString = urlString.toLowerCase()
                    .split(' ')
                    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
                    .join(' ');
        
        if(urlString == 'District Courts'){
            courtType = 1;
        }else if(urlString == 'Justice Courts'){
            courtType = 2;
        }else if(urlString == 'Municipal Courts'){
            courtType = 3;
        }

        displayAllCourts();
        //Initialize array to store names of all counties that will be used at the bottom of the page header.

        // console.log('Counties', counties[0]);
        function displayAllCourts(tempData) {
            //Override the title at the top of the page.
            document.getElementsByClassName('highlighted')[0].innerHTML = urlString;

            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/FindACourt/${courtType}` // production
            // let url = 'https://localhost:7019/api/FindACourt/1' // Dev
            clearContents();
            loaderSwitch(1);
            fetch(url)
            .then((response) => {
                if(response.ok){
                    return response.json();
                }
                throw new Error(response.status + '. Please contact the administrator.');
            })
            .then((data) => {
                //Succesfully API call

                let p = document.createElement("p");
                p.innerHTML = "Nevada's Justice Courts are limited jurisdiction courts handling matters detailed in Nevada Revised Statutes. The 65 Justices of the Peace at the 40 Justice Courts determine whether felony or gross misdemeanor cases have enough evidence to be bound over to a District Court for trial. In addition, Justice Courts preside over non-traffic misdemeanor, small claims, summary eviction, temporary protection, and traffic cases.";
                tableWrapper.appendChild(p);

                //Iterate through all of the courts, add the classes that Squiz currently used to style the pages, and append the elements starting with the innermost child and work our way to the dynamic table element.
                for(let i = 0; i < data.length; i++){

                    //Create and populate all the elements
                    let linkedTitle = document.createElement('div');
                    linkedTitle.classList.add('no-wysiwyg');
                    linkedTitle.classList.add('listing');
                    linkedTitle.classList.add('listing--linked-title');

                    let startingListingNode = document.createElement('div');
                    startingListingNode.classList.add('listing__items');
                    startingListingNode.setAttribute('id', 'starting-listing-node');

                    let article = document.createElement("article");
                    article.classList.add("listing-item");
                    article.classList.add("listing-item--generic");

                    let divContent = document.createElement("div");
                    divContent.classList.add("listing-item__content");

                    let divHeader = document.createElement("div");
                    divHeader.classList.add("listing-item__header");

                    let anchor = document.createElement("a");
                    anchor.classList.add("listing-item__title-link");
                    anchor.addEventListener(`click`, () => {displaySingleCourt(data[i].id, data[i].name)}, false);

                    let anchorH3 = document.createElement('h3');
                    anchorH3.classList.add('listing-item__title');
                    anchorH3.innerHTML = data[i].name;

                    //Append the elements starting from the inner most childern
                    anchor.appendChild(anchorH3);
                    divHeader.appendChild(anchor);
                    divContent.appendChild(divHeader);
                    article.appendChild(divContent);
                    startingListingNode.appendChild(article);
                    linkedTitle.appendChild(startingListingNode);
                    tableWrapper.appendChild(linkedTitle);

                    //Turn off the loader.
                    loaderSwitch(0);
                }
            })
            .catch((e) => {
                //Write the error in the console.
                console.log(e);
                loaderSwitch(0);
            });
            // console.log("Data: ", tempData.length);
        }

        function displaySingleCourt(courtID,courtName){
            clearContents();

            //Turn on the loader
            loaderSwitch(1);
            //Override the page header with the court name.
            document.getElementsByClassName('highlighted')[0].innerHTML = courtName;
            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/FindACourt/Court/${courtID}`; //Production
            // let url = `https://localhost:7019/api/FindACourt/Court/1`;//DEV
            fetch(url)
            .then((response) => {
                if(response.ok){
                    return response.json();
                }
                throw new Error(response.status + '. Please contact the administrator.');
            })
            .then((data) => {
                //Succesfully API call
                loaderSwitch(0);
                
                let br = document.createElement('br');

                //Begin adding content from API to page. First we'll create the elements and then populate them with the fetched data.
                let contactInfo = document.createElement('div');
                contactInfo.classList.add('design_group');
                contactInfo.setAttribute('id', 'contactInformation');
                contactInfo.setAttribute('name', 'contactInformation');

                let countyDiv = document.createElement('div');
                countyDiv.classList.add('design_group');
                countyDiv.setAttribute('id', 'county');
                countyDiv.setAttribute('name', 'county');

                let h4Title = document.createElement('h4');
                h4Title.innerHTML = data.county;
                
                //Append the county name at the top of the page.
                countyDiv.appendChild(h4Title);
                contactInfo.appendChild(countyDiv);

                let address = document.createElement('div');
                address.classList.add('design_group');
                address.setAttribute('id', 'Address');
                address.setAttribute('name', 'Address');

                let addressText = '';

                //If the secondary street address exists, add the secondary address to the overall court address.
                (data.street_2) 
                ? addressText = `${data.street_1} <br> ${data.street_2} <br> ${data.city}, ${data.state} ${data.zip}<br><br>`
                : addressText =  `${data.street_1} <br> ${data.city}, ${data.state} ${data.zip}<br><br>`;

                //Assign address values to the address DIV
                address.innerHTML = addressText;
                contactInfo.appendChild(address);

                //Create the contact section
                let contact = document.createElement('div');
                contact.classList.add('design_group');
                contact.setAttribute('id', 'Contact');
                contact.setAttribute('name', 'Contact');

                //If the court email exists, create and populate the court's email and append the elements to the partent contact section.
                if(data.email){
                    let emailDiv = document.createElement('div');
                    let emailAnchor = document.createElement('a');
                    emailDiv.innerHTML = 'Email: ';
                    emailAnchor.setAttribute('href', 'mailto:' + data.email);
                    emailAnchor.innerHTML = data.email;
                    emailDiv.appendChild(emailAnchor);
                    contactInfo.appendChild(emailDiv);
                }

                //If the court phone exists, create and populate the court's phone and append the elements to the partent contact section.
                if(data.phone){
                    let phoneDiv = document.createElement('div');
                    let phoneAnchor = document.createElement('a');
                    phoneDiv.innerHTML = 'Phone: ';
                    phoneAnchor.setAttribute('href', 'phone:' + data.phone);
                    (data.ext) ?  phoneAnchor.innerHTML = data.phone + " Ext: " + data.ext: phoneAnchor.innerHTML = data.phone;
                    phoneDiv.appendChild(phoneAnchor);
                    contactInfo.appendChild(phoneDiv);
                }

                //If the court fax exists, create and populate the court's fax and append the elements to the partent contact section.
                if(data.fax){
                    let faxDiv = document.createElement('div');
                    let faxAnchor = document.createElement('a');
                    faxDiv.innerHTML = 'Fax: ';
                    faxAnchor.setAttribute('href', 'phone:' + data.fax);
                    faxAnchor.innerHTML = data.fax;
                    faxDiv.appendChild(faxAnchor);
                    contactInfo.appendChild(faxDiv);
                }

                //If the court phone website, create and populate the court's website and append the elements to the partent contact section.
                if(data.website){
                    let websiteDiv = document.createElement('div');
                    let websiteAnchor = document.createElement('a');
                    websiteDiv.innerHTML = 'Website: ';
                    websiteAnchor.setAttribute('href', data.website);
                    websiteAnchor.setAttribute('target', '_blank');
                    websiteAnchor.innerHTML = data.website;
                    websiteDiv.appendChild(websiteAnchor);
                    contactInfo.appendChild(websiteDiv);
                }

                //If the court payment link exists, create and populate the court's payment link and append the elements to the partent contact section.
                if(data.payment_link){
                    let paymentDiv = document.createElement('div');
                    let paymentAnchor = document.createElement('a');
                    paymentDiv.innerHTML = 'Online Payment Link: ';
                    paymentAnchor.setAttribute('href', data.payment_link);
                    paymentAnchor.setAttribute('target', '_blank');
                    paymentAnchor.innerHTML = data.payment_link;
                    paymentDiv.appendChild(paymentAnchor);
                    contactInfo.appendChild(paymentDiv);
                }
                
                contactInfo.appendChild(br);
                tableWrapper.appendChild(contactInfo);

                //Append a back button to the end of the single court page. By doing so, we can go back to displaying all of the courts.
                let backButton = document.createElement('button');
                backButton.classList.add('carousel-item__cta');
                backButton.innerHTML = "Back";
                backButton.onclick = goBack;

                contactInfo.appendChild(backButton);
                
            })
            .catch((e) => {
                //Write the error in the console.
                console.log(e);
                loaderSwitch(0);
            });
        }

        //As the user interacts with the page, the clearContents function will clear all contents on the page, so that new data can be added.
        function clearContents() {
            tableWrapper.innerHTML = '';
        }

        //Function is attached to the back button, which will clear the page with current content and display all the courts.
        function goBack(){
            displayAllCourts();
        }

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
<body>
    <div class="container height-fit-content">
    <div class="loader hide-div"></div>
    <div id="fac-table-wrapper"></div>
    </div>
</body>
</html>