<?php
include '__connect.php';
$_SESSION['currentMonth'] = intval(date("m"));
$_SESSION['currentYear'] = intval(date("Y"));
$_SESSION['currentDate'] = intval(date("d"));

?>


<!DOCTYPE html>
<html>

<head>
    <?php include '__header.php'; ?>
    <style>
    </style>
</head>
<body>





<?php
include '__navbar_admin.php';
?>


<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-sm-4">
            <h2>บริการของเรา</h2>
            <h5>อาหารและเครื่องดื่ม</h5>
            <ul>
                <li>เครื่องดื่มสมุนไพร</li>
                <li>อาหารพื้นเมือง</li>
            </ul>
            <h3>สถานที่ท่องเที่ยวใกล้เคียง</h3>
            <p>สถานที่พักของเราใกล้สถานที่ท่องเที่ยวที่น่าสนใจ</p>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link " href="#">เกาะขาม</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">วัดใหญ่ชัยมงคล</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">วันป่า</a>
                </li>
            </ul>
            <hr class="d-sm-none">
        </div>
        <div class="col-sm-8">
            <h2>ประกาศ1</h2>
            <h5>โดย admin, Dec 7, 2018</h5>
            <p>ประกาศห้องใหม่</p>
            <p>เพิ่มห้อง 129 ดูรายละเอียดเพิ่มเติม <a href="#">คลิก</a></p>
            <br>

            <h2>ประกาศเปิดใช้ระบบ</h2>
            <h5>โดย admin, Jan 7, 2019</h5>
            <p>ระบบพร้อมใช้งานเร็วๆนี้...</p>
            <p>พร้อม function อำนวยความสะดวกมากมาย...</p>
        </div>
    </div>
</div>

<div class="jumbotron text-center" style="margin-bottom:0">
    <p>Footer</p>
</div>

</body>
</html>