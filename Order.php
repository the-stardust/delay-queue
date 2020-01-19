<?php


require_once('Queue.php');
/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   2:23 下午
 *
 * ${PARAM_DOC}
 */

$client = new Queue();
$pintuan_keys  = [
    'pintuan_order1',
    'pintuan_order2',
];
$orderId = 123456789;
$index = fmod($orderId,count($pintuan_keys));
$key = $pintuan_keys[intval($index)];
$score = time() + 10 ;
$res = $client->addJob($key,$score,$orderId);
//$res = $client->deleteJob($key,$orderId);
var_dump($res);