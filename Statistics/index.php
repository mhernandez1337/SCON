<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://nvcourts.gov/__data/assets/css_file/0037/39988/styles.css">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://kit.fontawesome.com/4eb1d74c79.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <title>Statistics</title>
</head>

<body>
    <script>
        //Reference Page: image.png
        var tableData;
        
        $(document).ready(function() {
            
            //Receive the max and min year records from the database then create the year checkbox. No need to manually add/edit/remove years. Whatever is in the database will display in the checkbox.
            var yearSelection = document.getElementsByClassName('statistics-year');
            var minYear = 0;
            var maxYear = 0;
            var districtCourts = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 8888, 10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000, 100000, 110000];
            var generatedChart = 0;
            var filingsHighChart1;
            var filingsHighChart2;
             //Test
            //  url = 'https://localhost:7019/api/Statistics/YearSelection';
            //Production
            url = 'https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Statistics/YearSelection';
            fetch(url)
            .then((response) => response.json())
            .then((data) => {
                yearObject = data;
                //Assign the min and max years
                console.log("Year", yearObject);
                minYear = yearObject[0];
                maxYear = yearObject[1];

                //Start with the minimum year and increment it until we get the the max year.
                for(minYear; minYear <= maxYear; minYear++){
                    for(var i = 0; i < 2; i++){
                        //Create an option element that will be used to store the years.
                        option = document.createElement('option');
                        option.setAttribute('value', minYear);
                        option.innerHTML = minYear;
                        //Current year is selected by default on page load.
                        if(minYear === maxYear){
                            option.setAttribute('selected', 'selected');
                        }
                        //Append the option to the year selection element. 
                        yearSelection[i].appendChild(option); 
                    }
                }
            });

            //Get all the courts in the database.
            //Test
            // url = 'https://localhost:7019/api/Courts';
            //Production
            url = 'https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Courts';
            fetch(url)
            .then((response) => response.json())
            .then((data) => {
                var courts = data;
                //Grab the element that holds all the courts. Each court will be a checkbox along with its label. 
                courtList = document.getElementsByClassName('court-list');
                for(var i = 0; i < Object.keys(courts).length; i++){
                    for(var j = 0; j < 2; j++){
                        //Create an input that will be a checkbox
                        checkbox = document.createElement('input');
                        checkbox.setAttribute('type', 'checkbox');
                        checkbox.setAttribute('value', courts[i].id);
                        var className = `chart-courtslist-${j+1}`;
                        checkbox.setAttribute('class', className);
                        //Add the court name as the ID and Name attributes
                        checkbox.setAttribute('id', `${courts[i].courtname.replaceAll(" ", "-")}-${j+1}`);
                        checkbox.setAttribute('name', `${courts[i].courtname}`);

                        //Create the label element for the previously made checkbox
                        label = document.createElement('label');
                        label.setAttribute('for', `${courts[i].courtname.replaceAll(" ", "-")}-${j+1}`);
                        label.innerHTML = courts[i].courtname;

                        //Create a <br> element that will be used after the label.
                        br = document.createElement('br');

                        //Append the checkbox, label, and <br> elements. This will create a single court.
                        courtList[j].appendChild(checkbox);
                        courtList[j].appendChild(label);
                        courtList[j].appendChild(br);

                        //If the next court's type is different than the previous one, we'll seperate the court types by a "------------".
                        if(i < Object.keys(courts).length - 1 && courts[i].typeId != courts[i + 1].typeId){
                            p = document.createElement('p');
                            p.innerHTML = "------------------";
                            courtList[j].appendChild(p);
                        }
                    }  
                }
                //The court list is intitally hidden because some of the courts checkboxes were hardcoded. Some of those hardcodes court checkboxes are All Courts, All District Courts, 1st Judicial District, 8th Judicial District, etc. These court options are not stored in the databse; thus, needing to hard code them. Once all the courts are pulled and organized as checkboxes, all the courts become visibile by removing the 'hide-div' class.
                courtList[0].classList.remove('hide-div');
                courtList[1].classList.remove('hide-div');
            });

            //Function used to build the statistics table. 
            getTableStatistics = function() {
                //Since there are two court checkbox lists, we'll grab the first one, which is linked to the stats table.
                var courtList = document.getElementsByClassName('chart-courtslist-1');
                var loader = document.getElementsByClassName('loader');
                var statsTable = document.getElementById('stats-table');

                //We'll add opacity to the table during the loading phase of pulling the data. This is a minor UI feature to show the user that the data is loading.
                statsTable.classList.add('add-opacity');
                //Remove the 'hide-div' class on the loader so it becomes visible. Again, this will only appearing during the data loading phase.
                loader[0].classList.remove('hide-div');
                var checkedCourts = [];
                var courtNames = [];
                var url = ``;
                //Clear table function is used to empty all the values that may have been added to the table. 
                clearTable();

                //We iterate through all of the court checkboxes and see which ones were checked. If it was checked, then push the court name to the courtNames array and the court name to the checkedCourts array. The courtNames array is used to add the court name to the header of the table. 
                for(var i = 0; i < courtList.length; i++){
                    if(courtList[i].checked){
                        courtNames.push(courtList[i].name);
                        checkedCourts.push(courtList[i]);
                    }
                }
                //If the user checks no courts, then alert the user that they need to select a court.
                if(checkedCourts.length == 0){
                    alert('Please select a minimum of one court.');
                } 
                //If the total amount of courts selected is more than two, then alert the user that they can only select up to two courts.
                else if(checkedCourts.length > 2){
                    alert('You can only compare 2 sets of statistics at a time.');
                }else{
                    if(checkedCourts.length == 1){
                        //If one court is selected then place the court option into the URL as the first parameter, 0 for the second court option parameter, and the year selection as the third parameter.
                        //Test
                        // url = `https://localhost:7019/api/Statistics/${checkedCourts[0].value}/0/${yearSelection[0].value}`;
                        //Production
                        url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Statistics/${checkedCourts[0].value}/0/${yearSelection[0].value}`;
                    }else if (checkedCourts.length == 2){
                        //If two courts are selected then place the first court option into the URL as the first parameter, the second court option as the second court option parameter, and the year selection as the third parameter.4
                        //Test
                        // url = `https://localhost:7019/api/Statistics/${checkedCourts[0].value}/${checkedCourts[1].value}/${yearSelection[0].value}`;
                        //Production
                        url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Statistics/${checkedCourts[0].value}/${checkedCourts[1].value}/${yearSelection[0].value}`;
                    }
                    fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        //Convert the response data to a json variable.
                        tableData = JSON.parse(JSON.stringify(data));
                        console.log("tableData: ", tableData);
                        
                        //Add the year to the first table header
                        document.getElementById('title-column-fiscal-year').innerHTML = `Fiscal year ${yearSelection[0].value}`;

                        //Call the buildTable function to build the table for the first court.
                        buildTable(1, tableData[0], courtNames[0]);
                               
                        //If a second option was selected, call the buildTable function again to build the table for the second court option.
                        if(tableData.length == 2){
                            buildTable(2, tableData[1], courtNames[1]);
                        }
                        //By adding the hide-div class to the loader, the loader is hidden and then we remove the opacity to the chart table. This ends the loading data phase.
                        loader[0].classList.add('hide-div');
                        statsTable.classList.remove('add-opacity');
                    });
                }
                
            }

            //Option = court option, tempCheckedCourts = the courts that were checked, tempCourtName = the name of the courts selected.
            //Builds the court column for a given court option. 
            buildTable = function(option, tempCheckedCourts, tempCourtName) {
                console.log("tempCheckedCourts: ", districtCourts.includes(tempCheckedCourts.courtID));
                //Calculate the Total non-traffic filings, grand total filings, total non-traffic dipsos, grand total dispos, non-traffic clearance rate, traffic clearance rate, and overall clearance reate
                var totalNonTrafficFilings = tempCheckedCourts.crimFilings + tempCheckedCourts.civilFilings + tempCheckedCourts.famFilings + tempCheckedCourts.juvFilings;
                var grandTotalFilings = totalNonTrafficFilings + tempCheckedCourts.trafficFilings;
                var totalNonTrafficDispo = tempCheckedCourts.crimDispo + tempCheckedCourts.civilDispo + tempCheckedCourts.famDispo + tempCheckedCourts.juvDispo;
                var grandTotalDispo = totalNonTrafficDispo + tempCheckedCourts.trafficDispo;
                var nonTrafficClearanceRate = (totalNonTrafficFilings) ? (totalNonTrafficDispo / totalNonTrafficFilings) * 100 : 0;
                //Verify we aren't dividing by zero which causes a NaN error
                var trafficClearanceRate;
                (tempCheckedCourts.trafficFilings == 0) ? trafficClearanceRate = 0 : trafficClearanceRate = (tempCheckedCourts.trafficDispo / tempCheckedCourts.trafficFilings) * 100;
                var overallClearanceRate;
                (totalNonTrafficFilings + tempCheckedCourts.trafficFilings == 0) ? overallClearanceRate = 0 : overallClearanceRate = (((totalNonTrafficDispo + tempCheckedCourts.trafficDispo) / (totalNonTrafficFilings + tempCheckedCourts.trafficFilings)) * 100);

                //Declare cariables that will hold the filings and dispo data.
                var civilFilings;
                var famFilings;
                var jubFilings;
                var civilDispos;
                var famDispos;
                var juvDispos;

                //Add the filing and dispo data to either the first or second court option (based on the option passed through)
                document.getElementById(`title-column-court-${option}`).innerHTML = tempCourtName;
                document.getElementById(`crf-${option}`).innerHTML = (tempCheckedCourts.crimFilings).toLocaleString();

                //If civil, family, or juvenil filings are zero, add No Jurisdction to the table unless it's a district court
                (tempCheckedCourts.civilFilings) ? civilFilings = (tempCheckedCourts.civilFilings).toLocaleString() : civilFilings = 0;
                document.getElementById(`cvf-${option}`).innerHTML = civilFilings;

                (tempCheckedCourts.famFilings || districtCourts.includes(tempCheckedCourts.courtID)) ? famFilings = (tempCheckedCourts.famFilings).toLocaleString() : famFilings = "No Jurisdiction";
                document.getElementById(`famf-${option}`).innerHTML = famFilings;

                (tempCheckedCourts.juvFilings || districtCourts.includes(tempCheckedCourts.courtID)) ? juvFilings = (tempCheckedCourts.juvFilings).toLocaleString() : juvFilings = "No Jurisdiction";
                document.getElementById(`juvf-${option}`).innerHTML = juvFilings;

                //Continue adding filing and dispo data to either the first or second court option (based on the option passed through)
                document.getElementById(`total-non-traffic-file-${option}`).innerHTML = (totalNonTrafficFilings).toLocaleString();
                document.getElementById(`trf-${option}`).innerHTML = (tempCheckedCourts.trafficFilings).toLocaleString();
                document.getElementById(`grand-total-filings-${option}`).innerHTML = (grandTotalFilings).toLocaleString() + "*";
                document.getElementById(`crd-${option}`).innerHTML = (tempCheckedCourts.crimDispo).toLocaleString();

                //If civil, family, or juvenile filings are zero, add No Jurisdction to the table unless a district court
                (tempCheckedCourts.civilDispo) ? civilDispo = (tempCheckedCourts.civilDispo).toLocaleString() : civilDispo = 0;
                document.getElementById(`cvd-${option}`).innerHTML = civilDispo;

                (tempCheckedCourts.famDispo || districtCourts.includes(tempCheckedCourts.courtID)) ? famDispo = (tempCheckedCourts.famDispo).toLocaleString() : famDispo = "No Jurisdiction";
                document.getElementById(`famd-${option}`).innerHTML = famDispo;

                (tempCheckedCourts.juvDispo || districtCourts.includes(tempCheckedCourts.courtID)) ? juvDispo = (tempCheckedCourts.juvDispo).toLocaleString() : juvDispo = "No Jurisdiction";
                document.getElementById(`juvd-${option}`).innerHTML = juvDispo;

                //Continue adding filing and dispo data to either the first or second court option (based on the option passed through)
                document.getElementById(`total-non-traff-disp-${option}`).innerHTML = (totalNonTrafficDispo).toLocaleString();
                document.getElementById(`trd-${option}`).innerHTML = (tempCheckedCourts.trafficDispo).toLocaleString();
                document.getElementById(`grand-total-dispo-${option}`).innerHTML = (grandTotalDispo).toLocaleString() + "*";
                document.getElementById(`non-traffic-clearance-rate-${option}`).innerHTML = `${nonTrafficClearanceRate.toFixed(1)}%`;
                document.getElementById(`traffic-clearance-rate-${option}`).innerHTML = `${trafficClearanceRate.toFixed(1)}%`;
                document.getElementById(`overall-clearance-rate-${option}`).innerHTML = `${overallClearanceRate.toFixed(1)}%` + "*";
            }

            //Pulls the statistics data and generates a graph
            getChartStatistics = function() {
                
                //Grab all the court inputs for the second court checkbox list.
                var courtList = document.getElementsByClassName('chart-courtslist-2');
                //Grab the graph type from the radio buttons
                var graphType = document.getElementsByClassName('graph-type');
                var loader = document.getElementsByClassName('loader');
                var filingsChart = document.getElementById('filings-chart-container');
                var disposChart = document.getElementById('dispos-chart-container');

                //Get the type of graph the user selected
                for(var i = 0; i < 2; i++){
                    if(graphType[i].checked){
                        graphType = graphType[i].value;
                    }
                }
                var checkedCourts = [];
                var courtNames = [];
                var url = ``;

                //We iterate through all of the court checkboxes and see which ones were checked. If it was checked, then push the court name to the courtNames array and the court name to the checkedCourts array. The courtNames array is used to add the court name to the header of the table. 
                for(var i = 0; i < courtList.length; i++){
                    if(courtList[i].checked){
                        courtNames.push(courtList[i].name);
                        checkedCourts.push(courtList[i]);
                    }
                }
                //Initialize the highcharts component
                var H = Highcharts;
                //Add commas to the values in the View Data Table option.
                H.addEvent(H.Chart, 'aftergetTableAST', function(e) {
                    e.tree.children[2].children.forEach(function(row) {
                        row.children.forEach(function(cell, i) {
                            if (i !== 0) {
                                row.children[i].textContent = cell.textContent.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        });
                    });
                });
                //Add commas to the chart values.
                Highcharts.setOptions({
                    lang: {
                        thousandsSep: ','
                    }
                });

                //We use a switch statement to evaluate whether a line or bar graph was selected.
                switch(graphType){
                    case 'line':
                        //Alert the user if no court is selected
                        if(checkedCourts.length == 0){
                            alert('Please select one court.');
                        } 
                        //Alert the user if more than one court is selected. Line graphs only allow one user.
                        else if(checkedCourts.length > 1){
                            alert('Only one set of statistics can be shown at a time on the line graph');
                        }else{
                            //If one court is selected we begin the loading phase. We remove the 'hide-div' class from the loader which will make it visible and we add opacity to both the filings and dispo charts.
                            loader[1].classList.remove('hide-div');
                            filingsChart.classList.add("add-opacity");
                            disposChart.classList.add('add-opacity');

                            //Add the court option to the first and only url parameter
                            //Test
                            // url = `https://localhost:7019/api/Statistics/Line/${checkedCourts[0].value}`;
                            //Production
                            url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Statistics/Line/${checkedCourts[0].value}`;

                            fetch(url)
                            .then((response) => response.json())
                            .then((data) => {
                                tableData = JSON.parse(JSON.stringify(data));
                                
                                //Declare arrays that will hold our line data for each of the different filings and dispositions
                                var civilFilings = [];
                                var crimFilings = [];
                                var famFilings = [];
                                var juvFilings = [];
                                var trafficFilings = [];
                                var civilDispos = [];
                                var crimDispos = [];
                                var famDispos = [];
                                var juvDispos = [];
                                var trafficDispos = [];
                                var seriesFilingsVariable = [];
                                var seriesDisposVariable = [];
                                var categories = [];

                                //Iterate through all the returned data and plae them into the correct filing and dispo arrays
                                for(var i = tableData.length - 1; i >= 0 ; i--){
                                    civilFilings[(tableData.length -1) - i] = tableData[i].civilFilings;
                                    crimFilings[(tableData.length -1) - i] = tableData[i].crimFilings;
                                    famFilings[(tableData.length -1) - i] = tableData[i].famFilings;
                                    juvFilings[(tableData.length -1) - i] = tableData[i].juvFilings;
                                    trafficFilings[(tableData.length -1) - i] = tableData[i].trafficFilings;
                                    civilDispos[(tableData.length -1) - i] = tableData[i].civilDispo;
                                    crimDispos[(tableData.length -1) - i] = tableData[i].crimDispo;
                                    famDispos[(tableData.length -1) - i] = tableData[i].famDispo;
                                    juvDispos[(tableData.length -1) - i] = tableData[i].juvDispo;
                                    trafficDispos[(tableData.length -1) - i] = tableData[i].trafficDispo;

                                }

                                // Once the data has been placed into the correct arrays, we will iterate through each of the arrays to verify that none of the values for every year isn't zero. An example is Municipal courts do not process juvenile filings or dispositions; thus, we should remove the juvenile values from the graphs. We iterate through each of the arrays and total the counts for every array for every year. If any are zero, then we omit them from the graph.
                                //In order the data goes Civil, Criminal, Juvenile, Family, and Traffic. 

                                //Intitalize a counter that starts at 0.
                                var civCounter = 0;
                                //Iterate through the array to verify if any data exists throughtout all the years. If data is more than zero, add the name of the data to the graph data. 
                                civilFilings.forEach(element => {
                                    civCounter += element;
                                });
                                if(civCounter){
                                    seriesFilingsVariable.push({
                                        name: 'Civil Filings',
                                        data: civilFilings
                                    })
                                }
                                civCounter = 0;
                                civilDispos.forEach(element => {
                                    civCounter += element;
                                });
                                if(civCounter){
                                    seriesDisposVariable.push({
                                        name: 'Civil Dispositions',
                                        data: civilDispos
                                    })
                                }

                                //Since every court will hold criminal data, we do not need to check criminal data. We simply will add it.
                                seriesFilingsVariable.push({
                                    name: 'Criminal Filings',
                                    data: crimFilings
                                })
                                seriesDisposVariable.push({
                                    name: 'Criminal Dispositions',
                                    data: crimDispos
                                })

                                //If Civil, Family or Juvenile Filings are 0, then we won't include the data on the graph.
                                var famCounter = 0;
                                famFilings.forEach(element => {
                                    famCounter += element;
                                });
                                if(famCounter){
                                    seriesFilingsVariable.push({
                                        name: 'Family Filings',
                                        data: famFilings
                                    })
                                }
                                var juvCounter = 0;
                                juvFilings.forEach(element => {
                                    juvCounter += element;
                                });
                                if(juvCounter){
                                    seriesFilingsVariable.push({
                                        name: 'Juvenile Filings',
                                        data: juvFilings
                                    })
                                }
                                
                                //If Family or Juvenile Dispositions are 0, then we won't include the data on the graph.
                                famCounter = 0;
                                famDispos.forEach(element => {
                                    famCounter += element;
                                });
                                if(famCounter){
                                    seriesDisposVariable.push({
                                        name: 'Family Dispositions',
                                        data: famDispos
                                    })
                                }
                                juvCounter = 0;
                                juvDispos.forEach(element => {
                                    juvCounter += element;
                                });
                                if(juvCounter){
                                    seriesDisposVariable.push({
                                        name: 'Juvenile Dispositions',
                                        data: juvDispos
                                    })
                                }

                                //If Traffic Dispositions are 0, then we won't include the data on the graph.
                                var trafficCounter = 0;
                                trafficFilings.forEach(element => {
                                    trafficCounter += element
                                });
                                if(trafficCounter){
                                    seriesFilingsVariable.push({
                                    name: 'Traffic Filings',
                                    data: trafficFilings
                                    })
                                }
                                trafficCounter = 0;
                                trafficDispos.forEach(element => {
                                    trafficCounter += element;
                                });
                                if(trafficCounter){
                                    seriesDisposVariable.push({
                                    name: 'Traffic Dispositions',
                                    data: trafficDispos
                                    })
                                }
                                //Hide the loaning spinner once the data has been downloaded and organized.
                                loader[1].classList.add('hide-div');
                                filingsChart.classList.remove("add-opacity");
                                disposChart.classList.remove('add-opacity');
                                
                                //Fill the chart with previously collected data.
                                //Reference: https://www.highcharts.com/demo\
                                
                                if(generatedChart){
                                    $('.highcharts-data-table').remove();
                                }
                                generatedChart = 1;
                                filingsHighChart1 = Highcharts.chart('filings-chart-container', {
                                    title: {
                                    text: `${checkedCourts[0].name}`,
                                    align: 'center'
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Number of Filings'
                                        }
                                    },

                                    xAxis: {
                                        type: 'category',
                                        allowDecimals: false,
                                        labels: {
                                            step: 1
                                        }
                                    },

                                    legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                    },

                                    plotOptions: {
                                    series: {
                                        label: {
                                        connectorAllowed: false
                                        },
                                        pointStart: 2009
                                    }
                                    },

                                    series: seriesFilingsVariable,
                                
                                    responsive: {
                                        rules: [{
                                            condition: {
                                            maxWidth: 500
                                            },
                                            chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                            }
                                        }]
                                    }
                                });
                                filingsHighChart2 = Highcharts.chart('dispos-chart-container', {
                                    title: {
                                    text: `${checkedCourts[0].name}`,
                                    align: 'center'
                                    },
                                    yAxis: {
                                    title: {
                                        text: 'Number of Dispositions'
                                    }
                                    },

                                    xAxis: {
                                        type: 'category',
                                        allowDecimals: false,
                                        labels: {
                                            step: 1
                                        }
                                    },

                                    legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                    },

                                    plotOptions: {
                                    series: {
                                        label: {
                                        connectorAllowed: false
                                        },
                                        pointStart: 2009
                                    }
                                    },

                                    series: seriesDisposVariable,

                                    responsive: {
                                        rules: [{
                                            condition: {
                                            maxWidth: 500
                                            },
                                            chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                            }
                                        }]
                                    }
                                });
                            });
                        }
                        
                        break;
                    case 'bar':
                        //The bar graphs works a little differently than the line. The graph will group the data by statistic type(criminal, civil, etc), and then list the individual courts that are selected. 
                        //NOTE: You can select an unlimited number of courts to graph.

                        //Initialize empty arrays for the court IDs and names.
                        var tempCourtIDArray = [];
                        var tempCourtNameArray = [];

                        //iterate through all of the checked courts and place their respecive ID and name into the above arrays
                        for(var i = 0; i < checkedCourts.length; i++){
                            tempCourtIDArray.push(parseInt(checkedCourts[i].value))
                            tempCourtNameArray.push(checkedCourts[i].name);
                        }
                        
                        //Alert the user if no court is selected
                        if(checkedCourts.length == 0){
                            alert('Please select one court.')
                        }else{
                            //Begin the loading phase by adding oppacity to the graphs and making the loader appear.
                            filingsChart.classList.add("add-opacity");
                            disposChart.classList.add('add-opacity');
                            loader[1].classList.remove('hide-div');

                            //Prepare the URL by adding the year selection to the only url paramater
                            //Test
                            // url = `https://localhost:7019/api/Statistics/Bar/${yearSelection[1].value}`;
                            //Production
                            url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/Statistics/Bar/${yearSelection[1].value}`;

                            //For the bar, we use a POST http call. We add a body which contains all of the court ID options, which will be interpreted by the API
                            fetch(url, {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({
                                    courtIDs: tempCourtIDArray
                                }),
                            })
                            .then((response) => response.json())
                            .then((data) => {
                                //Create a json variable with the data response
                                tableData = JSON.parse(JSON.stringify(data));

                                //Declare empty arrays to hold out data
                                var filingsData = [];
                                var disposData = [];
                                var seriesFilingsVariable = [];
                                var seriesDisposVariable = [];
                                var filingsCategories = [];
                                var disposCategories = [];

                                //Declare variables to see if criminal, civil, family, juvenile, or traffic stats exists. If they do not exist, we will not add them to the graph.
                                var crimFilingsExists = false;
                                var civilFilingsExists = false;
                                var famFilingsExists = false;
                                var juvFilingsExists = false;
                                var trafficFilingsExists = false;
                                var crimDisposExists = false;
                                var civilDisposExists = false;
                                var famDisposExists = false;
                                var juvDisposExists = false;
                                var trafficDisposExists = false;
                                for(var i = 0; i < tableData.length; i++){
                                    //Verify none of the data is 0. We don't want to graph 0 values.
                                    //Calculate whether filings exists by iterating through all of the records from the POST response. If data exits, we add a category for it on the graph.
                                    if(tableData[i].crimFilings > 0 && !crimFilingsExists){
                                        filingsCategories.push("Criminal");
                                        crimFilingsExists = true;
                                    }
                                    if(tableData[i].civilFilings > 0 && !civilFilingsExists){
                                        filingsCategories.push("Civil");
                                        civilFilingsExists = true;
                                    }
                                    if(tableData[i].famFilings > 0 && !famFilingsExists){
                                        filingsCategories.push("Family");
                                        famFilingsExists = true;
                                    }
                                    if(tableData[i].juvFilings > 0 && !juvFilingsExists){
                                        filingsCategories.push("Juvenile");
                                        juvFilingsExists = true;
                                    }
                                    if(tableData[i].trafficFilings > 0 && !trafficFilingsExists){
                                        filingsCategories.push("Traffic");
                                        trafficFilingsExists = true;
                                    }

                                    //Calculate whether dispos exists
                                    if(tableData[i].crimFilings > 0 && !crimDisposExists){
                                        disposCategories.push("Criminal");
                                        crimDisposExists = true;
                                    }
                                    if(tableData[i].civilFilings > 0 && !civilDisposExists){
                                        disposCategories.push("Civil");
                                        civilDisposExists = true;
                                    }
                                    if(tableData[i].famFilings > 0 && !famDisposExists){
                                        disposCategories.push("Family");
                                        famDisposExists = true;
                                    }
                                    if(tableData[i].juvFilings > 0 && !juvDisposExists){
                                        disposCategories.push("Juvenile");
                                        juvDisposExists = true;
                                    }
                                    if(tableData[i].trafficFilings > 0 && !trafficDisposExists){
                                        disposCategories.push("Traffic");
                                        trafficDisposExists = true;
                                    }

                                }

                                //Iterate through the court ID options that were selected, and if records exists for the given record types, add them to the filings/dispo data array. 
                                for(var i = 0; i < tempCourtNameArray.length; i++){
                                    //Filings are first. If the filings are greater than zero and we confirmed that the criminal filings exists then push the criminal data to the filings data array.
                                    if(tableData[i].crimFilings > 0 || crimFilingsExists){
                                        filingsData.push(tableData[i].crimFilings)
                                    }
                                    if(tableData[i].civilFilings > 0 || civilFilingsExists){
                                        filingsData.push(tableData[i].civilFilings)
                                    }
                                    if(tableData[i].famFilings > 0 || famFilingsExists){
                                        filingsData.push(tableData[i].famFilings)
                                    }
                                    if(tableData[i].juvFilings > 0 || juvFilingsExists){
                                        filingsData.push(tableData[i].juvFilings)
                                    }
                                    if(tableData[i].trafficFilings > 0 || trafficFilingsExists){
                                        filingsData.push(tableData[i].trafficFilings)
                                    }
                                    //Dispositions are next
                                    if(tableData[i].crimDispo > 0 || crimDisposExists){
                                        disposData.push(tableData[i].crimDispo)
                                    }
                                    if(tableData[i].civilDispo > 0 || civilDisposExists){
                                        disposData.push(tableData[i].civilDispo)
                                    }
                                    if(tableData[i].famDispo > 0 || famDisposExists){
                                        disposData.push(tableData[i].famDispo)
                                    }
                                    if(tableData[i].juvDispo > 0 || juvDisposExists){
                                        disposData.push(tableData[i].juvDispo)
                                    }
                                    if(tableData[i].trafficDispo > 0 || trafficDisposExists){
                                        disposData.push(tableData[i].trafficDispo)
                                    }

                                    //Push the records into the series arrays. The series arrays are used in the chart object. 
                                    if(filingsData.length != 0){
                                        seriesFilingsVariable.push({
                                            name: tempCourtNameArray[i],
                                            data: filingsData
                                        });
                                        
                                        seriesDisposVariable.push({
                                            name: tempCourtNameArray[i],
                                            data: disposData
                                        });
                                        //reset the filings and dispo arrays.
                                        filingsData = [];
                                        disposData = [];
                                    }
                                }

                                //Grab the second loader and hide it to end the loading phase. Remove opacity from the tables.
                                loader[1].classList.add('hide-div');
                                filingsChart.classList.remove("add-opacity");
                                disposChart.classList.remove('add-opacity');

                                if(generatedChart){
                                    $('.highcharts-data-table').remove();
                                }
                                generatedChart = 1;
                                //Fill the chart with previously collected data.
                                //Reference: https://www.highcharts.com/demo
                                Highcharts.chart('filings-chart-container', {
                                    chart:{
                                        type: 'column'
                                    },
                                    title: {
                                    text: `Filings FY(${yearSelection[1].value})`,
                                    align: 'center'
                                    },
                                    yAxis: {
                                    title: {
                                        text: 'Number of Filings'
                                    }
                                    },

                                    xAxis: {
                                        categories: filingsCategories
                                    },
                                    
                                    legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                    },
                                    series: seriesFilingsVariable,

                                    responsive: {
                                        rules: [{
                                            condition: {
                                            maxWidth: 500
                                            },
                                            chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                            }
                                        }]
                                    }
                                });
                                Highcharts.chart('dispos-chart-container', {
                                    chart:{
                                        type: 'column'
                                    },
                                    title: {
                                    text: `Dispositions FY(${yearSelection[1].value})`,
                                    align: 'center'
                                    },
                                    yAxis: {
                                    title: {
                                        text: 'Number of Dispositions'
                                    }
                                    },

                                    xAxis: {
                                        categories: disposCategories
                                    },

                                    legend: {
                                    layout: 'horizontal',
                                    align: 'center',
                                    verticalAlign: 'bottom'
                                    },

                                    series: seriesDisposVariable,

                                    responsive: {
                                        rules: [{
                                            condition: {
                                            maxWidth: 500
                                            },
                                            chartOptions: {
                                            legend: {
                                                layout: 'horizontal',
                                                align: 'center',
                                                verticalAlign: 'bottom'
                                            }
                                            }
                                        }]
                                    }
                                });
                            });
                        }
                        break;
                }
                
            }

            //Hide the year selection if the line option is chosen.
            hideYearSelection = function(temp){
                var yearSelection = document.getElementsByClassName('var-year-select');
                (temp == 'line') ? yearSelection[1].classList.add('hide-div') : yearSelection[1].classList.remove('hide-div') 
            }

            //Option = which court checkbox list; 0 = top and 1 = bottom.
            //Clear the court checkboxs by passing the option.
            clearSelection = function(option){
                var checkboxList = document.getElementsByClassName(`chart-courtslist-${option}`);
                for(var i = 0; i < checkboxList.length; i++){
                    checkboxList[i].checked = false;
                }
            }

            //Clears the statistics table by removing all the information previously added in the buildTable function.
            clearTable = function(){
                for(var i = 0; i < 2; i++){
                    document.getElementById(`title-column-court-${i+1}`).innerHTML = "";
                    document.getElementById(`crf-${i+1}`).innerHTML = "";
                    document.getElementById(`cvf-${i+1}`).innerHTML = "";
                    document.getElementById(`famf-${i+1}`).innerHTML = "";
                    document.getElementById(`juvf-${i+1}`).innerHTML = "";
                    document.getElementById(`total-non-traffic-file-${i+1}`).innerHTML = "";
                    document.getElementById(`trf-${i+1}`).innerHTML = "";
                    document.getElementById(`grand-total-filings-${i+1}`).innerHTML = "";
                    document.getElementById(`crd-${i+1}`).innerHTML = "";
                    document.getElementById(`cvd-${i+1}`).innerHTML = "";
                    document.getElementById(`famd-${i+1}`).innerHTML = "";
                    document.getElementById(`juvd-${i+1}`).innerHTML = "";
                    document.getElementById(`total-non-traff-disp-${i+1}`).innerHTML = "";
                    document.getElementById(`trd-${i+1}`).innerHTML = "";
                    document.getElementById(`grand-total-dispo-${i+1}`).innerHTML = "";
                    document.getElementById(`non-traffic-clearance-rate-${i+1}`).innerHTML = "";
                    document.getElementById(`traffic-clearance-rate-${i+1}`).innerHTML = "";
                    document.getElementById(`overall-clearance-rate-${i+1}`).innerHTML = "";
                }
            }
        });
    </script>
    
    <div class="stats-page-container">
        <div id="statistics-wrapper">
            <div class="statistics-table-wrapper">
                <div class="statistics-table">
                    <div id="variable-selection">
                        <div id="var-header">
                            <p><b>Select Court to Compare</b></p>
                            <p>Select up to two courts</p>
                        </div>
                        <div class="var-year-select">
                            <p>Select Year:</p>
                            <div id="year-selection">
                                <select name="year" class="statistics-year"></select>
                            </div>
                        </div>
                        <div id="input-btns">
                            <input type="button" value="Show Statistics" onClick="getTableStatistics()" />
                            <input type="submit" name="" value="Clear Selections" id="" onClick="clearSelection(1)"/>
                        </div>
                        <div class="court-list hide-div">
                            <input type="checkbox" class="chart-courtslist-1"  name="All Courts" value="9999" id="all-courts-1">
                            <label for="all-courts-1">All Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="All District Courts" value="8888" id="all-district-courts-1">
                            <label for="all-district-courts-1">All District Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="All Justice Courts" value="7777" id="all-justice-courts-1">
                            <label for="all-justice-courts-1">All Justice Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="All Municipal Courts" value="6666" id="all-municipal-courts-1">
                            <label for="all-municipal-courts-1">All Municipal Courts</label></br>
                            <p>------------------</p>
                            <input type="checkbox" class="chart-courtslist-1"  name="1st Judicial District (All Courts)" value="10000" id="1st-judicial-district-1">
                            <label for="1st-judicial-district-1">1st Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="2nd Judicial District (All Courts)" value="20000" id="2nd-judicial-district-1">
                            <label for="2nd-judicial-district-1">2nd Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="3rd Judicial District (All Courts)" value="30000" id="3rd-judicial-district-1">
                            <label for="3rd-judicial-district-1">3rd Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="4th Judicial District (All Courts)" value="40000" id="4th-judicial-district-1">
                            <label for="4th-judicial-district-1">4th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="5th Judicial District (All Courts)" value="50000" id="5th-judicial-district-1">
                            <label for="5th-judicial-district-1">5th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="6th Judicial District (All Courts)" value="60000" id="6th-judicial-district-1">
                            <label for="6th-judicial-district-1">6th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="7th Judicial District (All Courts)" value="70000" id="7th-judicial-district-1">
                            <label for="7th-judicial-district-1">7th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="8th Judicial District (All Courts)" value="80000" id="8th-judicial-district-1">
                            <label for="8th-judicial-district-1">8th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="9th Judicial District (All Courts)" value="90000" id="9th-judicial-district-1">
                            <label for="9th-judicial-district-1">9th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="10th Judicial District (All Courts)" value="100000" id="10th-judicial-district-1">
                            <label for="10th-judicial-district-1">10th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-1"  name="11th Judicial District (All Courts)" value="110000" id="11th-judicial-district-1">
                            <label for="11th-judicial-district-1">11th Judicial District (All Courts)</label></br>
                            <p>------------------</p>
                        </div>
                    </div>
                </div>
                <div class="statistics-table">
                    <div class="loader hide-div"></div>
                    <table id="stats-table"> 
                        <tbody> 
                            <tr> 
                                <th id="title-column-fiscal-year"></th> 
                                <th id="title-column-court-1"></th> 
                                <th id="title-column-court-2"></th> 
                            </tr> 
                            <tr>
                                <td>Criminal Filings</td>
                                <td id="crf-1"></td>
                                <td id="crf-2"></td>
                            </tr>
                            <tr>
                                <td>Civil Filings</td>
                                <td id="cvf-1"></td>
                                <td id="cvf-2"></td>
                            </tr>
                            <tr>
                                <td>Family Filings</td>
                                <td id="famf-1"></td>
                                <td id="famf-2"></td>
                            </tr>
                            <tr>
                                <td>Juvenile Filings</td>
                                <td id="juvf-1"></td>
                                <td id="juvf-2"></td>
                            </tr>
                            <tr>
                                <td><i>Total Non-Traffic Filings</i></td>
                                <td id="total-non-traffic-file-1"></td>
                                <td id="total-non-traffic-file-2"></td>
                            </tr>
                            <tr>
                                <td>Traffic Filings</td>
                                <td id="trf-1"></td>
                                <td id="trf-2"></td>
                            </tr>
                            <tr>
                                <td><strong>Grand Total Filings</strong></td>
                                <td id="grand-total-filings-1"></td>
                                <td id="grand-total-filings-2"></td>
                            </tr>
                            <tr>
                                <td>Criminal Dispositions</td>
                                <td id="crd-1"></td>
                                <td id="crd-2"></td>
                            </tr>
                            <tr>
                                <td>Civil Dispositions</td>
                                <td id="cvd-1"></td>
                                <td id="cvd-2"></td>
                            </tr>
                            <tr>
                                <td>Family Dispositions</td>
                                <td id="famd-1"></td>
                                <td id="famd-2"></td>
                            </tr>
                            <tr>
                                <td>Juvenile Dispositions</td>
                                <td id="juvd-1"></td>
                                <td id="juvd-2"></td>
                            </tr>
                            <tr>
                                <td class="italic-text"><i>Total Non-Traffic Dispositions</i></td>
                                <td id="total-non-traff-disp-1"></td>
                                <td id="total-non-traff-disp-2"></td>
                            </tr>
                            <tr>
                                <td>Traffic Dispositions</td>
                                <td id="trd-1"></td>
                                <td id="trd-2"></td>
                            </tr>
                            <tr>
                                <td><strong>Grand Total Dispositions</strong></td>
                                <td id="grand-total-dispo-1"></td>
                                <td id="grand-total-dispo-2"></td>
                            </tr>
                            <tr>
                                <td>Non-Traffic Clearance Rate</td>
                                <td id="non-traffic-clearance-rate-1"></td>
                                <td id="non-traffic-clearance-rate-2"></td>
                            </tr>
                            <tr>
                                <td>Traffic Clearance Rate</td>
                                <td id="traffic-clearance-rate-1"></td>
                                <td id="traffic-clearance-rate-2"></td>
                            </tr>
                            <tr>
                                <td><strong>Overall Clearance Rate</strong></td>
                                <td id="overall-clearance-rate-1"></td>
                                <td id="overall-clearance-rate-2"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-size:Small;">* The Annual Report of the Nevada Judiciary contains additional data, as well as important footnotes about this information. The Annual Report can be found <a href="https://nvcourts.gov/supreme/reports/annual_reports">here.</a></td>
                            </tr>
                        </tbody> 
                    </table> 
                </div>
                <div class="statistics-table">
                    <div class="courtMap">
                        <img class="courtMap__img" src="https://nvcourts.gov/__data/assets/image/0010/10405/nevada20district20map.png" alt="Nevada Judicial District Map" usemap="#courtMap__imgMap" style="border-color:Transparent;">
                        <map name="courtMap__imgMap" id="courtMap__imgMap">
                            <area shape="poly" coords="184,13,321,13,322,120,232,121,223,92,223,71,184,72" href="https://nvcourts.gov/__data/assets/pdf_file/0017/23417/4th_Judicial_District_.pdf" target="_blank" target="_blank" title="Elko County" alt="Elko County">
                            <area shape="poly" coords="83,13,182,13,183,90,174,90,172,94,170,89,156,89,157,78,107,79,107,71,84,72" href="https://nvcourts.gov/__data/assets/pdf_file/0014/23504/6th_Judicial_District_.pdf" target="_blank" alt="Humboldt County">
                            <area shape="poly" coords="49,13,80,12,81,127,88,129,85,134,86,141,88,147,82,150,76,149,73,152,68,152,63,156,64,162,61,168,55,173,49,172" href="https://nvcourts.gov/__data/assets/pdf_file/0018/23427/2nd_Judicial_District_.pdf" target="_blank" title="Washoe County" alt="Washoe County">
                            <area shape="poly" coords="83,74,105,72,105,80,154,80,155,90,169,90,174,96,160,126,83,127" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23844/11th_Judicial_District_.pdf" target="_blank" title="Pershing County" alt="Pershing County">
                            <area shape="poly" coords="238,122,322,124,321,204,276,206,233,176,234,164,238,154,235,146,240,137,235,127" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23484/7th_Judicial_District_.pdf" target="_blank" title="White Pine County" alt="White Pine County">
                            <area shape="poly" coords="184,73,199,74,201,177,166,176,151,182,153,175,154,174,153,171,149,169,155,163,154,160,165,154,162,141,164,134,160,127,176,93,185,92" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23844/11th_Judicial_District_.pdf" target="_blank" title="Lander County" alt="Lander County">
                            <area shape="poly" coords="276,206,321,207,321,308,237,310,235,244,276,244" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23484/7th_Judicial_District_.pdf" target="_blank" title="Lincoln County" alt="Lincoln County">
                            <area shape="poly" coords="132,188,148,182,167,177,233,177,274,205,274,242,235,242,234,356,176,302,177,244,131,193" href="https://nvcourts.gov/__data/assets/pdf_file/0017/23444/5th_Judicial_District_.pdf" target="_blank" title="Nye County" alt="Nye County">
                            <area shape="poly" coords="88,128,160,129,163,136,160,141,164,152,154,158,153,163,148,168,153,174,149,176,149,181,107,180,89,162,93,154,92,144,88,142,87,134" href="https://nvcourts.gov/__data/assets/pdf_file/0027/23778/10th_Judicial_District_.pdf" target="_blank" title="Churchill County" alt="Churchill County">
                            <area shape="poly" coords="202,73,222,73,220,90,230,122,237,122,234,128,238,138,235,145,237,154,232,163,233,176,203,177" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23484/7th_Judicial_District_.pdf" target="_blank" title="Eureka County" alt="Eureka County">
                            <area shape="poly" coords="101,183,138,183,130,186,130,193,154,220,148,220,121,250,88,220,99,220,99,194,95,193" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23844/11th_Judicial_District_.pdf" target="_blank" title="Mineral County" alt="Mineral County">
                            <area shape="poly" coords="149,221,156,222,176,246,176,301,122,251" href="https://nvcourts.gov/__data/assets/pdf_file/0017/23444/5th_Judicial_District_.pdf" target="_blank" title="Esmeralada County" alt="Esmeralada County">
                            <area shape="poly" coords="237,312,322,311,323,344,316,355,309,348,298,348,290,352,289,362,295,366,292,368,301,397,301,405,294,413,235,358" href="https://nvcourts.gov/__data/assets/pdf_file/0022/23467/8th_Judicial_District_.pdf" target="_blank" title="Clark County" alt="Clark County">
                            <area shape="poly" coords="64,329,82,322,95,299,109,290,105,298,110,305,105,318,130,346,121,347,111,361,113,370,121,369,120,409,103,410,89,398,90,386,81,364,89,356,90,346,75,346,74,336" href="https://nvcourts.gov/__data/assets/pdf_file/0014/23432/3rd_Judicial_District_.pdf" target="_blank" title="Lyon County" alt="Lyon County">
                            <area shape="poly" coords="40,342,54,342,60,348,88,347,89,355,86,355,79,365,88,385,88,397,41,354" href="https://nvcourts.gov/__data/assets/pdf_file/0019/23509/9th_Judicial_District_.pdf" target="_blank" title="Douglas County" alt="Douglas County">
                            <area shape="poly" coords="70,302,79,302,88,297,94,299,80,321,63,328,62,321,67,316,64,307" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23394/1st_Judicial_District_.pdf" target="_blank" title="Storey County" alt="Storey County">
                            <area shape="poly" coords="40,334,40,342,54,340,60,346,74,346,74,337,62,330,52,336" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23394/1st_Judicial_District_.pdf" target="_blank" title="Carson City" alt="Carson City">
                            <area shape="poly" coords="83,150,90,147,91,153,87,161,103,180,99,181,92,191,98,195,97,217,87,219,79,211,80,203,75,197,74,190,82,185,80,179,72,179,71,174,65,171,74,166" href="https://nvcourts.gov/__data/assets/pdf_file/0014/23432/3rd_Judicial_District_.pdf" target="_blank" title="Lyon County" alt="Lyon County">
                            <area shape="poly" coords="49,178,58,178,80,180,75,189,73,197,78,210,49,186" href="https://nvcourts.gov/__data/assets/pdf_file/0019/23509/9th_Judicial_District_.pdf" target="_blank" title="Douglas County" alt="Douglas County">
                            <area shape="poly" coords="64,156,73,152,81,152,74,165,64,169,66,162" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23394/1st_Judicial_District_.pdf" target="_blank" title="Storey County" alt="Storey County">
                            <area shape="poly" coords="49,173,55,172,62,169,70,174,70,180,60,180,49,177" href="https://nvcourts.gov/__data/assets/pdf_file/0021/23394/1st_Judicial_District_.pdf" target="_blank" title="Carson City" alt="Carson City">
                        </map><br>
                        <span id="courtMap__imgNote" style="font-size:Small;"> Note: The numbers indicate the Judicial District to which the county is assigned.</span>
                    </div>
                </div>
            </div>

            <div class="statistics-table-wrapper">
                <div class="statistics-table">
                    <div id="variable-selection">
                        <div id="var-header">
                            <p><b>Select Courts to Graph</b></p>
                            <p><b>Draw As:</b></p>
                            <div id="chart-type">
                                <input type="radio" class="graph-type" id="line" value="line" name="graph-type" checked="checked" onclick='hideYearSelection("line")' style="margin-right:10px;">
                                <label for="line" style="margin-right:20px;">Line</label>
                                <input type="radio" id="bar" class="graph-type" value="bar" name="graph-type" onclick='hideYearSelection("bar")' style="margin-right:10px;">
                                <label for="bar">Bar</label><br>
                            </div>
                        </div>
                        <div class="var-year-select hide-div">
                            <p>Select Year:</p>
                            <div id="year-selection">
                                <select name="year" class="statistics-year"></select>
                            </div>
                        </div>
                        <div id="input-btns">
                            <input type="button" value="Show Statistics" onClick="getChartStatistics()" />
                            <input type="submit" name="" value="Clear Selections" id="" onClick="clearSelection(2)"/>
                        </div>
                        <div class="court-list hide-div">
                            <input type="checkbox" class="chart-courtslist-2"  name="All Courts" value="9999" id="all-courts-2">
                            <label for="all-courts-2">All Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="All District Courts" value="8888" id="all-district-courts-2">
                            <label for="all-district-courts-2">All District Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="All Justice Courts" value="7777" id="all-justice-courts-2">
                            <label for="all-justice-courts-2">All Justice Courts</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="All Municipal Courts" value="6666" id="all-municipal-courts-2">
                            <label for="all-municipal-courts-2">All Municipal Courts</label></br>
                            <p>------------------</p>
                            <input type="checkbox" class="chart-courtslist-2"  name="1st Judicial District (All Courts)" value="10000" id="1st-judicial-district-2">
                            <label for="1st-judicial-district-2">1st Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="2nd Judicial District (All Courts)" value="20000" id="2nd-judicial-district-2">
                            <label for="2nd-judicial-district-2">2nd Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="3rd Judicial District (All Courts)" value="30000" id="3rd-judicial-district-2">
                            <label for="3rd-judicial-district-2">3rd Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="4th Judicial District (All Courts)" value="40000" id="4th-judicial-district-2">
                            <label for="4th-judicial-district-2">4th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="5th Judicial District (All Courts)" value="50000" id="5th-judicial-district-2">
                            <label for="5th-judicial-district-2">5th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="6th Judicial District (All Courts)" value="60000" id="6th-judicial-district-2">
                            <label for="6th-judicial-district-2">6th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="7th Judicial District (All Courts)" value="70000" id="7th-judicial-district-2">
                            <label for="7th-judicial-district-2">7th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="8th Judicial District (All Courts)" value="80000" id="8th-judicial-district-2">
                            <label for="8th-judicial-district-2">8th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="9th Judicial District (All Courts)" value="90000" id="9th-judicial-district-2">
                            <label for="9th-judicial-district-2">9th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="10th Judicial District (All Courts)" value="100000" id="10th-judicial-district-2">
                            <label for="10th-judicial-district-2">10th Judicial District (All Courts)</label></br>
                            <input type="checkbox" class="chart-courtslist-2"  name="11th Judicial District (All Courts)" value="110000" id="11th-judicial-district-2">
                            <label for="11th-judicial-district-2">11th Judicial District (All Courts)</label></br>
                            <p>------------------</p>
                        </div>
                    </div>
                </div>
                <div class="statistics-table">
                    <div class="loader hide-div"></div>
                    <figure class="highcharts-figure">
                        <div id="filings-chart-container"></div>
                    </figure>
                    <figure class="highcharts-figure">
                        <div id="dispos-chart-container"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>