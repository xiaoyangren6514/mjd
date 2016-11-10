<?php

namespace Admin\Model;

use Think\Model;

/**
 * Created by PhpStorm.
 * User: zhonglongquan
 * Date: 2016/11/10
 * Time: 13:24
 */
class GoodsModel extends Model
{

    //定义请求只接收的字段值，防止被恶意篡改
    protected $insertFields = 'goods_name,market_price,shop_price,shop_price,is_on_sale';

    //定义验证规则
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空', 1),
        array('market_price', 'currency', '市场价格必须是货币类型', 1),
        array('shop_price', 'currency', '本店价格必须是货币类型', 1),
        array('shop_price', 'require', '商品描述不能为空', 1),
    );

    /**
     * 如果在模型中定义了该方法，那么在添加数据之前会先执行该方法
     * 一般这种方法叫做钩子方法
     * 函数内部要修改传入函数的变量值，需要使用引用传递，对象除外，对象默认就是引用传递
     * 其它钩子方法：
     *  _after_insert
     *  _before_update
     *  _after_update
     *  _before_delete
     *  _after_delete
     */
    protected function _before_insert(&$data, $options)
    {
        $data['addtime'] = date('Y-m-d H:i:s', time());
        $data['goods_desc'] = removeXSS($_POST['goods_desc']);
    }


}