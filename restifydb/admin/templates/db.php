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
    <?php if (isset($this->id) && $this->id >= 0) { ?>
        <h1>Edit Data Source</h1>
    <?php } else { ?>
        <h1>Add Data Source</h1>
    <?php } ?>

    <ul class="nav nav-tabs" id="tabs">
        <li role="presentation" class="active"><a href="#">General configuration</a></li>
        <?php if (isset($this->id) && $this->id >= 0) { ?>
            <li role="presentation"><a href="tables.php?id=<?php echo $this->id; ?>">Table permissions</a></li>
        <?php } else { ?>
            <li role="presentation" class="disabled"><a href="#">Table permissions</a></li>
        <?php } ?>
    </ul>

    <form class="form-horizontal" role="form" method="post">
        <?php include_once('tiles/errors.php'); ?>

        <div class="form-group">
            <label for="dsname" class="col-sm-2 control-label">Data source context name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="dsname" name="name"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['name'] : ''; ?>">
                <span class="help-block">The data source context name will be used to construct the URL of this data source, thus making it visible to the users.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="alias" class="col-sm-2 control-label">Data source alias</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="alias" name="alias"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['alias'] : ''; ?>">
                <span class="help-block">The alias serves as a title for the data source.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">Data source description</label>

            <div class="col-sm-10">
                <textarea class="form-control" rows="3" name="description"
                          id="description"><?php echo isset($this->bean) ? $this->bean['description'] : ''; ?></textarea>
                <span class="help-block">The detailed description of this data source.</span>
            </div>
        </div>


        <div class="form-group">
            <label for="driver" class="col-sm-2 control-label">Data source type and driver</label>

            <div class="col-sm-10">
                <select class="form-control" id="driver" name="driver">
                    <option value="">Please select a type...</option>
                    <?php
                    foreach ($this->drivers as $driver => $desc) {
                        if (isset($this->bean) && $driver == $this->bean['driver']) {
                            echo <<<out
                    <option value="{$driver}" selected="true">$desc</option>
out;
                        } else {
                            echo <<<out
                    <option value="{$driver}">$desc</option>
out;
                        }
                    }
                    ?>
                </select>

                <span class="help-block">The type of the data source and the associated PHP extension. Supported drivers which correspond
                    to unavailable extensions are not being shown here. Please read the <a href="https://restifydb.com/api/documentation">documentation</a> for further details.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="host" class="col-sm-2 control-label">Host name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="host" name="hostname"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['hostname'] : ''; ?>">
                <span class="help-block">The IP or host name of the database server.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="port" class="col-sm-2 control-label">Port</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="port" name="port"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['port'] : ''; ?>">
                <span class="help-block">The port the database server accepts connections.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="dbname" class="col-sm-2 control-label">Database name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="dbname" name="database"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['database'] : ''; ?>">
                <span class="help-block">The name of the database.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="username" class="col-sm-2 control-label">User name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="" value="<?php echo isset($this->bean) ? $this->bean['username'] : ''; ?>">
                <span class="help-block">The user name for connecting to the database.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="userpassword" class="col-sm-2 control-label">Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control" id="userpassword" name="password"
                       placeholder="" autocomplete="off"
                       value="<?php echo isset($this->bean) ? $this->bean['password'] : ''; ?>">
                <span class="help-block">The user's password.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="isdisabled" class="col-sm-2 control-label">Disabled</label>

            <div class="col-sm-10">
                <input type="checkbox" id="isdisabled"
                       name="disabled" <?php if (isset($this->bean['disabled']) && $this->bean['disabled'] == \restify\Constants::PARAM_VALUE_TRUE) echo ' checked="checked "'; ?>>
                <span class="help-block">If the data source is disabled, it will not be available to the users connecting to the REST web-services.</span>
            </div>
        </div>

        <?php include_once('tiles/dbbuttons.php'); ?>
    </form>

</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>

<script src="js/db.js"></script>

</body>
</html>