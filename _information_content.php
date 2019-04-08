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
$id = $_GET['id'];
$sql = "SELECT * FROM information WHERE info_id = {$id}";
$query = mysqli_query($conn,$sql);
$info = mysqli_fetch_assoc($query);
?>

<div class="container-fluid"  style="margin-top: 10px; margin-bottom: 150px;" >
    <div class="card">
        <form action="_information_create.php" method="post">
            <div class="card-body">
                <?php echo base64_decode($info['info_content']); ?>
            </div>
        </form>
    </div>
</div>
</body>
</html>