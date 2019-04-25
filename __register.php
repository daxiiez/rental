<?php
include '__connect.php';
$address = '';
$tel = '';
$lineId = '';
$password = '';
$name = '';
$username = '';
$type = '';
$email = '';
$resultMsg = '';
$viewProfile = false;
if (isset($_POST['insertRegis'])) {
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
    } else {
        $type = 'M';
    }
    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];
    $lineId = $_POST['lineId'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sql = "INSERT INTO rental.user (username, password, type, name, tel, address, line_id, email) 
VALUES ('$username', '$password', '$type', '$name', '$tel', '$address', '$lineId', '$email')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        if (!isset($_SESSION['username'])) {
            $_SESSION['username'] = $username;
            $_SESSION['type'] = $type;
        }
        echo '<script>alert("สมัครสำเร็จ! ยินดีต้อนรับเข้าสู่ Rose Resort"); window.location = "index.php";</script>';
    } else {
        $resultMsg = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><i class="fa fa-times-circle"></i> เพิ่มข้อมูลไม่สำเร็จ!</strong> ชื่อผู้ใช้ซ้ำกรุณาหรอกใหม่
                    </div>';
    }
}
if (isset($_POST['updateProfile'])) {

    $name = $_POST['name'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];
    $lineId = $_POST['lineId'];
    $username = $_POST['username'];
    $type = $_POST['type'];
    $email = $_POST['email'];
    $sql = "UPDATE user set 
                            name = '$name',
                            address = '$address',
                            tel='$tel',
                            line_id='$lineId',
                            email = '$email'
                            where username = '$username'";
    echo $sql;
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $resultMsg = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><i class="fa fa-check-circle"></i> สำเร็จ!</strong> แก้ไขข้อมูลเรียบร้อยแล้ว
                    </div>';
    } else {
        $resultMsg = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><i class="fa fa-times-circle"></i> แก้ไขข้อมูลไม่สำเร็จ!</strong> กรุณากรอกใหม่
                    </div>';
    }
}
if (isset($_GET['viewProfile'])) {
    $viewProfile = true;
    $username = $_SESSION['username'];
    $sql = "select * from user where username = '$username'";
    $query = mysqli_query($conn, $sql);
    $profile = mysqli_fetch_assoc($query);
    $tel = $profile['tel'];
    $lineId = $profile['line_id'];
    $address = $profile['address'];
    $password = $profile['password'];
    $name = $profile['name'];
    $username = $profile['username'];
    $type = $profile['type'];
    $email = $profile['email'];
}
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>
        $(document).ready(() => {
            $("#tel").mask("999-9999999");
        })
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>
<script>
    function checkPassword() {
        let pass = $("#password").val();
        let cpass = $("#cpassword").val();
        if (cpass != pass) {
            $("#cpassword").val("");
            $("#alertPass").html("*รหัสผ่านไม่ตรงกันกรุณากรอกใหม่อีกครั้ง");
        } else {
            $("#alertPass").html("");
        }
    }
</script>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <strong><i class="fa fa-users"></i> สมัครสมาชิก</strong>
        </div>
        <div class="card-body">
            <?php
            if ($resultMsg != '') {
                echo $resultMsg;
            }
            ?>
            <form id="regisForm" method="post">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">ชื่อผู้ใช้</label>
                                <input class="form-control" name="username" id="username"
                                    <?php if ($viewProfile) {
                                        echo 'readonly ';
                                        echo 'value="' . $username . '"';
                                    } ?>
                                       required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">ชื่อ-สกุล</label>
                                <input class="form-control" name="name" id="name"
                                    <?php if ($viewProfile) {
                                        echo 'value="' . $name . '"';
                                    } ?>
                                       required>
                            </div>
                        </div>
                        <?php if (!$viewProfile) {
                            ?>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">รหัสผ่าน</label>
                                    <input type="password" class="form-control" name="password" id="password"

                                           required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">คอนเฟิร์มรหัสผ่าน</label>
                                    <input type="password" onchange="checkPassword()" class="form-control"
                                           name="cpassword"
                                           id="cpassword" required>
                                    <small class="text-danger" id="alertPass"></small>
                                </div>
                            </div>
                            <?php
                        } ?>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">โทรศัพท์</label>
                                <input class="form-control" name="tel" id="tel"
                                    <?php if ($viewProfile) {
                                        echo 'value="' . $tel . '"';
                                    } ?>
                                       required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">ไลน์ไอดี</label>
                                <input class="form-control" name="lineId" id="lineId"
                                    <?php if ($viewProfile) {
                                        echo 'value="' . $lineId . '"';
                                    } ?>
                                >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">อีเมล</label>
                                <input class="form-control" name="email" id="email"
                                    <?php if ($viewProfile) {
                                        echo 'value="' . $lineId . '"';
                                    } ?>
                                >
                            </div>
                        </div>
                        <div class="col-6"></div>
                        <?php

                        if (isset($_SESSION['username'])) {
                            ?>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">ประเภทผู้ใช้ระบบ</label>
                                    <select class="form-control" name="type" id="type">
                                        <?php if ($_SESSION['type'] == 'A') { ?>
                                            <option value="A">เจ้าของ</option>
                                            <option value="C">แคชเชียร์</option>
                                        <?php } ?>
                                        <option value="M" selected>ลูกค้า</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6"></div>
                            <?php
                        } ?>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Address</label>
                                <textarea class="form-control" name="address" id="address"  required><?php if ($viewProfile) {
                                        echo  $address;
                                    } ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div align="center">
                        <?php if ($viewProfile) { ?>
                            <button type="submit" name="updateProfile" class="btn btn-primary"><i
                                        class="fa fa-edit"></i> แก้ไข Profile
                            </button><?php } else { ?>
                            <button type="submit" name="insertRegis" class="btn btn-primary"><i
                                        class="fa fa-check-circle"></i> ยืนยันการสมัคร
                            </button><?php } ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="footer bg-warning text-white">

        </div>
    </div>
</div>
</body>
</html>