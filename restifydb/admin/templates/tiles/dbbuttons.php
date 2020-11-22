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

<div class="form-group" id="buttons">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary" name="saveChanges"><i class="fa fa-check fa-fw"></i> Save
            data source
        </button>

        <?php if (isset($this->id) && $this->id >= 0) { ?>
            <button type="submit" id="deleteDs" class="btn btn-danger pull-right" name="deleteDs"><i
                    class="fa fa-trash fa-fw"></i> Delete data
                source
            </button>
            <span class="pull-right">&nbsp;</span>
        <?php } ?>

        <a role="button" class="btn btn-primary pull-right"
           href="db.php<?php if (isset($this->id)) echo '?id=' . $this->id; ?>"><i class="fa fa-ban fa-fw"></i>
            Reset form</a>
    </div>
</div>
