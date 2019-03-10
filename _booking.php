<?php
include '__connect.php';
include '__checkSession.php';
if($_SESSION['userType'] != 'A'){
    header("Location: _home.php");
}
$msg = '';
if (isset($_POST['insertBooking'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $towerNo = $_POST['towerNo'];
    $towerType = $_POST['towerType'];
    $sql = "INSERT INTO booking VALUES (STR_TO_DATE('$startDate','%d/%c/%Y'),STR_TO_DATE('$endDate','%d/%c/%Y'), $semester, $year, '$towerNo','$towerType')";
    $result = mysqli_query($conn, $sql);
    print_r($result);
    if ($result) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>เปิดการจองสำเร็จสำเร็จ!</strong> 
                    </div>';
    }
}
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>
        let towerList = [];

        $(document).ready(() => {
            $("#bookingTable").dataTable();
            $.get('SQL_Select/selectTowerList.php', r => {
                towerList = JSON.parse(r);
                setTowerList();
                console.log(towerList);
            });

            let year = new Date().getFullYear() + 543;
            let yearList = '';
            for (let i = 0; i < 5; i++) {
                let y = Number(Number(year) + i);
                yearList += "<option value='" + y + "'>" + y + "</option>"
            }
            $("#year").html(yearList);
            $('#startDate').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd/mm/yyyy'
            });
            $('#endDate').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd/mm/yyyy'
            });
            validateButton()
        });

        function setTowerList() {
            let type = $('#towerType').val();
            let list = towerList.filter(f => f.type == type);
            let html = '';
            for (let tower of list) {
                html += '<option value="' + tower.tower_no + '">' + tower.tower_no + '</option>'
            }
            $('#towerNo').html(html);
        }

        let openBookingInfo = {
            startDate: "",
            endDate: "",
            year: "",
            towerType: "",
            towerNo: ""
        }

        function validateButton() {
            let valid = true;
            for (let key in openBookingInfo) {
                openBookingInfo[key] = $('#' + key).val();
                if (openBookingInfo[key] == '') {
                    valid = false;
                }
            }
            if (!valid) $("#submitBtn").attr('disabled', 'disabled');
            else $("#submitBtn").removeAttr('disabled');


        }

        function validateDateBooking() {
            let startDateTxt = setDateToActualFormat($('#startDate').val());
            let endDateTxt = setDateToActualFormat($('#endDate').val());
            let startDate = new Date(startDateTxt).setHours(0, 0, 0, 0);
            let endDate = new Date(endDateTxt).setHours(0, 0, 0, 0);
            console.log(startDate);
            console.log(endDate);
            if (endDate < startDate) {
                alert('ระบุวันที่ติดตั้งไม่ถูกต้อง');
                $('#endDate').val('');
                console.log(serviceDetail);
            }
            validateButton()
        }

        function setDateToActualFormat(date) {
            return date.substr(3, 2) + "/" + date.substr(0, 2) + '/' + date.substr(6, 4);
        }
    </script>
</head>
<body>
<?php
include '__navbar_admin.php';
?>

<div class="container-fluid" style="margin-top: 10px; margin-bottom: 10px;">
    <?php
    echo $msg;
    ?>
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb  bg-dark">
                <h5><i class="fa fa-star"></i> จัดการการจอง</h5>
            </nav>
        </div>
        <div class="card-body">
            <div class="row" align="center">
                <div class=" col-3 col-sm col-md col-lg">
                    <div class="img-area ">
                        <a href="_booking.php?bookingList="> <img src="img/menu/booked.png" class=" image"
                                                                  width="125" height="125">
                            <div class="overlay bg-secondary ">
                                <div class="text"> หอพักที่เปิดจอง</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class=" col-3 col-sm col-md col-lg">
                    <div class="img-area">
                        <a href="_booking.php?bookingCreate="> <img src="img/menu/booking.png" class="image"
                                                                width="125" height="125">
                            <div class="overlay bg-info ">
                                <div class="text"> เพิ่มช่วงจองหอ</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer bg-warning text-white">
        </div>
    </div>
</div>

<?php
if(isset($_GET['bookingList'])) include '_booking_list.php';
if(isset($_GET['bookingCreate'])) include '_booking_create.php';
?>


</body>
</html>
