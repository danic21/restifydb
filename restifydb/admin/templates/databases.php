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
    <h1>Configured Data Sources</h1>

    <?php include_once('tiles/success.php'); ?>

    <div id="my-alert" class="alert alert-dismissible hidden" role="alert">
        <div id="my-alert-content"></div>
    </div>

    <div class="table-responsive">
        <?php
        if (isset($this->config) && count($this->config)) {
            ?>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Alias</th>
                    <th>Type</th>
                    <th>Database</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $idx = -1;
                foreach ($this->config as $db) {
                    $url = \restify\utils\HTTPUtils::prepareDbUrl($db['name']);
                    $idx++;
                    $cls = $db['disabled'] == \restify\Constants::PARAM_VALUE_TRUE ? ' class="danger" ' : '';
                    echo <<<out
        <tr{$cls}>
            <td>
            <i class="fa fa-fw fa-database"></i>
            {$db['name']} (<a href="{$url}?_view=xml" target="_blank">xml</a> /
            <a href="{$url}?_view=json" target="_blank">json</a>)
            </td>
            <td>{$db['alias']}</td>
            <td>{$db['driver']}</td>
            <td>{$db['database']}</td>
            <td class="text-center">
                <div class="btn-group">
                    <a href="db.php?id={$idx}" role="button" class="btn btn-primary btn-sm" title="Edit data source"><i class="fa fa-edit fa-fw"></i></a>
                    <button type="button" class="btn btn-info btn-sm" title="Refresh data source cache" data-refresh="{$idx}"><i class="fa fa-refresh fa-fw"></i></button>
                    <button type="button" class="btn btn-info btn-sm" title="Check if the connection is valid" data-ping="{$idx}"><i class="fa fa-chain fa-fw"></i></button>
                </div>
            </td>
        </tr>
out;

                }
                ?>
                </tbody>
            </table>
        <?php
        } else {
            ?>
            <p class="large bg-warning">There are no data sources currently configured. You can add a data source by
                using the button below.</p>
        <?php
        }
        ?>


        <div>
            <a href="db.php" class="btn btn-primary" role="button"><i
                    class="fa fa-fw fa-plus fa-lg"></i>
                Add new data source
            </a>
        </div>

    </div>
</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>

<script src="js/databases.js"></script>

</body>
</html>