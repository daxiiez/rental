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
$roomIdSearch = '';
if (isset($_GET['roomIdSearch'])) {
    if ($_GET['roomIdSearch'] != '') {
        $roomIdSearch = $_GET['roomIdSearch'];
        echo $roomIdSearch . " room";
    }
}
?>
<script>
    $(document).ready(() => {
        setInterval(() => {
            let h = (new Date().getHours()) + '';
            let m = (new Date().getMinutes()) + '';
            let s = (new Date().getSeconds()) + '';
            let time = h.padStart(2, '0') + ":" + m.padStart(2, '0') + ":" + s.padStart(2, '0');
            $("#timer").val(time);
        }, 1000)

    })
</script>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="row">
            <div class=" col col-sm col-md col-lg">
                <div class="card">
                    <div class="card-header bg-info text-white"
                         id="collapseNews"><strong><i class="fa fa-list"></i> รายการจอง ที่พร้อมเข้า Check In</strong>
                    </div>
                    <div class="card-body" id="collapseInfo">
                        <form>
                            <div class="row">
                                <div class="col-4">
                                    <div class="input-group">
                                        <label class="font-weight-bold">ค้นหาห้องพัก : </label>
                                        <input type="text" class="form-control" placeholder="" name="roomIdSearch"
                                               aria-label="" aria-describedby="basic-addon1">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="submit" name="search">ค้นหา</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="col-form-label">เวลา Check In</label>
                                    <input class="form-control" name="timer" id="timer" value="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?php
                        if ($roomIdSearch != '') {
                            $sql = "select * from rental_detail where (status='S') and room_id like '%$roomIdSearch%' order by check_out ";
                        } else {
                            $sql = "select * from rental_detail where (status='S') order by check_out ";
                        }
                        $list = mysqli_query($conn, $sql);
                        $count = 0;
                        while ($temp = mysqli_fetch_array($list)) {
                            $count++;
                            $usernameRental = $temp['username'];
                            $sqlAccount = "select * from user where username='$usernameRental'";
                            $queryAccount = mysqli_query($conn, $sqlAccount);
                            $profile = mysqli_fetch_assoc($queryAccount);
                            $css = 'success';
                            $txt = '';
                            $status = '';
                            $currentDate = date("d-m-Y");

                            $check_in = date_format(date_create($temp['check_in']), 'd-m-Y');
                            $check_out = date_format(date_create($temp['check_out']), 'd-m-Y');
                            if ($check_in == $currentDate) {
                                $status = 'Y';
                                $txt = 'Check In วันนี้';
                                $css = 'warning';
                            } else {
                                $css = 'secondary';
                                $status = 'N';
                                $txt = 'Check In วันที่ ' . $check_in;
                            }
                            ?>
                            <div class="alert alert-<?php echo $css; ?>" role="alert">
                                <div class="row">
                                    <div class="col-3">
                                        <strong>ลำดับการจองที่ <?php
                                            echo $temp['rental_id'];
                                            ?></strong><br> <strong>ห้องที่จอง </strong>
                                        <h3 class="badge badge-success"><?php
                                            echo $temp['room_id'];
                                            ?></h3><br> <span class="font-weight-bold ">สถานะ</span> <label
                                                class="badge badge-secondary"><?php echo $txt; ?> </label><b>เวลา : </b> <?php echo $temp['check_in_time']; ?>
                                        <br><strong>Check Out : </strong><?php echo $check_out; ?> <b>เวลา : </b> <?php echo $temp['check_out_time']; ?>
                                        (<?php echo $temp['rent_days']." วัน ".($temp['rent_days']-1)." คืน"; ?> )<br>
                                        <strong>การชำระเงิน : </strong>
                                        <?php
                                        if ($temp['rent_cost'] - $temp['deposit'] != 0) {
                                            ?>
                                            <span class="badge badge-danger">กรุณาชำระเงินที่เหลือ <?php echo $temp['rent_cost'] - $temp['deposit'] ?> บาท
                                       </span>
                                            <br>
                                            <a href="_reserve_payment.php?rental_id=<?php echo $temp['rental_id'] ?>"
                                               class="badge badge-warning">ชำระเงิน</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="_reserve.php?rental_id=<?php echo $temp['rental_id'] ?>"><span
                                                        class="badge btn-success">ชำระเงินเรียบร้อยแล้ว <i
                                                            class="fa fa-check"></i></span></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-3">
                                        <strong>ผู้เช่า </strong><?php
                                        echo $profile['title'] . " " . $profile['name'];
                                        ?>
                                        <br>
                                        <strong>Tel : </strong><?php echo $profile['tel']; ?>
                                        <strong> Line : </strong><?php echo $profile['line_id']; ?>

                                    </div>
                                    <div class="col-6">
                                        <script>

                                            function setStatus(id, status) {
                                                let saveObj = {
                                                    rent_id: id,
                                                    status: status
                                                }
                                                let txt = status == 'C' ? "ยกเลิก" : "Check In";
                                                if (confirm("ต้องการ" + txt + "ห้องพักนี้หรือไม่ ?")) {
                                                    $.post('SQL_Update/updateRentStatus.php', saveObj, (r) => {
                                                        if (r) {
                                                            console.log(r);
                                                            alert("แก้ไขสถานะเรียบร้อยแล้ว")
                                                            location.reload();
                                                        }
                                                    })
                                                }
                                            }

                                        </script>
                                        <div align="right">
                                            <div class="btn btn-group">
                                                <?php
                                                if ($status == 'Y') {
                                                    ?>
                                                    <button class="btn btn-primary"
                                                            onclick="setStatus('<?php echo $temp['rental_id'] ?>','I')">
                                                        Check In
                                                    </button>
                                                    <?php
                                                }
                                                ?>

                                                <button class="btn btn-danger"
                                                        onclick="setStatus('<?php echo $temp['rental_id'] ?>','C')">
                                                    ยกเลิกการเข้าพัก
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if ($count == 0 && $roomIdSearch != '') {
                            ?>
                            <span class="text-center">*ไม่พบข้อมูลของห้องพักหมายเลข <?php echo $roomIdSearch ?></span>
                            <?php
                        } elseif ($count == 0) {
                            ?>
                            <span class="text-center">*ไม่ห้องพักที่พร้อมให้ Check In</span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>