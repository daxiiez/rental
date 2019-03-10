<nav class="navbar navbar-expand-lg navbar-dark bg-success " style="margin-top: 25px;">
    <a class="navbar-brand" href="index.php">KU Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="navbar-collapse collapse">
        <ul class="navbar-nav mr-auto">
            <?php
            if ($_SESSION['userType'] == 'A' || $_SESSION['userType'] == 'N') {
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="_tower.php">ข้อมูลหอพัก</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_reserve.php">ค้นหาหอพัก</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_information.php">ประชาสัมพันธ์</a>
                </li>

                <?php
            }
            ?>
            <?php
            if ($_SESSION['userType'] == 'A') {
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="_nisit.php">รายชื่อนิสิต</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_booking.php">เปิดการจอง</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_manage_room.php">จัดการหอพัก</a>
                </li>
                <?php
            }
            ?>

            <?php
            if ($_SESSION['userType'] == 'A' || $_SESSION['userType'] == 'S' ) {
            ?>

            <li class="nav-item">
                <a class="nav-link" href="_rules.php">กฎเกณฑ์และข้อบังคับ</a>
            </li>

                <?php
            }
            ?>

        </ul>
        <ul class="navbar-nav">

            <li class="nav-item dropdown" style="cursor: pointer;">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <?php
                    if ($_SESSION['type'] == 'N') echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['picture']) . '" width="25"/>';
                    echo $_SESSION['username'];
                    ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                    <?php
                    if ($_SESSION['book_status'] == 'Y') {
                        ?>
                        <a class="dropdown-item" data-toggle="modal" data-target="#bookDetailModal">
                            <i class="fa fa-print"></i> ดูรายละเอียดการจอง</a>
                        <?php
                    }
                    ?>


                    <?php
                    if ($_SESSION['type'] == 'Y') {
                        ?>
                        <a class="dropdown-item" onclick="initProfileModal()" data-toggle="modal"
                           data-target="#profileModal">
                            <i class="fa fa-user-circle-o"></i> Profile</a>
                        <?php
                    } else {
                        ?>
                        <?php if ($_SESSION['book_status'] == 'Y') { ?>
                            <a class="dropdown-item" data-toggle="modal" onclick="initReportModal()" data-target="#reportModal"><i
                                        class="fa fa-exclamation-triangle"></i> แจ้งปัญหา</a>

                        <?php } ?>
                        <a class="dropdown-item" href="_nisit_insert.php?code=<?php echo $_SESSION['code']; ?>">
                            <i class="fa fa-user-circle-o"></i> Profile</a>

                        <?php
                    } ?>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="__logout.php">Logout</a>
            </li>

        </ul>
    </div>
</nav>

<nav class="fixed-bottom navbar navbar-expand-lg text-white navbar-light bg-success d-flex justify-content-center"
     style="margin-bottom: 10px;">
    <p class="lead">
    <center>
        KU Home
    </center>
    </p>
</nav>


<?php if ($_SESSION['userType'] == 'N') include '__report_modal.php'; ?>

<?php if ($_SESSION['type']) include '__profile_modal.php'; ?>

<?php if ($_SESSION['book_status'] == 'Y') include '__book_detail_modal.php'; ?>


