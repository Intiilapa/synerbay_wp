<?php


namespace SynerBay\Helper;


class Database
{
    public static function beginTransaction()
    {
        global $wpdb;
        $wpdb->query('START TRANSACTION;');
    }


    public static function commitTransaction()
    {
        global $wpdb;
        $wpdb->query('COMMIT;');
    }


    public static function rollbackTransaction()
    {
        global $wpdb;
        $wpdb->query('ROLLBACK;');
    }
}