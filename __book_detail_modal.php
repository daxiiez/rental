<?php
$sqlBookingDeatil = "SELECT b.*,type.full_status as tower_type,t.tower_name,r.size,r.cost,s.full_status as payment_status FROM booking_detail b 
INNER JOIN STATUS type On type.status = b.type
INNER JOIN STATUS s ON s.status = b.status
INNER JOIN ROOM r ON r.room_no = b.room_no
INNER JOIN TOWER t ON t.tower_no = t.tower_no
WHERE student_code='".$_SESSION['code']."'";
$queryBookDetail = mysqli_query($conn,$sqlBookingDeatil);
$bookDetail = mysqli_fetch_assoc($queryBookDetail);

$c_date = date_create(date("Y-m-d"));
$r_date = date_create($bookDetail['book_date']);
$diff = date_diff($c_date, $r_date);
$day = $diff->format('%r%d');
$remainDay = 7 +$day;
?>
<script>

    function cancelReserveModal(code){
        alert()
        if(confirm('รายการนี้จะถูกยกเลิก กรุณายืนยัน')) {
            $.post('SQL_Delete/deleteReserve.php', {code: code}, r => {
                if (r == true || r == 'true') {
                    alert('ยกเลิกสำเร็จแล้ว !')
                    location.reload()
                }
            })
        }
    }
</script>
<form class="simple-form">
<div class="modal fade " id="bookDetailModal" tabindex="-1" role="dialog" aria-labelledby="bookDetailLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="bookDetailLabel"><i class="fa fa-print"></i> รายละเอียดการจอง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="container">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th class="bg-success text-center text-white" colspan="2">รายละเอียดการจอง</th>
                            </tr>
                            </thead>
                            <tr>
                                <th class="bg-success text-right text-white">ประเภท</th>
                                <td><?php echo $bookDetail['tower_type'] ?></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right text-white">เลขตึก</th>
                                <td><?php echo $bookDetail['tower_no'].' (หอพัก'.$bookDetail['tower_name'].')' ?></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right text-white">เลขห้อง</th>
                                <td><?php echo $bookDetail['room_no']. ' (ขนาด '.$bookDetail['size'].' คน)' ?></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right text-white">ราคา</th>
                                <td><?php echo $bookDetail['cost'].' ('.$bookDetail['cost']/$bookDetail['size'].' บาท/คน)' ?></td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right text-white">เหลือเวลาชำระค่าธรรมเนียม</th>
                                <?php
                                    if($bookDetail['status']=='Y'){
                                    ?>
                                        <td>
                                            <span class="badge badge-success">ชำระเงินเสร็จสิ้นแล้ว</span>
                                        </td>
                                        <?php
                                    }else{
                                ?>
                                <td>
                                    <?php echo $remainDay ?> วัน <small class="text-danger text-right"><i class="fa fa-exclamation-triangle"></i> กรุณาชำระเงินที่กองกิจการภายใน 7 วัน</small>
                                </td>
                                <?php
                                }
                                ?>
                            </tr>
                        </table>
                          </div>
                </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php
                if(($bookDetail['status']!='Y')){ ?>
                    <button  class="btn btn-danger" type="button"
                             onclick="cancelReserveModal('<?php echo $_SESSION['code']; ?>')" ><i
                                class="fa fa-times"></i>
                        ยกเลิกการจอง
                    </button>

                    <a  class="btn btn-primary" target="_blank" href="__PDF_booking_cost.php?code=<?php echo $_SESSION['code'];?>"><i
                                class="fa fa-print"></i>
                        พิมพ์ใบชำระเงิน
                    </a>
                    <?php
                    }
                ?>

            </div>
        </div>
    </div>
</div></form>