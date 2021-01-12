<?php


namespace SynerBay\Resource;


abstract class AbstractResource
{
    public function toArray($row)
    {
        if (!$row || !is_array($row) || !count($row)) {
            return [];
        }

        return $this->prepare($row);
    }

    public function collection($searchResult)
    {
        $ret = [];

        if (count($searchResult)) {
            foreach ($searchResult as $result) {
                $ret[] = $this->toArray($result);
            }
        }

        return $ret;
    }

    protected function beforeCreateCollection($searchResult)
    {

    }

    abstract protected function prepare($row): array;
}