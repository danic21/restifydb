<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'templates/tiles/head.php'; ?>
    <title>restifydb - Documentation</title>
</head>
<body data-spy="scroll" data-target="#sidebar">

<?php include_once 'templates/tiles/header.php'; ?>

<div class="container">
    <div class="row">

        <div class="col-md-3 hidden-xs hidden-sm">
            <?php include_once 'templates/tiles/sidebar.php'; ?>
        </div>

        <div class="col-md-9">
            <?php include_once 'templates/intro.php'; ?>
            <?php include_once 'templates/setup.php'; ?>
            <?php include_once 'templates/crud.php'; ?>
            <?php include_once 'templates/read.php'; ?>
            <?php include_once 'templates/write.php'; ?>
            <?php include_once 'templates/errors.php'; ?>
            <?php include_once 'templates/design.php'; ?>
        </div>
    </div>
</div>

<?php include_once 'templates/tiles/scripts.php'; ?>

</body>
</html>