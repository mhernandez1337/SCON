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
    <title>Aging Submitted Cases</title>
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
        //Reference Page: https://nvcourts.gov/supreme/aging_submitted_case_report
        var tableData;
        let createNewTablesHeaders = function() {
            table = document.createElement('table');
            tr = document.createElement('tr');
            //Create the headers manually

            caseNumberTh = document.createElement('th');
            caseNumberTh.innerHTML = "Case Number";
            tr.appendChild(caseNumberTh);

            var shortTitleTh = document.createElement('th');
            shortTitleTh.innerHTML = "Short Title";
            tr.appendChild(shortTitleTh);

            dateTh = document.createElement('th');
            dateTh.innerHTML = "Submission Date";
            tr.appendChild(dateTh);

            tr.classList.add('dynamic-table-header')
            table.appendChild(tr);
            wrapper.appendChild(table);
        }
        let requestUrl = function(urlType, key){
            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/urlRequest/${urlType}/${key}`;
            // let url = `https://localhost:7019/api/urlRequest/${urlType}/${key}`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                },
            })
            .then((response) => response.json())
            .then((data) => {
                window.open(data.value);
            });
        }
        $(document).ready(function() {
            //Local development
            // let url = `https://localhost:7019/api/Aging-Submitted-Case-Report`;
            //Production
            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Aging-Submitted-Case-Report`;
            fetch(url, {
                method: 'GET',
                headers: {
                    'XApiKey': '080d4202-61b2-46c5-ad66-f479bf40be11'
                },
            })
            .then((response) => response.json())
            .then((data) => {
                tableData = JSON.parse(JSON.stringify(data));
                wrapper = document.getElementById('dynamic-table-wrapper');
                createNewTablesHeaders();

                for (const [parentKey, parentValue] of Object.entries(tableData)) {
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
                        } 
                        else if(childKey == 'caseurl'){
                            tr.appendChild(td);
                            previousTd = td.previousElementSibling;
                            // Create the new anchor tag
                            anchor = td.previousElementSibling.children[0];
                            anchor.setAttribute('href', "javascript:;");
                            anchor.setAttribute('onclick', `requestUrl("case", "${childValue}")`)
                            anchor.innerHTML = previousTd.innerHTML;
                            
                            td.remove();
                            previousTd.innerHTML = "";
                            previousTd.appendChild(anchor);
                        }
                        else if(childKey == 'submissionDate'){
                            date = moment(`${childValue}`).format('MMM D, YYYY');
                            td.innerHTML = `${date}`;
                            tr.appendChild(td);
                        }
                        else {
                            td.innerHTML = `${childValue}`;
                            tr.appendChild(td);
                        }
                    }
                }
            });
        });

    </script>
    <div class="container">
        <div id="dynamic-table-wrapper">
            <p>
                Below is a list of the cases which have been submitted to the Supreme Court for more than 90 days.
            </p>
        </div>
    </div>
    
</body>
</html>