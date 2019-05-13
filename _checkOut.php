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
if(isset($_GET['roomIdSearch'])){
    if($_GET['roomIdSearch']!=''){
        $roomIdSearch = $_GET['roomIdSearch'];
        echo $roomIdSearch." room";
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
                         id="collapseNews"><strong><i class="fa fa-list"></i> รายการที่ Check In เข้ามาแล้ว</strong></div>
                    <div class="card-body" id="collapseInfo">
                        <form>
                            <div class="row">
                                <div class="col-4">
                                    <div class="input-group">
                                        <label class="font-weight-bold">ค้นหาห้องพัก : </label>
                                        <input type="text" class="form-control" placeholder="" name="roomIdSearch" aria-label="" aria-describedby="basic-addon1">
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
                                    <label class="col-form-label">เวลา Check Out</label>
                                    <input class="form-control" name="timer" id="timer" value="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <?php
                        if($roomIdSearch!=''){
                            $sql = "select * from rental_detail where (status='I') and room_id like '%$roomIdSearch%' order by check_out ";
                        }else{
                            $sql = "select * from rental_detail where (status='I') order by check_out ";
                        }
                        $list = mysqli_query($conn, $sql);
                        $count=0;
                        while ($temp = mysqli_fetch_array($list)) {
                            $count++;
                            $usernameRental = $temp['username'];
                            $sqlAccount = "select * from user where username='$usernameRental'";
                            $queryAccount = mysqli_query($conn, $sqlAccount);
                            $profile = mysqli_fetch_assoc($queryAccount);
                            $css = 'success';
                            $txt = '';
                            $status = '';
                            $cssO = 'success';
                            $txtO = '';
                            $statusO = '';
                            $currentDate = date("d-m-Y");

                            $check_in = date_format(date_create($temp['check_in']), 'd-m-Y');
                            $check_out = date_format(date_create($temp['check_out']), 'd-m-Y');
                            if ($temp['check_in'] == $currentDate) {
                                $status = 'Y';
                                $txt = 'วันนี้';
                                $css = 'warning';
                            } else {
                                $css = 'secondary';
                                $status = 'N';
                                $txt = $check_in;
                            }
                            if ($check_out == $currentDate) {
                                $statusO = 'Y';
                                $txtO = 'Check Out วันนี้';
                                $cssO = 'warning';
                            } else {
                                $cssO = 'secondary';
                                $statusO = 'N';
                                $txtO = 'Check Out วันที่ ' . $check_out;
                            }
                            ?>
                            <div class="alert alert-<?php echo $cssO; ?>" role="alert">
                                <div class="row">
                                    <div class="col-3">
                                        <strong>ลำดับการจองที่ <?php
                                            echo $temp['rental_id'];
                                            ?></strong><br> <strong>ห้องที่จอง </strong>
                                        <h3 class="badge badge-success"><?php
                                            echo $temp['room_id'];
                                            ?></h3><br> <span class="font-weight-bold ">วันที่ Check In</span><label
                                                class="badge badge-secondary"><?php echo $txt; ?> </label> <span class="font-weight-bold">เวลา</span> <label
                                                class="badge badge-secondary"> <?php echo $temp['check_in_time']; ?>  </label>

                                        <br>
                                        <span class="font-weight-bold ">กำหนด Check Out :</span> <label
                                                class="badge badge-secondary"><?php echo $txtO; ?></label>
                                        <br>
                                        <span><strong>จำนวนวันที่เข้าพัก : </strong><?php echo $temp['rent_days']." วัน ".($temp['rent_days']-1)." คืน"; ?></span>
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
                                                let txt = status == 'O' ? "Check Out" : "Check In";
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
                                                <button class="btn btn-primary"
                                                        onclick="setStatus('<?php echo $temp['rental_id'] ?>','O')">
                                                    Check Out
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if($count==0 && $roomIdSearch!=''){
                            ?>
                            <span class="text-center">*ไม่พบข้อมูลของห้องพักหมายเลข <?php echo $roomIdSearch?></span>
                        <?php

                        }else if($count==0){
                            ?>

                            <span class="text-center">*ไม่ห้องพักใดให้ Check Out</span>

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