<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb framework.
 *
 * @license https://restifydb.com/#license
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('tiles/head.php'); ?>

    <link rel="stylesheet" href="css/login.css">

    <title>restifydb Configuration Panel</title>
</head>
<body>

<div class="container" id="login">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Login to restifydb</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="post" action="login.php">
                        <?php include_once('tiles/success.php'); ?>
                        <?php include_once('tiles/errors.php'); ?>

                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary" name="login"><i class="fa fa-sign-in fa-fw"></i> Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('tiles/foot.php'); ?>
</body>
</html>