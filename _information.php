<?php
include '__connect.php';
include '__checkSession.php';

if($_SESSION['userType'] == 'S'){
    header("Location: _home.php");
}

if (isset($_POST['insertInfo'])) {
    $info_owner = $_SESSION["admin"];
    $info_name = $_POST['info_name'];
    $info_content = $_POST['comment'];
    $info_content = base64_encode($info_content);
    $sql = "INSERT INTO information VALUES('','$info_name','$info_content','$info_owner',now(),0)";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $sql = "SELECT * FROM information ORDER BY info_id DESC LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $id = mysqli_fetch_assoc($query);
        echo '<script>alert("เพิ่มข้อมูลสำเร็จ"); window.location="_information_content.php?id=' . $id['info_id'] . '";</script>';
        //  echo 'สำเร็จ';
    } else {
        echo 'ไม่สำเร็จ';
        echo "<script>alert('เพิ่มข้อมูลไม่สำเร็จ'); window.location = '_information_create.php';</script>";
    }
}
?>

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
<script>
    $(document).ready(() => {

        $("#infoList").dataTable();

        $("#reportListTable").dataTable();
        $("#reportHeader").click();
        $("#infoHeader").click();


    })
</script>

<?php
if ($_SESSION['userType'] == 'A') {
?>
<div class="container-fluid" style="margin-top: 20px; margin-bottom: 30px;">
    <div class="card">
        <div class="card-header bg-success text-white font-weight-bold"><i class="fa fa-star"></i>
            ข่าวสาร/แจ้งปัญหา
        </div>
        <div class="card-body">
            <div class="row" align="center">
                <div class=" col-3 col-sm col-md col-lg">
                    <div class="img-area">
                        <a href="_information_create.php?create"> <img src="img/icon/news.png" class="image"
                                                                width="125" height="125">
                            <div class="overlay bg-primary ">
                                <div class="text"> เพิ่มข่าวสารใหม่</div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                if($_SESSION['userType']=='N'){
                ?>
                <div class=" col-3 col-sm col-md col-lg">
                    <div class="img-area ">
                        <a data-toggle="modal" data-target="#reportModal"> <img src="img/icon/uninsurance.png"
                                                                                class=" image"
                                                                                width="125" height="125">
                            <div class="overlay bg-warning ">
                                <div class="text"> แจ้งปัญหา</div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<div class="container-fluid" style="margin-top: 20px; margin-bottom: 30px;">
    <div class="card">

        <div class="card-body">
            <div class="row">
                <div class=" col col-sm col-md col-lg">
                    <div class="card">
                        <div class="card-header bg-success text-white" id="infoHeader" data-toggle="collapse"
                             href="#collapseInfo"><strong><i class="fa fa-list"></i> รายการข่าวสาร/กิจกรรม</strong>
                        </div>
                        <div class="card-body collapse" id="collapseInfo">
                            <div class="table-responsive">
                                <table id="infoList" class="table table-bordered rounded">
                                    <thead class="bg-success text-white">
                                    <tr>
                                        <th>หัวข้อ</th>
                                        <th>วันที่สร้าง</th>
                                        <th>สร้างโดย</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "select i.info_name,i.info_id,i.info_date,u.name from information i inner join user u on u.username = i.info_owner";
                                    $info_list = mysqli_query($conn, $sql);
                                    while ($temp = mysqli_fetch_array($info_list)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="_information_content.php?id=<?php echo $temp['info_id']; ?>"><?php echo $temp['info_name']; ?></a>
                                            </td>
                                            <td><?php echo $temp['info_date']; ?></td>
                                            <td><?php echo $temp['name']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($_SESSION['userType'] == 'A') {
            ?>
            <div class="row" style="margin-top: 20px;">
                <div class=" col col-sm col-md col-lg">
                    <div class="card">
                        <div class="card-header bg-warning text-white" id="reportHeader" data-toggle="collapse"
                             href="#collapseReport"><strong>
                                <i class="fa fa-exclamation-triangle"></i>
                                การแจ้งปัญหา</strong></div>
                        <div class="card-body collapse" id="collapseReport">
                            <div class="table-responsive">
                                <table id="reportListTable" class="table table-bordered rounded">
                                    <thead class="bg-warning text-white">
                                    <tr>
                                        <th>หัวข้อ</th>
                                        <th>วันที่สร้าง</th>
                                        <th>ตึก</th>
                                        <th>เลขตึก</th>
                                        <th>เลขห้อง</th>
                                        <th>สร้างโดย</th>
                                        <th>เบอร์ติดต่อ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    //if($_SESSION['type']=='N') $table = 'student'; else $table='user';
                                    $sql = "select i.*,s.full_status,u.name from report i inner join status s on s.status = i.type inner join student u on u.username = i.report_owner ORDER by report_date";
                                    $info_list = mysqli_query($conn, $sql);
                                    while ($temp = mysqli_fetch_array($info_list)) {
                                        ?>
                                        <tr>
                                            <td><a href="#/"><?php echo $temp['report_content']; ?></a></td>
                                            <td><?php echo $temp['report_date']; ?></td>
                                            <td><?php echo $temp['full_status']; ?></td>
                                            <td><?php echo $temp['tower']; ?></td>
                                            <td><?php echo $temp['room']; ?></td>
                                            <td><?php echo $temp['name']; ?></td>
                                            <td><?php echo $temp['tel']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php }?>
        </div>
    </div>
</div>
</body>
</html>