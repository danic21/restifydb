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

    <title>restifydb Configuration Panel</title>
</head>
<body>

<?php include_once('tiles/header.php'); ?>

<div class="container">
    <h1>Change the Administrator Password</h1>

    <form class="form-horizontal" role="form" method="post" action="changepwd.php">
        <?php include_once('tiles/errors.php'); ?>

        <div class="form-group">
            <label for="currentPwd" class="col-sm-2 control-label">Current Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" id="currentPwd" name="current_pwd"
                       placeholder="" value="">
                <span class="help-block">This is the currently defined user password. If the system has not been yet initialised, the default password is &quot;admin&quot;.</span>
            </div>
        </div>
        <div class="form-group">
            <label for="newPwd" class="col-sm-2 control-label">New Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" id="newPwd" name="new_pwd" placeholder=""
                       value="">
                <span class="help-block">The new administrator password.</span>
            </div>
        </div>
        <div class="form-group">
            <label for="pwdConfirmation" class="col-sm-2 control-label">Retype the New Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" id="pwdConfirmation" name="pwd_confirmation" placeholder=""
                       value="">
                <span class="help-block">The password confirmation. This should be identical to the one in the &quot;New Password&quot; field (above).</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" name="saveChanges"><i class="fa fa-check fa-fw"></i> Change the password</button>
                &nbsp;
                <a role="button" class="btn btn-primary pull-right" href="changepwd.php" name="discardChanges"><i class="fa fa-ban fa-fw"></i> Reset form</a>
            </div>
        </div>
    </form>

</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>
</body>
</html>