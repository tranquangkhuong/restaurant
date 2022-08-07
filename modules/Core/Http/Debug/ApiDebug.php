<?php

namespace Modules\Core\Http\Debug;

use Illuminate\Support\Facades\DB;

/**
 * Xử lý debug sql cho API
 *
 * created at 05/08/2022
 * @author khuongtq
 */
class ApiDebug
{
    /**
     * Check bật chế độ debug
     *
     * @return Boolean
     */
    public static function check()
    {
        return env('APP_DEBUG', false);
    }

    /**
     * Lấy tên database
     *
     * @param String $connection Tên connection
     * @return String $dbName Tên database
     */
    public static function getDbName($connection)
    {
        return DB::connection($connection)->getDatabaseName();
    }

    /**
     * Lấy dữ liệu Db log
     *
     * @param String $connection Tên connection
     * @return Array $return Mảng các câu lệnh truy vấn
     */
    public static function getDbLog($connection)
    {
        $datas = DB::connection($connection)->getQueryLog();
        $i = 0;
        $return = [];
        foreach ($datas as $data) {
            $query = $data['query'];
            $bindings = $data['bindings'];
            $sql = str_replace('?', "'%s'", $query);
            $sql = (string)sprintf($sql, ...$bindings);
            $return[$i]['query'] = $sql;
            $return[$i]['time'] = $data['time'];
            $i++;
        }

        return $return;
    }

    /**
     * Bật chế độ queryLog cho connection
     *
     * @param String $connection Tên connection
     */
    public static function setDbLog($connection)
    {
        DB::connection($connection)->enableQueryLog();
    }
}
