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

<div class="container-fluid wrapper">
    <?php include(dirname(__FILE__) . '/tiles/header.php'); ?>

    <h1>restifydb API Browser</h1>

    <div class="row" id="search">
        <div class="col-lg-7">
            <form class="form" role="form" method="post">
                <div class="input-group">
                    <input type="text" class="form-control" id="url" name="url"
                           placeholder="Enter restifydb URL, must start with https://restifydb.com/api" value="">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary" title="Process URL"><i
                                class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title">
                <i class="fa fa-bug fa-fw"></i>
                <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne2">
                    Debug information
                </a>
            </h4>
        </div>
        <div id="collapseOne2" class="panel-collapse">
            <div class="panel-body">

                Processing took <?php echo $this->duration; ?> seconds.
                <br>
                Requested URL: <a href="<?php echo $this->url; ?>" target="_blank"><?php echo $this->url; ?></a>.
                The server response was:
                <div>
                    <pre id="output"><?php echo $this->result; ?></pre>
                </div>

            </div>
        </div>
    </div>


    <div class="push"></div>
</div>

<?php include(dirname(__FILE__) . '/tiles/footer.php'); ?>
<?php include(dirname(__FILE__) . '/tiles/scripts.php'); ?>
</body>
</html>