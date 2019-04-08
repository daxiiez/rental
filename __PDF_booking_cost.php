<?php
function convert($num)
{
    $num = str_replace(",", "", $num);
    $num_decimal = explode(".", $num);
    $num = $num_decimal[0];
    $returnNumWord = '';
    $lenNumber = strlen($num);
    $lenNumber2 = $lenNumber - 1;
    $kaGroup = array("", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
    $kaDigit = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ต", "แปด", "เก้า");
    $kaDigitDecimal = array("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ต", "แปด", "เก้า");
    $ii = 0;
    for ($i = $lenNumber2; $i >= 0; $i--) {
        $kaNumWord[$i] = substr($num, $ii, 1);
        $ii++;
    }
    $ii = 0;
    for ($i = $lenNumber2; $i >= 0; $i--) {
        if (($kaNumWord[$i] == 2 && $i == 1) || ($kaNumWord[$i] == 2 && $i == 7)) {
            $kaDigit[$kaNumWord[$i]] = "ยี่";
        } else {
            if ($kaNumWord[$i] == 2) {
                $kaDigit[$kaNumWord[$i]] = "สอง";
            }
            if (($kaNumWord[$i] == 1 && $i <= 2 && $i == 0) || ($kaNumWord[$i] == 1 && $lenNumber > 6 && $i == 6)) {
                if ($kaNumWord[$i + 1] == 0) {
                    $kaDigit[$kaNumWord[$i]] = "หนึ่ง";
                } else {
                    $kaDigit[$kaNumWord[$i]] = "เอ็ด";
                }
            } elseif (($kaNumWord[$i] == 1 && $i <= 2 && $i == 1) || ($kaNumWord[$i] == 1 && $lenNumber > 6 && $i == 7)) {
                $kaDigit[$kaNumWord[$i]] = "";
            } else {
                if ($kaNumWord[$i] == 1) {
                    $kaDigit[$kaNumWord[$i]] = "หนึ่ง";
                }
            }
        }
        if ($kaNumWord[$i] == 0) {
            if ($i != 6) {
                $kaGroup[$i] = "";
            }
        }
        $kaNumWord[$i] = substr($num, $ii, 1);
        $ii++;
        $returnNumWord .= $kaDigit[$kaNumWord[$i]] . $kaGroup[$i];
    }
    if (isset($num_decimal[1])) {
        $returnNumWord .= "จุด";
        for ($i = 0; $i < strlen($num_decimal[1]); $i++) {
            $returnNumWord .= $kaDigitDecimal[substr($num_decimal[1], $i, 1)];
        }
    }
    return $returnNumWord . 'บาทถ้วน';
}

include '__connect.php';
$rental_id = $_GET['rental_id'];
$username = $_SESSION['username'];

$sql = "SELECT * from rental_detail where rental_id = '$rental_id'";
$query = mysqli_query($conn, $sql);
$rental = mysqli_fetch_assoc($query);
$status = $rental['status'];


$sql = "select * from user where username = '$username'";
$query = mysqli_query($conn, $sql);
$profile = mysqli_fetch_assoc($query);

require_once __DIR__ . '/vendor/autoload.php';
include '__header.php';
$mpdf = new \Mpdf\Mpdf();

?>
<html>
<body style="background: white;">
<div class="container-fluid" style="margin-top: 30px;">

    <div class="container" style="margin-top: 10px;">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold">รายการจอง</h4> <?php echo $rental['rental_id'] ?>
                <hr>
                <div class="row">
                    <div class="col-4">
                    <span>
                        <b>จองวันที่</b> : <?php echo $rental['rent_date'] ?>
                    </span>
                    </div>
                    <div class="col-8">
                        <span>
                            <b>ห้องที่จอง</b> : <?php echo $rental['room_id'] ?>
                        </span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                    <span>
                        <b>วันที่ Check In</b> : <?php echo $rental['check_in'] ?>
                    </span>
                    </div>
                    <div class="col-4">
                        <span>
                            <b>วันที่ Check Out</b> : <?php echo $rental['check_out'] ?>
                        </span>
                    </div>
                    <div class="col-4">
                        <span>
                            <b>รวม(วัน)</b> : <?php echo $rental['rent_days'] ?>
                        </span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <span>
                            <b>รวมราคา (บาท)</b> : <?php echo $rental['rent_cost'] ?>
                        </span>
                    </div>
                    <div class="col-4">
                    <span>
                        <b>มัดจำ (บาท)</b> : <?php echo $rental['deposit'] ?>
                    </span>
                    </div>
                    <div class="col-4">
                    <span>
                        <b>คงเหลือ (บาท)</b> : <?php echo $rental['rent_cost'] - $rental['deposit'] ?>
                    </span>
                    </div>
                </div>
                <br>
                <hr>
                <h4 class="font-weight-bold">ข้อมูลผู้จอง</h4>
                <hr>
                <div class="row">
                    <div class="col-4">
                    <span>
                        <b>ชื่อ : </b><?php echo $profile['name']; ?>
                    </span>
                    </div>
                    <div class="col-4">
                    <span>
                        <b>Line id : </b><?php echo $profile['line_id']; ?>
                    </span>
                    </div>
                    <div class="col-4">
                    <span>
                        <b>เบอร์โทรศัพท์ : </b><?php echo $profile['tel']; ?>
                    </span>
                    </div>
                </div>

                <hr>
                <br>

                <div class="row">
                    <div class="col-4 mx-auto">
                        <div align="center">
                            <img id="img-upload" src="img/scb-logo.jpg" width="200" border="5"></div>
                    </div>

                    <div class="col-6 mx-auto" align="left">
                        <p><b>ช่องทางการชำระเงิน</b></p>
                        <ul>
                            <li>
                                <small>เลขบัญชี ธนาคารไทยพานิช 401-990-1486 สาขาตลาดกำแพงแสน</small>
                            </li>
                            <li>
                                <small>Prompt Pay : 081-1151415</small>
                            </li>
                            <li>
                                <small>เลขบัญชี ธนาคารไทยพานิช 401-990-1486 สาขาตลาดกำแพงแสน</small>
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <hr>
                <br>
                <div class="row">
                    <div class="col col-md col-lg col-sm text-center">
                        <strong>ลงชื่อ............................................................................ผู้ชำระเงิน</strong><br>
                        <strong>(.......................................................)</strong><br>
                        <strong>วันที่ (....../....../......)</strong><br>
                    </div>
                    <div class="col col-md col-lg col-sm text-center">
                        <strong>ลงชื่อ............................................................................ผู้รับเงิน</strong><br>
                        <strong>(.......................................................)</strong><br>
                        <strong>วันที่ (....../....../......)</strong><br>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>


</html>


