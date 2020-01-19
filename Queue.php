<?php
/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   10:09 上午
 *
 * ${PARAM_DOC}
 */

require_once("RedisClient.php");
class Queue
{

    private static $_instances;
    private static $hosts   = [
        '127.0.0.1:6379',
        '127.0.0.1:6380',
    ];
    public static function getInstance()
    {
        if (!isset(self::$_instances) || self::$_instances == NULL) {
            self::$_instances = RedisClient::getInstance(self::$hosts);
        }

        return self::$_instances;
    }

    /**
     *
     * @DESC: 添加延迟任务
     *
     * @param $key
     * @param $score
     * @param $member
     *
     * @return mixed
     * @author: WangYuHao
     * @Time: 2020/1/19   2:21 下午
     *
     */

    public function addJob($key,$score,$member)
    {
        $client = self::getInstance();
        return $client->zAdd($key,[],$score,$member);

    }

    public function deleteJob($key,$member)
    {
        $client = self::getInstance();
        return $client->zrem($key,$member);
    }

}

