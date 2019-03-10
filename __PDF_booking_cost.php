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

$code = $_GET['code'];
if ($code == '') {
    header("Location: _home.php");
}
include '__connect.php';
$sql = "select distinct d.*,
       s.name,
       s.department,
       s.major,
       s.level,
       s.tel,
       r.cost,
       r.size,
       t.full_status as tower_type,
       tower.tower_name
from booking_detail d
       inner join student s on s.code = d.student_code
       inner join status t on t.status = d.type
       inner join room r on r.room_no = d.room_no
       inner join tower  on tower.tower_no = d.tower_no
where d.student_code = '$code' limit 1
";
$result = mysqli_query($conn, $sql);
$detail = mysqli_fetch_assoc($result);
require_once __DIR__ . '/vendor/autoload.php';
include '__header.php';
$mpdf = new \Mpdf\Mpdf();

?>
<html>
<body style="background: white;">
<div class="container-fluid" style="margin-top: 20px;">
    <div class="card">
        <div class="container">
            <div class="row">
                <div class="col">
                    <span class="font-weight-bold rounded" style="margin-top: 10px;">ใบนำฝากชำระเงินค่าสินค้าหรือบริการ (Bill Payment Pay-In Slip)</span>
                </div>
                <div class="col">
                    <div class="text-right"><i>สำหรับลูกค้า</i><br>โปรดเรียกเก็บค่าธรรมเนียมจากผุ้ชำระเงิน*</div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-2">
                            <img src="img/KU_TH.png" height="75">
                        </div>
                        <div class="col">
                            มหาวิทยาลัยเกษตร์ วิทยาเขตกำแพงแสน<br>
                            ที่อยู่เลขที่ 1 หมู่ 6 ต.กำแพงแสน อ.กำแพงแสน จ.นครปฐม 73140<br>
                            โทรศัทพ์ : 0 3434 1550-3 หรือ 0 2942 8003-19
                        </div>
                    </div>
                </div>
                <div class="col">
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-right"><b>สาขา/Branch</b>.........................</td>
                            <td><b>วันที่/Date</b>.........................</td>
                        </tr>
                        <tr>
                            <th class="text-right">ชื่อ-สกุล (Name-Lastname)</th>
                            <td>
                                <?php echo $detail['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">เลขประจำตัวนิสิต (Ref.1)</th>
                            <td>
                                <?php echo $detail['student_code'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">เลขตึก/เลขห้อง (Ref.2)</th>
                            <td>
                                <?php echo $detail['tower_no'] ?>
                                <?php echo $detail['room_no'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            เพื่อนำเข้าบัญชีหอพักนิสิต<br>
            <img src="img/scb-logo.jpg" height="40"> บมจ.ธนาคารไทยพานิช เลขที่บัญชี 769-3-00117-4 (Bill Payment)(10/10)
            <br>
            <br>
            <table class="table table-bordered">
                <tr>
                    <td>☐ เงินสด/Cash</td>
                    <td>จำนวนเงิน/Amount</td>
                    <td>ค่าธรรมเนียม</td>
                    <td>รวม</td>
                </tr>
                <tr>
                    <td>ค่าหอพักประจำวันที่ <?php echo date("Y/m/d") . ' (' . $detail['tower_name'] . ')'; ?></td>
                    <td><?php echo $detail['cost'] ?></td>
                    <td>10</td>
                    <td><?php echo $detail['cost']+10 ?></td>
                </tr>
                <tr>
                    <td>จำนวนเงินเป็นตัวอักษร/Amount in words</td>
                    <td colspan="3"><?php echo convert($detail['cost']+10); ?></td>
                </tr>
            </table>
            <div class="row">
                <div class="col-8">
                    ชื่อผู้นำฝาก/Deposit By........................................................
                    เบอร์โทรศัพท์/Telephone............................
                </div>
                <div class="col-4">
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-center">สำหรับเจ้าหน้าที่ธนาคาร</td>
                        </tr>
                        <tr>
                            <td>ผู้รับเงิน ........................................................</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-center" style="margin-bottom: 5px;">
                <i>
                    <small>โปรดนำใบนำฝากนี้ไปชำระเงินได้ที่ บมจ.ธนาคารไทยพานิชย์ ทุกสาขาทั่วประเทศ</small>
                    <br>
                    <small>โปรดชำระภายในวันที่ 1-10 ของทุกเดือนมิฉะนั้นจะโดนค่าปรับวันละ 50 บาท</small>
                </i>
            </div>
        </div>
    </div>
</div>
</body>

<p class="text-center" style="margin-top: 20px; margin-bottom: 20px;">
    --------------------------------------------------------------------------
    <small>สำหรับธนาคาร</small>
    --------------------------------------------------------------------------
</p>

<body style="background: white;">
<div class="container-fluid" style="margin-top: 20px;">
    <div class="card">
        <div class="container">
            <div class="row">
                <div class="col">
                    <span class="font-weight-bold rounded" style="margin-top: 10px;">ใบนำฝากชำระเงินค่าสินค้าหรือบริการ (Bill Payment Pay-In Slip)</span>
                </div>
                <div class="col">
                    <div class="text-right"><i>สำหรับธนาคาร</i><br>โปรดเรียกเก็บค่าธรรมเนียมจากผุ้ชำระเงิน*</div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-2">
                            <img src="img/KU_TH.png" height="75">
                        </div>
                        <div class="col">
                            มหาวิทยาลัยเกษตร์ วิทยาเขตกำแพงแสน<br>
                            ที่อยู่เลขที่ 1 หมู่ 6 ต.กำแพงแสน อ.กำแพงแสน จ.นครปฐม 73140<br>
                            โทรศัทพ์ : 0 3434 1550-3 หรือ 0 2942 8003-19
                        </div>
                    </div>
                </div>
                <div class="col">
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-right"><b>สาขา/Branch</b>.........................</td>
                            <td><b>วันที่/Date</b>.........................</td>
                        </tr>
                        <tr>
                            <th class="text-right">ชื่อ-สกุล (Name-Lastname)</th>
                            <td>
                                <?php echo $detail['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">เลขประจำตัวนิสิต (Ref.1)</th>
                            <td>
                                <?php echo $detail['student_code'] ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right">เลขตึก/เลขห้อง (Ref.2)</th>
                            <td>
                                <?php echo $detail['tower_no'] ?>
                                <?php echo $detail['room_no'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            เพื่อนำเข้าบัญชีหอพักนิสิต<br>
            <img src="img/scb-logo.jpg" height="40"> บมจ.ธนาคารไทยพานิช เลขที่บัญชี 769-3-00117-4 (Bill Payment)(10/10)
            <br>
            <br>
            <table class="table table-bordered">
                <tr>
                    <td>☐ เงินสด/Cash</td>
                    <td>จำนวนเงิน/Amount</td>
                    <td>ค่าธรรมเนียม</td>
                    <td>รวม</td>
                </tr>
                <tr>
                    <td>ค่าหอพักประจำวันที่ <?php echo date("Y/m/d") . ' (' . $detail['tower_name'] . ')'; ?></td>
                    <td><?php echo $detail['cost'] ?></td>
                    <td>10</td>
                    <td><?php echo $detail['cost']+10 ?></td>
                </tr>
                <tr>
                    <td>จำนวนเงินเป็นตัวอักษร/Amount in words</td>
                    <td colspan="3"><?php echo convert($detail['cost']+10); ?></td>
                </tr>
            </table>
            <div class="row">
                <div class="col-8">
                    ชื่อผู้นำฝาก/Deposit By........................................................
                    เบอร์โทรศัพท์/Telephone............................
                </div>
                <div class="col-4">
                    <table class="table table-bordered">
                        <tr>
                            <td class="text-center">สำหรับเจ้าหน้าที่ธนาคาร</td>
                        </tr>
                        <tr>
                            <td>ผู้รับเงิน ........................................................</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="text-center" style="margin-bottom: 5px;">
                <i>
                    <small>โปรดนำใบนำฝากนี้ไปชำระเงินได้ที่ บมจ.ธนาคารไทยพานิชย์ ทุกสาขาทั่วประเทศ</small>
                    <br>
                    <small>โปรดชำระภายในวันที่ 1-10 ของทุกเดือนมิฉะนั้นจะโดนค่าปรับวันละ 50 บาท</small>
                </i>
            </div>
        </div>
    </div>
</div>
</body>

</html>


