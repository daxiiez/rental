<?php
include '__connect.php';
//include '__checkSession.php';
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
if (isset($_GET['selectedRoom'])) {
    $selectedRoom = $_GET['selectedRoom'];
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

    function selectedDate(index, month, maxDate) {
        let id = "#selected" + index + "m" + month;
        let indexMonth = 9;
        let indexPreviousMonth = 9;
        let indexNextMonth = 9;

        selectedIndex.forEach((f, i) => {
            if (f.month == month) {
                indexMonth = i;
            }
            if (f.month < month) {
                indexPreviousMonth = i;
            }
            if (f.month > month) {
                indexNextMonth = i;
            }
        });

        if (indexMonth != 9) {
            let allowPush = true;
            if (selectedIndex[indexMonth].date.includes(index)) {
                allowPush = false;
                selectedIndex[indexMonth].date.forEach((f, i) => {
                    if (f >= index) {
                        $("#selected" + f + "m" + selectedIndex[indexMonth].month).toggleClass("btn-success btn-outline-success")
                    }
                })
                selectedIndex[indexMonth].date = selectedIndex[indexMonth].date.filter(f => f < index)
                if (indexNextMonth != 9) {
                    selectedIndex[indexNextMonth].date.forEach(f => {
                        $("#selected" + f + "m" + selectedIndex[indexNextMonth].month).toggleClass("btn-success btn-outline-success")
                    })
                    selectedIndex.splice(indexNextMonth, 1);
                }
                if (selectedIndex[indexMonth].date == 0) {
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
                $(id).toggleClass("btn-outline-success btn-success")
            }
        } else {
            let valid = true;
            if (indexPreviousMonth != 9) {
                if (selectedIndex[indexPreviousMonth].date.includes(selectedIndex[indexPreviousMonth].lastDate)) {
                    valid = (index == 1);
                } else {
                    valid = false;
                }
                if (!valid) {
                    alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
                }
            } else if (indexNextMonth != 9) {
                if (selectedIndex[indexNextMonth].date.includes(1)) {
                    console.log();
                    selectedIndex[indexMonth]
                    valid = index == maxDate;
                } else {
                    valid = false;
                }
                if (!valid) {
                    alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
                }
            }
            if (valid) {
                selectedIndex.push({month: Number(month), lastDate: Number(maxDate), date: [index]});
                selectedIndex.sort((a, b) => (a.month > b.month) ? 1 : -1)
                $(id).toggleClass("btn-outline-success btn-success")
            }

        }
        /* if (selectedIndex.includes(index)) {
             selectedIndex.forEach((f) => {
                 if (f >= index) {
                     $("#selected" + f).toggleClass("btn-success btn-outline-success")
                 }
                 selectedIndex = selectedIndex.filter(f => f < index);
             })
         } else {
             let valid = false;
             if (selectedIndex.length == 0) {
                 valid = true;
             } else {
                 selectedIndex.forEach(f => {
                     let diff = f - index;
                     if ([1, -1].includes(diff)) {
                         valid = true;
                     }
                 })
             }
             if (!valid) {
                 alert("คุณเลือกวันจองไม่ถูกต้อง กรุณาเลือกวันที่ต่อเนื่อง")
             } else {

                 selectedIndex.push(index);
                 $("#selected" + index).toggleClass("btn-outline-success btn-success")
             }
         }*/
        console.log(selectedIndex);
    }
</script>
<button class="btn btn-primary" data-toggle="modal" data-target="#imageModal">
    Large modal
</button>
<div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <?php
                for ($ind = 0; $ind < 12; $ind++) {
                    $notValidMonth = ((intval($ind + 1) < intval($_SESSION['currentMonth'])) && $selectedYear==$_SESSION['currentYear']);
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
        </div>
        <div class="card-body">
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
                                            $notValidDate = ($i<$_SESSION['currentDate']&&$month==$_SESSION['currentMonth']&&$year==$_SESSION['currentYear'])
                                            || ($month<$_SESSION['currentMonth']&&$year==$_SESSION['currentYear'])
                                            || $year<$_SESSION['currentYear'];
/*$year==$_SESSION['currentYear']
                                                &&($month<$_SESSION['currentMonth'] &&$year==$_SESSION['currentYear'])
                                                &&*/
                                            if ($day == $j) {
                                                ?>

                                                <td class="border-bottom">
                                                    <button
                                                            id="selected<?php echo $i; ?>m<?php echo $month; ?>"
                                                            onclick="selectedDate(<?php echo $i; ?>,'<?php echo $month; ?>','<?php echo $totalDate; ?>')"
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
                                            } elseif ($i == 1) {
                                                echo '<td></td>';
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
        <div class="footer bg-warning text-white">
            <button class="btn btn-block btn-primary"><i class="fa fa-check-circle"></i> ยืนยันการจอง</button>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="imageModal"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

</body>
</html>

