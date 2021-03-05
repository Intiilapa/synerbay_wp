<?php


namespace SynerBay\Traits;


use SynerBay\Helper\MemcacheHelper;

trait Memcache
{
    protected function setCacheData(string $group, string $key, $data, $expire = 15 * 60)
    {
        $this->initMC();

        $cacheKey = sprintf('%s_%s', $group, $key);

        MemcacheHelper::set($cacheKey, $data, $expire);

        return $this->addToGroup($group, $key);
    }

    protected function getCacheData(string $group, string $key)
    {
        $this->initMC();

        return MemcacheHelper::get(sprintf('%s_%s', $group, $key));
    }

    protected function deleteCacheData(string $group, string $key)
    {
        $this->initMC();

        MemcacheHelper::delete(sprintf('%s_%s', $group, $key));

        return $this->removeFromGroup($group, $key);
    }

    protected function deleteGroupFromCache(string $group)
    {
        $this->initMC();

        $data = MemcacheHelper::get('key_group');
        $groupData = $data ? $data : [];

        if (array_key_exists($group, $groupData) && count($groupData[$group])) {
            foreach ($groupData[$group] as $storedKeys) {
                MemcacheHelper::delete(sprintf('%s_%s', $group, $storedKeys));
            }
        }

        return $this->removeGroup($group);
    }

    private function addToGroup(string $group, string $key)
    {
        $this->initMC();

        $data = MemcacheHelper::get('key_group');
        $groupData = $data ? $data : [];

        if (array_key_exists($group, $groupData)) {
            if (!in_array($key, $groupData[$group])) {
                $groupData[$group][] = $key;
            }
        } else {
            $groupData[$group] = [$key];
        }

        return MemcacheHelper::set('key_group', $groupData, 0);
    }

    private function removeFromGroup(string $group, string $key)
    {
        $this->initMC();

        $data = MemcacheHelper::get('key_group');
        $groupData = $data ? $data : [];

        if (array_key_exists($group, $groupData)) {
            if (in_array($key, $groupData[$group])) {
                $tmp = [];

                foreach ($groupData[$group] as $storedKey) {
                    if ($storedKey == $key) {
                        continue;
                    }

                    $tmp[] = $storedKey;
                }

                $groupData[$group] = $tmp;
                unset($tmp);
            }
        }

        return MemcacheHelper::set('key_group', $groupData, 0);
    }

    public function removeGroup(string $group)
    {
        $this->initMC();

        $data = MemcacheHelper::get('key_group');
        $groupData = $data ? $data : [];

        if (array_key_exists($group, $groupData)) {
            $tmp = [];
            foreach ($groupData as $cacheGroup => $storedKeys) {
                if ($cacheGroup == $group) {
                    continue;
                }

                $tmp[$cacheGroup] = $storedKeys;
            }

            $groupData = $tmp;
            unset($tmp);
        }

        return MemcacheHelper::set('key_group', $groupData, 0);
    }

    protected function getAllStoredValue()
    {
        $this->initMC();

        $data = MemcacheHelper::get('key_group');
        $groupData = $data ? $data : [];
        if (count($groupData)) {
            $tmp = [];
            foreach ($groupData as $cacheGroup => $storedKeys) {
                if (count($storedKeys)) {
                    foreach ($storedKeys as $storedKey) {
                        $tmp[$cacheGroup][$storedKey] = $this->getCacheData($cacheGroup, $storedKey);
                    }
                }
            }

            print '<pre>';
            var_dump($tmp);
//            die;
        } else {
            die('no data stored');
        }
    }

    private function initMC()
    {
        if (!MemcacheHelper::$inited) {
            MemcacheHelper::init();
        }
    }
}