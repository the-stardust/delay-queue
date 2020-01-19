<?php
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


if(count($argv) < 4){
    echo "请输入redis的host、port和zset的key";
    exit();
}

$host = $argv[1];
$port = $argv[2];
$key = $argv[3];

$server = new \Redis();
$status    = $server->pconnect($host, $port);

if ($status === false) {
    throw new \RuntimeException("Could not establish Redis connection.");
}

while (TRUE){
    $jobs = $server->zRevRange($key,0,1,TRUE);
    if(!empty($jobs)){
        foreach ($jobs as $orderId => $score){
            if($score == time()){
                // 丢给执行队列
                $res = $server->rPush($key."_job",$orderId);
                var_dump($res);
                if(!$res){
                    // 可以进行报警
                    $server->rPush($key.'_job',$orderId);
                }else{
                    // 删除任务
                    $res = $server->zrem($key,$orderId);
                }
            }
        }
    }
    sleep(1);
}


