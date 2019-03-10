<?php

if($_SESSION['userType'] != 'A'){
    header("Location: _home.php");
}
//STR_TO_DATE('$install_date','%d/%c/%Y')'$start_date'
if (isset($_POST['editData'])) {
    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];
    $tower_no = $_POST['towerNo'];
    $tower_type = $_POST['towerType'];
    $_start_date = $_POST['_startDate'];
    $_end_date = $_POST['_endDate'];
    $_semester = $_POST['_semester'];
    $_year = $_POST['_year'];
    $_tower_no = $_POST['_towerNo'];
    $_tower_type = $_POST['_towerType'];
    $sql = "update booking set start_date = DATE_FORMAT('$start_date','%Y-%m-%d'),
                                   end_date = DATE_FORMAT('$end_date','%Y-%m-%d'),
                                   semester = $semester,
                                   year = $year,
                                   tower_no = '$tower_no',
                                   tower_type   = '$tower_type' where start_date = DATE_FORMAT('$_start_date','%Y-%m-%d')
                                                                  and end_date = DATE_FORMAT('$_end_date','%Y-%m-%d')
                                                                  and semester = $_semester
                                                                  and year = $_year
                                                                  and tower_no = '$_tower_no'
                                                                  and tower_type = '$_tower_type'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> แก้ไขข้อมูลการเปิดจองแล้ว
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>แก้ไขข้อมูลไม่สำเร็จ!</strong>
                    </div>';
    }
}

?>
<script>

    function openModal(startDate, endDate, semester, towerNo, year, towerType) {
        openBookingInfo = {
            startDate: startDate,
            endDate: endDate,
            semester: semester,
            towerNo: Number(towerNo),
            year: year,
            towerType: towerType
        }
        for (let key in openBookingInfo) {
            $("#" + key).val(openBookingInfo[key]);
            $("#_" + key).val(openBookingInfo[key]);
        }
        setTowerList();
        console.log(openBookingInfo);
        $("#editBookingModal").modal();
    }

    function deleteBooking(startDate, endDate, semester, towerNo, year, towerType) {
        let obj = {
            startDate: startDate,
            endDate: endDate,
            semester: semester,
            towerNo: Number(towerNo),
            year: year,
            towerType: towerType
        }
        if (confirm('ต้องการลบรายการหรือไม่ ?')) {
            $.post('SQL_Delete/deleteBooking.php', obj, r => {
                if (r) {
                    alert('ลบรายการสำเร็จ');
                }
                location.reload()
            })
        }
    }
</script>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">
                <h5><i class="fa fa-star"></i> จัดการการจอง</h5>
            </nav>
        </div>
        <div class="card-body">
            <div class="container">

                <div class="table-responsive">
                <table class="table table-bordered rounded text-center" id="bookingTable">
                    <thead class="bg-warning">
                    <tr>
                        <th>ลำดับ</th>
                        <th>หอพัก</th>
                        <th>หมายเลข</th>
                        <th>ชื่อหอพัก</th>
                        <th>เวลาเปิดจอง</th>
                        <th>เวลาปิดจอง</th>
                        <th>ปีการศึกษา</th>
                        <th>เทอม</th>
                        <th>จัดการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    $sql = "SELECT b.*,s.full_semester,t.full_status,w.tower_name FROM BOOKING b 
                            INNER JOIN tower w ON w.tower_no = b.tower_no and w.type = b.tower_type
                            INNER join semeter_detail s ON b.semester = s.semester INNER join status t on t.status = b.tower_type";
                    $result = mysqli_query($conn, $sql);
                    while ($temp = mysqli_fetch_array($result)) {
                        $count++;
                        ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo $temp['full_status'] ?></td>
                            <td><?php echo $temp['tower_no']?></td>
                            <td><?php echo $temp['tower_name'] ?></td>
                            <td><?php echo $temp['start_date'] ?></td>
                            <td><?php echo $temp['end_date'] ?></td>
                            <td><?php echo $temp['full_semester'] ?></td>
                            <td><?php echo $temp['year'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info"
                                        onclick="openModal(
                                                '<?php echo $temp['start_date']; ?>',
                                                '<?php echo $temp['end_date']; ?>',
                                                '<?php echo $temp['semester']; ?>',
                                                '<?php echo $temp['tower_no']; ?>',
                                                '<?php echo $temp['year']; ?>',
                                                '<?php echo $temp['tower_type']; ?>')"><i class="fa fa-edit"></i> แก้ไข
                                </button>
                                <button class="btn btn-sm btn-outline-warning"
                                        onclick="deleteBooking('<?php echo $temp['start_date']; ?>',
                                                '<?php echo $temp['end_date']; ?>',
                                                '<?php echo $temp['semester']; ?>',
                                                '<?php echo $temp['tower_no']; ?>',
                                                '<?php echo $temp['year']; ?>',
                                                '<?php echo $temp['tower_type']; ?>')"><i class="fa fa-trash"></i> ลบ
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade " id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel"><i class="fa fa-users"></i> แก้ไขข้อมูลการจอง
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container">
                        <div class="row">

                            <div class="col col-sm col-md col-lg">
                                <div class="form-group">
                                    <label class="font-weight-bold">วันที่เริ่มจอง</label>
                                    <input id="startDate" class="form-control" name="startDate" required readonly>
                                    <input id="_startDate" type="hidden" class="form-control" name="_startDate" required
                                           readonly>
                                </div>
                            </div>

                            <div class="col col-sm col-md col-lg">
                                <div class="form-group">
                                    <label class="font-weight-bold">วันที่สิ้นสุดการจอง</label>
                                    <input id="endDate" class="form-control" name="endDate"
                                           onchange="validateDateBooking()"
                                           required readonly>
                                    <input id="_endDate" class="form-control" name="_endDate"
                                           type="hidden"
                                           required readonly>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col col-sm col-md col-lg">
                                <div class="form-group">
                                    <label class="font-weight-bold">ปีการศึกษา</label>
                                    <select class="form-control" id="year" name="year" required>
                                    </select>
                                    <input class="form-control" type="hidden" id="_year" name="_year" required>
                                </div>
                            </div>
                            <div class="col col-sm col-md col-lg">
                                <div class="form-group">
                                    <input type="hidden" id="_semester" name="_semester" required>
                                    <label class="font-weight-bold">เทอม</label>
                                    <select class="form-control" id="semester" name="semester" required>
                                        <option value="1">ภาคต้น</option>
                                        <option value="2">ภาคปลาย</option>
                                        <option value="3">ฤดูร้อน</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">ประเภทหอพัก</label>
                                    <input type="hidden" id="_towerType" name="_towerType">
                                    <select class="form-control" id="towerType" name="towerType"
                                            onchange="setTowerList()"
                                            required>
                                        <option value="M"> หอชาย</option>
                                        <option value="F"> หอหญิง</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">หอที่เปิด</label>
                                    <input type="hidden" id="_towerNo" name="_towerNo">
                                    <select class="form-control" id="towerNo" name="towerNo"
                                            onchange=" validateButton()"
                                            required>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> ปิด
                    </button>
                    <button type="submit" class="btn btn-primary" name="editData"><i
                                class="fa fa-save"></i>
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>