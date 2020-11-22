<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 * @version 1.1
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
    <h1>General Options</h1>

    <form class="form-horizontal" role="form" method="post" action="options.php">
        <?php include_once('tiles/success.php'); ?>
        <?php include_once('tiles/errors.php'); ?>

        <div class="form-group">
            <label for="maxOutputSize" class="col-sm-2 control-label">Maximum output field size (in characters)</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="maxOutputSize" name="max_output_value_size"
                       placeholder=""
                       value="<?php if (isset($this->bean)) echo $this->bean['max_output_value_size']; ?>">
                <span class="help-block">You can decide how much information should be presented to the user. When dealing with large fields (TEXT, LOB), it is recommended that the output value should be truncated.</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Disable operations</label>

            <div class="col-sm-10">
                <label class="checkbox-inline">
                    <input type="checkbox"
                           name="disableRead" <?php if (isset($this->bean) && $this->bean['disable_read'] == \restify\Constants::PARAM_VALUE_TRUE) echo 'checked="true"' ?>>
                    Read
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox"
                           name="disableCreate" <?php if (isset($this->bean) && $this->bean['disable_create'] == \restify\Constants::PARAM_VALUE_TRUE) echo 'checked="true"' ?>>
                    Create
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox"
                           name="disableUpdate" <?php if (isset($this->bean) && $this->bean['disable_update'] == \restify\Constants::PARAM_VALUE_TRUE) echo 'checked="true"' ?>>
                    Update
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox"
                           name="disableDelete" <?php if (isset($this->bean) && $this->bean['disable_delete'] == \restify\Constants::PARAM_VALUE_TRUE) echo 'checked="true"' ?>>
                    Delete
                </label>
                <span class="help-block">Allows you to specify which operations are disabled for the clients connecting to the services.</span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" name="saveChanges"><i class="fa fa-check fa-fw"></i> Save
                    general settings
                </button>
                &nbsp;
                <a role="button" class="btn btn-primary pull-right" href="options.php" name="discardChanges"><i
                        class="fa fa-ban fa-fw"></i> Reset form</a>
            </div>
        </div>
    </form>
</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>
</body>
</html>