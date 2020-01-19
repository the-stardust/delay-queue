<?php
/**
 *
 * @DESC:
 *
 * @return ${TYPE_HINT}
 * ${THROWS_DOC}
 * @author: WangYuHao
 * @Time: 2020/1/19   10:30 上午
 *
 * ${PARAM_DOC}
 */

class ConsistentHashing
{
    // 真实节点
    protected $nodes = [];
    // 虚拟节点
    protected $position = [];
    // 虚拟节点个数
    protected $mul = 16;

    /**
     *
     * @DESC: 计算hash值
     *
     * @param $val
     *
     * @return string
     * @author: WangYuHao
     * @Time: 2020/1/19   10:38 上午
     *
     */
    public function hash($val)
    {
        return sprintf("%u",crc32($val));
    }

    /**
     *
     * @DESC: 寻找节点
     *
     * @param $key
     *
     * @return mixed
     * @author: WangYuHao
     * @Time: 2020/1/19   11:34 上午
     *
     */
    public function lookup($key)
    {
        $point = $this->hash($key);

        $node = current($this->position);

        foreach ($this->position as $key => $item) {
            if($point <= $key){
                $node = $item;
                break;
            }
        }

        reset($this->position);
        return $node;
    }

    /**
     *
     * @DESC: 添加节点
     *
     * @param $node
     *
     * @author: WangYuHao
     * @Time: 2020/1/19   10:50 上午
     *
     */
    public function addNode($node)
    {
        if(isset($this->nodes[$node])) return ;
        // 虚拟节点添加
        for ($i = 0;$i < $this->mul;$i++ ){
            $pos = $this->hash($node."-".$i);
            $this->position[$pos] = $node;
            $this->nodes[$node][] = $pos;
        }

        $this->sortPos();
    }

    /**
     *
     * @DESC: 批量添加节点
     *
     * @param $nodes
     *
     * @author: WangYuHao
     * @Time: 2020/1/19   11:20 上午
     *
     */
    public function addNodes($nodes)
    {
        foreach ($nodes as $node){
            if(isset($this->nodes[$node])) return ;
            // 虚拟节点添加
            for ($i = 0;$i < $this->mul;$i++ ){
                $pos = $this->hash($node."-".$i);
                $this->position[$pos] = $node;
                $this->nodes[$node][] = $pos;
            }
        }

        $this->sortPos();
    }

    /**
     *
     * @DESC: 删除节点
     *
     * @param $node
     *
     * @author: WangYuHao
     * @Time: 2020/1/19   11:20 上午
     *
     */
    public function deleteNode($node)
    {
        if(!isset($this->nodes[$node])) return ;
        // 删除虚拟节点
        foreach ($this->nodes[$node] as $val){
            unset($this->position[$val]);
        }

        unset($this->nodes[$node]);

    }

    /**
     *
     * @DESC: 排序虚拟节点
     *
     * @author: WangYuHao
     * @Time: 2020/1/19   11:12 上午
     *
     */
    public function sortPos()
    {
        ksort($this->position,SORT_REGULAR);
    }
}