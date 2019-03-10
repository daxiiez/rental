<?php
include '__connect.php';
include '__checkSession.php';


if($_SESSION['userType'] == 'S'){
    header("Location: _home.php");
}
if (isset($_GET['room_no']) && isset($_GET['tower_no']) && isset($_GET['type'])) {
//เแก้ไข

    $room_no = $_GET['room_no'];
    $type = $_GET['type'];
    $tower_no = $_GET['tower_no'];

    $sql = "SELECT * FROM `tower` WHERE `type` = '$type' AND `tower_no` = '$tower_no'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $tower_name = $row['tower_name'];
        $headetroom_update = "<nav aria-label='breadcrumb  bg-dark'><h5><i class='fa fa-star'></i> แก้ไขข้อมูลห้องพัก </h5></nav>";

        $sql = "SELECT * FROM `room` WHERE `room_no` = '$room_no' AND `tower_no` = '$tower_no'";
        $result = mysqli_query($conn, $sql);
        $res = mysqli_fetch_assoc($result);
        if ($res) {
            $size = $res['size'];
            $status = $res['status'];
            $cost = $res['cost'];
        }

    }
} else if (isset($_GET['type']) && isset($_GET['tower_no'])) {
//เพิ่มข้อมูล
    $room_no = "";
    $tower_no = "";
    $size = "";
    $status = "";
    $type = "";
    $cost = "";

    $type = $_GET['type'];
    $tower_no = $_GET['tower_no'];
    $sql = "SELECT * FROM `tower` WHERE `type` = '$type' AND `tower_no` = '$tower_no'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $tower_name = $row['tower_name'];
        $headetroom_insert = "<nav aria-label='breadcrumb  bg-dark'><h5><i class='fa fa-star'></i> เพิ่มข้อมูลห้องพัก</h5></nav>";
    }
}

if (isset($_POST['insertRoom'])) {
    $room_no = $_POST['room_no'];
    $tower_no = $_POST['check_tower_no'];
    $size = $_POST['size'];
    $status = $_POST['status'];
    $type = $_POST['check_type'];
    $cost = $_POST['cost'];

    $sql = "INSERT INTO `room` (`room_no`, `tower_no`, `size`, `status`, `type`, `cost`,`current_size`) VALUES ('$room_no', '$tower_no', '$size','$status', '$type', '$cost',0)";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $_GET['type'] = $type;
        $_GET['tower_no'] = $tower_no;
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> เพิ่มข้อมูลห้องพักแล้ว
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> ข้อมูลห้องที่ต้องการเพิ่มไม่ถูกต้อง
                    </div>';
    }
} else if (isset($_POST['updateRoom'])) {


    $room_no = $_POST['room_no'];
    $tower_no = $_POST['check_tower_no'];
    $size = $_POST['size'];
    $status = $_POST['status'];
    $type = $_POST['check_type'];
    $cost = $_POST['cost'];
    $type = $_GET['type'];

    $sql = "UPDATE `room` SET `room_no` = '$room_no', `tower_no` = '$tower_no', `size` = '$size', `status` = '$status', `type` = '$type', `cost` = '$cost' WHERE `room`.`room_no` = '$room_no' AND `room`.`tower_no` = '$tower_no' AND `room`.`type` = '$type'";
    $query = mysqli_query($conn, $sql);
    if ($query) {

        $sql = "UPDATE report SET room_no = '$room_no' WHERE  room_no='$room_no' and type='$type' and tower_no = '$tower_no'";
        $query = mysqli_query($conn, $sql);
        $sql = "UPDATE booking_detail SET room_no = '$room_no' WHERE  room_no='$room_no' and type='$type' and tower_no = '$tower_no'";
        $query = mysqli_query($conn, $sql);

        $_GET['type'] = $type;
        $_GET['tower_no'] = $tower_no;
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> แก้ไขข้อมูลห้องแล้ว
                    </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> ข้อมูลห้องที่แก้ไขไม่ถูกต้อง
                    </div>';
    }
}
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>
        $(document).ready(() => {
            $("#tel").mask("999-9999999");
        });
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>
<form method="POST" class="simple-form">
    <div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
        <div class="card">
            <div class="card-header">
                <?php
                if (isset($headetroom_insert)) {
                    echo "$headetroom_insert";
                } else if (isset($headetroom_update)) {
                    echo "$headetroom_update";
                }
                ?>

            </div>
            <div class="card-body">
                <div class="container">

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>หอพัก :</label>
                                <input name="tower_name" id="tower_name" class="form-control" required
                                       placeholder="ชื่อหอพัก" readonly value="<?php echo $tower_name; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>เลขห้อง :</label>

                                <?php
                                if($room_no != null){
                                    echo "<input name='room_no' id='room_no' class='form-control' required
                                            readonly  value='$room_no'>";
                                }else {
                                    echo "<input name='room_no' id='room_no' class='form-control' required
                                            maxlength='5' placeholder='เลขห้องพัก' >";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>เจำนวนที่เข้าพักได้ :</label>
                                <input name="size" id="size" type="number" maxlength="2" class="form-control" required
                                       placeholder="จำนวนของผู้เข้าพัก" value="<?php echo $size; ?>">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col col-sm col-md col-lg">
                            <div class="form-group">
                                <label class="font-weight-bold">สถานะ</label>
                                <select class="form-control" id="status" name="status" required>
                                    <?php

                                    if ($status == 'N') {
                                        echo "<option value='N' selected>ว่าง</option>
                                    <option value='Y'>จองแล้ว</option>
                                    <option value='F'>ปรับปรุง</option>";
                                    } else if ($status == 'Y') {
                                        echo "<option value='N'>ว่าง</option>
                                    <option value='Y' selected>จองแล้ว</option>
                                    <option value='F'>ปรับปรุง</option>";
                                    } else if ($status == 'F') {
                                        echo "<option value='N'>ว่าง</option>
                                    <option value='Y'>จองแล้ว</option>
                                    <option value='F' selected>ปรับปรุง</option>";
                                    } else {
                                        echo "<option></option>
                                    <option value='N'>ว่าง</option>
                                    <option value='Y'>จองแล้ว</option>
                                    <option value='F'>ปรับปรุง</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>ค่าธรรมเนียม :</label>
                                <input name="cost" id="cost" class="form-control" required
                                       placeholder="ค่าธรรมเนียม(บาท)" value="<?php echo $cost ; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2 col-sm-2 col-lg-2 col-md-2"></div>

                        <?php
                        echo "<input value='$type' name='check_type' type='hidden'>";
                        echo "<input value='$tower_no' name='check_tower_no' type='hidden'>";
                        if (isset($_GET['room_no']) && isset($_GET['tower_no']) && isset($_GET['type'])) {
                            echo "<button type='submit' name='updateRoom' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> เแก้ไขข้อมูลห้องพัก</button>";
                        } else {
                            echo "<button type='submit' name='insertRoom' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> เพิ่มข้อมูลห้องพัก</button>";
                        }
                        ?>
                        <span style="margin: 10px"></span>
                        <button onclick="window.history.back();" type="button"
                                class="btn btn-danger col-4 col-sm-4 col-lg-4 col-md-4 "><i class="fa fa-times"></i>
                            ยกเลิก
                        </button>
                        <div class="col-2 col-sm-2 col-lg-2 col-md-2"></div>

                    </div>

                    <div class="footer" align="center">

                    </div>
                </div>
            </div>
        </div>
</form>
</body>
</html>