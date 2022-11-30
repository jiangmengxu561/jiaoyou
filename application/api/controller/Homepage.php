<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Config;
use think\Db;

/**
 * 示例接口
 */
class Homepage extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['carousel', 'consumptionList','nearby','common'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['test2'];

    //首页轮播图
    public function carousel(){

        $image = Config::get('site.carousel');
        $this->success('success',$image);

    }


//消费榜单
    public function consumptionList(){

        $data = Db::name('consumptionlist')
            ->order('createtime desc')
            ->whereTime('createtime','today')
            ->where('price','>=','30')
            ->select();

        $this->success('success',$data);
    }
//首页附近的人
    public function nearby(){

        $parameter = $this->request->param('parameter');
        $parpage = $this->request->param('parpage');

        if ($parameter == 1 ){
            $data = Db::name('user')
                ->orderRaw('rand()')
                ->field('avatar,nickname,charm,bio,address')
                ->paginate($parpage);
        }else{

            $where =[];
            if ($parameter['gender'] == 1){
                array_push($where,['gender','=',1]);
//                 ;
            }elseif ($parameter['gender'] == 2){
                $where[] = ['gender','=',2];
            }

            if (!empty($parameter['minimum'])&&!empty($parameter['highest'])){
                    $where[] =  [['age','<',$parameter['highest']],['age','>',$parameter['minimum']]];
            }
            if ($parameter['contact'] == 1){
                $where[]  = ['WeChatNumber' ,'=','no null'];
            }else{
                $where[]  = ['WeChatNumber','=', 'null'];
            }

            if (!empty($parameter['realname'])){
                $where[]  = ['isrealname' ,'=', $parameter['realname']];
            }
//            print_r($where);die;
            $data = Db::name('user')
                ->where($where)
                ->orderRaw('rand()')
                ->field('avatar,nickname,charm,bio,address')
//                ->paginate($parpage);
                ->select();
            print_r($data);die;
        }





        $this->success('success',$data);
    }

    public function common(){
        $data = Db::name('common')->select();

        $res = [];
        foreach ($data  as $k=>$v) {
            $res[$v['c_name']][] = $v;
        }
        $this->success('success',$res);

    }

}
