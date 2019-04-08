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
$sql = "SELECT * FROM information ";
$query = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($query);
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="row" align="center">
            <div class=" col col-sm col-md col-lg">
                <div class="card">
                    <div class="card-header bg-info text-white"
                         id="collapseNews"><strong><i class="fa fa-list"></i> รายการข่าวสาร/กิจกรรม</strong></div>
                    <div class="card-body" id="collapseInfo">
                        <div align="right">
                            <a href="_information_create.php" class="btn btn-xs btn-outline-primary"><i class="fa fa-plus-circle"></i> เพิ่มข่าวสารใหม่</a>
                        </div> <br>
                        <table id="infoList" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>หัวข้อ</th>
                                <th>วันที่สร้าง</th>
                                <th>สร้างโดย</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "select i.info_name,i.info_id,DATE_FORMAT(i.info_date, '%d/%m/%Y') as info_date,u.name from information i inner join user u on u.username = i.info_owner";
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
</div>
</body>
</html>