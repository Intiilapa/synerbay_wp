<?php

namespace SynerBay\Helper;

class ArrayHelper
{
    /**
     * @param array $array
     * @return array
     */
    public static function reKeyBySlugFromValue(array $array)
    {
        $retArr = [];

        foreach ($array as $item) {
            $retArr[sanitize_title($item)] = $item;
        }

        return $retArr;
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function isMultiDimensional(array $array = [])
    {
        return count($array) == count($array, COUNT_RECURSIVE) ? false : true;
    }

    public static function getMaxIntFromArray(array $data)
    {
        var_dump($data);die;
    }
}