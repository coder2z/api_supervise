<?php
/**
 * Created by PhpStorm.
 * User: Yang
 * Date: 2019-11-15
 * Time: 13:59
 */

namespace App\Utils;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Logs
{
    /**
     * @param $message
     * @param string $data
     * @param string $filename
     * @param string $isDate 是否按月份分文件夹
     * @param string $type
     * @throws \Exception
     */
    private static function _save($message, $data = null, $filename = '', $isDate = true, $type = '')
    {
        $log = new Logger('mylog');
        if (PHP_SAPI == 'cli') {
            $filename .= '_cli';
        }

        $filename = $filename . '.log';

        if ($isDate) {
            $path = storage_path('logs/' . date('Y-m-d'));
        } else {
            $path = storage_path('logs/');
        }

        self::mkDirs($path);

        $path = $path . '/' . $filename;
        if (gettype($data) != 'array') {
            $message .= "：" . $data;
            $data = array();
        }

        $log->pushHandler(new StreamHandler($path, Logger::INFO));
        switch ($type) {
            case 'info':
                $log->addInfo($message, $data);
                break;
            case 'error':
                $log->addError($message, $data);
                break;
            case 'warning':
                $log->addWarning($message, $data);
                break;
        }
    }

    /**
     * @param $message
     * @param string $data
     * @param string $filename
     * @param bool $isDate
     * @param string $isType
     * @throws \Exception
     */


    /**
     * @param $message
     * @param string $data
     * @param string $filename
     * @param bool $isDate
     * @param string $isType
     * @throws \Exception
     */
    public static function logError($message, $data = null, $filename = 'error', $isDate = true, $isType = 'error')
    {
        self::_save($message, $data, $filename, $isDate, $isType);
    }


    /**
     * @param $message
     * @param string $data
     * @param string $filename
     * @param bool $isDate
     * @param string $isType
     * @throws \Exception
     */
    public static function logWarning($message, $data = null, $filename = 'warning', $isDate = true, $isType = 'warning')
    {
        self::_save($message, $data, $filename, $isDate, $isType);
    }

    /**
     * 给日志文件夹权限
     * @param $dir
     * @param int $mode
     * @return bool
     */
    public static function mkDirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return TRUE;
        }
        if (!self::mkdirs(dirname($dir), $mode)) {
            return FALSE;
        }
        return @mkdir($dir, $mode);
    }
}