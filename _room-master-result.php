<?php


$query = mysqli_query($conn, $sql);
?>
<script>
</script>
<div class="card">
    <div class="card-header font-weight-bold">
        ช้อมูลห้องพักทั้งหมด
    </div>
    <div class="card-body">
        <div class="row">
            <?php
            $count = 0;
            while ($temp = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                $count++;
                ?>
                <div class="col-6" style="margin-bottom: 10px;">
                    <div class="card">
                        <div class="card-body" style="background: #cce5ff;">
                            <div class="row">
                                <div class="col-8">
                                    <a class="btn-block btn btn-info"
                                       href="_room-master.php?roomDetail=1&roomNo=<?php echo $temp['room_id']; ?>"><i
                                                class="fa fa-edit"></i>
                                        แก้ไขข้อมูล
                                    </a>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($temp['img']); ?>"
                                         style="width: 100%;">
                                    <button class="btn-block btn btn-primary" data-toggle="modal" data-target="#imageModal"><i class="fa fa-image"></i>
                                        ดูรูปภาพเพิ่มเติม
                                    </button>
                                </div>
                                <div class="col-4">
                                    <div class="card">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><i class="fa fa-users"></i> <span
                                                        class="font-italic text-right"> <?php echo $temp['name']; ?></span>
                                            </li>
                                            <li class="list-group-item"><i class="fa fa-bitcoin"></i><span
                                                        class="font-italic text-right"> <?php echo $temp['cost']; ?>
                                                    บาท</span></li>
                                            <li class="list-group-item"><i class="fa fa-bed"></i> <span
                                                        class="font-italic text-right"> <?php echo $temp['status_desc']; ?> </span>
                                            </li>
                                            <li class="list-group-item">
                                                <a href="_calendar.php?selectedRoom=<?php echo $temp['room_id']; ?>" class="btn btn-sm btn-block btn-outline-primary"><i
                                                            class="fa fa-bookmark" ></i> จอง
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            if ($count == 0) {
                ?>
                <div align="center">
                    <span class=" text-danger"> <strong><i class="fa fa-exclamation-triangle"></i> ไม่พบรายการที่ต้องการ</strong></span>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="imageModal"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">รูปภาพห้อง 111</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="demo" class="carousel slide" data-ride="carousel">
                    <ul class="carousel-indicators">
                        <li data-target="#demo" data-slide-to="0" class="active"></li>
                        <li data-target="#demo" data-slide-to="1"></li>
                        <li data-target="#demo" data-slide-to="2"></li>
                    </ul>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="la.jpg" alt="Los Angeles" width="1100" height="500">
                            <div class="carousel-caption">
                                <h3>Los Angeles</h3>
                                <p>We had such a great time in LA!</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="chicago.jpg" alt="Chicago" width="1100" height="500">
                            <div class="carousel-caption">
                                <h3>Chicago</h3>
                                <p>Thank you, Chicago!</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="ny.jpg" alt="New York" width="1100" height="500">
                            <div class="carousel-caption">
                                <h3>New York</h3>
                                <p>We love the Big Apple!</p>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>