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

}