<?php
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

class Utils
{
    public static function getFromGet($param, $defaultValue = '')
    {
        return isset($_GET[$param]) ? $_GET[$param] : $defaultValue;
    }

    public static function getFromPost($param, $defaultValue = '')
    {
        return isset($_POST[$param]) ? $_POST[$param] : $defaultValue;
    }

    public static function getFromSession($param, $defaultValue = '')
    {
        return isset($_SESSION[$param]) ? $_SESSION[$param] : $defaultValue;
    }

    public static function setToSession($param, $value)
    {
        $_SESSION[$param] = $value;
    }

    public static function addUrlParam($url, $param, $value)
    {
        $url = self::removeUrlParam($url, $param);
        if (strpos($url, '?') === false) {
            $url = $url . '?';
        } else {
            $url = $url . '&';
        }
        $url = $url . $param . '=' . urlencode($value);

        return $url;
    }

    public static function removeUrlParam($url, $param)
    {
        $parts = parse_url($url);
        if (isset($parts['query']) && $parts['query']) {
            parse_str($parts['query'], $params);
            if (isset($params[$param])) {
                unset($params[$param]);

                $parts['query'] = http_build_query($params);

                return $parts['scheme'] .
                '://' .
                $parts['host'] .
                (isset($parts['port']) && $parts['port'] != 80 ? ':' . $parts['port'] : '') .
                $parts['path'] .
                '?' .
                $parts['query'];
            } else {
                return $url;
            }
        } else {
            return $url;
        }
    }

    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function redirect($url)
    {
        header('Location: ' . $url);
        die();
    }

    public static function url64Encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function url64Decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public static function prepareName($name)
    {
        return ucwords(strtolower(str_replace('_', ' ', $name)));
    }

}

?>