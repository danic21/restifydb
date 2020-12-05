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

(function () {
    var config = angular.module("com.countrylicious.config", []);

    config.get = function() {
        return {
            apiUrl: "http://restify-api/countries/"
        }
    }
})();