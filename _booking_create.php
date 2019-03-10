

<form class="simple-form" action="_booking.php" method="post">
    <div class="container-fluid" style="margin-top: 10px; margin-bottom: 150px;">
        <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb  bg-dark">
                    <h5><i class="fa fa-star"></i> จัดการการจอง</h5>
                </nav>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">

                        <div class="col col-sm col-md col-lg">
                            <div class="form-group">
                                <label class="font-weight-bold">วันที่เริ่มจอง</label>
                                <input id="startDate" class="form-control" name="startDate" required readonly>

                            </div>
                        </div>
                        <div class="col col-sm col-md col-lg">
                            <div class="form-group">
                                <label class="font-weight-bold">วันที่สิ้นสุดการจอง</label>
                                <input id="endDate" class="form-control" name="endDate" onchange="validateDateBooking()"
                                       required readonly>

                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col col-sm col-md col-lg">
                            <div class="form-group">
                                <label class="font-weight-bold">ปีการศึกษา</label>
                                <select class="form-control" id="year" name="year" required>
                                </select>
                            </div>
                        </div>
                        <div class="col col-sm col-md col-lg">
                            <div class="form-group">
                                <label class="font-weight-bold">เทอม</label>
                                <select class="form-control" id="semester" name="semester" required>
                                    <option value="1">ภาคต้น</option>
                                    <option value="2">ภาคปลาย</option>
                                    <option value="3">ฤดูร้อน</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">ประเภทหอพัก</label>
                                <select class="form-control" id="towerType" name="towerType" onchange="setTowerList()"
                                        required>
                                    <option value="M"> หอชาย</option>
                                    <option value="F"> หอหญิง</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="font-weight-bold">หอที่เปิด</label>
                                <select class="form-control" id="towerNo" name="towerNo" onchange=" validateButton()"
                                        required>

                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer" align="center">
                <button class="btn btn-sm btn-outline-success" id="submitBtn" name="insertBooking" style="margin: 10px">
                    <i class="fa fa-check"></i> เปิดการจอง
                </button>
            </div>
        </div>
    </div>
</form>
