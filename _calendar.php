<?php
include '__connect.php';
?>
<!Document>
<html>

<head>
    <?php include '__header.php'; ?>
    <script>

    </script>
</head>
<body>
<?php
include '__navbar_admin.php';

$monthList = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

$selectedRoom = '';
$roomCost = 0;
if (isset($_GET['selectedRoom'])) {
    $selectedRoom = $_GET['selectedRoom'];
    $query = mysqli_query($conn, "SELECT cost from room where room_id = '$selectedRoom'");
    $result = mysqli_fetch_assoc($query);
    $roomCost = $result['cost'];
}


$selectedMonth = $_SESSION['currentMonth'];
$selectedYear = $_SESSION['currentYear'];
if (isset($_GET['selectedMonth'])) {
    $selectedMonth = $_GET['selectedMonth'];
}
if (isset($_GET['selectedYear'])) {
    $selectedYear = $_GET['selectedYear'];
}

?>

<script>

    let selectedIndex = [
        /*
        {
        month:1,
        date:[1,2,3,4,5,6]
        },
        {
        month:1,
        date:[1,2,3,4,5,6]
        }
        * */
    ];

    function selectedDate(index, month, year, maxDate) {
        index = Number(index);
        month = Number(month);
        year = Number(year);
        let id = "#selected" + index + "m" + month;
        let indexMonth = 99;
        let indexPreviousMonth = 99;
        let indexNextMonth = 99;

        selectedIndex.forEach((f, i) => {
            if (f.month == month) {
                indexMonth = i;
            }
            if (f.month < month && f.year == year || year > f.year) {
                indexPreviousMonth = i;
            }
            if (f.month > month && f.year == year || year < f.year) {
                indexNextMonth = i;
            }
        });
        if (indexMonth != 99) {
            let allowPush = true;
            if (selectedIndex[indexMonth].date.includes(index)) {
                allowPush = false;
                selectedIndex[indexMonth].date.forEach((f, i) => {
                    if (f >= index) {
                        $("#selected" + f + "m" + selectedIndex[indexMonth].month).toggleClass("btn-success btn-outline-success")
                    }
                })
                selectedIndex[indexMonth].date = selectedIndex[indexMonth].date.filter(f => f < index)
                //selectedIndex = selectedIndex.filter(f => f.year < year);
                if (indexNextMonth != 99) {
                    selectedIndex[indexNextMonth].date.forEach(f => {
                        $("#selected" + f + "m" + selectedIndex[indexNextMonth].month).toggleClass("btn-success btn-outline-success")
                    })
                    selectedIndex.splice(indexNextMonth, 1);
                }
                if (selectedIndex[indexMonth].date.length == 0) {
                    selectedIndex.splice(indexMonth, 1);
                }
            }
            else {
                let valid = false;
                if (selectedIndex.length == 0) {
                    valid = true;
                } else {
                    selectedIndex[indexMonth].date.forEach(f => {
                        let diff = f - index;
                        if ([1, -1].includes(diff)) {
                            valid = true;
                        }
                    })
                }
                if (!valid) {
                    allowPush = false;
                    alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
                }
            }
            if (allowPush) {
                selectedIndex[indexMonth].date.push(index);
                for (let i in selectedIndex) {
                    selectedIndex[i].date = selectedIndex[i].date.sort();
                }
                $(id).toggleClass("btn-outline-success btn-success")
            }
        } else {
            let valid = true;
            if (indexPreviousMonth != 99) {
                if (selectedIndex[indexPreviousMonth].date.includes(selectedIndex[indexPreviousMonth].lastDate)) {
                    valid = (index == 1);
                } else {
                    valid = false;
                }
                if (!valid) {
                    alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
                }
            } else if (indexNextMonth != 99) {
                if (selectedIndex[indexNextMonth].date.includes(1)) {
                    valid = index == maxDate;
                } else {
                    valid = false;
                }
                if (!valid) {
                    alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
                }
            }
            if (valid) {
                selectedIndex.push({
                    month: Number(month),
                    year: Number(year),
                    lastDate: Number(maxDate),
                    date: [index]
                });
                selectedIndex = _.orderBy(selectedIndex, ['year', 'month'], ['asc', 'asc']);
                $(id).toggleClass("btn-outline-success btn-success")
            }

        }

        let txt = "ไม่ได้เลือกวันใด";
        if (selectedIndex.length > 0) {
            let indexLast = selectedIndex.length - 1;
            let indexLastDate = selectedIndex[indexLast].date.length - 1;
            let startDate = selectedIndex[0].date[0] + "-" + selectedIndex[0].month + "-" + selectedIndex[0].year
            let endDate = selectedIndex[indexLast].date[indexLastDate] + "-" + selectedIndex[indexLast].month + "-" + selectedIndex[indexLast].year
            let totalDate = 1;
            txt = "จองวันที่ " + startDate;
            if (startDate != endDate) {
                if (selectedIndex.length > 1) {
                    totalDate = selectedIndex[indexLast].date.length + selectedIndex[0].date.length
                } else {
                    totalDate = selectedIndex[0].date.length
                }
            }
            if (totalDate == 2) {
                txt += " และ " + endDate;
            } else if (totalDate > 2) {
                txt = "จองตั้งแต่ " + startDate + " ถึงวันที่ " + endDate;
            }
            let cost = $("#roomCost").val();
            $("#startDate").val(startDate)
            $("#endDate").val(endDate)
            $("#totalDate").val(totalDate)
            $("#totalCost").val(Number(totalDate) * Number(cost))
            $("#deposit").removeAttr("disabled");
        } else {

            $("#deposit").attr("disabled", "disabled");
            $("#startDate").val("")
            $("#endDate").val("")
            $("#totalDate").val("")
            $("#totalCost").val("")
        }
        //$("#reserveDate").html("<h3>" + txt + "</h3>");
    }
</script>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <h3 class="font-weight-bold"> ตารางการจอง ห้อง <?php echo $selectedRoom; ?> </h3>
        </div>
        <div class="card-body">

            <div class="row">
                <?php
                for ($ind = 0; $ind < 12; $ind++) {
                    $notValidMonth = ((intval($ind + 1) < intval($_SESSION['currentMonth'])) && $selectedYear == $_SESSION['currentYear']);
                    ?>
                    <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4 col-xs-4">
                        <a
                            <?php if ($notValidMonth) echo 'style="pointer:not-allowed;"'; ?>
                                class="btn btn-lg
                        btn-<?php if ($ind + 1 != $selectedMonth) echo "outline-"; ?>info
                        <?php if ($notValidMonth) echo ' disabled'; ?>"
                                href="_calendar.php?selectedRoom=<?php echo $selectedRoom . "&selectedMonth=" . ($ind + 1) . "&selectedYear=" . $selectedYear; ?>"
                        >
                            <?php echo $monthList[$ind]; ?>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
            <hr>
            <div class="row">
                <?php
                for ($m = $selectedMonth; $m <= $selectedMonth + 1; $m++) {
                    $year = $selectedYear;
                    $month = $m;
                    if ($month > 12) {
                        $year = $year + 1;
                        $month = 1;
                    }
                    $totalDate = intval(date("t", strtotime($selectedYear . '-' . ($month) . '-' . '1')));
                    ?>

                    <div class="col-6">
                        <div class="card">
                            <div class="card-header text-center bg-info text-white">
                                <strong>
                                    เดือน
                                    <?php
                                    if ($m == 13) echo $monthList[0];
                                    else echo $monthList[$m - 1];
                                    ?>
                                    ปี
                                    <?php
                                    echo $year;
                                    ?>
                                </strong>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-primary text-white">
                                    <tr>
                                        <th width="14%">อาทิตย์</th>
                                        <th width="14%">จันทร์</th>
                                        <th width="14%">อังคาร</th>
                                        <th width="14%">พุธ</th>
                                        <th width="14%">พฤหัสบดี</th>
                                        <th width="14%">ศุกร์</th>
                                        <th width="14%">เสาร์</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $end = true;
                                    for ($i = 1; $i <= $totalDate; $i++) {
                                        $sql = "select * from rental_detail where room_id='$selectedRoom' AND  DATE_FORMAT('$selectedYear-$month-$i','%Y-%m-%d') between check_in and check_out and status = 'S'";
                                        $dateQuery = mysqli_query($conn, $sql);
                                        $dateResult = mysqli_fetch_assoc($dateQuery);
                                        if ($end) {
                                            echo '<tr>';
                                        }
                                        for ($j = 0; $j < 7; $j++) {
                                            $day = intval(date("w", strtotime($_SESSION['currentYear'] . '-' . $month . '-' . $i)));
                                            $notValidDate = ($i < $_SESSION['currentDate'] && $month == $_SESSION['currentMonth'] && $year == $_SESSION['currentYear'])
                                                || ($month < $_SESSION['currentMonth'] && $year == $_SESSION['currentYear'])
                                                || $year < $_SESSION['currentYear'];
                                            /*$year==$_SESSION['currentYear']
                                                                                            &&($month<$_SESSION['currentMonth'] &&$year==$_SESSION['currentYear'])
                                                                                            &&*/
                                            if ($day == $j) {
                                                ?>

                                                <td class="border-bottom">
                                                    <button
                                                            id="selected<?php echo $i; ?>m<?php echo $month; ?>"
                                                            onclick="selectedDate(<?php echo $i; ?>,'<?php echo $month; ?>',<?php echo $year; ?>,'<?php echo $totalDate; ?>')"
                                                            title="
                                                            <?php if ($dateResult)
                                                                echo 'ห้องถูกจองแล้ววันนี้';
                                                            elseif ($notValidDate)
                                                                echo 'ไม่สามารถจองย้อนหลังได้'; ?>"
                                                            style="font-size: x-large; <?php if ($notValidDate) echo 'cursor:not-allowed;'; ?>"
                                                            class="btn <?php
                                                            if ($dateResult) {
                                                                echo 'btn-danger';
                                                            } else if ($notValidDate) {
                                                                echo 'btn-warning';
                                                            } else {
                                                                echo 'btn-outline-success';
                                                            } ?> "
                                                        <?php if (($notValidDate) || $dateResult) echo 'disabled'; ?>>
                                                        <?php echo $i; ?>
                                                    </button>
                                                </td>

                                                <?php
                                                $end = ($j == 6);
                                                if ($i == 1) {
                                                    break;
                                                }
                                            } elseif ($i == 1 || ($i == $totalDate && $j > $day)) {
                                                echo '<td class="border-bottom"></td>';
                                            }
                                        }
                                        if ($end) {
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="footer text-white">
            <form id="reserveForm">
                <input type="hidden" name="roomId" value="<?php echo $selectedRoom; ?>">
                <div class="row" style="margin-bottom: 10px;">
                    <?php
                    if ($_SESSION['type'] != 'M' && $_SESSION['type']!='') {
                        $sqlUser = "SELECT * FROM user WHERE type='M'";
                        $query = mysqli_query($conn, $sqlUser);
                        ?>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label class="text-black-50 font-weight-bold">เลือกลูกค้า</label>
                                        <input list="userList"
                                               id="username"
                                               name="username"
                                               data-link-field="userList"
                                               class="form-control"
                                               placeholder="พิมพ์รหัสหรือชื่อเพื่อเลือกลูกค้า" required/>
                                        <datalist id="userList">
                                            <?php
                                            while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                                                ?>
                                                <option value="<?php echo $temp['username']; ?>"> <?php echo $temp['username'] . ' : ' . $temp['name']; ?></option>
                                            <?php } ?>
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col-2"><a style="margin-top: 30px;" href="__register.php"
                                                      class="btn btn-block btn-outline-success"><i
                                                class="fa fa-plus-circle"></i> เพิ่มลูกค้า</a></div>
                                <div class="col-3"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-12 text-left text-black-50 font-weight-bold" id="reserveDate">
                        <div class="container">

                            <div class="row text-center">
                                <div class="col-4"><label>ตั้งแต่วันที่ </label>
                                    <input name="startDate" id="startDate"
                                           class="form-control"
                                           placeholder="ยังไม่ได้เลือกวันที่" readonly required></div>
                                <div class="col-4"><label> ถึงวันที่</label>
                                    <input name="endDate" id="endDate"
                                           class="form-control" placeholder="ยังไม่ได้เลือกวันที่" readonly required>
                                </div>
                                <div class="col-4"><label>จำนวนวันรวม </label>
                                    <input name="totalDate" id="totalDate"
                                           class="form-control"
                                           placeholder="ยังไม่ได้เลือกวันที่" readonly required></div>
                                <div class="col-3"><label>ราคาห้อง </label>
                                    <input name="roomCost" id="roomCost"
                                           class="form-control" value="<?php echo $roomCost ?>" readonly required>
                                </div>
                                <div class="col-3"><label>รวมทั้งหมด </label>
                                    <input name="totalCost" id="totalCost"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-3"><label>เงินมัดจำ </label>
                                    <input name="deposit" id="deposit"
                                           class="form-control" placeholder="มากกว่า 50% ของราคาทั้งหมด"
                                           onchange="checkDeposit()" disabled required>
                                </div>
                                <div class="col-3"><label>คงเหลือ </label>
                                    <input name="balance" id="balance"
                                           class="form-control" readonly>
                                </div>
                            </div>

                        </div>
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6">
                                <script>
                                    let isAdmin = '<?php if ($_SESSION['type'] != 'M') echo 'Y'; else echo 'N';?>';

                                    function checkDeposit() {
                                        let deposit = Number($("#deposit").val());
                                        let total = Number($("#totalCost").val());
                                        let balance = total - deposit;
                                        $("#balance").val(balance);
                                        if (deposit < total / 2 || balance < 0) {
                                            $("#confirmBtn").attr("disabled", "disabled");
                                            $("#warningDeposit").html("*กรุณากรอกจำนวนมัดจำให้ถูกต้อง(>50% ของราคาทั้งหมด ไม่มากกว่าจำนวนเงินทั้งหมด)");
                                        } else {
                                            $("#confirmBtn").removeAttr("disabled");
                                            $("#warningDeposit").html("");
                                        }
                                    }

                                    function reserveRoom() {
                                        console.log(isAdmin);
                                        let saveObj = arrayToObject($("#reserveForm").serializeArray())
                                        console.log(saveObj);
                                        if (!saveObj.startDate) {
                                            alert("กรุณาเลือกวันที่ที่จะจอง");
                                        } else if (!saveObj.username && isAdmin == 'Y') {
                                            alert("กรุณาเลือกลูกค้าที่จะจอง");
                                        } else if (!saveObj.deposit) {
                                            alert("กรุณากรอกจำนวนมัดจำ");
                                        }else{
                                            $.post('SQL_Insert/insertRentalDetail.php', saveObj, (r) => {
                                                if (r) {
                                                   /* alert("บันทึกข้อมูลสำเร็จ !")
                                                    location.reload();*/
                                                   window.location = '_reserve.php';
                                                   console.log(r);
                                                }
                                            })
                                        }
                                    }
                                </script>
                                <?php
                                if (isset($_SESSION['username'])) {
                                    ?>
                                    <small class="text-danger" id="warningDeposit"></small>
                                    <button type="button" onclick="reserveRoom()" id="confirmBtn"
                                            class="btn btn-lg btn-block btn-primary"><i
                                                class="fa fa-check-circle"></i>
                                        ยืนยันการจอง
                                    </button>
                                    <?php
                                } else {
                                    ?>
                                    <a class="btn btn-lg btn-block btn-primary" href="__register.php"><i
                                                class="fa fa-user"></i>
                                        กรุณาสมัครสมาชิก
                                    </a>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>

