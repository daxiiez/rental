<?php
include '__connect.php';
include '__checkSession.php';


?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>

    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
$sql = 'SELECT i.*,u.name from information i inner join user u on u.username = i.info_owner order by info_id desc limit 1';
$query = mysqli_query($conn,$sql);
$currentNew = mysqli_fetch_assoc($query);
?>
<div class="container-fluid" style="margin-top: 10px;">

    <div class="jumbotron jumbotron-fluid" style='background:transparent  url("img/bgjumbro.jpg"); font-family: headers;'>
        <div class="row">
            <div class="col-4 col-lg-4 col-sm-4 col-md-4">
                <img class=" float-right" src="img/KU.png" height="350">
            </div>

            <div class="col-8 col-lg-8 col-sm-8 col-md-8">
                <div class="container">
                    <h1 class="display-3 text-success" style=" font-size:150px; text-shadow: 5px 5px #ff0000; "> ระบบจองหอพักนิสิต</h1>
                    <h3 style=" font-size:50px; color: #1c7430; text-shadow: 1px 1px #ff0000; " > มหาวิทยาลัยเกษตรศาสตร์ วิทยาเขตกำแพงแสน</h3>
                    <h4> กองกิจการนิสิต ติดต่อ <i class="fa fa-phone"></i> 02-24236248</h4>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">
                    <h5><b><i class="fa fa-star"></i> กิจกรรมล่าสุด</b></h5>
                </nav>
            </div>
            <div class="card-body">
                <?php echo base64_decode($currentNew['info_content']); ?>
            </div>
            <div class="footer bg-warning text-white">
                <div class="container-fluid text-right">
                    <strong>ประกาศโดย</strong> <?php echo $currentNew['name'];  ?>
                    <strong>วันที่</strong> <?php echo $currentNew['info_date'];  ?>
                </div>
            </div>
    </div>
</div>
</body>
</html>
