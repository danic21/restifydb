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


namespace restify\utils;

class XmlSerializer
{

    public static function toXml($input)
    {
        $result = '<?xml version="1.0" encoding="UTF-8"?>';

        if (is_array($input)) {
            $result .= self::traverse($input, 'root');
        }

        return $result;
    }

    private static function traverse($input, $parent)
    {
        $result = '';
        foreach ($input as $key => $value) {
            $currentKey = $key;
            //for un-indexed arrays consider that the parent node is a plural noun
            //so, we can just delete the ending and make it singular
            if (is_numeric($currentKey)) {
                $currentKey = substr($parent, 0, strlen($parent) - 1);
            }
            $result .= '<' . $currentKey . '>';
            if (is_array($value)) {
                $result .= self::traverse($value, $currentKey);
            } else {
                $result .= htmlentities($value);
            }
            $result .= '</' . $currentKey . '>';
        }

        return $result;
    }
}

?>