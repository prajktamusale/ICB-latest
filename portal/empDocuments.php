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
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/coreTeam.css">
    <link rel="stylesheet" href="./css/utils.css">
    <style>
        <?php include "./css/header.css" ?>
    </style>
    <link rel="stylesheet" href="./css/bottomNav.css">
    <script src="./js/sideBar.js" defer></script>
    <script src="./js/sliderAccordian.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Employee Documents</title>
    <link rel="stylesheet" href="./css/emp.css">
    <style>
        .coming {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            font-weight: 600;
        }
        .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            border: none;
            outline: none;
            transition: 0.4s;
        }
        .active, .accordion:hover {
            background-color: #ccc;
        }
        .panel {
            padding: 0 18px;
            background-color: #e5e5e5;
            max-height: 0;
            overflow: hidden;
            /* display: none; */
            transition: max-height 0.2s ease-out;
        }
        .innerPanel{
            display:flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            padding: 10px 0;
        }
        .accordion:after {
            content: '\02795'; /* Unicode character for "plus" sign (+) */
            font-size: 13px;
            color: #777;
            float: right;
            margin-left: 5px;
        }
            .active:after {
            content: "\2796"; /* Unicode character for "minus" sign (-) */
        }
        #basicTable {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
        }

        #basicTable td {
        border: 1px solid #ddd;
        padding: 8px;
        word-wrap: break-word;
        }

        #basicTable td:nth-child(odd){
            width: 15%;
            font-weight: bold;
        }

        #basicTable td:nth-child(3){
            /* border-left: 2px solid #000; */
        }

        #basicTable tr:nth-child(even){background-color: #f2f2f2;}

        #basicTable tr:hover {background-color: #ddd;}
    </style>
</head>
<body style="background: #fff;">
    <!-- Navigation Bar -->
    <?php
        include './header.php';
    ?>

    <!-- <div class="topbar">
        <a href="javascript:void(0)" onclick="openNav()"><img src="./assets/hamburger.png" alt="Menu Icon"></a>
        <img id="userProfile" src="./assets/userProfile.png" alt="User Profile">
        <p>MandeepDalavi</p>

        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="./index.html">Home</a>
            <a href="./attendance.html">My Attendance</a>
            <a href="#">My Documents</a>
        </div>
    </div> -->
    <div class="baseContainer docCtn">
        <h3 id="documentTitle">DOCUMENTS</h3>
    </div>
    <p class="daysPresent"></p>
    <div class="tableContainer">
        <?php
            $result = $mysqli->query("SELECT ed.*, u.email, u.address, u.mobile FROM empdetails ed INNER JOIN users u ON ed.id = u.id WHERE ed.id = '".$_SESSION['id']."'");
            $empData = array();
            while ($row = $result->fetch_assoc()) {
                $empData[] = $row;
            }
            print_r($empData);
        ?>
        <button class='accordion'>Basic Details</button>
        <div class='panel'>
            <div class='innerPanel'>
                <table id="basicTable">
                    <tr>
                        <td>Name:</td>
                        <td><?php echo $empData["name"]; ?></td>
                        <td>Email:</td>
                        <td><?php echo $empData["email"]; ?></td>
                    </tr>
                    <tr>
                        <td>Emp. ID:</td>
                        <td><?php echo $empData["id"]; ?></td>
                        <td>Emp. Position:</td>
                        <td><?php echo $empData["empPosition"]; ?></td>
                    </tr>
                    <tr>
                        <td>Mobile:</td>
                        <td><?php echo $empData["mobile"]; ?></td>
                        <td>Address:</td>
                        <td><?php echo $empData["address"]; ?></td>
                    </tr>
                    <tr>
                        <td>Aadhaar:</td>
                        <td><?php echo $empData["aadhaar"]; ?></td>
                        <td>PAN:</td>
                        <td><?php echo $empData["pan"]; ?></td>
                    </tr>
                    <tr>
                        <td>A/C No.:</td>
                        <td><?php echo $empData["accountNum"]; ?></td>
                        <td>IFSC:</td>
                        <td><?php echo $empData["ifsc"]; ?></td>
                    </tr>
                    <tr>
                        <td>UAN:</td>
                        <td><?php echo $empData["uan"]; ?></td>
                        <td>Joining Date:</td>
                        <td><?php echo $empData["joiningDate"]; ?></td>
                    </tr>
                    <tr>
                        <td>ESIC No.:</td>
                        <td><?php echo $empData["esicNum"]; ?></td>
                        <td>PF No.:</td>
                        <td><?php echo $empData["pfNum"]; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <button class='accordion'>Salary Details</button>
        <div class='panel'>
            <div class='innerPanel'>
                <table id="basicTable">
                    <tr>
                        <td>Basic Pay:</td>
                        <td><?php echo $empData["basicPay"]; ?></td>
                        <td>Travel Allowance:</td>
                        <td><?php echo $empData["travelAll"]; ?></td>
                    </tr>
                    <tr>
                        <td>Other Allowance:</td>
                        <td><?php echo $empData["otherAll"]; ?></td>
                        <td>ESIC:</td>
                        <td><?php echo $empData["esic"]; ?></td>
                    </tr>
                    <tr>
                        <td>EPFO:</td>
                        <td><?php echo $empData["epfo"]; ?></td>
                        <td>PT:</td>
                        <td><?php echo $empData["pt"]; ?></td>
                    </tr>
                    <tr>
                        <td>TDS:</td>
                        <td><?php echo $empData["tds"]; ?></td>
                        <td>Other Deductions:</td>
                        <td><?php echo $empData["otherDeduction"]; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <button class='accordion'>Credits</button>
        <div class='panel'>
            <div class='innerPanel'>
                <div class='select'>
                    <select name='month' class='selectMonth' id='creditYear'>
                        <option value=' selected disabled hidden>Year</option>
                        <option value='2024'>2024</option>
                    </select>
                </div>
                <div class='select'>
                    <select name='month' class='selectMonth' id='creditMonth'>
                        <option value='' selected disabled hidden>Month</option>
                        <option value='Jan'>January</option>
                        <option value='Feb'>February</option>
                        <option value='Mar'>March</option>
                        <option value='Apr'>April</option>
                        <option value='May'>May</option>
                        <option value='Jun'>June</option>
                        <option value='Jul'>July</option>
                        <option value='Aug'>August</option>
                        <option value='Sep'>September</option>
                        <option value='Oct'>October</option>
                        <option value='Nov'>November</option>   
                        <option value='Dec'>December</option>
                    </select>
                </div>                
            </div>
            <div class="docRow">
                    <table id="basicTable">
                        <tr>
                            <td>Social (Min 3):</td>
                            <td><?php echo $_SESSION["username"]; ?></td>
                            <td>Sports (Min 2):</td>
                            <td><?php echo $_SESSION["email"]; ?></td>
                        </tr>
                        <tr>
                            <td>Office Activity:</td>
                            <td><?php echo $_SESSION["type"]; ?></td>
                            <td>Critics:</td>
                            <td><?php echo $_SESSION["address"]; ?></td>
                        </tr>
                        <tr>
                            <td>Uniform (Friday):</td>
                            <td><?php echo $_SESSION["type"]; ?></td>
                            <td>Cultural Activity:</td>
                            <td><?php echo $_SESSION["address"]; ?></td>
                        </tr>
                        <tr>
                            <td>Cleanliness:</td>
                            <td><?php echo $_SESSION["type"]; ?></td>
                            <td>Task Completion:</td>
                            <td><?php echo $_SESSION["address"]; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Total (Month):</td>
                            <td><?php echo $_SESSION["address"]; ?></td>
                        </tr>
                    </table>
                </div>
        </div>

        <?php
            $result = $mysqli->query("SELECT * FROM empDocs WHERE document='payslip' AND empID='{$_SESSION['id']}'") or die($mysqli->error());
            if ( $result->num_rows > 0 ) {
                echo "<button class='accordion'>Pay Slips</button>
                        <div class='panel' id='paySlipAccord'>
                            <div class='innerPanel'>
                                <div class='select'>
                                    <select name='month' class='selectMonth' id='year'>
                                        <option value='' selected disabled hidden>Year</option>
                                        <option value='2024'>2024</option>
                                    </select>
                                </div>
                                <div class='select'>
                                    <select name='month' class='selectMonth' id='month'>
                                        <option value='' selected disabled hidden>Month</option>
                                        <option value='Jan'>January</option>
                                        <option value='Feb'>February</option>
                                        <option value='Mar'>March</option>
                                        <option value='Apr'>April</option>
                                        <option value='May'>May</option>
                                        <option value='Jun'>June</option>
                                        <option value='Jul'>July</option>
                                        <option value='Aug'>August</option>
                                        <option value='Sep'>September</option>
                                        <option value='Oct'>October</option>
                                        <option value='Nov'>November</option>   
                                        <option value='Dec'>December</option>
                                    </select>
                                </div>
                            </div>
                        </div>";
            }
        ?>
        
    </div>
    <!--<div class="coming">COMING SOON!</div>-->
    <?php
        include "components/bottomNav.php";
    ?>
    <script>
        var acc = document.getElementsByClassName("accordion");
        var i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");

                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + 50 + "px";
                }
            });
        }

        $(document).ready(function () {
            $('#creditMonth').change(function () {
                var selectedMonth = $(this).val();
                var selectedYear = document.getElementById('creditYear').value;
                console.log(selectedMonth);
                console.log(selectedYear);

                $.ajax({
                    url: './database/checkCredits.php',
                    method: 'post',
                    data: { month: selectedMonth, year: selectedYear, userEmail: '<?php echo $_SESSION['email']?>' },
                    dataType: 'json',
                    success: function (res) {
                        var result = JSON.stringify(res);
                        console.log(res);
                        if(res.length == 0 || res == "No Data Found!"){
                            alert('No Data Found!');
                            // codeSet = '<div class="docRow" id="paySlipDiv"><span>Pay Slip Not Available</span></div>';
                        } else {
                             
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#month').change(function () {
                var selectedMonth = $(this).val();
                var selectedYear = document.getElementById('year').value;
                console.log(selectedMonth);
                console.log(selectedYear);

                $.ajax({
                    url: './database/checkPaySlipEligibility.php',
                    method: 'post',
                    data: { month: selectedMonth, year: selectedYear, userID: '<?php echo $_SESSION['id']?>' },
                    dataType: 'json',
                    success: function (res) {
                        var result = JSON.stringify(res);
                        console.log(res);
                        if(res == "No Data Found!"){
                            codeSet = '<div class="docRow" id="paySlipDiv"><span>Pay Slip Not Available</span></div>';
                        } else {
                            codeSet = '<div class="docRow" id="paySlipDiv"><span>Pay Slip '+selectedMonth+' '+selectedYear+'</span><div class="payDownloadDiv"><form action="./database/paySlip.php" method="post" id="paySlipDownload"><input type="hidden" name="userID" value="'+<?php echo $_SESSION["id"] ?>+'"><input type="hidden" name="payMonth" value="'+selectedMonth+'"><input type="hidden" name="payYear" value="'+selectedYear+'"><input type="hidden" name="daysPresent" value="'+res[0].daysPresent+'"><input type="hidden" name="slipDate" value="'+res[0].date+'"><input type="hidden" name="lop" value="'+res[0].lop+'"><input type="hidden" name="otherDed" value="'+res[0].otherDed+'"><input type="hidden" name="bonus" value="'+res[0].bonus+'"><input type="submit" value=""></form></div></div>';
                        }
                        $('#paySlipDiv').remove();
                        $('#paySlipAccord').append(codeSet);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });
    </script>
</body>
</html>