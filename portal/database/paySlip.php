<?php
    error_reporting(0);
    require '../config.php';

    $userID = $_POST['userID'];
    $payMonth = $_POST['payMonth'];
    $payYear = $_POST['payYear'];
    $daysPresent = $_POST['daysPresent'];
    $slipDate = $_POST['slipDate'];
    $lop = $_POST['lop'];
    $otherDed = $_POST['otherDed'];
    $bonus = $_POST['bonus'];

    $strmonth = strtotime($payMonth." ".$payYear);
    $payMonthCap = strtoupper(date("F",$strmonth));
    $monthNum = date("m", $strmonth);
    $yearNum = date("Y", $strmonth);
    
    $sql = "SELECT * FROM empDetails WHERE id=$userID";
    $result = mysqli_query($mysqli, $sql) or die("SQL Failed");
    $userData = [];
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $userData[] = $row;
        }
    }

    $empName = $userData[0]['name'];
    $empJoiningDate = $userData[0]['joiningDate'];
    $empPAN = $userData[0]["pan"];
    $empBankAC = $userData[0]["accountNum"];
    $empBankIFSC = $userData[0]["ifsc"];
    $empPosition = $userData[0]["empPosition"];
    $empUAN = $userData[0]["uan"];
    $empESICNum = $userData[0]["esicNum"];
    $empPFNum = $userData[0]["pfNum"];
    $empBasicPay = $userData[0]["basicPay"];
    $empTravelAll = $userData[0]["travelAll"];
    $empOtherAll = $userData[0]["otherAll"];
    $empBonus = $userData[0]["bonus"];
    $empESIC = $userData[0]["esic"];
    $empEPFO = $userData[0]["epfo"];
    $empPT = $userData[0]["pt"];
    $empTDS = $userData[0]["tds"];
    $empOtherDeduction = $userData[0]["otherDeduction"];
    $empGrossSalary = $empBasicPay+$empTravelAll+$empOtherAll;
    
    $totalIncome = $empBasicPay+$empTravelAll+$empOtherAll;
    $monthIncome = ($daysPresent*$lop)+$bonus;
    $deductions = $empESIC+$empEPFO+$empPT+$empTDS+$otherDed;
    $empNetIncome = $monthIncome-$deductions;
    $empNetPayable = round($monthIncome-$deductions);

    
    function AmountInWords(float $amount) {
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;

        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(
            0 => 'Zero', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Fourty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );
        $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($x < $count_length) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            } else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "
    " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees Only' : '') . $get_paise;
    }

    $empNetPayableInWords = AmountInWords($empNetPayable);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>careforbharat</title>
</head>
<body>
    <?php
        echo"<div id='pay_month' style='display:none;'>$payMonthCap</div>
        <div id='pay_year' style='display:none;'>$payYear</div>
        <div id='emp_name' style='display:none;'>$empName</div>
        <div id='emp_designation' style='display:none;'>$empPosition</div>
        <div id='emp_id' style='display:none;'>$userID</div>
        <div id='emp_joining_date' style='display:none;'>$empJoiningDate</div>
        <div id='emp_pan' style='display:none;'>$empPAN</div>
        <div id='emp_days_present' style='display:none;'>$daysPresent</div>
        <div id='emp_lop' style='display:none;'>$lop</div>
        <div id='emp_uan' style='display:none;'>$empUAN</div>
        <div id='emp_pfno' style='display:none;'>$empPFNum</div>
        <div id='emp_esicno' style='display:none;'>$empESICNum</div>
        <div id='comp_tan' style='display:none;'>PNEA35723C</div>
        <div id='emp_account' style='display:none;'>$empBankAC</div>
        <div id='emp_ifsc' style='display:none;'>$empBankIFSC</div>
        <div id='emp_basic_pay' style='display:none;'>$empBasicPay</div>
        <div id='emp_travelAll' style='display:none;'>$empTravelAll</div>
        <div id='emp_otherAll' style='display:none;'>$empOtherAll</div>
        <div id='emp_bonus' style='display:none;'>$bonus</div>
        <div id='emp_pt' style='display:none;'>$empPT</div>
        <div id='emp_pf' style='display:none;'>$empEPFO</div>
        <div id='emp_insurance' style='display:none;'>$empESIC</div>
        <div id='emp_tds' style='display:none;'>$empTDS</div>
        <div id='emp_otherDed' style='display:none;'>$otherDed</div>
        <div id='emp_totalincome' style='display:none;'>$totalIncome</div>
        <div id='emp_monthincome' style='display:none;'>$monthIncome</div>
        <div id='emp_deduction' style='display:none;'>$deductions</div>
        <div id='emp_net_income' style='display:none;'></div>
        <div id='emp_net_payable' style='display:none;'></div>
        <div id='emp_net_payable_word' style='display:none;'>$empNetPayableInWords</div>
        <div id='slip_date' style='display:none;'>$slipDate</div>"
    ?>
    
    <script>
        const num1 = document.getElementById('emp_net_income');
        const num2 = document.getElementById('emp_net_payable');
        const toIndianCurrency = (num) => {
            const curr = num.toLocaleString('en-IN', {
                style: 'currency',
                currency: 'INR'
            });
            return curr;
        };
        num1.innerHTML = toIndianCurrency(<?php echo $empNetIncome?>)+" /-";
        num2.innerHTML = toIndianCurrency(<?php echo $empNetPayable?>)+" /-";
    </script>
    <script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
    <script src="../js/FileSaver.js"></script>
    <script src="https://unpkg.com/@pdf-lib/fontkit@0.0.4"></script>
    <script src="../js/paySlip.js"></script>
</body>
</html>
<?php
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("refresh:2;url=$previousPage");
?>