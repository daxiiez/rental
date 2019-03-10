<?php
include '__connect.php';
include '__checkSession.php';

if($_SESSION['userType'] != 'A'){
    header("Location: _home.php");
}
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
</head>
<body>
<?php
include '__navbar_admin.php';
?>

<?php
$sqlManageReserve = "select distinct d.*,
                s.name,
                s.department,
                s.major,
                s.level,
                s.tel,
                t.full_status as tower_type,
                p.full_status as payment_status,
                p.status as p_status
from booking_detail d
       inner join student s on s.code = d.student_code
       inner join status t on t.status = d.type
       inner join status p on p.status = d.status";
$resultReserve = mysqli_query($conn, $sqlManageReserve);

?>
<script>
    $(document).ready(()=>{
        $("#reserveListTable").dataTable();
    });

    function paymentSuccess(code){
        if(confirm('รายการนี้จะถูกปรับปรุงเป็นชำระแล้ว กรุณายืนยัน')){
            $.post('SQL_Update/updatePaymentReserve.php',{code:code,status:'Y'},r=>{
                if(r==true || r=='true'){
                    alert('ชำระสำเร็จแล้ว !')
                    location.reload()
                }
            });
        }
    }
    function cancelReserve(code){
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
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">
                <h5><i class="fa fa-bookmark"></i> จัดการการจอง</h5>
            </nav>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover" id="reserveListTable">
                    <thead class="bg-success text-white text-center">
                    <tr >
                        <th>รหัสนิสิต</th>
                        <th>ชื่อ</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ตึก</th>
                        <th>เลขตึก</th>
                        <th>เลขห้อง</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($temp = mysqli_fetch_array($resultReserve)) {
                        ?>
                        <tr>
                            <td><?php echo $temp['student_code']?></td>
                            <td><?php echo $temp['name']?></td>
                            <td><?php echo $temp['tel']?></td>
                            <td><?php echo $temp['tower_type']?></td>
                            <td><?php echo $temp['tower_no']?></td>
                            <td><?php echo $temp['room_no']?></td>
                            <td><?php echo $temp['payment_status']?></td>
                            <td align="center">
                                <?php
                                if($temp['p_status']=='Y'){
                                    ?>
                                    <button type="button" class="btn btn-sm btn-success" disabled><i
                                                class="fa fa-check" ></i> ชำระเงินเสร็จสิ้นแล้ว
                                    </button>
                                    <?php
                                }else{
                                    ?>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="paymentSuccess('<?php echo $temp["student_code"]; ?>')"><i
                                                    class="fa fa-bitcoin"></i> ชำระเงิน
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="cancelReserve('<?php echo $temp["student_code"]; ?>')"><i
                                                    class="fa fa-times"></i> ยกเลิกการจอง
                                        </button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer bg-warning text-white">

        </div>
    </div>
</div>
</body>
</html>
