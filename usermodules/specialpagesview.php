<?php
if (!defined('included')) {
    exit();
}
if (!empty($_GET['type'])) {

    $type = abs($_GET['type']);
    ?>
    <div class="contact_us padd-7">
        <div class="container">
            <div class="row">
                <div class="col-md-12" id="pagecontent">
                    <?php
                    echo $dbf->GetOneVal("SELECT details FROM $ai_specialpage where pagetype='$type'");
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>