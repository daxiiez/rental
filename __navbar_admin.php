<style>
    .bg-rose {
        background-image: url("img/bg.jpg");
    }
</style>
<div class="jumbotron text-center bg-rose" style="margin-bottom:0; ">
    <h1><img src="img/rose.png" width="400px;"></h1>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary ">
    <a class="navbar-brand" href="index.php">Rose Resort</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="navbar-collapse collapse">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item">
                <a class="nav-link" href="_room-master.php">ห้องพัก</a>
            </li>
            <?php
            if (isset($_SESSION['type'])) {
                if ($_SESSION['type'] == 'A') {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="_reserve_list.php">รายการจอง</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="_information.php">ประชาสัมพันธ์</a>
                    </li>

                <?php }
            } ?>
        </ul>
        <ul class="navbar-nav">
            <?php
            if (isset($_SESSION['username'])) {
                ?>
                <li class="nav-item dropdown" style="cursor: pointer;">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <!--img-->
                        <?php
                        echo $_SESSION['username'];
                        ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php
                        if (($_SESSION['reserveStatus'] == 'N' || $_SESSION['reserveStatus'] == 'W') && $_SESSION['type']=='M') {
                            ?>
                            <a class="dropdown-item" href="_reserve.php">
                                <i class="fa fa-exclamation-triangle"></i> ชำระรายการจอง</a>
                            <?php
                        }elseif( $_SESSION['reserveStatus'] == 'S'){
                            ?>
                            <a class="dropdown-item" href="_reserve.php">
                                <i class="fa fa-list"></i> รายละเอียดการเข้าพัก</a>
                            <?php
                        }
                        ?>
                        <a class="dropdown-item" href="__register.php?viewProfile=1">
                            <i class="fa fa-user-circle-o"></i> Profile</a>
                    </div>
                </li>
                <?php
            } ?>
            <?php
            if (isset($_SESSION['username'])) {
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="__logout.php">ออกจากระบบ</a>
                </li>
                <?php
            } else {
                ?>
                <li class="nav-item">
                    <div class="btn-group">
                        <a class="nav-link btn btn-primary" href="__login.php">เข้าสู่ระบบ</a>
                        <a class="nav-link btn btn-info" href="__register.php">สมัครสมาชิก</a>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</nav>
<script>
    function arrayToObject(arr) {
        let obj = {};
        arr.forEach(m => {
            obj[m.name] = m.value
        });
        return obj
    }
</script>

