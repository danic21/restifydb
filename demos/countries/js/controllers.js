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
    var controllers = angular.module("com.countrylicious.controllers", []);

    controllers.controller("ListController", ["$scope", "$routeParams", "WS_Countries",
        function ($scope, $routeParams, WS_Countries) {
            (function init() {
                var _count = 20;
                var _pageId = $routeParams.pageId ? $routeParams.pageId : 1;
                var _regionId = $routeParams.regionId ? $routeParams.regionId : -1;
                var _sortId = $routeParams.sortField ? $routeParams.sortField : -1;
                var _sortDirId = $routeParams.sortDirection ? $routeParams.sortDirection : -1;

                $scope.countries = [];
                $scope.paging = {};
                $scope.currentPage = 1;
                $scope.regionId = _regionId;
                $scope.sortId = _sortId;
                $scope.sortDirId = _sortDirId;

                var _sortField = "";
                switch (_sortId) {
                    case "1":
                        _sortField = "population";
                        break;
                    case "2":
                        _sortField = "area";
                        break;
                    case "3":
                        _sortField = "gdp";
                        break;
                    default :
                        _sortField = "name";
                }

                var _sortDir = "";
                switch (_sortDirId) {
                    case "1":
                        _sortDir = "asc";
                        break;
                    case "2":
                        _sortDir = "desc";
                        break;
                    default :
                        _sortDir = "asc";
                }

                var promise = WS_Countries.getCountries(_pageId, _count, _regionId, _sortField, _sortDir);
                promise.then(function success(data) {
                    $scope.countries = data.countries;
                    $scope.paging = data.paging;
                    $scope.currentPage = data.paging.currentPage;
                    $scope.pageCount = data.paging.pageCount;

                }, function error(data) {
                });
            })();

            $scope.constructPagingHref = function (page) {
                return ($scope.regionId != -1 ? ("/region/" + $scope.regionId) : "") + "/" +
                    page +
                    ($scope.sortId != -1 ? "/sort/" + $scope.sortId : "") +
                    ($scope.sortDirId != -1 ? "/dir/" + $scope.sortDirId : "");
                ;
            }

            $scope.constructSortingHref = function (sortField, sortDir) {
                return ($scope.regionId != -1 ? ("/region/" + $scope.regionId) : "") + "/" +
                    "1" +
                    "/sort/" + sortField +
                    "/dir/" + sortDir;
            }
        }]);


    controllers.controller("DetailsController", ["$scope", "$routeParams", "WS_Countries",
        function ($scope, $routeParams, WS_Countries) {
            (function init() {
                var _countryId = $routeParams.countryId ? $routeParams.countryId : -1;

                $scope.country = {};
                $scope.cities = [];

                var promise = WS_Countries.getCountryDetails(_countryId)
                promise.then(function success(data) {
                    $scope.country = data.country.values;

                }, function error(data) {
                });

                var promise2 = WS_Countries.getLargestCities(_countryId)
                promise2.then(function success(data) {
                    $scope.cities = data.cities;

                }, function error(data) {
                });
            })();
        }]);
})();