<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
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

    <h1>An Exception Has Occurred</h1>

    <ol class="breadcrumb">
        <li><a href="./">Home</a></li>
        <li class="active">Error</li>
    </ol>

    <div class="alert alert-danger">
        There was an error connecting to the restifydb server.
    </div>
    <div class="push"></div>
</div>

<?php include(dirname(__FILE__) . '/tiles/footer.php'); ?>
<?php include(dirname(__FILE__) . '/tiles/scripts.php'); ?>
</body>
</html>