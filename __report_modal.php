<script>
    let reportDetail = {
        type:"",
        room:"",
        tower:"",
        report_tel:"",
        report_content:"",
    }
    function initReportModal(){

        $("#report_tel").mask("999-9999999");
    }
    function insertReportProbelm(){
       let valid = true;
        for(let key in reportDetail){
            reportDetail[key] = $("#"+key).val();
            if( reportDetail[key] == ''){
                valid = false;
            }
       }

        if(!valid){
            alert('กรุณากรอกรายละเอียดให้ครบ!');
        }else{
            $.post('SQL_Insert/insertReport.php',reportDetail,r=>{
                console.log(r);
                if(r=='true'){
                    console.log(r);
                    alert('บันทึกการแจ้งเตือนสำเร็จ!');
                }else{
                    alert("ไม่สำเร็จ! มีบางอย่างผิพลาด กรุณาตรวจสอบอีกครั้ง")
                }
            });
            $("#reportContent").val('');
            location.reload();
        }
    }
</script>
<?php
$sqlReportDetail = "SELECT b.*,s.full_status as tower_type ,t.tower_name
from booking_detail b 
inner join status s on s.status = b.type 
inner join tower t on t.tower_no = b.tower_no
where b.student_code = '".$_SESSION['code']."'  ";
$resultReportDetail  = mysqli_query($conn,$sqlReportDetail);
$reportDetail = mysqli_fetch_assoc($resultReportDetail );
?>
<form class="simple-form">
<div class="modal fade " id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel"><i class="fa fa-users"></i> แจ้งปัญหา/ข้อขัดข้อง</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col col-sm-3 col-md-3 col-xs-3 col-lg-3">

                        <div class="form-group">
                            <label><strong>ตึก</strong></label>
                            <input class="form-control"  id="type" name="type" value="<?php echo $reportDetail['tower_type'];?>" readonly>
                        </div>
                    </div>
                    <div class="col col-sm-3 col-md-3 col-xs-3 col-lg-3">

                        <div class="form-group">
                            <label><strong>เลขตึก</strong></label>
                            <input class="form-control" type="text" value="<?php echo $reportDetail['tower_no'].' (หอพัก '.$reportDetail['tower_name'].')';?>" readonly>
                            <input class="form-control" type="hidden" id="tower" name="tower" value="<?php echo $reportDetail['tower_no'];?>" >
                        </div>
                    </div>
                    <div class="col col-sm-3 col-md-3 col-xs-3 col-lg-3">

                        <div class="form-group">
                            <label><strong>เลขห้อง</strong></label>
                            <input class="form-control" id="room" type="text" name="room" value="<?php echo $reportDetail['room_no'];?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-sm-9 col-md-9 col-sm-9 col-lg-9">

                        <div class="form-group">
                            <label><strong>เบอร์ติดต่อ</strong></label>
                            <input class="form-control" id="report_tel" type="text" name="report_tel" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>รายละเอียด</strong></label>
                    <textarea name="report_content" id="report_content" class="form-control" required></textarea>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> ปิด
                </button>

                <button type="button" class="btn btn-primary" onclick="insertReportProbelm()"><i
                        class="fa fa-check"></i>
                    แจ้งปัญหา
                </button>

            </div>
        </div>
    </div>
</div></form>