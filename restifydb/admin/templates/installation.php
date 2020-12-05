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
    <h1>Installation Options</h1>

    <form class="form-horizontal" role="form" method="post" action="installation.php">
        <?php include_once('tiles/success.php'); ?>
        <?php include_once('tiles/errors.php'); ?>

        <div class="form-group">
            <label for="baseUrl" class="col-sm-2 control-label">Base URL</label>

            <div class="col-sm-10">
                <input type="url" class="form-control" id="baseUrl" name="base_url"
                       placeholder=""
                       value="<?php echo isset($this->bean) ? $this->bean['base_url'] : ''; ?>">
                <span class="help-block">This should be the absolute URL where restifydb is installed.</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" name="saveChanges"><i class="fa fa-check fa-fw"></i> Save
                    installation settings
                </button>
                &nbsp;
                <a role="button" class="btn btn-primary pull-right" href="installation.php" name="discardChanges"><i
                        class="fa fa-ban fa-fw"></i> Reset form</a>
            </div>
        </div>
    </form>
</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>
</body>
</html>