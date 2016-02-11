<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">

            <?php if ($_SESSION['group'] == 'ผู้ดูแลระบบ' || $_SESSION['group'] == 'เจ้าหน้าที่') { ?>

                <li class="">
                    <a href="<?= ADDRESS ?>category" class="<?= substr($_GET['controllers'], 0, 8) == 'category' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> ข้อมูลประเภทคอมพิวเตอร์</a>
                </li>
                <li>
                    <a href="<?= ADDRESS ?>computer" class="<?= substr($_GET['controllers'], 0, 8) == 'computer' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> ข้อมูลคอมพิวเตอร์</a>
                </li>
                <li>
                    <a href="<?= ADDRESS ?>repair" class="<?= substr($_GET['controllers'], 0, 6) == 'repair' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> แจ้งซ่อม</a>
                </li>
            <?php } ?>
            <?php if ($_SESSION['group'] == 'ผู้ดูแลระบบ') { ?>
                <li >
                    <a href="<?= ADDRESS ?>staff" class="<?= substr($_GET['controllers'], 0, 5) == 'staff' ? 'active' : '' ?>"><i class="fa fa-table fa-fw "></i> ข้อมูลเจ้าหน้าที่</a>
                </li>
            <?php } ?>
            <?php if ($_SESSION['group'] == 'ผู้ดูแลระบบ' || $_SESSION['group'] == 'เจ้าหน้าที่' || $_SESSION['group'] == 'ผู้บริหาร') { ?>
                <li>
                    <a href="<?= ADDRESS ?>report_type" class="<?= substr($_GET['controllers'], 0, 6) == 'report' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> รายงาน</a>
                </li>
            <?php } ?>
            <?php if ($_SESSION['group'] == '') { ?>
                <li>
                    <a href="<?= ADDRESS ?>repair_add_user" class="<?= substr($_GET['controllers'], 0, 15) == 'repair_add_user' || substr($_GET['controllers'], 0, 15) == '' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> แจ้งซ่อม</a>
                </li>
                <li>
                    <a href="<?= ADDRESS ?>repair_user" class="<?= substr($_GET['controllers'], 0, 11) == 'repair_user' ? 'active' : '' ?>"> <i class="fa fa-table fa-fw"></i> ติดตามปัญหาที่แจ้ง	</a>
                </li>

            <?php } ?>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
