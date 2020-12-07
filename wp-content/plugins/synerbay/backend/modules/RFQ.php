<?php
namespace SynerBay\Module;

class RFQ
{
    /**
     * @param int $userID
     * @param int $productID
     * @return bool
     */
    public function appear(int $userID, int $productID)
    {
        return true;
    }

    /**
     * @param int $userID
     * @param int $productID
     * @return bool
     */
    public function disappear(int $userID, int $productID)
    {
        return true;
    }
}