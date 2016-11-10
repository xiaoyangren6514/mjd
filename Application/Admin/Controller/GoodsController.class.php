<?php
/**
 * Created by PhpStorm.
 * User: zhonglongquan
 * Date: 2016/11/10
 * Time: 13:16
 */

namespace Admin\Controller;


use Think\Controller;

class GoodsController extends Controller
{

    public function add()
    {
        if (IS_POST) {
            $goods = D('goods');
            /**
             * 1 添加 2 修改
             * I('post.name','','htmlspecialchars');
             * 采用htmlspecialchars方法对$_POST['name'] 进行过滤，如果不存在则返回空字符串
             * 系统默认的变量过滤机制
             * 'DEFAULT_FILTER'        => 'htmlspecialchars'
             */
            if ($goods->create(I('post.'), 1)) {
                if ($goods->add()) {
                    $this->success('操作成功', U('showList'));
                    exit;
                }
            }
            $error = $goods->getError();
            $this->error($error);
        }
        $this->display();
    }

    public function showList()
    {
        echo 'showList';
    }

}