<?php
    require './config.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='employees' || $_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
    }
    
    $sql = "SELECT * FROM users WHERE type='employees' OR type='admin'";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $outputResult = [];
    
    while($row = $result->fetch_assoc()){ 
        $outputResult[] = $row;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['salarySubmit'])) {
            $salaryPresentDays = $_POST['salaryPresentDays'];
            $salaryBasicPay = $_POST['salaryBasicPay'];
            $salaryTA = $_POST['salaryTA'];
            $salaryOA = $_POST['salaryOA'];
            $salaryBonus = $_POST['salaryBonus'];
            $salaryOther = $_POST['salaryOther'];
            $salaryTotal = $_POST['salaryTotal'];
            $salaryLOP = $_POST['salaryLOP'];
            $salaryEmpID = $_POST['salaryEmpID'];
            $salaryEmpName = $_POST['salaryEmpName'];
            $salaryTransferDate = $_POST['salaryTransferDate'];
            $salaryYear = $_POST['salaryYear'];
            $salaryMonth = $_POST['salaryMonth'];

            $result = $mysqli->query("SELECT * FROM empDocs WHERE document='payslip' AND empID='$salaryEmpID' AND year='$salaryYear' AND month='$salaryMonth'") or die($mysqli->error());
            if ( $result->num_rows > 0 ) {
                echo "<script>alert('PaySlip already exists!')</script>";
            }
            else {
                $sql_a = "INSERT INTO empDocs (empID, empName, document, date, year, month, daysPresent, lop, otherDed, bonus) VALUES ('$salaryEmpID', '$salaryEmpName', 'payslip', '$salaryTransferDate', '$salaryYear', '$salaryMonth', '$salaryPresentDays', '$salaryLOP', '$salaryOther', '$salaryBonus')";
                if ($mysqli->query($sql_a)){
                    echo "<script>alert('PaySlip Generated Successfully!')</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/utils.css">
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <link rel="stylesheet" href="./css/emp.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Employee Attendance</title>
</head>

<body style="background: #fff;">
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>
    <!-- Heading -->

    <!-- <div class="topbar">
        <a href="javascript:void(0)" onclick="openNav()"><img src="./assets/hamburger.png" alt="Menu Icon"></a>
        <img id="userProfile" src="./assets/userProfile.png" alt="User Profile">
        <p>MandeepDalavi</p>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="./index.html">Home</a>
            <a href="#">My Attendance</a>
            <a href="./documents.html">My Documents</a>
        </div>
    </div> -->
    <div class="baseContainer attendanceCtn">
        <h3 id="year">2023</h3>
        <div class="select">
            <select name="month" class="selectMonth" id="month">
                <option value="" selected disabled hidden>Month</option>
                <option value="Jan">January</option>
                <option value="Feb">February</option>
                <option value="Mar">March</option>
                <option value="Apr">April</option>
                <option value="May">May</option>
                <option value="Jun">June</option>
                <option value="Jul">July</option>
                <option value="Aug">August</option>
                <option value="Sep">September</option>
                <option value="Oct">October</option>
                <option value="Nov">November</option>
                <option value="Dec">December</option>
            </select>
        </div>
        <div class="select">
            <select name="employee" class="selectMonth" id="employee">
                <option value="" selected disabled hidden>Employee</option>
                <?php
                    for($x=0; $x<sizeof($outputResult); $x++){
                        echo '<option value="'.$outputResult[$x]["id"].'" >'.$outputResult[$x]["id"].' | '.$outputResult[$x]["firstName"].' '.$outputResult[$x]['lastName'].'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="displaySalDay">
        <p class="daysPresent" id="totalSalary"></p>
        <p class="daysPresent" id="noOfDaysPresent"></p>
    </div>
    <div class="tableContainer" id="attendanceTrack">
        <!-- <div class="tableLabel row">
            <span>Day</span>
            <span>Date</span>
            <span>Status</span>
        </div> -->
        <!-- <div class="tableContent row">
            <span>FRI</span>
            <span>01</span>
            <span>Present</span>
        </div> -->
    </div>
    <div class="sidebarOpener" id="sidebarOpener" onclick="openSideBar()">
        <img src="./images/left-arrow.png" alt="">
    </div>
    <div class='salarySideBar' id="salarySideBar">
        <div class="sideitem" id="sideitem1">
            <div class="leftbox">
                <h3>Salary Details</h3>
            </div>
            <div class="rightbox">
                <div class='salaryCross' onclick="closeSideBar()">
                    <img src='./images/cross.png' alt=''>
                </div>
            </div>
        </div>
        <div class="sideitem" id="sideitem2">
            <div class="leftbox">
                <p>Basic Pay</p>
                <span id="salaryBasicPayShow">0</span>
            </div>
            <div class="rightbox">
                <p>TA</p>
                <span id="salaryTAShow">0</span>
            </div>
        </div>
        <div class="sideitem" id="sideitem3">
            <div class="leftbox">
                <p>OA</p>
                <span id="salaryOAShow">0</span>
            </div>
            <div class="rightbox">
                <p>Bonus</p>
                <span id="salaryBonusShow">0</span>
            </div>
        </div>
        <div class="sideitem" id="sideitem4">
            <div class="leftbox">
                <p>ESIC</p>
                <span id="salaryESICShow">0</span>
            </div>
            <div class="rightbox">
                <p>EPFO</p>
                <span id="salaryEPFOShow">0</span>
            </div>
        </div>
        <div class="sideitem" id="sideitem5">
            <div class="leftbox">
                <p>PT</p>
                <span id="salaryPTShow">0</span>
            </div>
            <div class="rightbox">
                <p>Other Ded.</p>
                <span id="salaryOtherShow">0</span>
            </div>
        </div>
        <div class="sideitem" id="sideitem6">
            <h3 id="monthBrief">July Briefs</h3>
        </div>
        <form action="" id="monthBriefForm" action="empAttendanceView.php" method="POST">
            <div class="sideitem" id="sideitem7">
                <div class="leftbox">
                    <p>Present Days</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryPresentDays" name="salaryPresentDays" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem8">
                <div class="leftbox">
                    <p>Basic Pay</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryBasicPay" name="salaryBasicPay" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem9">
                <div class="leftbox">
                    <p>TA</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryTA" name="salaryTA" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem10">
                <div class="leftbox">
                    <p>OA</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryOA" name="salaryOA" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem11">
                <div class="leftbox">
                    <p>Bonus</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryBonus" name="salaryBonus" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem12">
                <div class="leftbox">
                    <p>ESIC</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryESIC" name="salaryESIC" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem13">
                <div class="leftbox">
                    <p>EPFO</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryEPFO" name="salaryEPFO" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem14">
                <div class="leftbox">
                    <p>PT</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryPT" name="salaryPT" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem15">
                <div class="leftbox">
                    <p>Other</p>
                </div>
                <div class="rightbox">
                    <input type="number" id="salaryOther" name="salaryOther" placeholder="0.0" step="0.01" min="0" value="0" required>
                </div>
            </div>
            <div class="sideitem" id="totalMonthSal" style="padding: 0 10px;">
                <div class="leftbox">
                    <h3>Total Salary</h3>
                </div>
                <div class="rightbox">
                    <h3 id="salaryTotalShow">42,000</h3>
                    <input type="hidden" id="salaryTotal" name="salaryTotal" value="0" required>
                    <input type="hidden" id="salaryLOP" name="salaryLOP" value="0" required>
                    <input type="hidden" id="salaryEmpID" name="salaryEmpID" required>
                    <input type="hidden" id="salaryEmpName" name="salaryEmpName" required>
                    <input type="hidden" id="salaryYear" name="salaryYear" required>
                    <input type="hidden" id="salaryMonth" name="salaryMonth" required>
                </div>
            </div>
            <div class="sideitem" id="sideitem16">
                <div class="leftbox">
                    <p>Transfer Date</p>
                </div>
                <div class="rightbox">
                    <input type="date" id="salaryTransferDate" name="salaryTransferDate" required>
                </div>
            </div>
            <div class="sideitem" id="monthBriefSubmit">
                <input type="submit" name="salarySubmit" value="Release Payslip">
            </div>
        </form>
    </div>
    <div class="remarkPopup" id="remarkpop">
        <div class="warning">
            <h1>Remarks!</h1>
        </div>
        <div class="warning-content">
            <p>In-Time: 10:00:00</p>
            <p>Out-Time: 18:00:00</p>
        </div>
    </div>
    <div class="closer" id="closer" onclick="closeRemarkPop();"></div>
    <?php
        include "components/bottomNav.php";
    ?>
</body>
<script>

    var sidebarOpener = document.getElementById("sidebarOpener");
    var salarySideBar = document.getElementById("salarySideBar");

    const openSideBar = () => {
        salarySideBar.style.width = "340px";
        salarySideBar.style.display = "block";
    };

    const closeSideBar = () => {
        salarySideBar.style.width = "0";
        salarySideBar.style.display = "none";
    };

    var salaryBasicPayShow = document.getElementById('salaryBasicPayShow');
    var salaryTAShow = document.getElementById('salaryTAShow');
    var salaryOAShow = document.getElementById('salaryOAShow');
    var salaryBonusShow = document.getElementById('salaryBonusShow');
    var salaryESICShow = document.getElementById('salaryESICShow');
    var salaryEPFOShow = document.getElementById('salaryEPFOShow');
    var salaryPTShow = document.getElementById('salaryPTShow');
    var salaryOtherShow = document.getElementById('salaryOtherShow');

    var salaryPresentDays = document.getElementById('salaryPresentDays');
    var salaryBasicPay = document.getElementById('salaryBasicPay');
    var salaryTA = document.getElementById('salaryTA');
    var salaryOA = document.getElementById('salaryOA');
    var salaryBonus = document.getElementById('salaryBonus');
    var salaryESIC = document.getElementById('salaryESIC');
    var salaryEPFO = document.getElementById('salaryEPFO');
    var salaryPT = document.getElementById('salaryPT');
    var salaryOther = document.getElementById('salaryOther');

    var salaryTotalShow = document.getElementById('salaryTotalShow');
    var salaryTotal = document.getElementById('salaryTotal');
    var salaryLOP = document.getElementById('salaryLOP');
    var salaryEmpID = document.getElementById('salaryEmpID');
    var salaryEmpName = document.getElementById('salaryEmpName');
    var salaryYear = document.getElementById('salaryYear');
    var salaryMonth = document.getElementById('salaryMonth');

    var presentDaysCount = 0;
    var daysInSelectedMonth;

    function statusBackground(monthTotalDays, salary, travel, other) {
        var statusDivs = document.getElementsByClassName('status');
        var daysPresent = document.getElementById('noOfDaysPresent');
        var totalSalary = document.getElementById('totalSalary');
        presentDaysCount = 0;
        // var salarySymbol = totalSalary.textContent;
        const stats = ["Leave", "Absent", "NA"];
        console.log(statusDivs);
        
        for(let i=0; i<statusDivs.length; i++){
            let div = statusDivs[i];
            let content = div.innerText.trim();
            if(content == "Present") {
                presentDaysCount += 1;
                div.style.background = "#66F597";
            } else if (content == "Late") {
                presentDaysCount += 0.75;
                div.style.background = "#F5DC66";
            } else if (content == "Half-Day") {
                presentDaysCount += 0.5;
                div.style.background = "#7BC2FF";
            } else if (content == "Leave") {
                div.style.background = "#FFE461";
            } else if (content == "Paid Leave") {
                presentDaysCount += 1;
                div.style.background = "#EA78FF";
            } else if (content == "Absent") {
                presentDaysCount += 0;
                div.style.background = "#FF5454";
            } else if (content == "Holiday") {
                div.style.background = "#F5A966";
                if ((i > 0 && stats.includes(statusDivs[i-1].innerText.trim())) || (i < statusDivs.length-1 && stats.includes(statusDivs[i+1].innerText.trim()))) {
                    presentDaysCount += 0;
                } else {
                    presentDaysCount += 1;
                }
            } else if (content == "Holiday" && i>0 && statusDivs[i-1].innerText.trim() == "NA" && (i+1 < statusDivs.length && statusDivs[i+1].innerText.trim() == "NA")){
                presentDaysCount -= 1;
            }
            // else if (content == "Holiday") {
            //     div.style.background = "#F5A966";
            //     if (i < statusDivs.length-1 && (stats.includes(statusDivs[i-1].innerText.trim()) || (i+1 < statusDivs.length && stats.includes(statusDivs[i+1].innerText.trim())))) {
            //         presentDaysCount += 0;
            //     } else {
            //         presentDaysCount += 1;
            //     }
            // } else if (content == "Holiday" && i>0 && statusDivs[i-1].innerText.trim() == "NA" && (i+1 < statusDivs.length && statusDivs[i+1].innerText.trim() == "NA")){
            //     presentDaysCount -= 1;
            // }
            // else if (content == "Holiday") {
            //     div.style.background = "#F5A966";
            //     if (i < statusDivs.length-1 && (stats.includes(statusDivs[i-1].innerText.trim()) || stats.includes(statusDivs[i+1].innerText.trim()))) {
            //         presentDaysCount += 0;
            //     } else {
            //         presentDaysCount += 1;
            //     }
            // } else if (content == "Holiday" && i>0 && statusDivs[i-1].innerText.trim() == "NA" && statusDivs[i+1].innerText.trim() == "NA"){
            //     presentDaysCount -= 1;
            // }
        }
        var calSalary = ((parseFloat(salary)+parseFloat(travel)+parseFloat(other))/monthTotalDays)*presentDaysCount;
        var lop = ((parseFloat(salary)+parseFloat(travel)+parseFloat(other))/monthTotalDays).toFixed(2);
        salaryLOP.value = lop;
        console.log("calSalary:"+calSalary);
        totalSalary.innerHTML = "Sal/M: &#8377;"+Math.round(calSalary);
        daysPresent.innerHTML = presentDaysCount+"/"+monthTotalDays;
    }

    var yearUpdate = document.getElementById('year');
    var currDateYear = new Date().getFullYear();
    yearUpdate.innerHTML = currDateYear;

    $(document).ready(function () {
        $('#employee').change(function () {
            var selectedEmployeeID = $(this).val();
            var selectedMonth = document.getElementById('month').value;
            var selectedYear = document.getElementById('year').innerText;
            var divAdd = document.getElementById('attendanceTrack');
            document.getElementById('sidebarOpener').style.display = "flex";
            salaryYear.value = selectedYear;
            salaryMonth.value = selectedMonth;
            console.log(selectedMonth);
            console.log(selectedYear);

            divAdd.innerHTML = '<div class="tableLabel row"><span>Day</span><span>Date</span><span>Status</span>';

            // Make AJAX request to fetch data
            $.ajax({
                url: './database/getAttendanceData.php',
                method: 'post',
                data: { month: selectedMonth, year: selectedYear, userID: selectedEmployeeID },
                dataType: 'json',
                success: function (res) {
                    var result = JSON.stringify(res);
                    console.log(res);

                    var monthInNumeric = new Date(Date.parse(selectedMonth +" 1, " +selectedYear)).getMonth()+1;
                    daysInSelectedMonth = new Date(selectedYear, monthInNumeric, 0).getDate();
                   
                    for(let y=1; y<=daysInSelectedMonth; y++){
                        var days = ['SUN', 'MON', 'TUE', 'WED', 'THUR', 'FRI', 'SAT'];
                        const annualHolidays = {Jan:[14,15,26], Feb:[19], Mar:[8,24,25], Apr:[9,14,17], May:[1], Jun:[28,29], Jul:[0], Aug:[15,19,26], Sep:[7], Oct:[2,9,12,31], Nov:[2], Dec:[25]};
                        var dayNameNumeric = new Date(selectedYear, monthInNumeric-1, y).getDay();
                        var codeSet = '';

                        if (days[dayNameNumeric] == "SUN" || annualHolidays[selectedMonth].includes(y)) {
                            codeSet = '<div class="tableContent row"><span>'+days[dayNameNumeric]+'</span><span id="dateID'+y+'">'+y+'</span><span class="status" id="statusID'+y+'">Holiday</span>';
                        } else {
                            codeSet = '<div class="tableContent row"><span>'+days[dayNameNumeric]+'</span><span id="dateID'+y+'">'+y+'</span><span class="status" id="statusID'+y+'">NA</span>';
                        }
                                                
                        $('#attendanceTrack').append(codeSet);
                    }

                    for(let i=0; i<res.length; i++){
                        var resDate = new Date(res[i].date).getDate();
                        var resAttendanceStatus = res[i].attendanceStat;
                        var statusD = document.getElementById("statusID"+resDate);
                        statusD.innerHTML = resAttendanceStatus;
                    }

                    salaryBasicPayShow.innerHTML = res[0].basicPay;
                    salaryTAShow.innerHTML = res[0].travelAll;
                    salaryOAShow.innerHTML = res[0].otherAll;
                    salaryBonusShow.innerHTML = res[0].bonus;
                    salaryESICShow.innerHTML = res[0].esic;
                    salaryEPFOShow.innerHTML = res[0].epfo;
                    salaryPTShow.innerHTML = res[0].pt;
                    salaryOtherShow.innerHTML = res[0].otherDeduction;

                    statusBackground(daysInSelectedMonth, res[0].basicPay, res[0].travelAll, res[0].otherAll);

                    var income = (parseFloat(res[0].basicPay)+parseFloat(res[0].travelAll)+parseFloat(res[0].otherAll))/daysInSelectedMonth*presentDaysCount;
                    console.log(income);
                    var deduction = parseFloat(res[0].esic)+parseFloat(res[0].epfo)+parseFloat(res[0].pt)+parseFloat(res[0].otherDeduction);
                    console.log(deduction);

                    salaryTotalShow.innerHTML = "&#8377; "+(income-deduction).toFixed(2);
                    salaryTotal.value = (income-deduction).toFixed(2);
                    salaryPresentDays.value = presentDaysCount;
                    salaryBasicPay.value = (res[0].basicPay/daysInSelectedMonth*presentDaysCount).toFixed(2);
                    salaryTA.value = (res[0].travelAll/daysInSelectedMonth*presentDaysCount).toFixed(2);
                    salaryOA.value = (res[0].otherAll/daysInSelectedMonth*presentDaysCount).toFixed(2);
                    salaryBonus.value = res[0].bonus;
                    salaryESIC.value = res[0].esic;
                    salaryEPFO.value = res[0].epfo;
                    salaryPT.value = res[0].pt;
                    salaryOther.value = res[0].otherDeduction;
                    salaryEmpID.value = res[0].empID;
                    salaryEmpName.value = res[0].empName;

                    setTimeout(function() {
                        statusBackground(daysInSelectedMonth, res[0].basicPay, res[0].travelAll, res[0].otherAll);
                    }, 100);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    });

    function safeParseFloat(value) {
        return parseFloat(value) || 0;
    }

    function roundToTwoDecimals(value) {
        return Math.round(value * 100) / 100;
    }

    function updateSalaryBrief() {
        var income = roundToTwoDecimals(
            safeParseFloat(salaryBasicPay.value) + 
            safeParseFloat(salaryTA.value) + 
            safeParseFloat(salaryOA.value) + 
            safeParseFloat(salaryBonus.value)
        );
        var deduction = roundToTwoDecimals(
            safeParseFloat(salaryESIC.value) + 
            safeParseFloat(salaryEPFO.value) + 
            safeParseFloat(salaryPT.value) + 
            safeParseFloat(salaryOther.value)
        );
        var total = roundToTwoDecimals(income - deduction);
        console.log(total);
        salaryTotalShow.innerHTML = "&#8377; "+total;
        // salaryTotalShow.innerHTML = "₹" + total.toFixed(2);
        salaryTotal.value = total;
    }

    document.querySelectorAll('input[type=number]').forEach(input => {
        input.addEventListener('change', updateSalaryBrief);
    });



</script>
</html>