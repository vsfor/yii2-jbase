<?php
namespace jext\jbase;

class JLog
{
    /**
     * 记录日志信息 dir 为相对日志目录的路径，如 form 或 model/user
     *
     * @param $msg
     * @param string $dir
     * @return bool
     * @throws \yii\base\Exception
     */
    public static function log($msg, $dir = '')
    {
        $path = \Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        if(!empty($dir)) {
            $path = $path . $dir . DIRECTORY_SEPARATOR;
            JFun::dirExistOrMake($path);
        }
        $file = $path . date("Y-m-d") . '.log';
        JFun::fileExistOrTouch($file);
        if(!is_string($msg)) {
            $msg = JFun::jsonEncode($msg);
        }
        $msg = JFun::getMicroDate('y-m-d H:i:s.v | ') . $msg . PHP_EOL;
        return error_log($msg, 3, $file);
    }

    public static function debug($msg, $dir = '')
    {
        $dir = ($dir ? 'debug/'.$dir : 'debug');
        if(!is_string($msg) && !is_numeric($msg)) {
            $msg = JFun::jsonEncode($msg, JSON_PRETTY_PRINT);
        }
        return self::log($msg, $dir);
    }

    public static function exception(\Exception $e, $data = [],$dir='')
    {
        $dir = ($dir ? 'exception/'.$dir : 'exception');
        if(!is_string($data) && !is_numeric($data)) {
            $data = JFun::jsonEncode($data);
        }
        $msg = strval($data) . PHP_EOL;
        $msg .= $e->getFile() .':'. $e->getLine() . PHP_EOL;
        $msg .= $e->getMessage() . PHP_EOL;
        $msg .= $e->getTraceAsString() . PHP_EOL;
        return self::log($msg, $dir);
    }

}
