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
<body class="bg-light">


<?php
include '__navbar_admin.php';
?>


<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-sm-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h2 class="font-weight-bold">บริการของเรา</h2>
                    <h5 class="font-weight-bold">อาหารและเครื่องดื่ม</h5>
                    <ul>
                        <li>เครื่องดื่มสมุนไพร</li>
                        <li>อาหารพื้นเมือง</li>
                    </ul>
                    <hr>
                    <h3 class="font-weight-bold">ห้องพัก</h3>
                    <p>เตรียมไว้สำหรับผู้ที่มากับครอบครัว เพื่อน หรือ คนรัก</p>
                    <ul>
                        <li>Size เล็ก 1-2 คน</li>
                        <li>Size กลาง 3-4 คน</li>
                        <li>Size ใหญ่ 4-5 คน</li>
                    </ul>
                    <hr class="d-sm-none">
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <?php
            $sql = "SELECT i.*,u.*,DATE_FORMAT(i.info_date,'%d/%m/%Y') as info_date_df FROM information i inner join user u on i.info_owner = u.username ORDER BY info_id DESC LIMIT 2";
            $query = mysqli_query($conn,$sql);
            while($temp = mysqli_fetch_array($query,MYSQLI_ASSOC)){
                ?>
                <div class="card bg-secondary text-white">
                    <div class="card-body ">
                        <h2><?php echo $temp['info_name']?></h2>
                        <h5>โดย <?php echo $temp['name']?> วันที่ <?php echo $temp['info_date_df']?></h5>
                        <div class="container">
                            <?php echo base64_decode($temp['info_content']); ?>
                        </div>
                    </div>
                </div>
                <br>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<div class="text-center bg-warning" style="margin-top:100px;">
    <span class="badge badge-success">ติดต่อ : 084-44514725</span>
</div>

</body>
</html>