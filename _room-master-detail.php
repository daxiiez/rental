<?php  echo $alert; ?>

<script>
    $(document).ready(function () {
        $(document).on('change', '.btn-file :file', function () {
            var input = $(this),
                // label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                label = input.val();
            //  label = input.files[0].name;
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function (event, label) {
            var input = $(this).parents('.input-group').find(':text'),
                log = label;
            if (input.length) {
                input.val(log);
            } else {
                // if (log) alert(log);
            }
        });

        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
                readFile(input.files[0], function (e) {
                    // alert( e.target.result);
                });
            }
        }

        function readFile(file, callback) {
            var reader = new FileReader();
            reader.onload = callback
            reader.readAsText(file);
        }

        $("#imgInp").change(function () {
            readURL(this, '#img-upload');
        });

        $("#imgEdit").change(function () {
            readURL(this, '#editProductImg');
        });
    });
</script>
<div class="card">
    <div class="card-header font-weight-bold">
        <?php echo $header . 'ข้อมูลห้องพัก' ?>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="card-body">
            <!-- Modal body -->
            <div class="row">
                <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12" align="center">
                            <?php
                            if($roomNo=='') {
                                ?>
                                <img id="img-upload" src="img/room.png" width="300" border="5">
                                <?php
                            }else {
                                ?>
                                <img id="img-upload" width="300" border="5" src="data:image/jpeg;base64,<?php echo base64_encode($roomDetail['img']); ?>">
                                <?php
                            }?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <div class="input-group">
                                            <span class="input-group-btn">
                                               <span class="btn btn-sm btn-default btn-file btn-outline-info"><i
                                                           class="fa fa-image"></i> เลือกรูปภาพ
                                                   <input type="file" name="imgInp" id="imgInp">
                                               </span>
                                           </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8 col-sm-8 col-md-8 col-lg-8">
                    <div class="row">


                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>เลขห้อง</label>
                                <input class="form-control" name="saveRoomNo" id="saveRoomNo"
                                       type="number"
                                       maxlength="20"
                                       value="<?php
                                       if($roomNo!='') echo $roomDetail['room_id'];
                                       ?>"
                                       required>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>ขนาดห้อง</label>
                                <select class="custom-select" name="saveRoomSize" id="saveRoomSize"
                                        value="<?php
                                        if($roomNo!='') echo $roomDetail['size'];
                                        ?>"
                                        required>
                                    <option value="S" selected> 1-2 คน</option>
                                    <option value="M"> 3-4 คน</option>
                                    <option value="L"> 4-5 คน</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <label>ราคา/คืน</label>
                            <input
                                    class="form-control"
                                    type="number"
                                    name="saveRoomCost"
                                    value="<?php
                                    if($roomNo!='') echo $roomDetail['cost'];
                                    ?>"
                                    id="saveRoomCost"
                                    required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" name="insertRoom" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่ม
            </button>
        </div>

    </form>
</div>
