<?php
include '__connect.php';
include '__checkSession.php';

if (isset($_GET['rule_no'])) {
    $rule_no = $_GET['rule_no'];

    $sql = "SELECT * FROM `rule` WHERE `rule_no` = '$rule_no' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $rule_no = $row['rule_no'];
        $detail = $row['detail'];
        $score = $row['score'];
        $remark = $row['remark'];
        }

}else {
    $rule_no = "";
    $detail = "";
    $score = "";
    $remark = "";
}

if (isset($_POST['insertRules'])) {
    $detail = $_POST['detail'];
    $score = $_POST['score'];
    $remark = $_POST['remark'];

    $sql = "INSERT INTO `rule` (`detail`, `score`, `remark`) VALUES ('$detail', '$score', '$remark')";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> เพิ่มข้อมูลกฏเกณฑ์และข้อบังคับแล้ว
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> ข้อมูลที่ต้องการเพิ่มไม่ถูกต้อง
                    </div>';
    }

}else if (isset($_POST['updateRules'])){
    $rule_no = $_POST['rule_no'];
    $detail = $_POST['detail'];
    $score = $_POST['score'];
    $remark = $_POST['remark'];

    $sql = "UPDATE `rule` SET `detail` = '$detail', `score` = '$score', `remark` = '$remark' WHERE `rule`.`rule_no` = '$rule_no'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong>  แก้ไขข้อมูลกฏเกณฑ์และข้อบังคับแล้ว
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> ข้อมูลที่ต้องการแก้ไขไม่ถูกต้อง
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
                if (isset($_GET['rule_no'])) {
                    echo "แก้ไขกฏเกณฑ์ของหอพัก";
                } else {
                    echo "เพิ่มกฏเกณฑ์ของหอพัก";
                }
                ?>

            </div>
            <div class="card-body">
                <div class="container">


                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>รายละเอียดข้อบังคับ :</label>
                                <input name="detail" id="detail" class="form-control" required
                                       placeholder="เช่นเข้าหอเกินเวลาที่กำหนด" value="<?php echo $detail; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>หมายเหตุ :</label>
                                <input name="remark" id="remark" class="form-control" required
                                       placeholder="เช่น กระทำผิด 1 ครั้ง ถูกหัก 5 คะแนนน"
                                       value="<?php echo $remark; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>คะแนน :</label>
                                <input name="score" id="score" class="form-control" required
                                       placeholder="หากเป็นคะแนนที่ถูกหักกรุณาใส่เครื่องหมายลบ(-)ด้วย"
                                       value="<?php echo $score; ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2 col-sm-2 col-lg-2 col-md-2"></div>

                        <?php
                        if (isset($_GET['rule_no'])) {
                            echo "<input name='rule_no' id='rule_no' value='$rule_no' type='hidden'>";
                            echo "<button type='submit' name='updateRules' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> เแก้ไขข้อมูล</button>";
                        } else {
                            echo "<button type='submit' name='insertRules' class='btn btn-primary col-4 col-sm-4 col-lg-4 col-md-4'>
                        <i class='fa fa-plus'></i> เพิ่มข้อมูล</button>";
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


