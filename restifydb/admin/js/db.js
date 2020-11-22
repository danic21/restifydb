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

$(document).ready(function () {
    $("#deleteDs").on("click", function () {
        var _ok = confirm("Are you sure you want to delete the specified data source?");
        return _ok;
    });
});