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


namespace restify\admin\framework;


use restify\Constants;
use restify\utils\HTTPUtils;
use Laminas\Crypt\Password\Bcrypt;

class SecurityUtils
{
    static $crypt;

    private static function getCrypt()
    {
        if (!self::$crypt) {
            self::$crypt = new Bcrypt();
        }
        return self::$crypt;
    }

    public static function isUserLoggedIn()
    {
        return HTTPUtils::getFromSession('__user_logged_in__', 'no') == 'yes';
    }

    public static function verifyPassword($userInput, $storedPassword)
    {
        $crypt = self::getCrypt();
        return $crypt->verify($userInput, $storedPassword);
    }

    public static function hashPassword($password)
    {
        $crypt = self::getCrypt();
        return $crypt->create($password);
    }

    public static function loginUser()
    {
        HTTPUtils::setToSession('__user_logged_in__', 'yes');
    }

    public static function logoutUser()
    {
        HTTPUtils::setToSession('__user_logged_in__', 'no');
        session_unset();
    }

    public static function isPasswordStrongEnough($password)
    {
        return
            strlen($password) > Constants::MIN_PASSWORD_LENGTH &&
            preg_match("/[0-9]+/", $password) &&
            preg_match("/[a-z]+/", $password) &&
            preg_match("/[A-Z]+/", $password) &&
            preg_match("/[\W]+/", $password);
    }

    public static function redirectToLogin()
    {
        self::sendRedirect('login.php');
    }

    public static function sendRedirect($url)
    {
        header('Location: ' . $url);
        die();
    }
}