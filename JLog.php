<?php
namespace jext\jeen;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class JLog
{
    /**
     * 记录日志信息 dir 为相对日志目录的路径，如 form 或 model/user
     *
     * @param $msg
     * @param $dir
     */
    public static function log($msg, $dir = '')
    {
        $path = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        if($dir != '') {
            $path = $path . $dir . DIRECTORY_SEPARATOR;
            FileHelper::createDirectory($path, 0777); 
        }
        $file = $path . date("Y-m-d") . '.log';
        if(!file_exists($file)) { //文件不存在 则创建文件 并开放权限
            @touch($file); @chmod($file,0777);
        }
        if(!is_string($msg) && !is_numeric($msg)) {
            $msg = Json::encode($msg);
        }
        $msg = date('y-m-d H:i:s | ') . $msg . PHP_EOL;
        return error_log($msg, 3, $file);
    }

    public static function debug($msg, $dir = '')
    {
        $path = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        if($dir != '') {
            $path = $path .'debug'. DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;
            FileHelper::createDirectory($path, 0777);
        }
        $file = $path . date("Y-m-d") . '.log';
        if(!file_exists($file)) { //文件不存在 则创建文件 并开放权限
            @touch($file); @chmod($file,0777);
        }
        if(!is_string($msg) && !is_numeric($msg)) {
            $msg = Json::encode($msg, JSON_PRETTY_PRINT);
        }
        $msg = date('y-m-d H:i:s | ') . $msg . PHP_EOL;
        return error_log($msg, 3, $file);
    }

    public static function exception(\Exception $e, $data = [],$dir='')
    {
        $dir = ($dir ? 'exception/'.$dir : 'exception');
        if(!is_string($data) && !is_numeric($data)) {
            $data = Json::encode($data);
        }
        $msg = strval($data) . PHP_EOL;
        $msg .= $e->getFile() .':'. $e->getLine() . PHP_EOL;
        $msg .= $e->getMessage() . PHP_EOL;
        $msg .= $e->getTraceAsString() . PHP_EOL;
        return self::log($msg, $dir);
    }

}
