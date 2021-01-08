<?php


namespace SynerBay\Resource;


abstract class AbstractResource
{
    public static function collection($searchResult)
    {

    }

    protected static function beforeCreateCollection($searchResult)
    {

    }

    abstract public function toArray();
}