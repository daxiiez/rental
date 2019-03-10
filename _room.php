<?php
include '__connect.php';
include '__checkSession.php';
if ($_SESSION['userType'] == 'S') {
    header("Location: _home.php");
}
if (isset($_GET['type']) && isset($_GET['tower_no'])) {
    $type = $_GET['type'];
    $tower_no = $_GET['tower_no'];
    $sql = "select * from tower where tower_no = '$tower_no' AND type = '$type'";
    $result = mysqli_query($conn, $sql);
    $temp = mysqli_fetch_array($result);
    $tower_name = $temp['tower_name'];
}
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>

        $(document).ready(() => {
            $('#reportContentList').DataTable();
        })
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 100px;">

    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">

                <h5>ข้อมูลห้องพัก <?php echo $tower_name; ?></h5>

            </nav>
        </div>
        <div class="card-body">
            <div style="width: 85%" class="mx-auto">


                <?php
                if ($_SESSION['userType'] == 'A') {
                ?> <td><a class="btn btn-sm btn-primary text-white"
                       onclick="window.location ='_room_insert.php?tower_no=<?php echo $_GET['tower_no'] ?>&type=<?php echo $_GET['type'] ?>'"><i
                                class="fa fa-search"></i>เพิ่มห้องพัก</a></td>
                    <?php
                }
                ?>
                <hr>
                <div class="table-responsive">
                    <table id="reportContentList" class="table table-bordered rounded">
                        <thead>
                        <tr class="text-center">
                            <th>ลำดับ</th>
                            <th>เลขห้อง</th>
                            <th>จำนวนที่พักได้</th>
                            <th>สถานะ<br>(จำนวนคนจอง/ขนาดห้อง)</th>
                            <?php
                            if ($_SESSION['userType'] == 'A') {
                                ?>
                                <th></th>
                                <?php
                            }
                            ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $type = '';
                        if (isset($_GET['type']) && isset($_GET['tower_no'])) {
                            $type = $_GET['type'];
                            $tower_no = $_GET['tower_no'];
                        }
                        $sql = "select * from room where tower_no like '%$tower_no%' AND type like '%$type%'";
                        $count = 0;
                        $result = mysqli_query($conn, $sql);
                        while ($temp = mysqli_fetch_array($result)) {
                            $count++;
                            if ($temp['status'] == 'Y' ) {
                                $temp['status'] = 'จองแล้ว';
                            } else if ($temp['status'] == 'N') {
                                $temp['status'] = 'ว่าง';
                            } else if ($temp['status'] == 'F') {
                                $temp['status'] = 'ปรับปรุง';
                            }
                            ?>
                            <tr class="text-center">
                                <td><?php echo $count ?></td>
                                <td><?php echo $temp['room_no'] ?></td>
                                <td><?php echo $temp['size'] ?></td>
                                <?php
                                if($temp['status'] != 'F'){
                                    ?>
                                    <td ><?php echo $temp['current_size'].'/'.$temp['size'] ?></td>
                                    <?php
                                }else{
                                ?>
                                <td> <?php echo $temp['status']; ?> </td>
                                  <?php
                                }
                                if ($_SESSION['userType'] == 'A') {
                                ?> <td>
                                        <a class="btn btn-sm btn-primary text-white"
                                       onclick="window.location ='_room_insert.php?tower_no=<?php echo $temp['tower_no'] ?>&room_no=<?php echo $temp['room_no'] ?>&type=<?php echo $_GET['type'] ?>'"><i
                                                class="fa fa-edit"></i> แก้ไขห้องพัก</a>
                                    </td>
                                <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="footer bg-warning text-white">

        </div>
    </div>


</div>
</body>
</html>
