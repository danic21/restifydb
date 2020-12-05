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
    var factories = angular.module("com.countrylicious.factories", []);

    var cfg = angular.module("com.countrylicious.config").get();
    factories.config.apiUrl = cfg.apiUrl;
    factories.config.apiBaseParams = {
        "_view": "json",
        "_expand": "yes"
    };

    factories.factory("WS_Countries", function ($http, $q) {
        var factory = {};

        factory.getCountries = function (pageId, count, _regionId, _sortField, _sortDir) {
            return $q(function (resolve, reject) {
                var _params = angular.extend({
                    "_sort": _sortField + " " + _sortDir,
                    "_start": (pageId - 1) * count,
                    "_count": count
                }, factories.config.apiBaseParams);

                if (_regionId != -1) {
                    _params = angular.extend(_params, {
                        "_filter": "regionid==" + _regionId
                    });
                }

                var request = {
                    method: "GET",
                    url: factories.config.apiUrl + "countries",
                    params: _params
                };

                $http(request)
                    .success(function (data, status, headers, config) {
                        var response = data.restify;

                        var result = {};
                        result.paging = {};

                        result.paging.total = response.rowCount;
                        result.paging.currentPage = response.currentPage;
                        result.paging.pageCount = response.pageCount;
                        result.paging.nextPage = response.currentPage < response.pageCount ? response.currentPage + 1 : response.pageCount;
                        result.paging.prevPage = response.currentPage > 1 ? response.currentPage - 1 : 1;

                        result.countries = response.rows;

                        resolve(result);
                    }).
                    error(function (data, status, headers, config) {
                        reject();
                    });
            });
        };

        factory.getCountryDetails = function (countryId) {
            return $q(function (resolve, reject) {
                var _params = factories.config.apiBaseParams;

                var request = {
                    method: "GET",
                    url: factories.config.apiUrl + "countries/" + countryId,
                    params: _params
                };

                $http(request)
                    .success(function (data, status, headers, config) {
                        var result = {};
                        result.country = data.restify.rows[0];

                        resolve(result);
                    }).
                    error(function (data, status, headers, config) {
                        reject();
                    });
            });
        };

        factory.getLargestCities = function (countryId) {
            return $q(function (resolve, reject) {
                var _params = angular.extend({
                    "_count": 10,
                    "_sort": "population desc",
                    "_fields": "name,population",
                    "_filter": "countryid==" + countryId
                }, factories.config.apiBaseParams);

                var request = {
                    method: "GET",
                    url: factories.config.apiUrl + "cities",
                    params: _params
                };

                $http(request)
                    .success(function (data, status, headers, config) {
                        var result = {};
                        result.cities = data.restify.rows;

                        resolve(result);
                    }).
                    error(function (data, status, headers, config) {
                        reject();
                    });
            });
        };

        return factory;
    });
})();