<?php
include '__connect.php';
include '__checkSession.php';

if (isset($_POST['uploadImg'])) {
    $upd_rental_id = $_POST['rental_id'];
    $file = $_FILES['imgInp']['tmp_name'];
    $img = addslashes(file_get_contents($file));
    $sql = "UPDATE rental_detail set pay_img = '$img' , status='W' where rental_id = '$upd_rental_id'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        echo '<script>alert("อัพโหลดสำเร็จ!")</script>';
    }
}

$username = $_SESSION['username'];
$sql = "SELECT rental_id,
               DATE_FORMAT(rent_date,'%d/%m/%Y') as rent_date,
               room_id,
               username,
               rent_days,
               DATE_FORMAT(check_in,'%d/%m/%Y') as check_in,
               DATE_FORMAT(check_out,'%d/%m/%Y') as check_out,
               rent_cost,
               deposit,
               status,
               pay_img FROM rental_detail WHERE username ='$username' order by rental_id desc";
$query = mysqli_query($conn, $sql);
$rental = mysqli_fetch_array($query);
$status = $rental['status'];


?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>

        $(document).ready(function () {
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

            $("#imgInp").change(function () {
                readURL(this, '#img-upload');
            });

            $("#imgEdit").change(function () {
                readURL(this, '#editProductImg');
            });
        });
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>

<div class="container" style="margin-top: 10px; margin-bottom: 150px;">
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
            <div class="row">
                <div class="col-4">
                    <b>สถานะการจอง : </b> <?php
                    if ($status == 'N') {
                        echo '<span class="badge badge-danger">ยังไม่ได้อัพโหลดหลักฐาน</span>';
                    } elseif ($status == 'Y') {
                        echo '<span class="badge badge-primary">อัพโหลดหลักฐานเรียบร้อยแล้ว</span>';
                    } elseif ($status == 'W') {
                        echo '<span class="badge badge-warning">รอพนักงานตรวจสอบหลักฐาน</span>';
                    } elseif ($status == 'S') {
                        echo '<span class="badge badge-success">พร้อมให้เข้าพัก</span>';
                    } elseif ($status == 'C') {
                        echo '<span class="badge badge-secondary">ถูกยกเลิกแล้ว</span>';
                    }
                    ?>
                </div>
            </div>
            <?php
            $sql = "select * from user where username = '$username'";
            $query = mysqli_query($conn, $sql);
            $profile = mysqli_fetch_assoc($query);
            ?>
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
            <br>
            <div class="row">
                <div class="col-4 mx-auto">
                    <div align="center">
                        <div class="card">
                            <div class="card-header">
                                หลักฐานการชำระเงิน
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">

                                        <?php
                                        if ($rental['pay_img'] != '0x') {
                                            ?>
                                            <img id="img-upload"
                                                 src="data:image/jpeg;base64,<?php echo base64_encode($rental['pay_img']) ?>"
                                                 width="50%">

                                            <?php
                                        } else {
                                            ?>
                                            <img id="img-upload" src="img/scb-logo.jpg" width="200" border="5"> <?php
                                        }
                                        ?>
                                        <hr>
                                    </div>
                                    <?php
                                    if ($status == 'W' || $status == 'N') {
                                        ?>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div align="center">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <form id="addImageForm" method="post"
                                                              enctype="multipart/form-data">
                                                            <input type="hidden" name="rental_id"
                                                                   value="<?php echo $rental['rental_id'] ?>">
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-file btn-outline-info"><i
                                                                            class="fa fa-image"></i> เลือกรูปภาพ
                                                                    <input type="file" name="imgInp" id="imgInp"
                                                                           required>
                                                                </button>
                                                                <button name="uploadImg"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                    <i class="fa fa-check"></i> อัพโหลด
                                                                </button>
                                                            </div>
                                                        </form>
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

        </div>
        <div class="card-footer">
            <div align="center">
                <a class="btn btn-info" target="_blank"
                   href="__PDF_booking_cost.php?rental_id=<?php echo $rental['rental_id']; ?>"><i
                            class="fa fa-print"></i> พิมพ์รายการ</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
