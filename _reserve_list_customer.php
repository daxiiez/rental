<?php
include '__connect.php';
include '__checkSession.php';


?>
<p></p>
<!Document>
<html>
<head>
    <?php
    include '__header.php';
    ?>
</head>
<body>
<?php
include '__navbar_admin.php';
?>
<?php
$roomIdSearch = '';
if (isset($_GET['roomIdSearch'])) {
    if ($_GET['roomIdSearch'] != '') {
        $roomIdSearch = $_GET['roomIdSearch'];
        echo $roomIdSearch . " room";
    }
}
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="row">
            <div class=" col col-sm col-md col-lg">
                <div class="card">
                    <div class="card-header bg-info text-white"
                         id="collapseNews"><strong><i class="fa fa-list"></i> รายการจอง ที่พร้อมเข้า Check In</strong>
                    </div>
                    <div class="card-body" id="collapseInfo">
                        <form>
                            <div class="row">
                                <div class="col-4">
                                    <div class="input-group">
                                        <label class="font-weight-bold">ค้นหาห้องพัก : </label>
                                        <input type="text" class="form-control" placeholder="" name="roomIdSearch"
                                               aria-label="" aria-describedby="basic-addon1">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit" name="search">ค้นหา</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>
                        <?php

                        $usernameRental = $_SESSION['username'];
                        $sql = "SELECT * FROM rental_detail WHERE username = '$usernameRental' order by rental_id desc";
                        $list = mysqli_query($conn, $sql);
                        $count = 0;
                        $sqlAccount = "select * from user where username='$usernameRental' ";
                        $queryAccount = mysqli_query($conn, $sqlAccount);
                        $profile = mysqli_fetch_assoc($queryAccount);
                        while ($temp = mysqli_fetch_array($list)) {
                            $count++;
                            $priority = 0;
                            $status = $temp['status'];
                            if ($status == 'N') $priority = 1;
                            elseif ($status == 'W') $priority = 2;
                            elseif ($status == 'S') $priority = 3;
                            elseif ($status == 'I') $priority = 4;
                            elseif ($status == 'O') $priority = 5;
                            elseif ($status == 'C') $priority = 0;


                            ?>
                            <div class="card" style="margin-bottom: 5px;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-8">
                                                    <strong>
                                                        ลำดับการจอง :
                                                    </strong> <?php echo $temp['rental_id'] ?>
                                                    <strong> ห้องที่จอง : </strong> <?php echo $temp['room_id'] ?><br>
                                                    <strong> Check In : </strong> <?php echo $temp['check_in'] ?> <strong> เวลา Check In : </strong> <?php echo $temp['check_in_time'] ?><br>
                                                    <strong> Check Out : </strong> <?php echo $temp['check_out'] ?> <strong> เวลา Check Out : </strong> <?php echo $temp['check_out_time'] ?><br>
                                                    <strong> รวมจำนวนวันที่เข้าพัก
                                                        : </strong> <?php echo $temp['rent_days'] ?> วัน
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn btn-primary" style="margin-bottom: 20px;"
                                                            type="button"
                                                            data-toggle="collapse"
                                                            data-target="#collapseExample<?php echo $count; ?>"
                                                            aria-expanded="false"
                                                            aria-controls="collapseExample">
                                                        ดูรายละเอียด
                                                    </button>
                                                </div>
                                            </div>


                                            <div class="collapse<?php if ($count == 1) echo 'show'; ?>"
                                                 id="collapseExample<?php echo $count; ?>">
                                                <div class="alert alert-info" role="alert">
                                                    <?php
                                                    if ($priority > 0) {
                                                        ?>

                                                        <div class="row">
                                                            <div class="col">
                                                                <h3>
                                                    <span class="badge badge-pill badge-<?php if ($priority == 1)
                                                        echo 'warning';
                                                    elseif ($priority > 1) echo 'primary'; ?>">อัพโหลดหลักฐาน
                                                        <?php
                                                        if ($priority > 1) {
                                                            ?>
                                                            <i class="fa fa-check-circle"></i>
                                                            <?php
                                                        }
                                                        ?>

                                                    </span>
                                                                </h3>
                                                            </div>
                                                            <div class="col">
                                                                <h3>
                                                <span class="badge badge-pill badge-<?php if ($priority == 2)
                                                    echo 'warning';
                                                elseif ($priority > 2) echo 'primary';
                                                else echo 'info'; ?> ">ตรวจสอบหลักฐาน
                                                        <?php
                                                        if ($priority > 2) {
                                                            ?>
                                                            <i class="fa fa-check-circle"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                </span>
                                                                </h3>
                                                            </div>
                                                            <div class="col">
                                                                <h3>
                                                    <span class="badge badge-pill badge-<?php if ($priority == 3)
                                                        echo 'warning';
                                                    elseif ($priority > 3) echo 'primary';
                                                    else echo 'info'; ?>">Check In
                                                        <?php
                                                        if ($priority > 3) {
                                                            ?>
                                                            <i class="fa fa-check-circle"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </span>
                                                                </h3>
                                                            </div>
                                                            <div class="col">
                                                                <h3>
                                                    <span class="badge badge-pill badge-<?php if ($priority == 4)
                                                        echo 'warning';
                                                    elseif ($priority > 4) echo 'primary';
                                                    else echo 'info'; ?> ">Check Out
                                                        <?php
                                                        if ($priority > 4) {
                                                            ?>
                                                            <i class="fa fa-check-circle"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </span>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="container text-center">
                                                            <h4 class="text-danger">รายการถูกยกเลิก</h4>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                                if ($priority > 0 && $priority < 3) {
                                                    ?>
                                                    <a href="_reserve.php?rental_id=<?php echo $temp['rental_id']?>" class="btn btn-xs btn-success">
                                                      <i class="fa fa-edit"></i>  อัพโหลด/แก้ไข หลักฐานการชำระเงิน
                                                    </a>
                                                    <?php
                                                }else if($priority>0){
                                                    ?>
                                                    <a href="_reserve.php?rental_id=<?php echo $temp['rental_id']?>" class="btn btn-xs btn-success">
                                                        <i class="fa fa-eye"></i> พิมพ์รายการ
                                                    </a>
                                                <?php
                                                }
                                                ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>