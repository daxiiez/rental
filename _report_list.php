<?php
include '__connect.php';
include '__checkSession.php';

$month = $_SESSION['currentMonth'];
$year = $_SESSION['currentYear'];
if (isset($_GET['search'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
}
$sql = "select * from rental_detail where date_format(check_in,'%m-%Y')  = CONCAT(LPAD($month,2,'0'),'-',$year) and status != 'C';";
$query = mysqli_query($conn, $sql);
$queryC = mysqli_query($conn, $sql);
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
<script>
    $(document).ready(() => {
        setMonth();
        setYear();

    });

    function setYear() {
        let year = "<?php echo $year?>";
        let txt = "";
        let currentYear = new Date().getFullYear();
        for (let i = currentYear - 1; i <= currentYear; i++) {
            txt += "<option value='" + i + "'>" + i + "</option>";
        }
        $("#year").html(txt);
        $("#year").val(year);
    }

    function setMonth() {

        let monthVal = "<?php echo $month?>";
        let txt = "";
        let month = ["มกราคม",
            "กุมภาพัน",
            "มีนาคม",
            "เมษายน",
            "พฤษภาคม",
            "มิถุนายน",
            "กรกฎาคม",
            "สิงหาคม",
            "กันยายน",
            "ตุลาคม",
            "พฤศจิกายน",
            "ธันวาคม"];
        month.forEach((f, i) => {
            txt += "<option value='" + (Number(i)+1) + "'>" + f + "</option>";
        });
        $("#month").html(txt);
        $("#month").val(monthVal);
    }
</script>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <form class="form-group">
                <div class="row">
                    <div class="col-4">

                        <div class="input-group">
                            <label class="col-form-label">
                                เดือน :
                            </label>
                            <select class="form-control" name="month" id="month">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">

                        <div class="input-group">
                            <label class="col-form-label">
                                ปี :
                            </label>
                            <select class="form-control" name="year" id="year">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <button name="search" class="btn btn-primary">ค้นหา</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <?php
            $count = 0;
            while ($temp = mysqli_fetch_array($queryC, MYSQLI_ASSOC)) {
                $count++;
            }
            ?>
            <h3>รวมผู้เข้าพัก : <label class="badge badge-primary"><?php echo $count?> คน</label></h3>
            <div class="row">


                <?php
                while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    $usernameRental = $temp['username'];
                    $sqlAccount = "select * from user where username='$usernameRental'";
                    $queryAccount = mysqli_query($conn, $sqlAccount);
                    $profile = mysqli_fetch_assoc($queryAccount);
                    $span = "";
                    if ($temp['status'] == 'N') {
                        $span = '<span class="badge badge-danger">ยังไม่ได้อัพโหลดหลักฐาน</span>';
                    } elseif ($temp['status'] == 'Y') {
                        $span = '<span class="badge badge-primary">อัพโหลดหลักฐานเรียบร้อยแล้ว</span>';
                    } elseif ($temp['status'] == 'W') {
                        $span = '<span class="badge badge-warning">รอพนักงานตรวจสอบหลักฐาน</span>';
                    } elseif ($temp['status'] == 'S') {
                        $span = '<span class="badge badge-success">พร้อมให้เข้าพัก</span>';
                    } elseif ($temp['status'] == 'C') {
                        $span = '<span class="badge badge-secondary">ถูกยกเลิกแล้ว</span>';
                    } elseif ($temp['status'] == 'I') {
                        $span = '<span class="badge badge-primary">Check In เข้ามาแล้ว</span>';
                    } elseif ($temp['status'] == 'O') {
                        $span = '<span class="badge badge-warning">Check Out ไปแล้ว</span>';
                    }


                    ?>
                    <div class="col-4">
                        <div class="card" style="margin-bottom: 10px;">
                            <h4 class="card-header bg-success text-white text-center font-weight-bold">
                                ห้อง <?php echo $temp['room_id'] ?></h4>
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold">สถานะ : <?php echo $span; ?></h5>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-sm">ชื่อผู้จอง</span>
                                    </div>
                                    <input type="text" value="<?php echo $profile['title'] . ' ' . $profile['name'] ?>"
                                           class="form-control bg-white" aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm" readonly>
                                </div>
                                <br>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-sm">Check In</span>
                                    </div>
                                    <input type="text" value="<?php echo $temp['check_in'] ?>"
                                           class="form-control bg-white" aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm" readonly>
                                </div>
                                <br>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-sm">Check Out</span>
                                    </div>
                                    <input type="text" value="<?php echo $temp['check_in'] ?>"
                                           class="form-control bg-white" aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm" readonly>
                                </div>
                                <br>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-primary text-white" id="inputGroup-sizing-sm">จำนวนวันเข้าพัก</span>
                                    </div>
                                    <input type="text" value="<?php echo $temp['rent_days'] ?>"
                                           class="form-control bg-white" aria-label="Small"
                                           aria-describedby="inputGroup-sizing-sm" readonly>
                                </div>
                                <br>
                                <div align="center">
                                    <a href="_reserve.php?rental_id=<?php echo $temp['rental_id'] ?>"
                                       class="btn btn-outline-primary"><i class="fa fa-list"></i> ดูรายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>

    </div>
</div>
</body>
