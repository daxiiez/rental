<div class="jumbotron text-center" style="margin-bottom:0">
    <h1>Real Home Rental</h1>
    <p>Resize this responsive page to see the effect!</p>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary ">
    <a class="navbar-brand" href="index.php">RealHome Rental</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="navbar-collapse collapse">
        <ul class="navbar-nav mr-auto">

                <li class="nav-item">
                    <a class="nav-link" href="_room-master.php">ห้องพัก</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_reserve.php">ค้นหาหอพัก</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="_information.php">ประชาสัมพันธ์</a>
                </li>

        </ul>
        <ul class="navbar-nav">

            <li class="nav-item dropdown" style="cursor: pointer;">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <!--img-->
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">


                        <a class="dropdown-item">
                            <i class="fa fa-user-circle-o"></i> Profile</a>
                            <a class="dropdown-item" data-toggle="modal" onclick="initReportModal()" data-target="#reportModal"><i
                                        class="fa fa-exclamation-triangle"></i> แจ้งปัญหา</a>
                        <a class="dropdown-item">
                            <i class="fa fa-user-circle-o"></i> Profile</a>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="__logout.php">Logout</a>
            </li>

        </ul>
    </div>
</nav>


