<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 * @version 1.1
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb demos.
 *
 * @license https://restifydb.com/#license
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include(dirname(__FILE__) . '/tiles/head.php'); ?>
</head>
<body>

<?php include(dirname(__FILE__) . '/tiles/header.php'); ?>

<div class="container-fluid wrapper">
    <h1>Configured Data Sources</h1>

    <ol class="breadcrumb">
        <li><a href="./">Home</a></li>
        <li class="active">Data Sources</li>
    </ol>

    <div class="panel-group" id="accordion1">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-fw fa-reorder"></i>
                    <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne4">
                        Results
                    </a>
                </h4>
            </div>
            <div id="collapseOne4" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <?php
                        foreach ($this->databases as $tbl => $data) {
                            echo <<<out
                    <div class="col-sm-6 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <i class="fa fa-fw fa-database fa-lg"></i> <a href="{$data->internalHref}">{$data->values->alias->value}</a>
                            </div>
                        </div>
                    </div>
out;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include(dirname(__FILE__) . '/tiles/debug.php'); ?>

    </div>
    <div class="push"></div>
</div>

<?php include(dirname(__FILE__) . '/tiles/footer.php'); ?>
<?php include(dirname(__FILE__) . '/tiles/scripts.php'); ?>
</body>
</html>