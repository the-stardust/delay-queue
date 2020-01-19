<?php
/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   10:59 上午
 *
 * ${PARAM_DOC}
 */

require_once('ConsistentHashing.php');

Class RedisClient
{
    /**
     * hash环
     * @var
     */
    private $hashRing;
    /**
     * 保存redis connection
     *
     * @var
     */
    private static $connections;

    /**
     * 单例
     */
    private static $_instances;

    public function __construct($hosts)
    {
        $this->hashRing = new ConsistentHashing();
        $this->hashRing->addNodes($hosts);
    }

    public static function getInstance($hosts)
    {
        if (!isset(self::$_instances) || self::$_instances == NULL) {
            self::$_instances = new RedisClient($hosts);
        }

        return self::$_instances;
    }

    /**
     *
     * @DESC: 获取redis服务器
     *
     * @param $key
     *
     * @return mixed
     * @author: WangYuHao
     * @Time: 2020/1/19   11:27 上午
     *
     */
    private function connect($key)
    {
        $server = $this->hashRing->lookup($key);
        if (isset(self::$connections[$server])) {
            return self::$connections[$server];
        }
        list($host, $port) = explode(":", $server);

        $lobjredis = new \Redis();
        $status    = $lobjredis->pconnect($host, $port);

        // check memcache connection
        if ($status === false) {
            throw new \RuntimeException("Could not establish Redis connection.");
        }

        self::$connections[$server] = $lobjredis;

        return self::$connections[$server];
    }

    public function __call($name, $arguments)
    {
        $server = $this->connect($arguments[0]);

        return call_user_func_array([$server, $name], $arguments);
    }

    public function __destruct()
    {
        if (is_array(self::$connections)) {
            foreach (self::$connections as $conn) {
                if (!empty($conn)) {
                    $conn->close();
                }
            }
        }

        self::$connections = NULL;
    }

}
