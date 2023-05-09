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
    <title>Nevada Judicial History Database Search</title>
</head>
<script>
    $(document).ready(function() {
        loaderSwitch = function(turnOffOn){
            if (turnOffOn) {
                loader[1].classList.remove('hide-div');
                historyTable.classList.add('add-opacity');

            }else {
                loader[0].classList.add('hide-div');
                loader[1].classList.add('hide-div');
                searchCriteria[0].classList.remove('add-opacity');
                historyTable.classList.remove('add-opacity');
            }
        }
        var pageNumber = 1;
        var loader = document.getElementsByClassName('loader');
        var searchCriteria = document.getElementsByClassName('criteria-search');
        var historyTable = document.getElementById('judicial-history-table-wrapper');
        var searchTable = document.getElementById('judicial-history-table');
        var viewMore = document.getElementById('view-more-wrapper');
        var prevNextBtns = document.getElementsByClassName('btn-wrapper');
        loader[0].classList.remove('hide-div');
        searchCriteria[0].classList.add('add-opacity');
        loaderSwitch(1);

        //Test
        // let url = `https://localhost:7019/api/JudicialHistoryParameters`;
        //Production
        let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/JudicialHistoryParameters`;
        //Local development
        fetch(url)
        .then((response) => response.json())
        .then((data) => {
            dropdownOptions = document.getElementsByClassName('judicial-history-dropdown');
            //The order the parameters come in is Judicial Position, Courts, Departments, and Counties.
            parameters = JSON.parse(JSON.stringify(data));

            //Each for loop iterates through each record of Judicial Positiions, Courts, Departments, and Counties.
            //Judicial Positions
            for(var i = 0; i < parameters[0].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', parameters[0][i].positionName);
                option.innerHTML = parameters[0][i].positionName;
                dropdownOptions[0].appendChild(option);
            }
            //Judicial Courts
            for(var i = 0; i < parameters[1].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', parameters[1][i].courtName);
                option.innerHTML = parameters[1][i].courtName;
                dropdownOptions[1].appendChild(option);
            }
            //Judicial Departments
            for(var i = 0; i < parameters[2].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', parameters[2][i].departmentName);
                option.innerHTML = parameters[2][i].departmentName;
                dropdownOptions[2].appendChild(option);
            }
            //Judicial Counties
            for(var i = 0; i < parameters[3].length; i++){
                var option = document.createElement('option');
                option.setAttribute('value', parameters[3][i].countyName);
                option.innerHTML = parameters[3][i].countyName;
                dropdownOptions[3].appendChild(option);
            }
            search(1);
        });

        search = function(resetPageNumber){
            if(resetPageNumber){
                 pageNumber = 1;
                 goBack();
            }

            var nextBtn = document.getElementById('next-btn');
            var prevBtn = document.getElementById('previous-btn');
            nextBtn.classList.add('disabled');
            prevBtn.classList.add('disabled');
            loaderSwitch(1);
            //Get all the search values.
            //We check to see if a value was selected, and if not make the value null
            var fname = document.getElementById('judicial-first-name').value != "" ? document.getElementById('judicial-first-name').value  : null;
            //Capitalize the first name
            (fname) ? fname = fname.charAt(0).toUpperCase() + fname.slice(1) : null;
            var lname = document.getElementById('judicial-last-name').value != "" ? document.getElementById('judicial-last-name').value  : null;
            //Capitalize the last name
            (lname) ? lname = lname.charAt(0).toUpperCase() + lname.slice(1) : null;
            var position = document.getElementById('judicial-position').value != "" ? document.getElementById('judicial-position').value  : null;
            var court = (document.getElementById('judicial-courts').value) != "" ? (document.getElementById('judicial-courts').value)  : null;
            var department = document.getElementById('judicial-department').value != "" ? document.getElementById('judicial-department').value  : null;
            var county = document.getElementById('judicial-county').value != "" ? document.getElementById('judicial-county').value  : null;

            var pageSize = document.getElementById('num-results').value;
            //Test
            // let url = `https://localhost:7019/api/JudicialHistory/Search`;
            //Production
            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/JudicialHistory/Search`;

            fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    first_name: fname,
                    last_name: lname,
                    position: position,
                    court: court,
                    department: department,
                    county: county,
                    page_number: pageNumber,
                    page_size: parseInt(pageSize)
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                searchData = JSON.parse(JSON.stringify(data[1]));
                var numSearchResults = data[0];
                
                
                console.log("Page Number: ", pageNumber);
                // console.log("Data: ", searchData);
                //Condition 1: the results fit all on the first page.
                if(((pageNumber * pageSize) > numSearchResults) && pageNumber == 1){
                    nextBtn.classList.add('disabled');
                    prevBtn.classList.add('disabled');
                }
                //Condition 2: The results can continue on page 2
                else if(((pageNumber * pageSize) < numSearchResults) && pageNumber == 1){
                    nextBtn.classList.remove('disabled');
                    prevBtn.classList.add('disabled');
                }
                //Condition 2: The results can continue on page 2
                else if(((pageNumber * pageSize) < numSearchResults) && pageNumber > 1){
                    nextBtn.classList.remove('disabled');
                    prevBtn.classList.remove('disabled');
                }
                //Condition 2: The results can continue on page 2
                else if(((pageNumber * pageSize) > numSearchResults) && pageNumber > 1){
                    nextBtn.classList.add('disabled');
                    prevBtn.classList.remove('disabled');
                }
                // else if (pageNumber == 1){
                //     nextBtn.classList.remove('disabled');
                //     prevBtn.classList.add('disabled');
                // }
                $('.temp-tr').remove();
                buildTable(searchData);
            });
        }

        buildTable = function(tempData) {
            var tbody = document.getElementById('judicial-history-tbody');
            // console.log("tbody: ", tempData);
            for(var i = 0; i < tempData.length; i++){
                //Initialize new variables to hold the new td elements
                var tr = document.createElement('tr');
                tr.setAttribute('class', 'temp-tr');
                var tdName = document.createElement('td');
                var tdCourtName = document.createElement('td');
                var tdJudicialPosition = document.createElement('td');
                var tdElectionDate = document.createElement('td');
                var tdTerm = document.createElement('td');
                var tdCounty = document.createElement('td');
                var tdMoreInfo = document.createElement('td');
                var infoAnchor = document.createElement('a');
                var beginTerm = (tempData[i].term_begin_date == '0000-00-00') ? 'NA' : moment(tempData[i].term_begin_date).format('MMM D, YYYY');
                var endTerm = (tempData[i].term_end_date == '0000-00-00') ? 'NA' : moment(tempData[i].term_end_date).format('MMM D, YYYY');
                infoAnchor.setAttribute('onclick', `viewMoreInfo(${tempData[i].id}, 1)`);
                //Assign values to <td>'s
                tdName.innerHTML = tempData[i].last_name + ', ' + tempData[i].first_name;
                tdName.setAttribute("align", "center");
                tr.appendChild(tdName);

                tdCourtName.innerHTML = tempData[i].courtName;
                tdCourtName.setAttribute("align", "left");
                tr.appendChild(tdCourtName);

                tdJudicialPosition.innerHTML = tempData[i].judicial_pos;
                tdJudicialPosition.setAttribute("align", "left");
                tr.appendChild(tdJudicialPosition);

                (tempData[i].election_date == '0000-00-00') ? tdElectionDate.innerHTML = 'NA' : tdElectionDate.innerHTML = moment(`${tempData[i].term_begin_date}`).format('MMM D, YYYY');
                tdElectionDate.setAttribute("align", "center");
                tr.appendChild(tdElectionDate);

                tdTerm.innerHTML = beginTerm + ' - ' + endTerm;
                tdTerm.setAttribute("align", "center");
                tr.appendChild(tdTerm);

                tdCounty.innerHTML = (tempData[i].county != "") ? tempData[i].county : "NA";
                tdCounty.setAttribute("align", "center");
                tr.appendChild(tdCounty);

                infoAnchor.innerHTML = "View";
                infoAnchor.setAttribute("class", "view-info-btn");
                tdMoreInfo.appendChild(infoAnchor);
                tdMoreInfo.setAttribute("align", "center");
                tr.appendChild(tdMoreInfo);

                tbody.appendChild(tr);
            }
            searchTable.style.visibility = "visible";
            searchTable.style.height = "auto";
            loaderSwitch(0);
        }

        next = function() {
            pageNumber++;
            search(0);
        }

        previous = function() {
            pageNumber--;
            search(0);
        }

        viewMoreInfo = function(id, turnOnOff){
            prevNextBtns[0].classList.add('remove-div');
            searchTable.classList.add('remove-div');
            viewMore.classList.add('show-div');
            viewMore.classList.add('add-opacity');
            loader[2].classList.remove('hide-div');
            historyTable.style.alignItems = "baseline !important";
            //Test
            // let url = `https://localhost:7019/api/JudicialHistory/Search/${id}`;
            //Production
            let url = `https://publicaccess.nvsupremecourt.us/WebSupplementalAPI/api/JudicialHistory/Search/${id}`;
            fetch(url)
            .then((response) => response.json())
            .then((data) => {
                searchData = JSON.parse(JSON.stringify(data));
                console.log("Judge: ", searchData);
                viewMore.classList.remove('add-opacity');
                loader[2].classList.add('hide-div');
                var nameP = document.getElementById('judge-name');
                var positionP = document.getElementById('judge-position');
                var courtP = document.getElementById('judge-court');
                var countyP = document.getElementById('judge-county');
                var electionP = document.getElementById('judge-election-date');
                var termP = document.getElementById('judge-term');
                var entreasonnameP = document.getElementById('judge-entreasonname');
                var termreasonnameP = document.getElementById('judge-termreasonname');
                var commentsP = document.getElementById('judge-comments');
                var beginTerm = (searchData.term_begin_date == '0000-00-00') ? 'NA' : moment(searchData.term_begin_date).format('MMM D, YYYY');
                var endTerm = (searchData.term_end_date == '0000-00-00') ? 'NA' : moment(searchData.term_end_date).format('MMM D, YYYY');
                nameP.innerHTML = `<b>Name:</b> ${searchData.first_name} ${searchData.middle_name} ${searchData.last_name}`;
                positionP.innerHTML = `<b>Judicial Position:</b> ${searchData.judicial_pos}`;
                courtP.innerHTML = `<b>Court:</b> ${searchData.courtName}`;
                (searchData.county == "") ? countyP.innerHTML = '<b>County:</b> NA' : countyP.innerHTML = `<b>County:</b> ${searchData.county}`;
                (searchData.election_date == "0000-00-00") ? electionP.innerHTML = `<b>Election Date:</b> NA` : electionP.innerHTML = `<b>Election Date:</b> ${moment(searchData.election_date).format('MMM D, YYYY')}`;

                termP.innerHTML = `<b>Term:</b> ${beginTerm} - ${endTerm}`;
                entreasonnameP.innerHTML = `<b>Term Begin Reason:</b> ${searchData.entreasonName}`;
                termreasonnameP.innerHTML = `<b>Term End Reason:</b>  ${searchData.termReasonName}`;
                commentsP.innerHTML = `<b>Comments:</b> ${searchData.comments}`;

            });
        }

        goBack = function() {
            prevNextBtns[0].classList.remove('remove-div');
            searchTable.classList.remove('remove-div');
            viewMore.classList.remove('show-div');
        }

    });
</script>
<body>
    <div class="container">
        <div id="judicial-history-wrapper">
            <div id="text-search-criteria">
                <p id="judicial-history-description">
                This database contains information, gathered from many resources, on terms of office for judges throughout Nevada since statehood. One of the primary sources of information from statehood to the 1980s was from a project that compiled all the judicial election information into one binder, which was sponsored by the <a href="https://nvcourts.gov/lawlibrary/judicial_historical_society/nevada_judicial_historical_society" target="_blank">Judicial Historical Society</a>. Much of the remaining information was from files within the Administrative Office of the Courts. Lastly, some of the information came from the courts who had completed their own historical review of records. <a href="https://nvcourts.gov/aoc/about_the_judicial_history_database" target="_blank">Learn about the creation of the Nevada Judicial History Database</a>.<br><br>

                We do recognize that some information may be missing or incomplete, especially as it relates to Municipal Courts. If you have additional information that may be of value to this effort, please send the information or source to <a href="email:aocmail@nvcourts.nv.gov">aocmail@nvcourts.nv.gov</a>.
                </p>
                <div class="criteria-search">
                    <div class="loader hide-div"></div>
                    <h2>Select Search Criteria:</h2>
                    <div class="criteria-input">
                        <label for="fname">First Name:</label>
                        <input type="text" name="fname" id="judicial-first-name">
                    </div>
                    <div class="criteria-input">
                        <label for="lname">Last Name:</label>
                        <input type="text" name="lname" id="judicial-last-name">
                    </div>
                    <div class="criteria-input">
                        <label for="judicial-position">Judicial Position:</label>
                        <select name="judicial-position" id="judicial-position" class="judicial-history-dropdown">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="criteria-input">
                        <label for="jud-courts">Courts: </label>
                        <select name="jud-courts" id="judicial-courts" class="judicial-history-dropdown">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="criteria-input">
                        <label for="department">Department</label>
                        <select name="department" id="judicial-department" class="judicial-history-dropdown">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="criteria-input">
                        <label for="county">County: </label>
                        <select name="county" id="judicial-county" class="judicial-history-dropdown">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="criteria-input">
                        <label for="num-results">Number of Results: </label>
                        <select name="num-results" id="num-results">
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="criteria-input">
                        <input type="submit" value="Search Database" onClick="search(1)">
                    </div>
                </div>
            </div>
            <div id="judicial-history-table-wrapper" >
                <div class="loader hide-div"></div>
                <table cellspacing="0"  id="judicial-history-table">
                    <tbody id="judicial-history-tbody">
                        <tr id="judicial-table-header">
                            <th scope="col"><a style="color:White;">Name</a></th>
                            <th scope="col"><a style="color:White;">Court Name</a></th>
                            <th scope="col"><a style="color:White;">Judicial Position</a></th>
                            <th scope="col"><a style="color:White;">Election Date</a></th>
                            <th scope="col"><a style="color:White;">Term</a></th>
                            <th scope="col"><a style="color:White;">County</a></th>
                            <th scope="col" style="color:White;">More Information</th>
                        </tr>
                    </tbody>
                </table>
                <div class="btn-wrapper">
                    <a class="previous-btn disabled" id="previous-btn" onClick="previous()">Previous</a>
                    <a class="next-btn" id="next-btn" onClick="next()">Next</a>
                </div>
                <div id="view-more-wrapper">
                    <div class="loader hide-div"></div>
                    <div id="view-more-info">
                        <div id="judge-info-container">
                            <div class="judge-info-p">
                                <p id="judge-name">Name: </p>
                                <p id="judge-position">Judicial Position: </p>
                                <p id="judge-court">Court: </p>
                                <p id="judge-county">County: </p>
                            </div>
                            <div class="judge-info-p">
                                <p id="judge-election-date">Election Date: </p>
                                <p id="judge-term">Term: </p>
                                <p id="judge-entreasonname">Term Begin Reason: </p>
                                <p id="judge-termreasonname">Term End Reason: </p>
                            </div>
                        </div>
                        <p id="judge-comments">Comments: </p>
                    </div>
                    <a onclick="goBack()" class="back-btn">Back</a>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>