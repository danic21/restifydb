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

<?php
if (isset($this->errors) && count($this->errors)) {
    echo <<<out
<div class="large bg-danger">
While submitting the form, the following errors appeared:
<ul>
out;

    foreach ($this->errors as $error) {
        echo <<<out
<li>{$error}</li>
out;
    }
    echo <<<out
</ul>
</div>
out;
    $names = '["' . implode('","', array_keys($this->errors)) . '"]';
echo <<<out
    <script type="text/javascript">
        var _errors = {$names};
    </script>
out;

}
?>
