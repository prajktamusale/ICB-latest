<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)){
        header("Location: ./index.php");
    }
    if (!(isset($_SESSION['type']) && ($_SESSION['type']=='employees' || $_SESSION['type']=='admin' || $_SESSION['type']=='superadmin'))){
        header("Location: ./index.php");
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

    function statusBackground(monthTotalDays, salary, travel, other) {
        var statusDivs = document.getElementsByClassName('status');
        var daysPresent = document.getElementById('noOfDaysPresent');
        var totalSalary = document.getElementById('totalSalary');
        const stats = ["Leave", "Absent", "NA"];
        console.log(statusDivs);
        
        var presentDaysCount = 0;
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
                if (i < statusDivs.length-1 && (stats.includes(statusDivs[i-1].innerText.trim()) || stats.includes(statusDivs[i+1].innerText.trim()))) {
                    presentDaysCount += 0;
                } else {
                    presentDaysCount += 1;
                }
            } else if (content == "Holiday" && i>0 && statusDivs[i-1].innerText.trim() == "NA" && statusDivs[i+1].innerText.trim() == "NA"){
                presentDaysCount -= 1;
            }
        }
        var calSalary = ((parseFloat(salary)+parseFloat(travel)+parseFloat(other))/monthTotalDays)*presentDaysCount;
        totalSalary.innerHTML = "Sal/M: &#8377;"+Math.round(calSalary);
        daysPresent.innerHTML = presentDaysCount+"/"+monthTotalDays;
    }

    var yearUpdate = document.getElementById('year');
    var currDateYear = new Date().getFullYear();
    yearUpdate.innerHTML = currDateYear;

    $(document).ready(function () {
        $('#month').change(function () {
            var selectedMonth = $(this).val();
            var selectedYear = document.getElementById('year').innerText;
            var daysPresent = document.getElementById('noOfDaysPresent');
            var totalSalary = document.getElementById('totalSalary');
            var divAdd = document.getElementById('attendanceTrack');
            
            daysPresent.innerHTML = "";
            totalSalary.innerHTML = "";

            // Make AJAX request to fetch data
            $.ajax({
                url: './database/getAttendanceData.php',
                method: 'post',
                data: { month: selectedMonth, year: selectedYear, userID: '<?php echo $_SESSION['id']?>' },
                dataType: 'json',
                success: function (res) {
                    var result = JSON.stringify(res);
                    console.log(res);
                    
                    $('#attendanceTrack').show();
                    divAdd.innerHTML = '<div class="tableLabel row"><span>Day</span><span>Date</span><span>Status</span>';

                    var monthInNumeric = new Date(Date.parse(selectedMonth +" 1, " +selectedYear)).getMonth()+1;
                    var daysInSelectedMonth = new Date(selectedYear, monthInNumeric, 0).getDate();
                   
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

                    var presentDaysCount = 0;

                    for(let i=0; i<res.length; i++){
                        var resDate = new Date(res[i].date).getDate();
                        var resAttendanceStatus = res[i].attendanceStat;
                        var statusD = document.getElementById("statusID"+resDate);
                        statusD.innerHTML = resAttendanceStatus;
                    }

                    statusBackground(daysInSelectedMonth, res[0].basicPay, res[0].travelAll, res[0].otherAll);
                },
                error: function (err) {
                    console.log(err);
                    $('#attendanceTrack').hide();
                    $('#totalSalary').text("No Data Found!")
                }
            });
        });
    });

</script>
</html>