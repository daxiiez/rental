<?php
include '__connect.php';
include '__checkSession.php';
if ($_SESSION['userType'] == 'S') {
    header("Location: _home.php");
}

$sql = "select b.start_date,
       b.end_date,
       r.room_no,
       r.tower_no,
       sex.full_status as tower_type,
       r.type as type,
       r.size,
       s.status,
       r.cost,
       r.current_size,
       if(sysdate() >= b.start_date and sysdate() <= b.end_date,'Y','N') as 'tower_status',
       if(sysdate() >= b.start_date and sysdate() <= b.end_date,'เปิดจองแล้ว','ยังไม่ถึงเวลาเปิดจอง') as 'tower_status_txt'
from room r
       left join status s on s.status = r.status
       left join status sex on sex.status = r.type
       inner join booking b on b.tower_no = r.tower_no
                                 and b.tower_type = r.type
                                 where r.status = 'N'";
$Stype = '';
$STowerNo = '';
$SRoomNo = '';
$SSize = '';
if (isset($_GET['searchKey'])) {
    $Stype = $_GET['searchType'];
    $SSize = $_GET['searchRoomSize'];
    $STowerNo = $_GET['searchTowerNo'];
    $SRoomNo = $_GET['searchRoomNo'];
    $sql .= " AND (r.type like '%$Stype%' OR '$Stype' = '')";
    $sql .= " AND (r.tower_no like '%$STowerNo%' OR '$STowerNo' = '')";
    $sql .= " AND (r.room_no like '%$SRoomNo%' OR '$SRoomNo' = '')";
    $sql .= " AND (r.size like '%$SSize%' OR '$SSize' = '')";
}

?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>
        $(document).ready(() => {
            $("#reserveList").dataTable();
        });

        let bookingDetail = {
            type: "",
            tower_no: "",
            room_no: ""
        }

        function initModalBooking(type, towerNo, roomNo) {
            bookingDetail = {
                type: type,
                tower_no: towerNo,
                room_no: roomNo
            }
            $.get('SQL_Select/selectRoomBookedList.php?type=' + type + "&tower_no=" + towerNo + "&room_no=" + roomNo, r => {
                let json = JSON.parse(r);
                let html = '';
                json.map((m, i) => {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + m.name + '</td>';
                    html += '<td>' + m.department + '</td>';
                    html += '<td>' + m.major + '</td>';
                    html += '<td>' + m.level + '</td>';
                    html += '<td>' + m.tel + '</td>';
                    html += '<td>' + m.full_status + '</td>';
                    html += '</tr>';
                })
                $("#book_list").html(html);
                $("#bookedDetailModal").modal();
                console.log(json);
            })
        }

        function insertBookingDetail() {
            if (confirm('ต้องการสมัครเข้าพัก ?')) {
                $.post('SQL_Insert/insertBookDetail.php', bookingDetail, r => {
                    if (r == 'true' || r == true) {
                        alert('จองหอสำเร็จ');
                        location.reload();
                    }
                });
            }
        }
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <form action="_reserve.php" method="get">
        <div class="card ">
            <div class="card-header bg-secondary text-white">
                <strong>รายละเอียดการค้นหา</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class=" col col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>ประเภทตึก</label>
                            <select class="custom-select" name="searchType" id="searchType" value="">
                                <option value="" <?php if ($Stype == '') echo 'selected'; ?> >-- เลือกประเภทหอ --
                                </option>
                                <option value="M" <?php if ($Stype == 'M') echo 'selected'; ?>>ชาย</option>
                                <option value="F" <?php if ($Stype == 'F') echo 'selected'; ?>>หญิง</option>
                            </select>
                        </div>
                    </div>

                    <div class=" col col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>หมายเลขตึก</label>
                            <input class="form-control" id="searchTowerNo" name="searchTowerNo" type="text"
                                   value="<?php echo $STowerNo; ?>">
                        </div>

                    </div>


                    <div class=" col col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>เลขที่ห้อง</label>
                            <input class="form-control" name="searchRoomNo" id="searchRoomNo"
                                   value="<?php echo $SRoomNo; ?>">
                        </div>
                    </div>

                    <div class=" col col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label>ขนาดห้อง</label>
                            <input class="form-control" type="number" name="searchRoomSize" id="searchRoomSize"
                                   value="<?php echo $SSize; ?>">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-outline-primary btn-sm" type="submit" name="searchKey"><i
                            class="fa fa-search"></i> ค้นหา
                </button>
                <a class="btn btn-outline-dark btn-sm" href="_reserve.php"><i class="fa fa-undo"></i>
                    คืนค่า</a>
            </div>
    </form>
</div>


<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card ">
        <div class="card-header bg-secondary text-white">
            <strong>รายการห้องที่เปิดให้จอง</strong>
        </div>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered rounded" id="reserveList">
                    <thead>
                    <tr class="text-center">
                        <th>วันที่เริ่มเปิดจอง</th>
                        <th>วันที่ปิดจอง</th>
                        <th>ประเภทหอ</th>
                        <th>หมายเลขตึก</th>
                        <th>เลขห้อง</th>
                        <th>ขนาดห้อง</th>
                        <th>สถานะห้อง</th>
                        <th>ราคาห้อง</th>
                        <th>จำนวนคนจอง</th>
                        <th>ดูรายละเอียด</th>
                        <th>สถานะหอ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $result = mysqli_query($conn, $sql);

                    while ($temp = mysqli_fetch_array($result)) {
                        ?>
                        <tr class="text-center">
                            <td><?php echo $temp['start_date'] ?></td>
                            <td><?php echo $temp['end_date'] ?></td>
                            <td><?php echo $temp['tower_type'] ?></td>
                            <td><?php echo $temp['tower_no'] ?></td>
                            <td><?php echo $temp['room_no'] ?></td>
                            <td><?php echo $temp['size'] ?></td>
                            <td><?php
                                if($temp['status']=='N'){
                                    echo 'ว่าง';
                                }else{
                                    echo 'เต็ม';
                                }
                                ?>

                            </td>
                            <td><?php echo $temp['cost'] ?></td>
                            <td><?php echo $temp['current_size'] ?>/<?php echo $temp['size'] ?></td>
                            <td><?php echo $temp['tower_status_txt'] ?></td>
                            <td>
                                <?php
                                if($temp['tower_status']=='Y'){
                                ?>
                                <button class="btn btn-outline-info btn-sm" onclick="initModalBooking(
                                        '<?php echo $temp['type'] ?>',
                                        '<?php echo $temp['tower_no'] ?>',
                                        '<?php echo $temp['room_no'] ?>'
                                        )">
                                    <i class="fa fa-list"></i> ดูรายละเอียด
                                </button>
                                <?php
                                }else{
                                    ?>
                                    <label class="badge badge-warning">ยังไม่เปิดให้จอง</label>
                                    <?php
                                }
                                ?>
                            </td>

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

</body>
</html>


<div class="modal fade " id="bookedDetailModal" tabindex="-1" role="dialog" aria-labelledby="bookedDetailModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="bookedDetailModalLabel"><i class="fa fa-users"></i> แจ้งปัญหา/ข้อขัดข้อง
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>คนที่</th>
                            <th>ชื่อ</th>
                            <th>คณะ</th>
                            <th>สาขา</th>
                            <th>ชั้นปี</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>สถานะ</th>
                        </tr>
                        </thead>
                        <tbody id="book_list">

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> ปิด
                </button>

                <?php
                if ($_SESSION['userType'] == 'N') {
                    if ($_SESSION['book_status'] == 'N') {
                        ?>
                        <button type="button" class="btn btn-primary" onclick="insertBookingDetail()"><i
                                    class="fa fa-check"></i>
                            สมัครห้อง
                        </button>
                        <?php
                    } else {
                        ?>
                        <button type="button" class="btn btn-outline-warning" disabled><i
                                    class="fa fa-times"></i>
                            คุณได้สมัครไว้แล้ว
                        </button>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>