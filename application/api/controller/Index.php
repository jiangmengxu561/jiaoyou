<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\model\Config;
use think\Db;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['visitlist','follow','blacklist','followlist','fanslist'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }

    //访问记录
    public function visitlist(){
        $user = $this->auth->getUser();
        $user['id'] = 1;
        $type = $this->request->param('type');
        if ($type == 1){
            $data = Db::name('visit')
                ->alias('a')
                ->join('user b','a.active_user = b.id ')
                ->field('b.username,b.avatar,b.bio')
                ->where('a.active_user',$user['id'])
                ->order('a.createtime desc')
                ->select();

        }else{
            $data = Db::name('visit')
                ->alias('a')
                ->join('user b','a.passive_user = b.id ')
                ->field('b.username,b.avatar,b.bio')
                ->where('a.passive_user',$user['id'])
                ->order('a.createtime desc')
                ->select();
//            $data = Db::name('visit')->where('passive_user',$user)->order('createtime desc')->select();
        }

        $this->success('success',$data);
    }


    //关注用户
     public function follow(){
         $user = $this->auth->getUser();
         $user['id'] = 1;
         $followid = $this->request->param('followid');
         $visit = [
             'userid' => $user['id'],
             'followid'=> $followid,
             'createtime'  => time()
         ];
         $isfollowid = Db::name('follow')
             ->where('userid',$user['id'])
             ->where('followid',$followid)
             ->find();
         if($isfollowid){
            $res =  Db::name('follow')->where('id',$isfollowid['id'])->delete();
            if ($res){
                $this->success('取消关注成功');
            }
         }else{
            $res =  Db::name('follow')->insert($visit);
             if ($res){
                 $this->success('关注成功');
             }
         }
     }


    //拉黑用户
    public function blacklist(){
        $user = $this->auth->getUser();
        $user['id'] = 1;
        $blackid = $this->request->param('blackid');
        $list = [
            'userid' => $user['id'],
            'blackid'=> $blackid,
            'createtime'  => time()
        ];
        $isblackid = Db::name('blacklist')
            ->where('userid',$user['id'])
            ->where('blackid',$blackid)
            ->find();
        if($isblackid){
            $res =  Db::name('blacklist')->where('id',$isblackid['id'])->delete();
            if ($res){
                $this->success('取消拉黑成功');
            }
        }else{
            $res =  Db::name('blacklist')->insert($list);
            if ($res){
                $this->success('拉黑成功');
            }
        }
    }

//关注列表
    public function followlist(){
        $user = $this->auth->getUser();
        $user['id'] = 1;
        $followlist = Db::name('follow')
            ->alias('a')
            ->join('user b','a.followid = b.id')
            ->field('b.username,b.avatar,b.bio')
            ->where('a.userid',$user['id'])
            ->select();

        $this->success('success',$followlist);

    }
    //粉丝列表
    public function fanslist(){
        $user = $this->auth->getUser();
        $user['id'] = 1;
        $followlist = Db::name('follow')
            ->alias('a')
            ->join('user b','a.userid = b.id')
            ->field('b.username,b.avatar,b.bio')
            ->where('a.followid',$user['id'])
            ->select();
        $this->success('success',$followlist);

    }
}
