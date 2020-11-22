/**
 * restifydb - expose your databases as REST web services in minutes
 *
 * @copyright (C) 2020 Daniel CHIRITA
 * @version 1.1
 * @author Daniel CHIRITA
 * @link https://restifydb.com/
 *
 * This file is part of restifydb demos.
 *
 * @license https://restifydb.com/#license
 *
 */

$(document).ready(function () {

    $("#output li").click(function (e) {
        e.stopImmediatePropagation();
        var _toggleIcon = $(this).find('i.toggler').first();
        if (_toggleIcon) {
            $(this).find('div.collapseable').first().toggle();
            if ($(this).find('div.collapseable').is(":visible")) {
                _toggleIcon.removeClass('fa-plus-square-o');
                _toggleIcon.addClass('fa-minus-square-o');
            } else {
                _toggleIcon.removeClass('fa-minus-square-o');
                _toggleIcon.addClass('fa-plus-square-o');
            }
        }
    })

})