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
    <title>Adminstrative Orders</title>
</head>
<body>
    <script>
        //Refernce Page: https://nvcourts.gov/supreme/decisions/administrative_orders
        var tableData;
        let createNewTablesHeaders = function() {
            table = document.createElement('table');
            tr = document.createElement('tr');
            //Create the headers manually
            ADKTNumTh = document.createElement('th');
            ADKTNumTh.innerHTML = "ADKT #";
            tr.appendChild(ADKTNumTh);

            caseTitleTh = document.createElement('th');
            caseTitleTh.innerHTML = "Case Title";
            tr.appendChild(caseTitleTh);

            var docTypeTh = document.createElement('th');
            docTypeTh.innerHTML = "Document Type";
            tr.appendChild(docTypeTh);

            dateTh = document.createElement('th');
            dateTh.innerHTML = "Date";
            tr.appendChild(dateTh);

            tr.classList.add('dynamic-table-header')
            table.appendChild(tr);
            wrapper.appendChild(table);
        }
        $(document).ready(function() {
            
            fetch('https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/AdminOrders')
            .then((response) => response.json())
            .then((data) => {
                tableData = JSON.parse(JSON.stringify(data));

                // Find the year of the newest data
                currentYear = moment(`${tableData[0].date}`).format('YYYY');
                h2 = document.createElement('h2');
                h2.innerHTML = currentYear;
                wrapper = document.getElementById('dynamic-table-wrapper');
                wrapper.appendChild(h2);
                createNewTablesHeaders();

                for (const [parentKey, parentValue] of Object.entries(tableData)) {
                    // console.log("Date", moment(`${parentValue.date}`).format('YYYY'));

                    // Find the year of the next record. If it's date is not equal to the previous year, then we generate a new h2 element and create a new table.
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
                        if(childKey == 'caseNumber' || childKey == 'doctype'){
                            td.innerHTML = `${childValue}`;

                            //Add anchor tag and append it to it's 'td'. 
                            a = document.createElement('a');
                            td.appendChild(a);
                            tr.appendChild(td);
                        }
                        else if(childKey == 'docurl' || childKey == 'caseurl'){
                            tr.appendChild(td);
                            previousTd = td.previousElementSibling;
                            // Create the new anchor tag
                            anchor = td.previousElementSibling.children[0];
                            anchor.setAttribute('href', childValue);
                            anchor.setAttribute('target', "_blank");
                            anchor.innerHTML = previousTd.innerHTML;
                            
                            td.remove();
                            previousTd.innerHTML = "";
                            previousTd.appendChild(anchor);
                        }
                        else if(childKey == 'date'){
                            let date = new Date(childValue);
                            td.innerHTML = moment(`${date}`).format('MMM D, YYYY');
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
        // console.log("Data: ", tableData);dynamic-table-header

    </script>
    <div class="container">
        <div id="dynamic-table-wrapper"></div>
    </div>
    
</body>
</html>