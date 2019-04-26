<?php echo $alert; ?>

    <script xmlns="http://www.w3.org/1999/html">
        let base64List = [
            /*
            index:0,
            base64:''
            * */
        ]
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


            <?php
            $count = 0;
            if ((isset($_GET['roomDetail']) && isset($_GET['roomNo'])))
            {
            $sql = "SELECT * from room_img where room_id = '$roomNo'";
            $query = mysqli_query($conn, $sql);
            while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count++;
            ?>
            $("#imgInp<?php echo $count; ?>").change(function () {
                readURL(this, '#img-upload<?php echo $count; ?>',<?php echo $count; ?>);
            });
            <?php
            }
            }?>


            $("#imgInp<?php echo $count + 1; ?>").change(function () {
                readURL(this, '#img-upload<?php echo $count + 1; ?>',<?php echo $count + 1; ?>);
            });
            $("#imgEdit").change(function () {
                readURL(this, '#editProductImg', 0);
            });
            $("#imgInp").change(function () {
                readURL(this, '#img-upload', 0);
            });


        });

        function setBse64List(base64, index) {
            let noData = true;
            base64List.forEach((f, i) => {
                if (index == f.index) {
                    noData = false;
                    base64List[i].base64 = base64;
                }
            });
            if (noData) {
                base64List.push({
                    index: index,
                    base64: base64
                })
            }
        }

        function readURL(input, id, index) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(id).attr('src', e.target.result);
                    setBse64List(e.target.result, index);
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


        function saveRoom() {
            let saveObj = arrayToObject($("#saveForm").serializeArray());
            $.post('SQL_Update/updateRoomDetail.php', saveObj, (r) => {
                if (r) {
                    alert("บันทึกข้อมูลสำเร็จ !")
                }
            })

        }
    </script>

    <div class="card">
        <div class="card-header font-weight-bold">
            <?php echo $header . 'ข้อมูลห้องพัก' ?>
        </div>
        <form method="post" id="saveForm" enctype="multipart/form-data">
            <div class="card-body">
                <!-- Modal body -->
                <div class="row">
                    <?php
                    if (isset($_GET['roomDetail']) && isset($_GET['roomNo'])) {
                    ?>
                    <div class="col-12">
                        <?php
                        }else{
                        ?>

                        <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12" align="center">
                                    <?php
                                    if ($roomNo == '') {
                                        ?>
                                        <img id="img-upload" src="img/room.png" width="300" border="5">
                                        <?php
                                    } else {
                                        ?>
                                        <img id="img-upload" width="300" border="5"
                                             src="data:image/jpeg;base64,<?php echo base64_encode($roomDetail['img']); ?>">
                                        <?php
                                    } ?>
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
                            <?php
                            }
                            ?>
                            <div class="row">


                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>เลขห้อง</label>
                                        <input class="form-control" name="saveRoomNo" id="saveRoomNo"
                                               type="number"
                                               maxlength="20"
                                               value="<?php
                                               if ($roomNo != '') echo $roomDetail['room_id'];
                                               ?>"
                                            <?php if ((isset($_GET['roomDetail']) && isset($_GET['roomNo']))) {
                                                echo ' readonly';
                                            } ?>
                                               required>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>ขนาดห้อง</label>
                                        <select class="custom-select" name="saveRoomSize" id="saveRoomSize"
                                                value="<?php
                                                if ($roomNo != '') echo $roomDetail['size'];
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
                                            if ($roomNo != '') echo $roomDetail['cost'];
                                            ?>"
                                            id="saveRoomCost"
                                            required>
                                </div>
                                <?php
                                if ((isset($_GET['roomDetail']) && isset($_GET['roomNo']))) {
                                    ?>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <label>สถานะ</label>
                                        <select class="form-control" name="saveRoomStatus" id="saveRoomStatus"
                                                value="<?php echo $roomDetail['status'] ?>">
                                            <option value="A" <?php if ($roomDetail['status'] == 'A') echo 'selected' ?>>
                                                เปิดให้จอง
                                            </option>
                                            <option value="C" <?php if ($roomDetail['status'] == 'C') echo 'selected' ?>>
                                                ปิดปรับปรุง
                                            </option>
                                        </select>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <?php
                    if (isset($_GET['roomDetail']) && isset($_GET['roomNo'])) {
                        ?>
                        <button type="button" name="updateRoom" onclick="saveRoom()" class="btn btn-primary"><i
                                    class="fa fa-edit"></i>
                            แก้ไขข้อมูล
                        </button>
                        <?php
                    } else {
                        ?>
                        <button type="submit" name="insertRoom" class="btn btn-primary"><i class="fa fa-plus"></i> เพิ่ม
                        </button>
                        <?php
                    }
                    ?>
                </div>

        </form>
    </div>
    <br>
    <script>

        let maxSize = <?php echo $count;?>;

        function mainFlagChange(index) {
            for (let i = 1; i <= maxSize + 1; i++) {
                let id = "#customRadio" + i;
                if (i == index) {
                    $(id).prop('checked', true);
                } else {
                    $(id).prop('checked', false);
                }
            }
        }

        function updateImg(id) {
            let index = id;
            id = "#edit" + id;
            let idImg = "#imgInp" + id;
            let obj = arrayToObject($(id).serializeArray());
            console.log(base64List.filter(f => f.index == index));
            if (base64List.filter(f => f.index == index).length > 0) {
                obj.img = base64List.filter(f => f.index == index)[0].base64.replace(/^data:image\/[a-z]+;base64,/, "");
            }

            $.post('SQL_Update/updateImage.php', obj, (r) => {
                if (r) {
                    alert("บันทึกข้อมูลสำเร็จ !")
                }
            })
        }

        function deleteImage(id) {
            if (confirm("ต้องการลบรูปภาพหรือไม่ ?")) {
                $.post('SQL_Delete/deleteImg.php', {imgId: id}, (r) => {
                    if (r) {
                        alert("ลบรูปภาพสำเร็จ !")
                        location.reload();
                    }
                })
            }
        }

        function addImage() {
            let index = maxSize + 1;
            let obj = arrayToObject($("#addImageForm").serializeArray());
            if (base64List.filter(f => f.index == index).length > 0) {
                obj.img = base64List.filter(f => f.index == index)[0].base64.replace(/^data:image\/[a-z]+;base64,/, "");
            }
            if (!obj.img) {
                alert("กรุณาเลือกรูปภาพก่อนเพิ่ม !");
            } else {
                $.post('SQL_Update/insertImage.php', obj, (r) => {
                    if (r) {
                        alert("บันทึกข้อมูลสำเร็จ !")
                        location.reload();
                    }
                })
            }
            console.log(obj);
        }

    </script>
<?php
if ((isset($_GET['roomDetail']) && isset($_GET['roomNo']))) {
    ?>
    <div class="card">
        <div class="card-header">ภาพประกอบ</div>
        <div class="card-body">

            <div class="row">
                <?php
                $sql = "SELECT * from room_img where room_id = '$roomNo'";
                $count = 0;
                $query = mysqli_query($conn, $sql);
                while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    $count++;
                    ?>
                    <div class="col-3">
                        <form id="edit<?php echo $count; ?>">
                            <input type="hidden" name="roomId" value="<?php echo $temp['room_id'] ?>">
                            <input type="hidden" name="imgId" value="<?php echo $temp['room_img_id'] ?>">
                            <div class="card">

                                <!---->
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12" align="center">

                                        <img id="img-upload<?php echo $count; ?>" class="card-img-top" height="20%"
                                             border="5"
                                             name="imageShow"
                                             src="data:image/jpeg;base64,<?php echo base64_encode($temp['img']); ?>">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-btn btn-outline-info btn-block">
                                               <span class="btn btn-sm btn-default btn-file "><i
                                                           class="fa fa-image"></i> แก้ไขรูปภาพ
                                                   <input type="file" name="imgUpdate"
                                                          id="imgInp<?php echo $count; ?>">
                                               </span>
                                           </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!---->

                                <div class="card-body">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio<?php echo $count; ?>" name="mainFlag"
                                               onclick="mainFlagChange(<?php echo $count; ?>)"
                                               class="custom-control-input" <?php if ($temp['main_flag'] == 'Y') echo 'checked' ?>>
                                        <label class="custom-control-label" for="customRadio<?php echo $count; ?>">
                                            ภาพหลัก</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="กรอกคำบรรยายรูปภาพ"
                                               value="<?php echo $temp['info']; ?>" aria-label=""
                                               name="saveInfo"
                                               aria-describedby="basic-addon1">
                                        <div class="input-group-append">
                                            <button class="btn btn-success"
                                                    onclick="updateImg(<?php echo $count; ?>)" type="button">บันทึก
                                            </button>
                                        </div>
                                    </div>
                                    <button class="btn btn-block btn-danger" type="button"
                                            onclick="deleteImage(<?php echo $temp['room_img_id'] ?>)"><i
                                                class="fa fa-trash"></i> ลบรูปภาพ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                ?>

                <div class="col-3">
                    <form id="addImageForm">
                        <input type="hidden" name="roomId" value="<?php echo $_GET['roomNo'] ?>">
                        <div class="card">

                            <div align="center">
                                <img class="card-img-top"
                                     name="imageShow"
                                     id="img-upload<?php echo $count + 1; ?>"
                                     height="20%"
                                     border="5"
                                     src="img/room.png" >
                                <div class="form-group">
                                    <div class="input-group">
                                            <span class="input-group-btn btn-outline-info btn-block">
                                               <span class="btn btn-sm btn-default btn-file "><i
                                                           class="fa fa-image"></i> เพิ่มรูปภาพ
                                                   <input type="file" name="imgUpdate"
                                                          id="imgInp<?php echo $count + 1; ?>">
                                               </span>
                                           </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">

                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio<?php echo $count + 1; ?>" name="mainFlag"
                                           onclick="mainFlagChange(<?php echo $count + 1; ?>)"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio<?php echo $count + 1; ?>">
                                        ภาพหลัก</label>
                                </div>

                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="กรอกคำบรรยายรูปภาพ"
                                           value=""
                                           aria-label="" aria-describedby="basic-addon1">
                                    <div class="input-group-append">
                                        <button class="btn btn-success" onclick="addImage()" type="button"> เพิ่มรูปภาพ
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
}
?>