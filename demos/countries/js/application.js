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

(function () {
    var demoApplication = angular.module("com.countrylicious", [
        "ngRoute",
        "ngAnimate",
        "com.countrylicious.config",
        "com.countrylicious.factories",
        "com.countrylicious.controllers"
    ]);

    demoApplication.config(
        function ($routeProvider) {
            $routeProvider.
                when("/:pageId", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/:pageId/sort/:sortField", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/:pageId/sort/:sortField/dir/:sortDirection", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/details/:countryId", {
                    templateUrl: "partials/details.html",
                    controller: "DetailsController"
                }).
                when("/region/:regionId", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/region/:regionId/:pageId", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/region/:regionId/:pageId/sort/:sortField", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                when("/region/:regionId/:pageId/sort/:sortField/dir/:sortDirection", {
                    templateUrl: "partials/list.html",
                    controller: "ListController"
                }).
                otherwise({
                    redirectTo: "/1"
                });
        });
})();