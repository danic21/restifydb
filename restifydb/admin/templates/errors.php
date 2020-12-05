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
    <h1>Error Log
        <small>(<?php echo $this->errors['count']; ?> errors in total)</small>
    </h1>

    <div class="row">
        <div class="col-lg-7">
            <form class="form" role="form" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" id="token" name="token"
                           placeholder="Search by error token code" value="<?php echo $this->token; ?>">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary" title="Search errors by token"><i
                                class="fa fa-search"></i></button>
                        <a role="button" class="btn btn-danger" href="errors.php" title="Clear search field"><i
                                class="fa fa-close"></i></a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <?php
    if (isset($this->errors['rows']) && count($this->errors['rows'])) {
        ?>

        <div class="table-responsive" id="results">

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
                    $srv = print_r($srv, true);
                    $stack = $e['exception'];
                    $req = unserialize($e['processed_request']);
                    $preq = print_r($req, true);
                    $url = $req['url'];
                    $style = ($this->token) ? '' : 'style="display:none;"';
                    echo <<<out
                    <tr>
                        <td>
                        <i class="fa fa-plus-square fa-fw"></i>
                        <a href="#r1_{$e['token']}" onclick="$('#r1_{$e['token']}').toggle();" title="Click for details">{$e['token']}</a></td>
                        <td>{$dt}</td>
                        <td class="danger">
                            <pre class="inner">{$e['message']}</pre>
                        </td>
                        <td>
                        <a href="{$url}" target="_blank">{$url}</a>
                        </td>
                        <td>{$ip}</td>
                    </tr>
                    <tr class="danger" {$style} id="r1_{$e['token']}">
                        <td colspan="5">
                        <strong>Error class name</strong>
                        <pre>{$e['class_name']}</pre>

                        <strong>Stack trace</strong>
                        <pre>{$stack}</pre>

                        <strong>Server variables</strong>
                        <pre>{$srv}</pre>

                        <strong>Processed request</strong>
                        <pre>{$preq}</pre>
                        </td>
                    </tr>
out;

                }
                ?>
                </tbody>
            </table>

        </div>

        <ul class="pager">
            <?php
            if (isset($this->prev)) {
                echo <<<out
                <li class="previous"><a href="errors.php?{$this->prev}">&larr; Older</a></li>
out;

            } else {
                echo <<<out
                <li class="previous disabled"><a href="#">&larr; Older</a></li>
out;
            }
            ?>

            <?php
            if (isset($this->next)) {
                echo <<<out
                <li class="next"><a href="errors.php?{$this->next}">Newer &rarr;</a></li>
out;

            } else {
                echo <<<out
                <li class="next disabled"><a href="#">Newer &rarr;</a></li>
out;
            }
            ?>
        </ul>

    <?php
    } else if ($this->token) {
        ?>
        <br>
        <div class="alert alert-danger" role="alert">
            The error message with the specified token does not exist. Please clear the search field in order to see all the errors.
        </div>
    <?php
    } else {
        ?>
        <br>
        <div class="alert alert-success" role="alert">
            Great! The error log is empty.
        </div>
    <?php
    }
    ?>

</div>

<?php include_once('tiles/footer.php'); ?>
<?php include_once('tiles/foot.php'); ?>
</body>
</html>