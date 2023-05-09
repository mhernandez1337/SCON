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
    <title>Unpublished Orders</title>
</head>
<body>
    <script>
        //Reference Page: https://nvcourts.gov/supreme/decisions/unpublished_orders
        var tableData;

        // the function below is called whenever a new table is created. When a new year is evaluated, a new table is created.
        let createNewTablesHeaders = function() {
            table = document.createElement('table');
            tr = document.createElement('tr');
            //Create the headers manually. If you are copying this code, make sure you add or subtract whatever headers the new table will use.

            //Create the header element
            caseNumberTh = document.createElement('th');
            caseNumberTh.innerHTML = "Case Number";
            //Append the header element to the newly created TD
            tr.appendChild(caseNumberTh);

            var runningTitleTh = document.createElement('th');
            runningTitleTh.innerHTML = "Case Title";
            tr.appendChild(runningTitleTh);

            dateTh = document.createElement('th');
            dateTh.innerHTML = "Date";
            tr.appendChild(dateTh);

            tr.classList.add('dynamic-table-header')
            //Append the tr to the table.
            table.appendChild(tr);
            //Append table to the table wrapper. From here the rest of the code will generate the table data.
            wrapper.appendChild(table);
        }
        $(document).ready(function() {
            //Fetch data from API endpoint
            fetch('https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/UnpublishedOrders')
            .then((response) => response.json())
            .then((data) => {
                //Move the response data to a local variable
                tableData = JSON.parse(JSON.stringify(data));

                //Create a date variable, which is used to add the date to the date column of each record.
                date = new Date();

                // Find the year of the newest data. Since the data comes in as descending based on date, it grabs the top records, checks the date and prints it at the top of the page. Moment is used to easily format the to YYYY.
                currentYear = moment(`${tableData[0].date}`).format('YYYY');
                h2 = document.createElement('h2');
                h2.innerHTML = currentYear;
                
                //Find the wrapper variable
                wrapper = document.getElementById('dynamic-table-wrapper');

                //Append the year to the wrapper.
                wrapper.appendChild(h2);

                //Create the table with headers.
                createNewTablesHeaders();

                //Loop through each record and fill the table.
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
                            tr.appendChild(td);
                            previousTd = td.previousElementSibling;
                            // Create the new anchor tag
                            anchor = td.previousElementSibling.children[0];
                            anchor.setAttribute('href', childValue);
                            anchor.setAttribute('target', "_blank");
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