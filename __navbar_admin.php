<div class="jumbotron text-center" style="margin-bottom:0">
    <h1>Rose Resort</h1>
    <p>ติดต่อ : 084-44514725</p>
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

