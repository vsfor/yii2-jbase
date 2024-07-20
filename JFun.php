<?php
namespace jext\jbase;

class JFun
{
    /**
     * 获取包含毫秒的日期
     * @param $format
     * @param $microTimeFloat
     * @return string
     */
    public static function getMicroDate($format = 'Y-m-d H:i:s.v', $microTimeFloat = null)
    {
        $retry = 0;
        do {
            if ($retry > 0) {
                usleep(3000);
            }
            $dt = date_create_from_format('U.u', $microTimeFloat ?? microtime(true));
            $retry++;
        } while($dt === false && $retry<10);
        return $dt->setTimezone(new \DateTimeZone('Asia/Shanghai'))->format($format);
    }

    /**
     * 初始化目录
     * @param string $path
     * @param numeric $mode
     * @return bool
     */
    public static function dirExistOrMake($path, $mode = 0777)
    {
        if (is_dir($path)) {
            return true;
        }
        static::dirExistOrMake(dirname($path), $mode);
        return \mkdir($path, $mode);
    }

    /**
     * 初始化文件
     * @param string $file
     * @param numeric $mode
     * @return bool
     */
    public static function fileExistOrTouch($file, $mode = 0777)
    {
        if (is_file($file)) {
            return true;
        }
        static::dirExistOrMake(dirname($file));
        if (\touch($file)) {
            return chmod($file, $mode);
        }
        return false;
    }

    /**
     * 生成32位唯一字符串
     * @return string
     */
    public static function genUniqueId()
    {
        return md5(microtime().':'.uniqid().':'.mt_rand(100000,999999));
    }

    public static function jsonEncode($data, $flags = 0)
    {
        return json_encode($data, $flags | JSON_UNESCAPED_UNICODE);
    }

    public static function jsonDecode($string, $asArray = true)
    {
        return json_decode($string, $asArray);
    }

}
