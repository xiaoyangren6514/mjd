<?php

namespace Admin\Model;

use Think\Image;
use Think\Model;
use Think\Upload;

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
        // 处理logo
        $fileErrorNo = $_FILES['logo']['error'];
        if ($fileErrorNo == 0) {
            // 初始化上传配置信息
            $config = array(
                'exts' => array('jpg', 'jpeg', 'png', 'bmp'), //允许上传的文件后缀
                'rootPath' => './Public/Uploads/', //保存根路径
                'savePath' => 'Goods/', //保存路径
            );
            $up = new Upload($config);
            // uploadOne方法会返回上传附件存储在服务器的 名字 路径等信息
            /**
             * array (size=9)
             * 'name' => string 'debug.log' (length=9)
             * 'type' => string 'application/octet-stream' (length=24)
             * 'size' => int 6123
             * 'key' => int 0
             * 'ext' => string 'log' (length=3)
             * 'md5' => string '88c9f4aba7287131ede4c9f21d64683e' (length=32)
             * 'sha1' => string '9369b5f5f40e68ba01146ac07819882c1dec98ad' (length=40)
             * 'savename' => string '581b197e70fdf.log' (length=17)
             * 'savepath' => string '2016-11-03/' (length=11)
             */
            $z = $up->uploadOne($_FILES['logo']);
            if ($z) {// upload success
                // 把上传好的图片保存到数据表中
                //  ./Public/Uploads/2016-11-03/581b19ec127ff.log
                $logo = $up->rootPath . $z['savepath'] . $z['savename'];
                $_POST['logo'] = ltrim($logo, './');
                // 给上传好的图片制作缩略图  130 350 50 700
                $im = new Image();// 实例化image对象
                $im->open($logo);// 打开目标文件

                $smPathName = $up->rootPath . $z['savepath'] . "sm_" . $z['savename'];
                $midlPathName = $up->rootPath . $z['savepath'] . "mid_" . $z['savename'];
                $bigPathName = $up->rootPath . $z['savepath'] . "big_" . $z['savename'];
                $mbigPathName = $up->rootPath . $z['savepath'] . "mbig_" . $z['savename'];
                $im->thumb(700, 700, 6)->save($mbigPathName);// 保存图片到服务器
                $im->thumb(350, 350, 6)->save($bigPathName);// 保存图片到服务器
                $im->thumb(130, 130, 6)->save($midlPathName);// 保存图片到服务器
                $im->thumb(50, 50, 6)->save($smPathName);// 制作缩略图 默认有自适应效果  保存图片到服务器

                $data['logo'] = $logo;
                $data['sm_logo'] = $smPathName;
                $data['mid_logo'] = $midlPathName;
                $data['big_logo'] = $bigPathName;
                $data['mbig_logo'] = $mbigPathName;
            } else {
                $this->error = $up->getError();
                return false;
            }
        } else if ($fileErrorNo == 1) {
            $this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
            return false;
        } else if ($fileErrorNo == 2) {
            $this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
            return false;
        } else if ($fileErrorNo == 3) {
            $this->error = '文件只有部分被上传';
            return false;
        } /*else if ($fileErrorNo == 4) {
            $this->redirect('add', array(), 1, '没有文件被上传');
        } */ else if ($fileErrorNo == 5) {
            $this->error = '上传文件大小为0';
            return false;
        }
    }


}