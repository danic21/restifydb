<?php
/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb demos.
 *
 * @license https://restifydb.com/#license
 *
 */
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fa fa-bug fa-fw"></i>
            <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne2">
                Debug information
            </a>
        </h4>
    </div>
    <div id="collapseOne2" class="panel-collapse collapse">
        <div class="panel-body">
            Processing took <?php echo $this->duration; ?> seconds.
            <br>
            Requested URL: <a href="<?php echo $this->url; ?>" target="_blank"><?php echo $this->url; ?></a>.
            The server response was:
            <div>
                <pre id="output"><?php echo $this->result; ?></pre>
            </div>

        </div>
    </div>
</div>

