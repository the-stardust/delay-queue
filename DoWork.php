<?php
/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   4:30 下午
 *
 * ${PARAM_DOC}
 */


/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   3:00 下午
 *
 * ${PARAM_DOC}
 */


if (count($argv) < 4) {
    echo "请输入redis的host、port和zset的key";
    exit();
}

$host = $argv[1];
$port = $argv[2];
$key = $argv[3];

$server = new \Redis();
$status = $server->pconnect($host, $port);

if ($status === FALSE) {
    throw new \RuntimeException("Could not establish Redis connection.");
}

while (TRUE) {
    $job = $server->lPop($key);
    if($job){
        // do something
        var_dump($job);
        // 如果失败，可以做一些补偿机制
    }
}


