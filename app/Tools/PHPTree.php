<?php
/**
 * Created by PhpStorm.
 * User: zhaijiazhen
 * Date: 2017/11/17
 * Time: 上午11:26
 */
namespace Core\Tools;

class PHPTree{

    protected static $config = array(
        /* 主键 */
        'primary_key' 	=> 'id',
        /* 父键 */
        'parent_key'  	=> 'pid',
        /* 展开属性 */
        'expanded_key'  => 'expanded',
        /* 叶子节点属性 */
        'leaf_key'      => 'leaf',
        /* 孩子节点属性 */
        'children_key'  => 'children',
        /* 是否展开子节点 */
        'expanded'    	=> false
    );

    /* 结果集 */
    protected static $result = array();

    /* 层次暂存 */
    protected static $level = array();
    /**
     * @name 生成树形结构
     * @param array 「二维数组」
     * @return mixed 「多维数组」
     */
    public static function makeTree($data, $primary_key='id', $parent_key='pid', $children_key='children'){
        $dataSet = self::buildData($data, $primary_key, $parent_key, $children_key);
        $ret = self::makeTreeCore(0, $dataSet, 'normal');
        return $ret;
    }

    /* 生成线性结构, 便于HTML输出, 参数同上 */
    public static function makeTreeForHtml($data, $primary_key='id', $parent_key='pid', $children_key='children'){
        $dataSet = self::buildData($data, $primary_key, $parent_key, $children_key);
        $ret = self::makeTreeCore(0, $dataSet, 'linear');
        return $ret;
    }

    /* 格式化数据, 私有方法 */
    private static function buildData($data, $primary_key, $parent_key, $children_key){
        $config = array_merge(self::$config, ['primary_key'=>$primary_key, 'parent_key'=>$parent_key, 'children_key'=>$children_key]);
        self::$config = $config;
        extract($config);

        $ret = array();
        foreach($data as $item){
//            preg_match('/\]/', $item['name'], $matches);
//            if(count($matches)>0){
//                $item['name'] = explode(']', $item['name'])[1];
//            }
            $id = $item[$primary_key];
            $parent_id = $item[$parent_key];
            $ret[$parent_id][$id] = $item;
        }
        return $ret;
    }

    /* 生成树核心, 私有方法  */
    private static function makeTreeCore($index, $data, $type='linear')
    {
        extract(self::$config);
        $ret = [];
        foreach($data[$index] as $id=>$item)
        {
            switch($type){
                case 'normal':
                    if(isset($data[$id]))
                    {
                        $item[self::$config['expanded_key']]= self::$config['expanded'];
                        $item[self::$config['children_key']]= self::makeTreeCore($id, $data, $type);
                    }
                    else
                    {
                        $item[self::$config['leaf_key']]= true;
                    }
                    $ret[] = $item;
                    break;
                case 'linear':
                    $parent_id = $item[self::$config['parent_key']];
                    self::$level[$id] = $index==0?0:self::$level[$parent_id]+1;
                    $item['level'] = self::$level[$id];
                    self::$result[] = $item;
                    if(isset($data[$id])){
                        self::makeTreeCore($id, $data, $type);
                    }

                    $ret = self::$result;
                    break;
            }
        }
        return $ret;
    }
}