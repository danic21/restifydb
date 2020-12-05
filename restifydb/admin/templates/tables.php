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
    <h1>Edit Data Source</h1>

    <ul class="nav nav-tabs" id="tabs">
        <li role="presentation"><a href="db.php?id=<?php echo $this->id; ?>">General configuration</a></li>
        <li role="presentation" class="active"><a href="#">Table permissions</a></li>
    </ul>

    <form class="form-horizontal" role="form" method="post">
        <span class="help-block">This dialog allows you to specify which data tables are considered disabled.
            Disabled data tables will not be accessible for connecting clients. Please tick the checkbox associated with a table name in order to disable it.
            <strong>Please beware:</strong> If tables are disabled then inward and outward references will not work for disabled tables.
            Please read the <a href="https://restifydb.com/api/documentation">documentation</a> for further details.</span>

        <?php include_once('tiles/errors.php'); ?>

        <?php
        if (!count($this->tables) && isset($this->notCached)) {
            echo <<<out
                    <div class="large bg-warning">
                        The structure of this data source has not been created yet. Please regenerate the data source cache from the <a href="databases.php" title="data sources">data sources view</a>.
                    </div>
out;
        } else if (!count($this->tables)) {
            echo <<<out
                    <div class="large bg-danger">
                        It seems this data source does not contain any data tables.
                    </div>
out;
        }
        ?>

        <div class="row">
            <?php
            $idx = 0;
            foreach ($this->tables as $tbl) {
                echo <<<out
            <div class="col-sm-6 col-md-4">
            <label class="checkbox-inline">
out;
                if (in_array($tbl, $this->bean['disabledTables'])) {
                    echo <<<out
                <input type="checkbox" name="tables[]" id="tbl{$idx}" value="{$tbl}" checked> {$tbl}
out;
                } else {
                    echo <<<out
                <input type="checkbox" name="tables[]" id="tbl{$idx}" value="{$tbl}"> {$tbl}
out;
                }
                echo <<<out
            </label>
            </div>
out;
                $idx++;
            }
            ?>
        </div>

        <?php include_once('tiles/dbbuttons.php'); ?>
    </form>

</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>

<script src="js/db.js"></script>

</body>
</html>