<?php
include '__connect.php';
include '__checkSession.php';

if($_SESSION['userType'] != 'A'){
    header("Location: _home.php");
}
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

$searchName = "";
$searchTel = "";
$searchAddress = "";


$searchUserUserName = '';
$searchUserName = '';
$searchUserType = '';
$searchUserTel = '';
$searchUserAddress = '';
$searchUserStatus = '';


if (isset($_POST['insertUser'])) {
    $newName = $_POST['newUserName'];
    $newUserName = $_POST['newUserUserName'];
    $newTel = $_POST['newUserTel'];
    $newAddress = $_POST['newUserAddress'];
    $newPassword = $_POST['newUserPass'];
    $newType = $_POST['newUserType'];

    $sql = "insert into user values('$newUserName','$newPassword','$newName','$newTel','$newAddress','$newType')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> เพิ่มข้อมูลผู้ใช้ระบบแล้ว
                    </div>';
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>มีบางอย่างผิดพลาด!</strong> เพิ่มข้อมูลผู้ใช้ระบบไม่สำเร็จ
                    </div>';
    }
}

if (isset($_POST['insertUser']) || isset($_POST['updateUser'])) {
    echo $alert;
}
?>

<script>
    $(document).ready(function () {
        $("#searchUserTel").mask("999-9999999");
        $("#newUserTel").mask("999-9999999");
        $("#editUserTel").mask("999-9999999");
        $('#UserTable').DataTable();
    });

    function updateStatus(username, status) {
        let obj = {
            username: username,
            status: status
        }
        if (confirm("ต้องการแก้ไขสถานะการใช้งาน ?")) {
            $.post("SQL_Update/updateUserStatus.php", obj, data => {
                if (data) {
                    alert("แก้ไขสถานะสำเร็จ");
                    window.location.reload();
                }
            })
        }
    }

    function updateType(username, type) {
        let obj = {
            username: username,
            position: type
        }
        if (confirm("ต้องการแก้ไขตำแหน่ง ?")) {
            $.post("SQL_Update/updateUserType.php", obj, data => {
                if (data) {
                    alert("แก้ไขตำแหน่งสำเร็จ");
                    window.location.reload();
                }
            })
        }
    }
</script>

<div class="container-fluid" style="margin-top: 20px;">
    <form action="_user_manage.php" method="get">
        <div class="card ">
            <div class="card-header bg-secondary text-white">
                <strong>รายละเอียดการค้นหา</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>ชื่อผู้ใช้</label>
                            <input class="form-control" id="searchUserUserName" name="searchUserUserName" type="text"
                                   value="<?php echo $searchUserUserName; ?>">
                        </div>

                    </div>

                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>ชื่อ</label>
                            <input class="form-control" id="searchUserName" name="searchUserName" type="text"
                                   value="<?php echo $searchUserName; ?>">
                        </div>

                    </div>


                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>ตำแหน่ง</label>
                            <select class="custom-select" name="searchUserType" id="searchUserType">
                                <option value="" selected>-- ทั้งหมด --</option>

                                <option value="S"> รักษาความปลอดภัย</option>
                                <option value="A"> ผู้ดูแล</option>
                            </select>
                        </div>
                    </div>

                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="img-area">
                            <div class="form-group">
                                <label>เบอร์โทรศัพท์</label>
                                <input class="form-control" id="searchUserTel" name="searchUserTel" type="text"
                                       value="<?php echo $searchUserTel; ?>">
                            </div>
                        </div>
                    </div>
                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">

                        <div class="form-group">
                            <label>ที่อยู่</label>
                            <input class="form-control" id="searchUserAddress" name="searchUserAddress" type="text"
                                   value="<?php echo $searchUserAddress; ?>">
                        </div>
                    </div>

                    <div class=" col-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>สถานะการใช้งาน</label>
                            <select class="custom-select" name="searchUserStatus" id="searchUserStatus">
                                <option value="" selected>-- ทั้งหมด --</option>
                                <option value="A"> ใช้งาน</option>
                                <option value="S"> พักงาน</option>
                                <option value="C"> ยกเลิก</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-outline-primary btn-sm" type="submit" name="searchKey"><i
                        class="fa fa-search"></i> ค้นหา
                </button>
                <a class="btn btn-outline-dark btn-sm" href="account.php"><i class="fa fa-undo"></i>
                    คืนค่า</a>
                <button class="btn btn-outline-info btn-sm"
                        data-toggle="modal"
                        data-target="#addUserModal"
                        type="button"><i class="fa fa-plus"></i> เพิ่มผู้ใช้ระบบ
                </button>
            </div>
    </form>
</div>
</div>


<div class="container-fluid" style="margin-bottom: 150px;">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <strong>รายการสินค้า</strong>
        </div>
        <div class="card-body">
            <?php
            if (isset($_GET['searchKey'])) {
                $searchUserUserName = $_GET['searchUserUserName'];
                $searchUserName = $_GET['searchUserName'];
                $searchUserType = $_GET['searchUserType'];
                $searchUserTel = $_GET['searchUserTel'];
                $searchUserAddress = $_GET['searchUserAddress'];
                $searchUserStatus = $_GET['searchUserStatus'];
                $sql = "select u.*,t.full_position from User u  inner join usertype t on t.position = u.position 
                                where (u.username like '%$searchUserUserName%' OR '$searchUserUserName' = '')
                                  AND (u.name like '%$searchUserName%' OR '$searchUserName' = '') 
                                  AND (u.tel like '%$searchUserTel%' OR '$searchUserTel' = '') 
                                  AND (u.position like '%$searchUserType%' OR '$searchUserType' = '') 
                                  AND (u.address like '%$searchUserAddress%' OR '$searchUserAddress' = '') ";
            } else {
                $sql = "select u.*,t.full_position from User u inner join usertype t on t.position = u.position";
            }
            $result = mysqli_query($conn, $sql);
            $count = 0;
            ?>
            <table id="UserTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr class="text-center">
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ</th>
                    <th>ตำแหน่ง</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>แก้ไขตำแหน่ง</th>
                </tr>
                </thead>

                <tbody>
                <?php
                while ($temp = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $count++;
                    ?>

                    </from>
                    <tr>
                        <td class="text-center"> <?php echo $temp['username']; ?></td>
                        <td> <?php echo $temp['name']; ?></td>
                        <td> <?php echo $temp['full_position']; ?></td>
                        <td> <?php echo $temp['tel']; ?></td>

                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Basic example">

                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        onclick="updateType('<?php echo $temp["username"]; ?>','S')"><i
                                        class="fa fa-wrench"></i> รักษาความปลอดภัย
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="updateType('<?php echo $temp["username"]; ?>','A')"><i
                                        class="fa fa-industry"></i> ผู้ดูแล
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<script>
    function validatePass() {
        let newPass = $("#newUserPass").val();
        let rePass = $("#newUserRePass").val();
        if (newPass != rePass) {
            alert("รหัสผ่านไม่ถูกต้อง");
            $("#newUserRePass").val('');
        }

    }
</script>
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> เพิ่มผู้ใช้ระบบใหม่</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>ชื่อผู้ใช้</label>
                                <input class="form-control" name="newUserUserName" id="newUserUserName"
                                       type="text"
                                       maxlength="20"
                                       required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>ชื่อ</label>
                                <input class="form-control" name="newUserName" id="newUserName"
                                       type="text"
                                       maxlength="20"
                                       required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>เบอร์โทรศัพท์</label>
                                <input class="form-control" name="newUserTel" id="newUserTel"
                                       type="text"
                                       maxlength="20"
                                       required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>ประเภท</label>
                                <select class="custom-select" name="newUserType" id="newUserType" required>
                                    <option value="S"> รักษาความปลอดภภัย</option>
                                    <option value="A"> ผู้ดูแล</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>รหัสผ่าน</label>
                                <input class="form-control"
                                       name="newUserPass"
                                       id="newUserPass"
                                       maxlength="15"
                                       type="password"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                       required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>ยืนยันรหัสผ่าน</label>
                                <input class="form-control"
                                       name="newUserRePass"
                                       id="newUserRePass"
                                       maxlength="15"
                                       type="password"
                                       onchange="validatePass()"
                                       required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <input class="form-control"
                                       name="newUserAddress"
                                       id="newUserAddress"
                                       type="text"
                                       required>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" name="insertUser" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่ม
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> ปิด
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
