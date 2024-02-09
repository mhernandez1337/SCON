<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../styles.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <title>Advance Opinions</title>
</head>
<body>
    <style>
        @media (max-width: 1100px){
            table {
                display: block;
                overflow-x: scroll;
            }
        }
    </style>
    <script>
        //Reference Page: https://nvcourts.gov/supreme/decisions/advance_opinions
        var tableData;
        let createNewTablesHeaders = function() {
            table = document.createElement('table');
            tr = document.createElement('tr');
            //Create the headers manually
            advanceNumTh = document.createElement('th');
            advanceNumTh.innerHTML = "Advance No.";
            tr.appendChild(advanceNumTh);

            caseNumberTh = document.createElement('th');
            caseNumberTh.innerHTML = "Case Number";
            tr.appendChild(caseNumberTh);

            var runningTitleTh = document.createElement('th');
            runningTitleTh.innerHTML = "Running Title";
            tr.appendChild(runningTitleTh);

            dateTh = document.createElement('th');
            dateTh.innerHTML = "Date";
            tr.appendChild(dateTh);

            tr.classList.add('dynamic-table-header')
            table.appendChild(tr);
            wrapper.appendChild(table);
        }
        let requestUrl = function(urlType, key){
            // let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/urlRequest/${urlType}/${key}`;
            let url = `https://localhost:7019/api/urlRequest/${urlType}/${key}`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                },
            })
            .then((response) => response.json())
            .then((data) => {
                // window.location.replace(data.value);
                var testTimerId;
                let autoDirect = function() {
                    window.location = data.value;
                }
                testTimerId = window.setTimeout(autoDirect, 30);
                
            });
        }
        $(document).ready(function() {
            //Local development
            let url = 'https://localhost:7019/api/AdvanceOpinions';
            //Production
            // let url = 'https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/AdvanceOpinions'
            fetch(url, {
                method: 'GET',
                headers: {
                    'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                },
            })
            .then((response) => response.json())
            .then((data) => {
                tableData = JSON.parse(JSON.stringify(data));
                console.log("Data: ", data);

                // Find the year of the newest data
                currentYear = moment(`${tableData[0].date}`).format('YYYY');
                h2 = document.createElement('h2');
                h2.innerHTML = currentYear;
                wrapper = document.getElementById('dynamic-table-wrapper');
                wrapper.appendChild(h2);
                createNewTablesHeaders();

                for (const [parentKey, parentValue] of Object.entries(tableData)) {
                    // console.log("Date", moment(`${parentValue.date}`).format('YYYY'));

                    // Find the year of the next record. If it's date is not equal to the previous year, then  we generate a new h2 element and create a new table.
                    if(moment(`${parentValue.date}`).format('YYYY') != currentYear){
                        currentYear = moment(`${parentValue.date}`).format('YYYY');
                        tempH2 = document.createElement('h2');
                        tempH2.innerHTML = currentYear;
                        wrapper.appendChild(tempH2);
                        createNewTablesHeaders();
                    }
                    // Continue creating the table.
                    var tr = document.createElement('tr');
                    var th = document.createElement('th');
                    table.appendChild(tr);
                    for (const [childKey, childValue] of Object.entries(parentValue)) {
                        var td = document.createElement('td');
                        if(childKey == 'caseNumber'){
                            td.innerHTML = `${childValue}`;

                            //Add anchor tag and append it to it's 'td'. 
                            a = document.createElement('a');
                            td.appendChild(a);
                            tr.appendChild(td);
                        } else if(childKey == 'docurl' || childKey == 'caseurl'){
                            let urlType = ""
                            if(childKey == 'docurl'){
                                urlType = "doc";
                            }else{
                                urlType = "case";
                            }
                            tr.appendChild(td);
                            previousTd = td.previousElementSibling;
                            // Create the new anchor tag
                            anchor = td.previousElementSibling.children[0];
                            anchor.setAttribute('href', "javascript:;");
                            anchor.setAttribute('onclick', `requestUrl("${urlType}", "${childValue}")`)
                            //anchor.setAttribute('target', "_blank");
                            if(childKey == 'docurl'){
                                // let date = new Date(childValue);
                                // console.log("Date: ", date);
                                anchor.innerHTML = moment(`${date}`).format('MMM D, YYYY');
                            }else{
                                anchor.innerHTML = previousTd.innerHTML;
                            }
                            
                            td.remove();
                            previousTd.innerHTML = "";
                            previousTd.appendChild(anchor);
                        }
                        else if(childKey == 'date'){
                            td.innerHTML = `${childValue}`;
                            date = moment(`${childValue}`).format('MMM D, YYYY');
                            a = document.createElement('a');
                            a.setAttribute('href', date);
                            td.appendChild(a);
                            tr.appendChild(td);
                            // tr.appendChild(td);
                        }
                        else {
                            td.innerHTML = `${childValue}`;
                            tr.appendChild(td);
                        }
                    }
                }

            });
        });
        // console.log("Data: ", tableData);dynamic-table-header

    </script>
    <div class="container">
        <div id="dynamic-table-wrapper"></div>
    </div>
    
</body>
</html>