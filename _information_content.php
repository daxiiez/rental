<?php
include '__connect.php';
include '__checkSession.php';

if($_SESSION['userType'] == 'S'){
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
?>
<?php
    $id = $_GET['id'];
    $sql = "SELECT * FROM information WHERE info_id = {$id}";
    $query = mysqli_query($conn, $sql);
    $info = mysqli_fetch_assoc($query);
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <form action="_information_content.php" method="post">

            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="_information.php">ข่าวสาร</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $info['info_name']; ?></li>
                    </ol>
                </nav>
            </div>

            <div class="card-body">
                <a class="btn btn-sm btn-outline-info float-right" href="_information_create.php?update=<?php echo $_GET['id']?>"><i class="fa fa-edit"></i> แก้ไข</a><br>
                <?php echo base64_decode($info['info_content']); ?>
            </div>
        </form>
    </div>
</div>
</body>
</html>