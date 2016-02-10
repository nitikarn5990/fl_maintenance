<?php if ($_SESSION['group'] == '') { ?>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 10px;">
            <p>&nbsp;</p>
            <img src="./dist/images/404.png" class="img-responsive" style="margin: auto;">
        </div>
    </div>
    <?php
    die();
}
?>