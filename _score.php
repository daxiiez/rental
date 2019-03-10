<?php
include '__connect.php';
include '__checkSession.php';

if (isset($_POST['cutpoint'])) {
    $rule_no = $_POST['rule_no'];
    $action_date = date("Y-m-d");;
    $action_by = $_SESSION['username'];
    $student_code = $_POST['student_code'];

    $sql = "INSERT INTO `score_history` (`id`, `rule_no`, `action_date`, `action_by`, `student_code`) VALUES (NULL, '$rule_no', '$action_date', '$action_by', '$student_code');";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> หักคะแนนสำเร็จ
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> ข้อมูลบางอย่างผิดพลาด
                    </div>';
    }

}

?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>
        $(document).ready(() => {
            $('#reportContentList').DataTable();
        })
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';

?>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 100px;">

    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">
                <h5>รายชื่อนิสิต</h5>
            </nav>
        </div>
        <div class="card-body">
            <div style="width: 85%" class="mx-auto">


                <div class="table-responsive">
                    <table id="reportContentList" class="table table-bordered rounded">
                        <thead>
                        <tr>
                            <th>รหัสนิสิต</th>
                            <th>ชื่อ</th>
                            <th>เบอร์โทร</th>
                            <th>คณะ</th>
                            <th>รูป</th>
                            <th>คะแนนคงเหลือ</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>


                        <?php

                        $sql = "
select s.*, 100 + IFNULL(score_detail.total_score, 0) as remain_score
from booking_detail d
       inner join student s on s.code = d.student_code
       left join (select sum(r.score) as total_score, h.student_code
                  from score_history h
                         inner join rule r on r.rule_no = h.rule_no
                  group by h.student_code) score_detail on score_detail.student_code = d.student_code
";
                        $result = mysqli_query($conn, $sql);
                        while ($temp = mysqli_fetch_array($result)) {
                            $picture = $temp['picture'];
                            ?>
                            <tr>
                                <td><?php echo $temp['code'] ?></td>
                                <td><?php echo $temp['name'] ?></td>
                                <td><?php echo $temp['tel'] ?></td>
                                <td><?php echo $temp['department'] ?></td>
                                <td>
                                    <?php
                                    if ($picture == '') {
                                        echo '<img id="showpicture" src="img/profile.png" width="100" border="5">';
                                    } else
                                        echo '<img id="showpicture" width="100" border="5" src="data:image/jpeg;base64,' . base64_encode($picture) . '" />';
                                    ?>
                                </td>
                                <td><?php echo $temp['remain_score'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary"
                                            onclick="initCutScoreModal('<?php echo $temp['code']; ?>','<?php echo $temp['name']; ?>')">
                                        หักคะแนน
                                    </button>

                                    <!-- Modal -->
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
        <div class="footer bg-warning text-white">

        </div>
    </div>
</div>

</div>
</body>
</html>
<script>
    function initCutScoreModal(code, name) {
        $("#exampleModal3Label").html('หักคะแนนนิสิต ชื่อ ' + name);
        $("#student_code").val(code);
        $("#exampleModal3").modal();
    }
</script>

<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog"
     aria-labelledby="exampleModal3Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal3Label">หักคะแนนนิสิต
                        ชื่อ </h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $sql = "select * from rule";
                    $count = 0;
                    $result = mysqli_query($conn, $sql);
                    ?>

                    <div class="row">
                        <div class="col col-sm col-lg col-md">
                            <div class="form-group">
                                <label>รหัสนิสิต :</label>
                                <input name="student_code" id="student_code"
                                       class="form-control" required
                                       value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>หักคะแนน</label>
                        <input list="rule_no_List"
                               id="rule_no"
                               name="rule_no"
                               data-link-field="rule_no_List"
                               class="form-control"
                               placeholder="พิมพ์รหัสหรือชื่อเพื่อเลือกหัวข้อ"
                               required/>
                        <datalist id="rule_no_List">
                            <?php
                            $count = 0;
                            while ($temp = mysqli_fetch_array($result)) {
                                ?>
                                <option value="<?php echo $temp['rule_no']; ?>"> <?php echo $temp['detail'] . " (" . $temp['remark'] . ") "; ?> </option>
                            <?php } ?>
                        </datalist>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">ปิด
                    </button>
                    <button type="submit" name="cutpoint" class="btn btn-primary">
                        ยืนยันการหักคะแนน
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>