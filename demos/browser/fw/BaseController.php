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

include_once(dirname(__FILE__) . '/../Config.php');

class BaseController
{
    protected $config;

    protected $duration;

    function __construct($displayName = '')
    {
        session_start();

        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');
        mb_language('uni');
        mb_regex_encoding('UTF-8');

        date_default_timezone_set('Europe/Brussels');

        $this->duration = microtime(true);

        $this->config = Config::getConfig();
    }

    protected function fetchDataFromUrl($url, $referer = null, $multipart = false)
    {
        return $this->fetch($url, null, $referer, $multipart);
    }

    protected function postDataToUrl($url, $postData, $multipart = false)
    {
        return $this->fetch($url, $postData, null, $multipart);
    }

    private function fetch($url, $postData = '', $referer = '', $multipart = false)
    {
        $data = '';
        try {
            $http = curl_init($url);
            curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($http, CURLOPT_HEADER, 0);
            curl_setopt($http, CURLOPT_SSL_VERIFYPEER, false);

            if ($referer != null && $referer != '') {
                curl_setopt($http, CURLOPT_REFERER, $referer);
            }
            if ($multipart) {
                curl_setopt($http, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
            }
            if ($postData != null && $postData != '') {
                curl_setopt($http, CURLOPT_POSTFIELDS, $postData);
            }

            if ($this->config['proxy']['enabled']) {
                curl_setopt($http, CURLOPT_PROXY, $this->config['proxy']['host']);
                curl_setopt($http, CURLOPT_PROXYPORT, $this->config['proxy']['port']);
                curl_setopt($http, CURLOPT_PROXYUSERPWD, $this->config['proxy']['user'] . ':' . $this->config['proxy']['password']);
                curl_setopt($http, CURLOPT_TIMEOUT_MS, 3000);
            }

            $data = curl_exec($http);
            curl_close($http);
        } catch (Exception $e) {
        }
        return $data;
    }

    protected function saveDataFromUrl($url, $location)
    {
        $result = false;
        try {
            $http = curl_init($url);
            $file = fopen($location, 'wb');
            curl_setopt($http, CURLOPT_FILE, $file);
            curl_setopt($http, CURLOPT_HEADER, 0);
            curl_setopt($http, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($http, CURLOPT_TIMEOUT_MS, 1000);

            if ($this->config['proxy']['enabled']) {
                curl_setopt($http, CURLOPT_PROXY, $this->config['proxy']['host']);
                curl_setopt($http, CURLOPT_PROXYPORT, $this->config['proxy']['port']);
                curl_setopt($http, CURLOPT_PROXYUSERPWD, $this->config['proxy']['user'] . ':' . $this->config['proxy']['password']);
            }

            curl_exec($http);
            curl_close($http);

            fclose($file);

            $result = true;
        } catch (Exception $e) {
        }
        return $result;
    }

    public function retrieve($url)
    {
        return $this->fetch($url);;
    }

    function __destruct()
    {
        $this->duration = microtime(true) - $this->duration;
    }

    protected function error($code)
    {
        header("Location: /errors/$code.html");
        die();
    }
}

?>