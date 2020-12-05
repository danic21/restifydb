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
<h1>Welcome to restifydb!</h1>

<?php
if (isset($this->notConfigured) && $this->notConfigured) {
    ?>
    <div class="large bg-danger">
        It appears this is the first time you run restifydb on this machine.
        As the system has not yet been initialised, the following steps need to be executed:
        <ol>
            <li>The administrator password must be changed.
                <?php
                if (isset($this->installationUpdated) && !$this->installationUpdated) {
                    ?>
                    <i class="fa fa-check"></i>
                <?php } else { ?>
                    <a href="changepwd.php">Change password</a>
                <?php } ?>
            </li>
            <li>The installation parameters must be specified.
                <?php
                if (isset($this->installationUpdated) && !$this->installationUpdated) {
                    ?>
                    <a href="installation.php">Installation options</a>
                <?php } ?>
            </li>
            <li>Some data sources should be added.</li>
        </ol>
    </div>
<?php
} else {
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">You are using version <?php echo \restify\Constants::PRODUCT_VERSION; ?></h3>
                </div>
                <div class="panel-body">
                    <p id="version"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Checking for new versions...</p>
                </div>
            </div>
        </div>


        <div class="col-md-5">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Configured Data Sources</h3>
                </div>
                <div class="panel-body">
                    <?php
                    if (count($this->dbs)) {
                        ?>
                        <ul class="list-unstyled">
                            <?php
                            $idx = 0;
                            foreach ($this->dbs as $db) {
                                if ($db['disabled'] != \restify\Constants::PARAM_VALUE_TRUE) {
                                    $url = \restify\utils\HTTPUtils::prepareDbUrl($db['name']);
                                    echo <<<out
                                    <li><i class="fa fa-database fa-fw"></i> <a href="db.php?id={$idx}" title="Configure this data source">{$db['alias']}</a> (<a href="{$url}?_view=xml" target="_blank">xml</a> / <a href="{$url}?_view=json" target="_blank">json</a>)</li>
out;
                                } else {
                                    echo <<<out
                                    <li class="text-danger"><i class="fa fa-database fa-fw"></i> <a href="db.php?id={$idx}" title="Configure this data source">{$db['alias']}</a> [disabled]
out;
                                }
                                $idx++;
                            }
                            ?>

                        </ul>
                    <?php
                    } else {
                        ?>

                        <div class="alert alert-warning" role="alert">
                            There are no available data sources.
                        </div>

                    <?php
                    }
                    ?>

                    <?php
                    if (count($this->dbs)) {
                        ?>
                        <a role="button" class="btn btn-primary pull-right" href="databases.php"
                           title="View all data sources"><i class="fa fa-database fa-fw"></i> View all data sources</a>
                    <?php
                    } else {
                        ?>
                        <a role="button" class="btn btn-primary pull-right" href="db.php"
                           title="Add a new data source"><i class="fa fa-plus fa-fw"></i> Add new data source</a>
                    <?php
                    }
                    ?>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Supported DB Drivers</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <?php
                        foreach ($this->extensions as $extension => $enabled) {
                            if ($enabled) {
                                echo <<<out
                    <li><i class="fa fa-eye fa-fw"></i> {$extension}</li>
out;
                            } else {
                                echo <<<out
                    <li class="text-danger"><i class="fa fa-eye fa-fw"></i> {$extension} [not installed]</li>
out;
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Latest Errors</h3>
                </div>
                <div class="panel-body">

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Token</th>
                            <th>Date and time</th>
                            <th>Error message</th>
                            <th>URL</th>
                            <th>User IP</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($this->errors['rows'] as $e) {
                            $dt = date('Y-m-d H:i:s', $e['ts']);
                            $srv = unserialize($e['server']);
                            $ip = $srv['REMOTE_ADDR'];
                            $req = unserialize($e['processed_request']);
                            $url = $req['url'];
                            echo <<<out
                            <tr>
                                <td>
                                <a href="errors.php?token={$e['token']}#r1_{$e['token']}" title="Click for details">{$e['token']}</a></td>
                                <td>{$dt}</td>
                                <td class="danger">
                                    <pre class="inner">{$e['message']}</pre>
                                </td>
                                <td>
                                <a href="{$url}" target="_blank">{$url}</a>
                                </td>
                                <td>{$ip}</td>
                            </tr>
out;
                        }
                        ?>
                        </tbody>
                    </table>

                    <a role="button" class="btn btn-primary pull-right" href="errors.php" title="View all errors"><i
                            class="fa fa-bug fa-fw"></i> View all errors</a>
                </div>
            </div>
        </div>
    </div>



<?php
}
?>

</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>

<script src="https://restifydb.com/version?current=<?php echo urlencode(\restify\Constants::PRODUCT_VERSION); ?>"></script>
</body>
</html>