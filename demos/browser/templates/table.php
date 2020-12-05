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

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include(dirname(__FILE__) . '/tiles/head.php'); ?>
</head>
<body>

<?php include(dirname(__FILE__) . '/tiles/header.php'); ?>

<div class="container-fluid wrapper">
<h1><?php echo Utils::prepareName($this->response->self->name); ?> Data</h1>

<ol class="breadcrumb">
    <li><a href="./">Home</a></li>
    <li><a href="./">Data Sources</a></li>
    <li>
        <a href="<?php echo $this->response->parent->internalHref; ?>"><?php echo $this->response->parent->name; ?></a>
    </li>
    <li class="active"><?php echo Utils::prepareName($this->response->self->name); ?></li>
</ol>


<?php
$columns = array();
$enabledColumnsCount = 0;
foreach (explode(',', $this->response->ownFields) as $f) {
    $columns[$f] = array(
        'name' => Utils::prepareName($f),
        'enabled' => ((isset($this->bean['columns']) && in_array($f, $this->bean['columns'])) || (!isset($this->bean['columns'])))
    );
    if ((isset($this->bean['columns']) && in_array($f, $this->bean['columns'])) || (!isset($this->bean['columns']))) {
        $enabledColumnsCount++;
    }
}
$joinedColumns = array();
if (isset($this->response->foreignFields)) {
    foreach (get_object_vars($this->response->foreignFields) as $k => $v) {
        foreach (get_object_vars($v) as $k1 => $v1) {
            $joinedColumns[$k][$k1] = explode(',', $v1);
        }
    }
}
?>

<div class="panel-group" id="accordion1">
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fa fa-cogs fa-fw"></i>
            <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne1">
                Data Options
            </a>
        </h4>
    </div>
    <div id="collapseOne1" class="panel-collapse collapse<?php if (isset($this->bean)) {
        echo ' in ';
    } ?>">
        <div class="panel-body">

            <form class="form-horizontal" role="form" method="post"
                  action="table.php?url=<?php echo $this->originalUrl; ?>">
                <input type="hidden" name="db" value="<?php echo $this->response->parent->name; ?>">
                <input type="hidden" name="tbl" value="<?php echo $this->response->self->name; ?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Sort by</label>

                            <div class="col-sm-7">
                                <select class="form-control" name="sortBy">
                                    <option value="">Please select...</option>
                                    <?php
                                    $sort = '';
                                    if (isset($this->bean) && isset($this->bean['sortBy'])) {
                                        $sort = $this->bean['sortBy'];
                                    }
                                    foreach ($columns as $c => $n) {
                                        if ($sort != $c) {
                                            echo <<<out
                                            <option value="{$c}">{$n['name']}</option>
out;
                                        } else {
                                            echo <<<out
                                            <option value="{$c}" selected="true">{$n['name']}</option>
out;
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <?php
                                $dir = '';
                                if (isset($this->bean) && isset($this->bean['sortDir'])) {
                                    $dir = $this->bean['sortDir'];
                                }
                                ?>
                                <select class="form-control" name="sortDir">
                                    <option value="asc" <?php if ($dir == 'asc') echo 'selected="true"'; ?>>ascending
                                    </option>
                                    <option value="desc" <?php if ($dir == 'desc') echo 'selected="true"'; ?>>
                                        descending
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Visible columns</label>

                            <div class="col-sm-10">
                                <?php
                                foreach ($columns as $c => $n) {
                                    echo <<<out
                                    <label class="checkbox-inline">
out;
                                    if ((isset($this->bean['columns']) && in_array($c, $this->bean['columns'])) || (!isset($this->bean['columns']))) {
                                        echo <<<out
                                        <input type="checkbox" value="{$c}" name="columns[]" checked="checked"> {$n['name']}
out;
                                    } else {
                                        echo <<<out
                                        <input type="checkbox" value="{$c}" name="columns[]"> {$n['name']}
out;
                                    }
                                    echo <<<out
                                    </label >
out;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Records per page</label>

                            <div class="col-sm-10">
                                <?php
                                $cnt = 0;
                                if (isset($this->bean) && isset($this->bean['pageSize'])) {
                                    $cnt = $this->bean['pageSize'];
                                }
                                ?>
                                <select class="form-control" name="pageSize">
                                    <option <?php if ($cnt == '20') echo 'selected="true"'; ?>>20</option>
                                    <option <?php if ($cnt == '5') echo 'selected="true"'; ?>>5</option>
                                    <option <?php if ($cnt == '10') echo 'selected="true"'; ?>>10</option>
                                    <option <?php if ($cnt == '15') echo 'selected="true"'; ?>>15</option>
                                    <option <?php if ($cnt == '50') echo 'selected="true"'; ?>>50</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Query expansion</label>

                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="disableExpansion"
                                            <?php if (isset($this->bean) && isset($this->bean['disableExpansion']) && $this->bean['disableExpansion'] == 'on') {
                                                echo ' checked="true" ';
                                            } ?>
                                            > Disable query expansion
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php
                        foreach ($columns as $c => $n) {
                            $checked = '';
                            $op = null;
                            $val = null;

                            $checked = (isset($this->bean[$c . '__cb']) && $this->bean[$c . '__cb']) ? ' checked="checked" ' : '';
                            if (isset($this->bean[$c . '__op'])) {
                                $op = htmlspecialchars($this->bean[$c . '__op']);
                            }
                            if (isset($this->bean[$c . '__v'])) {
                                $val = $this->bean[$c . '__v'];
                            }

                            echo <<<out
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Filter by</label>
                                            <div class="col-sm-2">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="{$c}__cb" {$checked}> {$n['name']}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="{$c}__op">
out;
                            $operations = array();
                            $operations['=='] = 'equals';
                            $operations['!='] = 'does not equal';
                            $operations['~~'] = 'like';
                            $operations['&gt;'] = 'greater than';
                            $operations['&lt;'] = 'lower than';
                            $operations['&gt;='] = 'greater than or equal';
                            $operations['&lt;='] = 'lower than or equal';

                            foreach ($operations as $ov => $on) {
                                if ($ov == $op) {
                                    echo <<<out
                                                <option value = "{$ov}" selected="true">{$on}</option >
out;
                                } else {
                                    echo <<<out
                                                <option value = "{$ov}" >{$on}</option >
out;
                                }
                            }

                            echo <<<out
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="{$c}__v" value="{$val}">
                                            </div>
                                        </div>

out;
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" name="saveChanges" class="btn btn-primary">Update data</button>
                                <button type="submit" name="resetChanges" class="btn btn-danger">Reset form</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fa fa-fw fa-reorder"></i>
            <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne4">
                Results
            </a>
        </h4>
    </div>
    <div id="collapseOne4" class="panel-collapse collapse in">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <?php
                        $cnt = $enabledColumnsCount + 1;
                        $tbl = Utils::prepareName($this->response->self->name);
                        echo <<<out
                        <th colspan="{$cnt}" class="bg-success">{$tbl}</th>
out;
                        ?>

                        <?php
                        foreach ($joinedColumns as $c => $v) {
                            foreach ($v as $c1 => $v1) {
                                $cn = Utils::prepareName($c1);
                                $cnt = count($v1);
                                echo <<<out
                        <th colspan="{$cnt}" class="bg-warning">{$cn}</th>
out;
                            }

                        }
                        ?>
                    </tr>

                    <tr>
                        <?php
                        echo <<<out
                        <th class="bg-success"></th>
out;

                        foreach ($columns as $k => $v) {
                            if (!$v['enabled']) {
                                continue;
                            }
                            echo <<<out
                        <th class="bg-success">{$v['name']}</th>
out;
                        }

                        foreach ($joinedColumns as $c => $v) {
                            foreach ($v as $c1) {
                                foreach ($c1 as $c2) {
                                    $cname = Utils::prepareName($c2);
                                    echo <<<out
                        <th class="bg-warning">{$cname}</th>
out;
                                }
                            }
                        }
                        ?>

                    </tr>
                    </thead>

                    <?php
                    foreach ($this->response->rows as $row) {
                        echo <<<out
                    <tr>
out;
                        if (isset($row->href)) {
                            echo <<<out
                        <td><a href="{$row->href}"><i class="fa fa-plus"></i></a></td>
out;
                        } else {
                            echo <<<out
                        <td><i class="fa fa-ban"></i></td>
out;
                        }
                        foreach ($columns as $k => $v) {
                            if (!$v['enabled']) {
                                continue;
                            }
                            $ev = isset($row->values->{$k}) ? $row->values->{$k}->value : '';
                            $inref = '';
                            if (isset($row->values->{$k}->inRreferences)) {
                                foreach ($row->values->{$k}->inRreferences as $ref) {
                                    $tname = Utils::prepareName($ref->name);
                                    $inref = $inref . '<a class="btn btn-default btn-xs pull-right" href="' . $ref->href . '" title="Table ' . $tname . ' points here."><i class="fa fa-mail-reply"></i></a>';
                                }
                                if ($inref) {
                                    $inref = '&nbsp;' . $inref;
                                }
                            }

                            if (isset($row->values->{$k}->outReference)) {
                                $name = Utils::prepareName($row->values->{$k}->outReference->name);
                                echo <<<out
                        <td><a href="{$row->values->{$k}->outReference->href}" title="Go to {$name}">{$ev}</a>{$inref}</td>
out;
                            } else if (isset($row->values->{$k}->href)) {
                                echo <<<out
                        <td class="text-center"><a class="btn btn-default btn-xs" href="{$row->values->{$k}->href}" target="_blank" title="download object"><i class="fa fa-fw fa-cloud-download"></i></a>{$inref}</td>
out;
                            } else {
                                echo <<<out
                        <td>{$ev}{$inref}</td>
out;
                            }
                        }

                        foreach (array_keys($columns) as $k) {
                            if (!isset($joinedColumns[$k])) {
                                continue;
                            }

                            foreach ($joinedColumns[$k] as $k1 => $v1) {
                                foreach ($v1 as $v2) {
                                    $refValue = isset($row->values->{$k}->outReference->values->{$v2}) ? $row->values->{$k}->outReference->values->{$v2} : '';
                                    echo <<<out
                        <td>{$refValue}</td>
out;
                                }
                            }
                        }
                        echo <<<out
                    </tr>
out;
                    }
                    ?>
                </table>
            </div>

            <ul class="pagination">
                <?php
                if (isset($this->response->firstPage)) {
                    echo <<<out
                <li><a href="{$this->response->firstPage->internalHref}">&laquo; First</a></li>
out;
                } else {
                    echo <<<out
                <li class="disabled"><a href="#">&laquo; First</a></li>
out;

                }

                if (isset($this->response->previousPage)) {
                    echo <<<out
                <li><a href="{$this->response->previousPage->internalHref}">&laquo; Previous</a></li>
out;
                } else {
                    echo <<<out
                <li class="disabled"><a href="#">&laquo; Previous</a></li>
out;

                }

                echo <<<out
                <li class="disabled"><a href="#">{$this->response->rowCount} row(s)</a></li>
out;

                if (isset($this->response->nextPage)) {
                    echo <<<out
                <li><a href="{$this->response->nextPage->internalHref}">Next &raquo;</a></li>
out;
                } else {
                    echo <<<out
                <li class="disabled"><a href="#">Next &raquo;</a></li>
out;

                }

                if (isset($this->response->lastPage)) {
                    echo <<<out
                <li><a href="{$this->response->lastPage->internalHref}">Last &raquo;</a></li>
out;
                } else {
                    echo <<<out
                <li class="disabled"><a href="#">Last &raquo;</a></li>
out;

                }
                ?>
            </ul>

        </div>
    </div>
</div>

<?php include(dirname(__FILE__) . '/tiles/debug.php'); ?>
</div>

<div class="push"></div>

</div>



<?php include(dirname(__FILE__) . '/tiles/footer.php'); ?>
<?php include(dirname(__FILE__) . '/tiles/scripts.php'); ?>
</body>
</html>