
<?php
include '__connect.php';
include '__checkSession.php';

$info_name_txt='';
$info_content_txt='';
$info_id = '';
if(isset($_GET['update'])){
    $info_id=$_GET['update'];
    $sql_content = "SELECT * from information where info_id=$info_id";
    $result = mysqli_query($conn,$sql_content);
    $content_result = mysqli_fetch_assoc($result);
    $info_content_txt = base64_decode($content_result['info_content']);
    $info_content_txt = str_replace('<br>',"\n", $info_content_txt);
    $info_content_txt =  quoted_printable_decode($info_content_txt);
    $info_name_txt = $content_result['info_name'];
}

if($_SESSION['userType'] == 'S'){
    header("Location: _home.php");
}
if(isset($_POST['insertInfo'])){
    $info_owner = $_SESSION["username"];
    $info_name = $_POST['info_name'];
    $info_content = $_POST['comment'];
    $info_content = str_replace("\r\n", '<br>', $info_content);
    $info_content = base64_encode($info_content);
    $sql = "INSERT INTO information VALUES(null,'$info_content',now(),'$info_name','$info_owner')";
    $query = mysqli_query($conn,$sql);

    if($query){
        $sql = "SELECT * FROM information ORDER BY info_id DESC LIMIT 1";
        $query = mysqli_query($conn,$sql);
        $id = mysqli_fetch_assoc($query);
        echo '<script>alert("เพิ่มข้อมูลสำเร็จ"); window.location="_information_content.php?id='.$id['info_id'].'";</script>';
        //  echo 'สำเร็จ';
    }else{
        echo 'ไม่สำเร็จ';
        echo "<script>alert('เพิ่มข้อมูลไม่สำเร็จ'); window.location = '_information_create.php';</script>";
    }
}
if(isset($_POST['updateInfo'])){
    $info_id = $_POST["info_id"];
    $info_name = $_POST['info_name'];
    $info_content = $_POST['comment'];
    $info_content = str_replace("\r\n", '<br>', $info_content);
    $info_content = base64_encode($info_content);
    $sql = "UPDATE information SET info_content='$info_content',info_name='$info_name' where info_id = $info_id";
    $query = mysqli_query($conn,$sql);
print_r($query);
    if($query){
        echo '<script>alert("แก้ไขสำเร็จ"); window.location="_information_content.php?id='.$info_id.'";</script>';
        //  echo 'สำเร็จ';
    }else{
        echo 'ไม่สำเร็จ';
       // echo '<script>alert("แก้ไขข้อมูลไม่สำเร็จ"); window.location="_information_content.php?update='.$id['info_id'].'";</script>';
    }
}
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
<script>
    function addTag(tag) {
        if (tag == 'br') {
            document.getElementById("comment").value += "<" + tag + ">";
        } else {
            document.getElementById("comment").value += "<" + tag + ">" + "</" + tag + ">";
        }
    }

    function preview() {


        document.getElementById("preview").innerHTML = document.getElementById("comment").value.replace(/\n/g,"<br>");
        $('xmp').html($('xmp').text().replace(/<br>/g,'\n'));
        /* var html = document.getElementById("comment").value;
         document.getElementById("preview").innerHTML = html;*/
    }

    function addLink(tag) {
        if (tag == 'a') {
            var link = $("#link").val();
            $("#link").html = "";
            document.getElementById("comment").value += "<a href='" + link + "'>" + link + "</a>";
        } else {
            var url = $("#imgurl").val();
            $("#imgurl").html = "";
            document.getElementById("comment").value += "<img src='" + url + "'>";
        }
        preview();
    }

</script>
<div class="container-fluid"  style="margin-top: 10px; margin-bottom: 150px;" >
    <div class="card">
        <form action="_information_create.php" method="post">
            <input type="hidden" name="info_id" id="info_id" value="<?php echo $info_id?>">
            <div class="card-header">
                <h5><i class="fa fa-calendar"></i> สร้างข่าวสารใหม่</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label class="float-right"><b>หัวข้อ</b></label>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <input type="text" style="font-size: 100%;" id="info_name" name="info_name" class="form-control" value="<?php  echo $info_name_txt?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label class="float-right"><b>เนื้อหา</b></label>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6" style="cursor: pointer;">
                        <i class="fa fa-bold" title="Strong charactor"  onclick="addTag('b')"></i>&nbsp;&nbsp;
                        <i class="fa fa-italic" title="Italic charactor" onclick="addTag('i')"></i>&nbsp;&nbsp;
                        <i class="fa fa-underline" title="Underline" onclick="addTag('u')"></i>&nbsp;&nbsp;
                        <i class="fa fa-link" title="Hyper Link" data-toggle="modal" data-target="#modal_addlink"></i>&nbsp;&nbsp;
                        <i class="fa fa-image" title="Add image" data-toggle="modal" data-target="#modal_addimage"></i>&nbsp;&nbsp;
                        <i class="fa fa-code" title="Code" onclick="addTag('xmp')"></i>&nbsp;&nbsp;
                        <i class="fa fa-indent" title="Newline" onclick="addTag('br')"></i>&nbsp;&nbsp;
                        <i class="fa fa-paint-brush" title="Hilight" onclick="addTag('kbd')"></i>
                        <textarea class="form-control" rows="15" onkeyup="preview()" name="comment" id="comment" style="font-size: 100%;" required><?php
                            echo $info_content_txt
                            ?></textarea>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <label class="float-right"><b>Preview</b></label>
                    </div>

                    <div class="rounded col-lg-6 col-md-6 border border-info" id="preview">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <?php
                        if(isset($_GET['update'])){
                          ?>

                            <button type="submit" class="btn btn-success" name="updateInfo" ><i class="fa fa-edit"></i> แก้ไขข่าวสาร</button>
                            <?php
                        }else{
                        ?>
                        <button type="submit" class="btn btn-success" name="insertInfo" ><i class="fa fa-plus"></i> เพิ่มหัวข้อ</button>   <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<div class="modal fade" id="modal_addlink">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title" style="font-size: 100%;"><b><i class="fa fa-link"></i> เพิ่ม Website</b></h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="usr" style="font-size: 100%;"><b>ใส่ URL ของ Website :</b></label>
                    <input type="text" class="form-control" name="link" id="link" required>
                </div>
                <button type="button" onclick="addLink('a')" data-dismiss="modal" class="btn btn-outline-info"  style="font-size: 100%;"><i class="fa fa-plus"></i> เพิ่มลิ้งค์</button>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size: 100%;"><i class="fa fa-times"></i> ปิด</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal_addimage">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title" style="font-size: 100%;"><b><i class="fa fa-image"></i> เพิ่มรูปภาพ</b></h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-group">
                    <label for="usr" style="font-size: 100%;"><b>ใส่ URL รูปภาพ :</b></label>
                    <input type="text" class="form-control" name="url" id="imgurl" required>
                </div>
                <button type="button" onclick="addLink('img')" data-dismiss="modal" class="btn btn-outline-primary"  style="font-size: 100%;"><i class="fa fa-plus"></i> เพิ่ม</button>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size: 100%;"><i class="fa fa-times"></i> ปิด</button>
            </div>

        </div>
    </div>
</div>