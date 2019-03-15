<?php
include '__connect.php';
//include '__checkSession.php';
$header = "แก้ไข";
$alert = '';
$isUpdate = 'N';
$roomNo = '';
if (isset($_POST['insertRoom'])) {
    if ($_FILES['imgInp']['error'] == 0) {
        $file = $_FILES['imgInp']['tmp_name'];
        $img = addslashes(file_get_contents($file));
    } else {
        $img = addslashes(file_get_contents('img/room.png'));
    }
    $saveRoomCost = $_POST['saveRoomCost'];
    $saveRoomNo = $_POST['saveRoomNo'];
    $saveRoomSize = $_POST['saveRoomSize'];
    $sql = "insert into room values('$saveRoomNo','$saveRoomSize','A','$saveRoomCost')";

    $query = mysqli_query($conn, $sql);
    if ($query) {
        $sql = "insert into room_img
                select '$saveRoomNo',ifnull(max(room_img_id)+1, 1),'$img','','Y' from room_img";
        $query = mysqli_query($conn, $sql);
        $isUpdate = 'Y';
        $roomNo = $saveRoomNo;
        $alert = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><i class="fa fa-check-circle"></i> สำเร็จ!</strong> เพิ่มข้อมูลเรียบร้อยแล้ว
                    </div>';
    } else {
        $alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong><i class="fa fa-times-circle"></i> เพิ่มข้อมูลไม่สำเร็จ!</strong> หมายเลขห้องซ้ำกรุณาระบุใหม่
                    </div>';
    }
} else if (isset($_GET['roomDetail']) && isset($_GET['roomNo'])) {
    $isUpdate = 'Y';
    $roomNo = $_GET['roomNo'];
}
if ($isUpdate == 'Y') {
    $sql = "select r.*,i.img from room r 
            inner join room_img i on i.room_id = r.room_id and i.main_flag='Y' 
            where  r.room_id = '$roomNo'";
    $query = mysqli_query($conn, $sql);
    $roomDetail = mysqli_fetch_assoc($query);
}
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

$sql = "SELECT r.*,i.img,s.name,t.status_desc FROM ROOM r 
INNER JOIN room_size s ON r.size = s.size_id
INNER JOIN room_status t ON t.status = r.status
 INNER JOIN room_img i ON i.room_id = r.room_id and i.main_flag = 'Y' where 1=1 ";
$size  = 'A';
$cost = 0;
if (isset($_GET['searchRoom'])) {
    $size = $_GET['searchSize'];
    $cost = $_GET['searchCost'];
    if($size!='A'){
        $sql = $sql . " AND size='$size'";
    }
    if ($cost == 1) {
        $sql = $sql . " AND cost between 100 and 299 ";
    } else if ($cost == 3) {
        $sql = $sql . " AND cost between 300 and 499 ";
    } else if ($cost == 5) {
        $sql = $sql . " AND cost >500 ";
    }
}
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <form method="get">
                    <div class="card-header font-weight-bold">รายละเอียดหอพัก</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item bg-warning font-weight-bold">
                                อัตราค่าเข้าพัก
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchCost1"  value="1" name="searchCost" class="custom-control-input" <?php if($cost==1) echo 'checked'?>>
                                    <label class="custom-control-label"  for="searchCost1">100 - 200
                                        บาท/คืน</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchCost2"  value="3" name="searchCost" class="custom-control-input" <?php if($cost==3) echo 'checked'?>>
                                    <label class="custom-control-label" for="searchCost2" >300 - 500
                                        บาท/คืน</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchCost3" name="searchCost" value="5" class="custom-control-input" <?php if($cost==5) echo 'checked'?>>
                                    <label class="custom-control-label"  for="searchCost3">มากกว่า 500
                                        บาท/คืน</label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchCost4" name="searchCost" value="0" class="custom-control-input"
                                           <?php if($cost==0) echo 'checked'?>>
                                    <label class="custom-control-label" for="searchCost4">ทุกราคา</label>
                                </div>
                            </li>
                        </ul>
                        <hr>
                        <ul class="list-group">
                            <li class="list-group-item bg-warning font-weight-bold">
                                ขนาดห้อง
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchSize1" name="searchSize" value="S" class="custom-control-input" <?php if($size=='S') echo 'checked'?>>
                                    <label class="custom-control-label" for="searchSize1"> 1 คน </label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchSize2" name="searchSize" value="M" class="custom-control-input" <?php if($size=='M') echo 'checked'?>>
                                    <label class="custom-control-label" for="searchSize2"> 2-3 คน </label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchSize3" name="searchSize" value="L" class="custom-control-input" <?php if($size=='L') echo 'checked'?>>
                                    <label class="custom-control-label" for="searchSize3"> 4-5 คน </label>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="searchSize4" name="searchSize" value="A" class="custom-control-input"
                                        <?php if($size=='A') echo 'checked'?>>
                                    <label class="custom-control-label" for="searchSize4"> ทุกขนาด </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer bg-white">
                        <div align="right">
                            <div class="btn-group">
                                <button class="btn btn-outline-primary" name="searchRoom" type="submit"> ค้นหา</button>
                                <a class="btn btn-outline-info" href="_room-master.php?roomDetail=1"> เพิ่มห้องพัก</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-9">
            <?php if (isset($_GET['roomDetail'])) {
                include '_room-master-detail.php';
            } else {
                include '_room-master-result.php';
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
