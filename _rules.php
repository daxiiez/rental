<?php
include '__connect.php';
include '__checkSession.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM `score_history` WHERE `id` = '$id'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        $_GET['blacklist'] = true;
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>สำเร็จ!</strong> ข้อมูลการหักคะแนนสำเร็จ
                    </div>';

    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>ไม่สำเร็จ!</strong> การลบข้อมูลการหักคะแนนไม่ถูกต้อง
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

<?php
if($_SESSION['userType']!='N'){
?>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 100px;">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">
                <h5><i class="fa fa-star"></i> ข้อมูลหอพัก</h5>
            </nav>
        </div>
        <div class="card-body">
            <div class="row" align="center">
                <?php
                if($_SESSION['userType']=='A'){
                ?>
                <div class=" col col-sm col-md col-lg">
                    <div class="img-area">
                        <a href="_rules_insert.php?rule=true">
                            <img src="img/rule.png" class="image" width="125" height="125">
                            <div class="overlay bg-info ">
                                <div class="text"> เพิ่ม/แก้ไขกฏเกณฑ์และข้อบังคับ</div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                }
                ?>

                    <div class=" col col-sm col-md col-lg">
                        <div class="img-area">
                            <a href="_score.php">
                                <img src="img/blacklist.png" class="image" width="125" height="125">
                                <div class="overlay bg-info ">
                                    <div class="text"> หักคะแนน</div>
                                </div>
                            </a>
                        </div>
                    </div>
                <div class=" col col-sm col-md col-lg">
                    <div class="img-area">
                        <a href="_rules.php?blacklist=true">
                            <img src="img/listscore.png" class="image" width="125" height="125">
                            <div class="overlay bg-info ">
                                <div class="text"> รายชื่อนิสิตที่ถูกหักคะแนน</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    }
    ?>
    <br>
    <?php
    if (isset($_GET['blacklist'])) { ?>

        <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">
                    <h5>กฏเกณฑ์และข้อบังคับ</h5>
                </nav>
            </div>
            <div class="card-body">
                <div style="width: 85%" class="mx-auto">
                    <div class="table-responsive">
                        <table id="reportContentList" class="table table-bordered rounded">
                            <thead>
                            <tr>
                                <th>วันที่ถูกหักคะแนน</th>
                                <th>รหัสนิสิต</th>
                                <th>ชื่อนิสิต</th>
                                <th>คณะ</th>
                                <th>สาขา</th>
                                <th>กฏข้อที่</th>
                                <th>รายละเอียด</th>
                                <th>คะแนนคงเหลือ</th>
                                <th>หักคะแนนโดย</th>
                            <?php if($_SESSION['userType']=='A'){?>    <th></th><?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "select h.id,
       h.action_date,
       s.code,
       s.name as student_name,
       s.department,
       s.major,
       r.rule_no,
       r.detail,
       100+(score_detail.total_score) as remain_score,
       u.name as action_by
from score_history h
       inner join student s on s.code = h.student_code
       inner join rule r on r.rule_no = h.rule_no
       inner join user u on u.username = h.action_by
       left join (select sum(r.score) as total_score,h.student_code
                   from score_history h
                          inner join rule r on r.rule_no = h.rule_no
                   group by h.student_code) score_detail on score_detail.student_code = h.student_code";

                            $result = mysqli_query($conn, $sql);
                            while ($temp = mysqli_fetch_array($result)) {
                                $id = $temp['id'];
                                $action_date = $temp['action_date'];
                                $code = $temp['code'];
                                $student_name = $temp['student_name'];
                                $department = $temp['department'];
                                $major = $temp['major'];
                                $rule_no = $temp['rule_no'];
                                $detail = $temp['detail'];
                                $action_by = $temp['action_by'];
                                ?>
                                <tr>

                                    <td><?php echo $action_date; ?></td>
                                    <td><?php echo $code; ?></td>
                                    <td><?php echo $student_name; ?></td>
                                    <td><?php echo $department; ?></td>
                                    <td><?php echo $major; ?></td>
                                    <td><?php echo $rule_no; ?></td>
                                    <td><?php echo $detail; ?></td>
                                    <td><?php echo $temp['remain_score']; ?></td>
                                    <td><?php echo $action_by ?></td>
                                    <?php if($_SESSION['userType']=='A'){?>          <td><a class="btn btn-sm btn-primary text-white"
                                           onclick="if(confirm('คุณต้องการลบรายการนี้ ?'))window.location ='_rules.php?blacklist=true&id=<?php echo $temp['id'] ?>'"><i
                                                    class="fa fa-edit"></i> ลบ</a></td><?php } ?>
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


    <?php } else { ?>
        <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">

                    <h5>กฏเกณฑ์และข้อบังคับ</h5>

                </nav>
            </div>
            <div class="card-body">
                <div style="width: 85%" class="mx-auto">
                    <div class="table-responsive">
                        <table id="reportContentList" class="table table-bordered rounded">
                            <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>รายละเอียด</th>
                                <th>คะแนน</th>
                                <th>หมายเหตุ</th>
                                <?php if($_SESSION['userType']=='A'){?>         <th></th> <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "select * from rule";
                            $count = 0;
                            $result = mysqli_query($conn, $sql);
                            while ($temp = mysqli_fetch_array($result)) {
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count ?></td>
                                    <td><?php echo $temp['detail'] ?></td>
                                    <td><?php echo $temp['score'] ?></td>
                                    <td><?php echo $temp['remark'] ?></td>
                                    <?php if($_SESSION['userType']=='A'){?>       <td><a class="btn btn-sm btn-primary text-white"
                                           onclick="window.location ='_rules_insert.php?rule_no=<?php echo $temp['rule_no'] ?>'"><i
                                                    class="fa fa-edit"></i> แก้ไขข้อมูล</a></td><?php }?>
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
        <?php
    }
    ?>

</body>
</html>