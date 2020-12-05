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

$(document).ready(function () {
    var _alert = $("#my-alert");
    var _alertContent = $("#my-alert-content");

    $("button[data-refresh]").on("click", function () {
        var _ok = confirm("Are you sure you refresh the structure cache for this data source? The operation might take a long time during which the database will not be available.");
        if (!_ok) {
            return false;
        }

        _alert.addClass('hidden');

        var _this = this;
        $(this).prop("disabled", true);
        var _children = $(this).children(":first-child");
        _children.removeClass("fa-refresh");
        _children.addClass("fa-spinner");
        _children.addClass("fa-spin");

        var _id = $(this).attr("data-refresh");
        $.ajax({
            url: "ws/recache.php",
            type: "POST",
            data: {
                id: _id
            }
        }).done(function (data) {
            _alert.removeClass('alert-success');
            _alert.removeClass('alert-danger');

            if (data == 'ok') {
                _alertContent.html("The data source cache was regenerated successfully.");
                _alert.addClass('alert-success');
            } else {
                _alertContent.html('There was a problem while regenerating the data source cache. Please check the <a href="errors.php">error log</a>. If this doesn\'t provide useful information, please see the application log.');
                _alert.addClass('alert-danger');
            }
        }).fail(function () {
            _alert.removeClass('alert-success');
            _alert.removeClass('alert-danger');

            _alertContent.html('There was a problem while regenerating the data source cache. Please check the <a href="errors.php">error log</a>. If this doesn\'t provide useful information, please see the application log.');
            _alert.addClass('alert-danger');
        }).always(function () {
            _children.removeClass("fa-spin");
            _children.removeClass("fa-spinner");
            _children.addClass("fa-refresh");
            $(_this).prop("disabled", false);

            _alert.removeClass('hidden');
        });
    });

    $("button[data-ping]").on("click", function () {
        _alert.addClass('hidden');

        var _this = this;
        $(this).prop("disabled", true);
        var _children = $(this).children(":first-child");
        _children.removeClass("fa-chain");
        _children.addClass("fa-spinner");
        _children.addClass("fa-spin");

        var _id = $(this).attr("data-ping");
        $.ajax({
            url: "ws/ping.php",
            type: "POST",
            data: {
                id: _id
            }
        }).done(function (data) {
            _alert.removeClass('alert-success');
            _alert.removeClass('alert-danger');

            if (data == 'ok') {
                _alertContent.html("The connection to the data source was established successfully.");
                _alert.addClass('alert-success');
            } else {
                _alertContent.html("There was a problem while connecting to the data source.");
                _alert.addClass('alert-danger');
            }
        }).fail(function () {
            _alert.removeClass('alert-success');
            _alert.removeClass('alert-danger');

            _alertContent.html("There was a problem while connecting to the data source.");
            _alert.addClass('alert-danger');
        }).always(function () {
            _children.removeClass("fa-spin");
            _children.removeClass("fa-spinner");
            _children.addClass("fa-chain");
            $(_this).prop("disabled", false);

            _alert.removeClass('hidden');
        });
    });
});