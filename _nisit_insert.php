<?php
include '__connect.php';
include '__checkSession.php';
?>
<!Document>
<html>

<head>
    <?php include '__header.php';

    $code = "";
    $name = "";
    $firstName = "";
    $lastname = "";
    $level = "";
    $major = "";
    $sex = "";
    $birthdate = "";
    $ID = "";
    $address = "";
    $tel = "";
    $picture = "";
    $parent_name = "";
    $teacher_name = "";
    $username = "";
    $password = "";
    $parent_tel = "";
    $department = "";

    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        //select data
        $sql = "SELECT * FROM `student` WHERE `code` = '$code'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $code = $row['code'];
            $name = $row['name'];
            list($firstName, $lastname) = explode(" ", $name);
            $level = $row['level'];
            $major = $row['major'];
            $sex = $row['sex'];
            $birthdate = $row['birthdate'];
            $ID = $row['ID'];
            $address = $row['address'];
            $tel = $row['tel'];
            $picture = $row['picture'];
            $showpicture = $row['picture'];
            $parent_name = $row['parent_name'];
            $teacher_name = $row['teacher_name'];
            $username = $row['username'];
            $password = $row['password'];
            $parent_tel = $row['parent_tel'];
            $department = $row['department'];
        }

    }
    if (isset($_POST['insertNisit'])) {
        $code = $_POST['code'];

        $firstName = trim($_POST['firstName']);
        $lastname = trim($_POST['lastName']);
        $name = $firstName . " " . $lastname;

        $level = $_POST['level'];
        $major = $_POST['major'];
        $sex = $_POST['sex'];
        $birthdate = $_POST['birthdate'];
        $ID = $_POST['ID'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];

        if (isset($_FILES['picture'])) {
            $file = $_FILES['picture']['tmp_name'];
            $picture = addslashes(file_get_contents($file));
        }

        $parent_name = $_POST['parent_name'];
        $teacher_name = $_POST['teacher_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $parent_tel = $_POST['parent_tel'];
        $department = $_POST['department'];


        $sql = "INSERT INTO `student` (`code`, `name`, `level`, `major`, `sex`, `birthdate`, `ID`, `address`, `tel`, `picture`, `parent_name`, `teacher_name`, `username`, `password`, `parent_tel`, `department`) VALUES ('$code', '$name', '$level', '$major', '$sex', '$birthdate', '$ID', '$address', '$tel','$picture' ,'$parent_name', '$teacher_name', '$username', '$password', '$parent_tel', '$department');";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> เพิ่มข้อมูลนิสิตแล้ว
                    </div>';
            echo '<script>alert("เพิ่มข้อมูลนิสิตแล้ว"); window.location = "_nisit.php";</script>';
        } else {

        }
    } else if (isset($_POST['updateNisit'])) {

        $firstName = trim($_POST['firstName']);
        $lastname = trim($_POST['lastName']);
        $name = $firstName . " " . $lastname;

        $level = $_POST['level'];
        $major = $_POST['major'];
        $sex = $_POST['sex'];
        $birthdate = $_POST['birthdate'];
        $ID = $_POST['ID'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $parent_name = $_POST['parent_name'];
        $teacher_name = $_POST['teacher_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $parent_tel = $_POST['parent_tel'];
        $department = $_POST['department'];

        $sql = "UPDATE student SET
                    name = '$name',
                    level = '$level',
                    major = '$major',
                    sex = '$sex',
                    birthdate = '$birthdate',
                    ID = '$ID',
                    address = '$address',
                    tel = '$tel',
                    parent_name = '$parent_name',
                    teacher_name = '$teacher_name',
                    password = '$password',
                    parent_tel = '$parent_tel',
                    department = '$department'";


        if ($_FILES['picture']['error'] == 0) {
            $file = $_FILES['picture']['tmp_name'];
            $picture = addslashes(file_get_contents($file));
            $sql .= ", picture='$picture' ";
        }

        $sql .= "WHERE student.code = '$code';";
        $query = mysqli_query($conn, $sql);
        if ($query) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> แก้ไขข้อมูลนิสิตแล้ว
                    </div>';

            $sql = "SELECT * FROM `student` WHERE `code` = '$code'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $code = $row['code'];
                $name = $row['name'];
                list($firstName, $lastname) = explode(" ", $name);
                $level = $row['level'];
                $major = $row['major'];
                $sex = $row['sex'];
                $birthdate = $row['birthdate'];
                $ID = $row['ID'];
                $address = $row['address'];
                $tel = $row['tel'];
                $picture = $row['picture'];
                $showpicture = $row['picture'];
                $parent_name = $row['parent_name'];
                $teacher_name = $row['teacher_name'];
                $username = $row['username'];
                $password = $row['password'];
                $parent_tel = $row['parent_tel'];
                $department = $row['department'];
            }


        }
    }


    ?>

    <script>
        $(document).ready(function () {

            $('#birthdate').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy/mm/dd'
            });

            $(document).on('change', '.btn-file :file', function () {
                var input = $(this),
                    // label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    label = input.val();
                //  label = input.files[0].name;
                input.trigger('fileselect', [label]);
            });
            $('.btn-file :file').on('fileselect', function (event, label) {
                var input = $(this).parents('.input-group').find(':text'),
                    log = label;
                if (input.length) {
                    input.val(log);
                } else {
                    // if (log) alert(log);
                }
            });

            function readURL(input, id) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(id).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                    readFile(input.files[0], function (e) {
                        // alert( e.target.result);
                    });
                }
            }

            function readFile(file, callback) {
                var reader = new FileReader();
                reader.onload = callback
                reader.readAsText(file);
            }

            $("#picture").change(function () {
                readURL(this, '#showpicture');
            });
            $(document).ready(() => {
                $("#tel").mask("999-9999999");
            });
        });


    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>
<div class="container-fluid" style="margin-top: 10px;">


    <div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
        <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">
                    <h5>
                        <?php
                            if ($code != null){
                                echo "แก้ไขข้อมูลนิสิต";
                            }else {
                                echo "เพิ่มข้อมูลนิสิต";
                            }
                        ?>
                    </h5>
                </nav>
            </div>
            <div class="card-body">
                <div style="width: 60%" class="mx-auto">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12" align="center">
                                <?php
                                if ($picture == '') {
                                    echo '<img id="showpicture" src="img/profile.png" width="200" border="5">';
                                } else
                                    echo '<img id="showpicture" width="200" border="5" src="data:image/jpeg;base64,' . base64_encode($picture) . '" />';
                                ?>
                                <hr>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                <span class="input-group-btn">
                                      <span class="btn btn-sm btn-default btn-file btn-outline-info"><i
                                                  class="fa fa-image"></i> เลือกรูปภาพ
                                           <input type="file" name="picture" id="picture">
                                      </span>
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            if (isset($_GET['code'])) {
                                echo "<div class='col col-sm col-lg col-md'>
                        <div class='form-group'>
                            <label>ชื่อเข้าใช้งาน :</label>
                            <input name='username' id='username' class='form-control' value='$username' readonly>
                        </div>
                    </div>";
                            } else {
                                echo '<div class="col col-sm col-lg col-md">
                        <div class="form-group">
                            <label>ชื่อเข้าใช้งาน :</label>
                            <input name="username" id="username" class="form-control" maxlength="20" minlength="8" required pattern="^[a-zA-Z0-9_.-]*$" placeholder="ชื่อผู้ใช้งานต้องมีตัวอักษรตั้งแต่ 8-20 ตัวอักษร">
                        </div>
                    </div>';
                            }
                            ?>

                        </div>
                        <script>


                        </script>
                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>รหัสผ่าน :</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                           value="<?php echo $password; ?>" maxlength="20" minlength="8" required
                                           pattern="^[a-zA-Z0-9_.-]*$"
                                           placeholder="รหัสผ่านต้องมีตัวอักษรตั้งแต่ 8-20 ตัวอักษร">
                                </div>
                            </div>
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>ยืนยันรหัสผ่าน :</label>
                                    <input type="password" name="conPassword" id="conPassword" class="form-control"
                                           value="<?php echo $password; ?>" maxlength="20" minlength="8" required
                                           pattern="^[a-zA-Z0-9_.-]*$"
                                           placeholder="รหัสผ่านและยืนยันรหัสผ่านต้องตรงกัน">
                                </div>
                            </div>
                        </div>


                        <hr>


                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>ชื่อ :</label>
                                    <input name="firstName" id="firstName" class="form-control"
                                           value="<?php echo $firstName; ?>"
                                           placeholder="ชื่อจริงของนิสิต" required>
                                </div>
                            </div>
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>นามสกุล :</label>
                                    <input name="lastName" id="lastName" class="form-control"
                                           value="<?php echo $lastname; ?>"
                                           placeholder="นามสกุลนิสิต" required>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>วันเกิด :</label>
                                    <input name="birthdate" id="birthdate" value="<?php echo $birthdate; ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <?php
                            if ($sex == 'm') {
                                echo '<div class="col col-sm col-lg col-md">
                        <label>เพศ :
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input checked type="radio" name="sex" id="sex" value="m">
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> ชาย</output>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" name="sex" id="sex" value="f">
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> หญิง</output>
                            </div>
                        </label>
                    </div>';
                            } else if ($sex == 'f') {
                                echo '<div class="col col-sm col-lg col-md">
                        <label>เพศ :
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" name="sex" id="sex" value="m">
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> ชาย</output>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input checked type="radio" name="sex" id="sex" value="f">
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> หญิง</output>
                            </div>
                        </label>
                    </div>';
                            } else {
                                echo '<div class="col col-sm col-lg col-md">
                        <label>เพศ :
                            <div class="input-group form-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" name="sex" id="sex" value="m" required>
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> ชาย</output>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="radio" name="sex" id="sex" value="f" required>
                                    </div>
                                </div>
                                <output type="text" class="form-control" style="width: 111px; text-align: center"> หญิง</output>
                            </div>
                        </label>
                    </div>';
                            }
                            ?>
                        </div>

                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>รหัสบัตรประชาชน :</label>
                                    <input name="ID" id="ID" class="form-control" value="<?php echo $ID; ?>"
                                           maxlength="13"
                                           minlength="13" required
                                           pattern="^([1|2|3|4|5|6]{1})([0-9]{12})$"
                                           placeholder="รหัสประชาชนเป็นตัวเลข 13 หลัก">
                                </div>
                            </div>

                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>เบอร์โทรศัพท์ :</label>
                                    <input type="tel" name="tel" id="tel" class="form-control"
                                           value="<?php echo $tel; ?>"
                                           maxlength="11"
                                           minlength="11" required
                                           placeholder="เบอร์โทรศัพท์ ตัวอย่าง(089-1231231)">
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>ที่อยู่ :</label>
                                    <textarea name="address" id="address"
                                              class="form-control" required
                                              placeholder="กรุณากรอกที่อยู่ให้ครบถ้วน"><?php echo $address; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>ชื่อผู้ปกครอง :</label>
                                    <input name="parent_name" id="parent_name" class="form-control"
                                           value="<?php echo $parent_name; ?>" required
                                           placeholder="ชื่อผู้ปกครองของนิสิต">
                                </div>
                            </div>
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>เบอร์ผู้ปกครอง :</label>
                                    <input name="parent_tel" id="parent_tel" class="form-control"
                                           maxlength="11"
                                           minlength="11" required
                                           placeholder="เบอร์โทรศัพท์ ตัวอย่าง(089-1231231)"
                                           value="<?php echo $parent_tel; ?>">
                                </div>
                            </div>
                        </div>
                        <hr>


                        <div class="row">
                            <?php
                            if (isset($_GET['code'])) {
                                echo "<div class='col col-sm col-lg col-md'>
                        <div class='form-group'>
                            <label>รหัสนิสิต :</label>
                            <input name='code' id='code' class='form-control' value='$code' disabled>
                        </div>
                    </div>";
                            } else {
                                echo "<div class='col col-sm col-lg col-md'>
                        <div class='form-group'>
                            <label>รหัสนิสิต :</label>
                            <input name='code' id='code' class='form-control'
                                   maxlength='10'
                                   minlength='10' required
                                   placeholder='รหัสนิสิต 10 ตัวอักษร'>
                        </div>
                    </div>";
                            }

                            ?>

                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>หลักสูตร/คณะ :</label>
                                    <input name="department" id="department" class="form-control"
                                           value="<?php echo $department; ?>" required
                                           placeholder="กรุณากรอกคณะของนิสิต">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>สาขา :</label>
                                    <input name="major" id="major" class="form-control" value="<?php echo $major; ?>"
                                           required placeholder="กรุณากรอกสาขาวิชาของนิสิต">
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <label>ชั้นปีที่ :</label>
                                    <input type="number" name="level" id="level" class="form-control"
                                           value="<?php echo $level; ?>" required
                                           placeholder="กรุณากรอกชั้นปีการศึกษาของนิสิต">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-sm col-lg col-md">
                                <div class="form-group">
                                    <label>ชื่ออาจารย์ที่ปรึกษา :</label>
                                    <input name="teacher_name" id="teacher_name" class="form-control"
                                           value="<?php echo $teacher_name; ?>"
                                           placeholder="กรุณาใส่ชื่ออาจารย์ที่ปรึกษา" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-2 col-sm-2 col-lg-2 col-md-2"></div>

                            <?php
                            if (isset($_GET['code'])) {
                                echo "<button type='submit' name='updateNisit' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> แก้ไขข้อมูลนักเรียน</button>";
                            } else {
                                echo "<button type='submit' name='insertNisit' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> เพิ่มข้อมูลนิสิต</button>";
                            }
                            ?>
                            <span style="margin: 10px"></span>
                            <button onclick="window.history.go(-1);" type="button"
                                    class="btn btn-danger col-4 col-sm-4 col-lg-4 col-md-4 "><i class="fa fa-times"></i>
                                ยกเลิก
                            </button>
                            <div class="col-2 col-sm-2 col-lg-2 col-md-2"></div>

                        </div>
                    </form>
                </div>

            </div>
            <div class="footer bg-warning text-white">

            </div>
        </div>
    </div>


    <!--    <div class="jumbotron jumbotron-fluid" style='background-image: url("img/Gear-BG-4.jpg"); '>-->

</div>
</body>
</html>
