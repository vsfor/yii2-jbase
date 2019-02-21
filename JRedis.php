<?php
namespace jext\jeen;

/**
 * Class JRedis
 *
 * add diy redis config to params.php like:

    'redisConfig' => [
        'common' => [
            'hostname' => '127.0.0.1', //ip or domain
            'port' => 6379,
            'database' => 0, //redis db index
            'timeout' => 3,
            'password' => 'yourPassword',
        ],
    ],

 *
 * @package jext\jeen
 */
class JRedis
{
    
    private static $instance = [];

    /**
     * @param string $configName
     * @return \Redis|mixed
     * @throws \Exception
     */
    public static function getInstance($configName = 'common')
    {
        if(empty(self::$instance[$configName])){
            new self($configName);
        } 
//        else {
//            $tr = self::$instance[$configName];
//            if ('+PONG' != $tr->ping()) {
//                self::$instance[$configName] = null;
//                new self($configName);
//            }
//        }
        return self::$instance[$configName];
    }

    private function __construct($configName = 'common')
    {
        if (!empty(self::$instance[$configName])) {
            return self::$instance[$configName];
        }
        if (!isset(\Yii::$app->params['redisConfig'][$configName])) {
            throw new \Exception("redis config not exists");
        }
        $configInfo = \Yii::$app->params['redisConfig'][$configName];
        $redis = new \Redis();
        $timeOut = isset($configInfo['timeout']) ? intval($configInfo['timeout']) : 3;
        $dbIndex = isset($configInfo['database']) ? intval($configInfo['database']) : 0;
        if ($redis->connect($configInfo['hostname'], $configInfo['port'], $timeOut)) {
            if (isset($configInfo['password']) && $configInfo['password']) {
                $redis->auth($configInfo['password']);
            }
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            $redis->select($dbIndex);
            self::$instance[$configName] = $redis;
        } else {
            throw new \Exception("redis is down");
        }
        return $redis;
    }

    public function __clone()
    {
        throw new \Exception('Clone is not allowed !');
    }

}
