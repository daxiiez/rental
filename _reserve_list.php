<?php
include '__connect.php';
include '__checkSession.php';
$type = 'W';

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}
$sql = "select r.rental_id,
r.room_id,
u.name,
r.status,
DATE_FORMAT(rent_date,'%d/%m/%Y') as rent_date,
DATE_FORMAT(check_in,'%d/%m/%Y') as check_in,
DATE_FORMAT(check_out,'%d/%m/%Y') as check_out,
r.deposit,
to_base64(r.pay_img) as pay_img,
r.rent_cost
 from rental_detail r inner join user u on u.username = r.username where 1=1 ";
if ($type != 'T') {
    $sql .= " and status = '$type'";
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
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php if ($type == 'W') echo 'active' ?>" href="_reserve_list.php?type=W">รายการรอการตรวจสอบ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($type == 'S') echo 'active' ?>" href="_reserve_list.php?type=S">รายการจองพร้อมเข้าพัก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($type == 'C') echo 'active' ?>" href="_reserve_list.php?type=C">รายการถูกยกเลิก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($type == 'T') echo 'active' ?>" href="_reserve_list.php?type=T">รายการทั้งหมด</a>
                </li>
            </ul>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered rounded ">
                    <thead class="bg-info text-white">
                    <tr>
                        <th>เลขที่การจอง</th>
                        <th>วันที่ทำรายการ</th>
                        <th>เลขที่ห้องพัก</th>
                        <th>ผู้จอง</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>ราคาทั้งหมด/ค้างชำระ(บาท)</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = mysqli_query($conn, $sql);
                    while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        ?>
                        <tr>
                            <td><?php echo $temp['rental_id']; ?></td>
                            <td><?php echo $temp['rent_date']; ?></td>
                            <td><?php echo $temp['room_id']; ?></td>
                            <td><?php echo $temp['name']; ?></td>
                            <td><?php echo $temp['check_in']; ?></td>
                            <td><?php echo $temp['check_out']; ?></td>
                            <td class="text-center"><?php echo $temp['rent_cost'] . '/' . ($temp['rent_cost'] - $temp['deposit']); ?></td>
                            <td><?php
                                if ($temp['status'] == 'N') {
                                    echo '<span class="badge badge-danger">ยังไม่ได้อัพโหลดหลักฐาน</span>';
                                } elseif ($temp['status'] == 'Y') {
                                    echo '<span class="badge badge-primary">อัพโหลดหลักฐานเรียบร้อยแล้ว</span>';
                                } elseif ($temp['status'] == 'W') {
                                    echo '<span class="badge badge-warning">รอพนักงานตรวจสอบหลักฐาน</span>';
                                    ?>
                                    <input type="hidden" id="<?php echo $temp['rental_id']; ?>"
                                           value="<?php echo $temp['pay_img']; ?>">
                                    <button onclick="showImage('<?php echo $temp['rental_id']; ?>')" class="btn btn-sm">
                                        ตรวจสอบ
                                    </button>
                                    <?php
                                } elseif ($temp['status'] == 'S') {
                                    echo '<span class="badge badge-success">พร้อมให้เข้าพัก</span>';
                                } elseif ($temp['status'] == 'C') {
                                    echo '<span class="badge badge-secondary">ถูกยกเลิกแล้ว</span>';}

                                ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <?php

                                    if ($temp['status'] == 'Y') { ?>

                                        <?php
                                    }
                                    if ($temp['status'] == 'W') { ?>
                                        <button class="btn btn-outline-primary btn-sm"
                                                onclick="setStatus('<?php echo $temp['rental_id'] ?>','S')">
                                            ยืนยันการตรวจสอบ
                                        </button>
                                        <?php
                                    }
                                    if ($temp['status'] == 'S') { ?>

                                        <?php
                                    }
                                    if ($temp['status'] == 'N' || $temp['status'] == 'W') { ?>
                                        <button class="btn btn-outline-danger btn-sm"
                                                onclick="setStatus('<?php echo $temp['rental_id'] ?>','C')">ยกเลิก</button>
                                        <?php
                                    }
                                    ?>
                                </div>

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
<script>
    function setStatus(id, status) {
        let saveObj = {
            rent_id: id,
            status: status
        }
        if (confirm("ต้องการแก้ไขสถานะหรือไม่ ?")) {
            $.post('SQL_Update/updateRentStatus.php', saveObj, (r) => {
                if (r) {
                    console.log(r);
                    alert("แก้ไขสถานะเรียบร้อยแล้ว")
                    location.reload();
                }
            })
        }
    }

    function showImage(img) {
        img = $("#" + img).val();
        let htmlTxt = "<img width='70%' src='data:image/jpeg;base64," + img + "'>";
        $("#imgPayImg").html(htmlTxt);
        $("#imgPayImgModal").modal('show');
    }
</script>
<div class="modal fade" tabindex="-1" role="dialog" id="imgPayImgModal"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">หลักฐานการชำระเงิน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="imgPayImg" align="center">
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
</html>
