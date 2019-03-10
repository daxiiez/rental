<script>


    let updateProfileDetail = {
        isPasswordChange: false,
        name: "",
        username: "",
        tel: "",
        address: "",
        password: "",
        newPassword: "",
        isValidPassword: false,
        isValidNewPassword: false
    }

    function initProfileModal() {
        validDatePassword()
        updateProfileDetail.isPasswordChange = false;
        $("#saveProfile").hide();
        $("#passwordField").hide();
        $("#saveButton").attr('disabled', 'disabled');
        $("#passwordChangeStatus").html('<span class="text-warning"><i class="fa fa-exclamation-triangle"></i> กรุณาระบุรหัสผ่านก่อนแก้ไขข้อมูล</span>');
    }

    function saveProfile(){
        let valid = true;
        if(updateProfileDetail.isPasswordChange){
            if(updateProfileDetail.isValidNewPassword){
                valid = false;
            }
        }
        updateProfileDetail.name = $("#updateName").val();
        updateProfileDetail.username = $("#updateUsername").val();
        updateProfileDetail.tel = $("#updateTel").val();
        updateProfileDetail.address = $("#updateAddress").val();
        updateProfileDetail.newPassword = $("#updatePassword").val();
        $.post('SQL_Update/updateProfile.php',updateProfileDetail,r=>{
                if(r){
                    alert('แก้ไขข้อมูลเรียบร้อยแล้ว');
                    location.reload()
                }
        });
    }

    function validDateButton() {
        if (updateProfileDetail.isValidPassword) {
            $("#saveButton").removeAttr('disabled');
        } else {
            $("#saveButton").attr('disabled', 'disabled');
        }
    }

    function validateNewPassword() {
        let valid = true;
        let newPass = $("#updatePassword").val();
        let reNewPass = $("#updateRePassword").val();
        if (updateProfileDetail.isPasswordChange) {
            if (!isPattern(newPass)) {
                alert("กรุณากรอกรหัสผ่านให้ถูกต้องตามกำหนด");
                $("#updatePassword").val('');
            }
            valid = (newPass == reNewPass);
            if(newPass=='' || reNewPass ==''){
                valid = false;
            }
            let html='';
            if(valid){
                html = '<span class="text-success"><i class="fa fa-check"></i> รหัสผ่านใหม่ถูกต้อง</span>'
            }else{
                html ='<span class="text-danger"><i class="fa fa-times"></i> รหัสผ่านใหม่ไม่ตรงกัน/ต้องไม่เป็นค่าว่าง</span>'
            }
            updateProfileDetail.isValidNewPassword = valid;
            $("#passwordChangeStatus").html(html);
        }

    }

    function isPattern(value) {
        return !/[\@\#\$\%\^\&\*\(\)\_\+\!]/.test(value) && /[a-z]/.test(value) && /[0-9]/.test(value) && /[A-Z]/.test(value);
    }

    function validDatePassword() {
        let detail = {
            username: $("#updateUsername").val(),
            password: $("#currentPassword").val()
        }
        $.post('SQL_Select/checkPassword.php', detail, r => {
            console.log(r);
            updateProfileDetail.isValidPassword = (r == 'true');
            if (updateProfileDetail.isValidPassword) {
                html = '<span class="text-success"><i class="fa fa-check"></i> รหัสผ่านถูกต้อง</span>'
            } else {
                html = '<span class="text-danger"><i class="fa fa-times"></i> รหัสผ่านไม่ถูกต้อง</span>'
            }
            validDateButton();
            $("#passwordStatus").html(html);
        });
    }

    function setUpdatePassword() {
        updateProfileDetail.isPasswordChange = $("#passwordFlag")[0].checked;
        if (updateProfileDetail.isPasswordChange) {
            console.log(updateProfileDetail.isPasswordChange);
            $("#passwordField").show();
        } else {

            console.log(updateProfileDetail.isPasswordChange);
            $("#passwordField").hide();
        }
    }
</script>

<div class="modal fade " id="profileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users"></i> ข้อมูลผู้ใช้ระบบ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>ชื่อผุ้ใช้</label>
                                <input name="updateUsername" id="updateUsername" class="form-control"
                                       value="<?php echo $_SESSION['username']; ?>" disabled>
                            </div>
                        </div>
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>ชื่อจริง-นามสกุล</label>
                                <input name="updateName" id="updateName" class="form-control"
                                       value="<?php echo $_SESSION['name']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>ตำแหน่ง</label>
                                <input name="updateType" id="updateType" class="form-control"
                                       value="<?php echo $_SESSION['full_position']; ?>" disabled>
                            </div>
                        </div>
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>เบอร์โทรศัพท์</label>
                                <input name="updateTel"
                                       maxlength="20" id="updateTel" class="form-control"
                                       value="<?php echo $_SESSION['tel']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <textarea name="updateAddress"
                                          id="updateAddress"
                                          class="form-control"
                                          ><?php echo $_SESSION['address']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col col-sm col-md col-lg">
                            <div
                                    class="custom-control custom-checkbox" style="margin-top: 5px;">
                                <input type="checkbox" class="custom-control-input" id="passwordFlag"
                                       onclick="setUpdatePassword();">
                                <label class="custom-control-label" for="passwordFlag"> แก้ไขรหัสผ่าน</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="passwordField">
                        <hr>
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>รหัสผ่านใหม่</label>
                                <input name="updatePassword"
                                       id="updatePassword"
                                       class="form-control"
                                       onchange="validateNewPassword()"
                                       type="password"
                                       value="">
                            </div>
                        </div>

                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>คอนเฟิร์มรหัสผ่านใหม่</label>
                                <input name="updateRePassword"
                                       id="updateRePassword"
                                       type="password"
                                       class="form-control"
                                       onchange="validateNewPassword()"
                                       value="">
                            </div>
                            <span id="passwordChangeStatus"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-4 col-lg-4 col-md-4">
                            <div class="form-group">
                                <label>กรุณากรอกรหัสผ่านก่อนบันทึก</label>
                                <input type="password"
                                       class="form-control"
                                       name="currentPassword"
                                       id="currentPassword"
                                       onchange="validDatePassword()">
                                <span id="passwordStatus"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> ปิด
                    </button>
                    <button type="button" class="btn btn-primary" id="saveButton" onclick="saveProfile()"><i
                                class="fa fa-save"></i>
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>