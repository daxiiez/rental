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

$sql = "SELECT *,date_format(rent_date,'%d-%m-%Y') as rent_date_txt ,date_format(check_in,'%d-%m-%Y') as check_in_txt ,date_format(check_out,'%d-%m-%Y') as check_out_txt  from rental_detail where rental_id = '$rental_id'";
$query = mysqli_query($conn, $sql);
$rental = mysqli_fetch_assoc($query);
$status = $rental['status'];

if (isset($_GET['payment'])) {
    $sqlPayment = "UPDATE rental_detail set deposit = rent_cost where rental_id = $rental_id";
    $queryPayment = mysqli_query($conn, $sqlPayment);
    $username = $rental['username'];
}
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
        <div align="center">
            <h2 class="font-weight-bold">ใบเสร็จรับเงิน</h2>
            <h2 class="font-weight-bold">โรสรีสอร์ท</h2>
            <h4 class="font-weight-bold">ที่ตั้ง <span class="font-weight-light">เลขที่ 133 หมู่ 2 ตำบลกำแพงแสน อำเภอกำแพงแสน จังหวัดนครปฐม 73140</span>
            </h4>
            <h4 class="font-weight-bold">เบอร์โทรศัพท์ <span class="font-weight-light">084-44514725</span></h4>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold">เลขที่ใบเสร็จ <span class="font-weight-light"><?php echo $rental['rental_id'] ?></span></h4>  <b>วันที่</b>
                : <?php echo $rental['rent_date_txt']; ?>
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
                    <div class="col-4">
                    <span>
                        <b>ที่อยู่ : </b><?php echo $profile['address']; ?>
                    </span>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>รายการ</th>
                            <th>ห้องที่จอง</th>
                            <th>วันที่ Check In</th>
                            <th>วันที่ Check Out</th>
                            <th>จำนวนวันที่เข้าพัก</th>
                            <th>ราคาต่อคืน (บาท)</th>
                            <th>รวม (บาท)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td><?php echo $rental['room_id'] ?></td>
                            <td><?php echo $rental['check_in_txt'] ?> เวลา: <?php echo $rental['check_in_time'] ?></td>
                            <td> <?php echo $rental['check_out_txt'] ?>
                                เวลา: <?php echo $rental['check_out_time'] ?></td>
                            <td><?php echo $rental['rent_days'] . " วัน " . ($rental['rent_days'] - 1) . " คืน" ?></td>
                            <td><?php echo $rental['rent_cost'] / $rental['rent_days'] ?></td>
                            <td><?php echo $rental['rent_cost'] ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-right">ยอดชำระสุทธิ</th>
                            <td><?php echo $rental['rent_cost'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div>

                    <strong colspan="6" class="text-right">ยอดชำระสุทธิ</strong>
                    <span><?php echo $rental['rent_cost'] ?> บาท</span>
                    <br>
                    <strong colspan="6" class="text-right">จำนวนที่ชำระ/มัดจำ</strong>
                    <span><?php echo $rental['deposit'] ?> บาท</span>
                    <br>
                    <strong colspan="6" class="text-right">คงเหลือ</strong>
                    <span><?php echo $rental['rent_cost'] - $rental['deposit'] ?> บาท</span>
                </div>
                <hr>
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


