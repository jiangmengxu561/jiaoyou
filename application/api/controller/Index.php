<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Config;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['carousel'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }




}
