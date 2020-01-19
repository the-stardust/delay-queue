# mycomposer
使用redis实现延迟队列任务

只是简单实现了下流程，很多需要改进的地方，比如config提取到一个文件里面
# 使用方式

## ConsistentHashing.php
    使用一致性生成一个hash环，添加自己的redis服务器节点
## RedisClient.php
    实现了一个简单的redis客户端，有需要的朋友可以自己使用已有的轮子，
    这里我自己实现d功能比较简单，就没有用composer包
## Queue.php
    在我的事例里面并没有用这个，只在Order.php里面使用了，主要是在业务调用封装好的方法
## Consumer.php
    常驻进程脚本，不断的从zset里面取出任务，如果时间等于现在的时候，就把任务分发给DoWork.php
    这个脚本只负责分发任务，不负责执行任务
    
    脚本命令：php Consumer.php redis的host  redis的端口  zset的key
    php Consumer.php 127.0.0.1 6380 pintuan_order2
## DoWork.php
    常驻进程进程脚本，用redis的list实现了简单的队列，不断从队列取任务去执行
    
    脚本命令：php Consumer.php redis的host  redis的端口  zset的key."_job"
    php Consumer.php 127.0.0.1 6380 pintuan_order2_job
## Order.php
    这个是我比较懒，没有写测试用例，简单的写了个脚本插入redis，验证程序是否通过
        
# tips

我在本机实现的架构是启动了多个redis，多个key

所以每个redis对应的每个key都要启动一个Consumer和DoWork，这样是避免任务太多处理不完
启动多进程处理

在DoWork里面执行任务，如果失败了，可以进行人工补偿，或者自己写一个守护进程进行补偿
我这里因为业务简单就没有去处理，大家有兴趣可以自己实现一下
             
    
    

