<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;

/**
 * 示例接口
 */
class Dynamic extends Api
{

    //如果$noNeedLogin为空表示所有接口都需要登录才能请求
    //如果$noNeedRight为空表示所有接口都需要验证权限才能请求
    //如果接口已经设置无需登录,那也就无需鉴权了
    //
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['release', 'dynlist','dynDetaile','WriteComments','delcomment'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['test2'];
//发布动态
    public function release(){
        $user = $this->request->param();

        $data = $this->request->param();
        if (empty($data['couunt'])) $this->error('内容不能为空');
        if (empty($data['type'])) $this->error('类型不能为空');
        if ($data['type'] == 1){
            if (empty($data['image'])) $this->error('图片不能为空');
        }else{
            if (empty($data['video_file'])) $this->error('视频不能为空');
        }

        $data['iamge'] = implode(',',$data['image']);
        $data['createtime'] = time();
        $data['userid'] = $user['id'];

        $res = Db::name('dynamic')->insert($data);
        if ($res){
            $this->success('success','发布成功');
        }else{
            $this->success('error','发布失败');
        }

    }
    //动态列表
    public function dynlist(){
        $parpage = $this->request->param('parpage');
        $data = Db::name('dynamic')
            ->order('createtime desc')
            ->paginate($parpage);
        $this->success('success',$data);
    }

    //动态详情
    public function dynDetaile(){
        $parent_comment_id = $this->request->param('d_id');

        $data = Db::name('dynamic')->where('id',$parent_comment_id)->find();
        $wenku_comment_tmp = Db::name('comment')->where('parent_comment_id',$parent_comment_id)->select();
        $wenku_comment_ay_tmp = [];
//不用每次都查询数据库
        foreach ($wenku_comment_tmp as $k => $v) {
            $wenku_comment_ay_tmp[$v['comment_id']] = $v;
        }

        $company_user_comment_list = $this->getWenkuCommentDetailAll($wenku_comment_ay_tmp, $wenku_comment_tmp, $parent_comment_id);

        $data['images'] = explode(',',$data['images']);
        $res = [
            'xiangqing' => $data,
            'pinglun'   =>$company_user_comment_list
        ];

        $this->success('success',$res);
//        print_r($company_user_comment_list);

    }


//评论详情
    public  function getWenkuCommentDetailAll($wenku_comment_ay_tmp, $data, $parent_comment_id)
    {

        $wenku_comment_list = [];
        foreach ($data as $k => $v) {


            //如果上一级的评论id和父级id一致 则放入子级中
            if ($parent_comment_id == $v['reply_comment_id']) {

                $reply_comment_user = $reply_tip = '';
                //如果父级id和回复id，不是同一个则需要增加 回复 字样
                if ($v['parent_comment_id'] != $v['reply_comment_id']) {
                    $reply_tip = '回复';
                    $reply_comment_user = $wenku_comment_ay_tmp[$v['reply_comment_id']]['user_name'];
                }

                $child_list = $this->getWenkuCommentDetailAll($wenku_comment_ay_tmp, $data, $v['comment_id']);

                //对子级评论，做排序
                if ($child_list) {
                    $comment_id = array_column($child_list, 'comment_id');
                    array_multisort($comment_id, SORT_ASC, $child_list);
                }

                $wenku_comment_list[] = array(
                    'comment_id' => $v['comment_id'],
                    'parent_comment_id' => (int)$v['parent_comment_id'],
                    'reply_comment_id' => (int)$v['reply_comment_id'],
                    'reply_comment_user' => $reply_comment_user,
                    'reply_tip' => $reply_tip,
                    'comment_time' => $v['comment_time'],//评论日期
                    'user_name' => $v['user_name'],
                    'comment_content' => $v['comment_content'],//评论内容
                    'comment_user_id' => (int)$v['comment_user_id'],//评论用户id
                    'comment_user_header' => $v['comment_user_header'],//评论人头像
                    'list' => $child_list ?: [],
                );
                unset($reply_tip, $reply_comment_user, $child_list);
            }

        }
        return $wenku_comment_list;
    }

//写评论
    public  function WriteComments(){
        $comment_user_id = $this->auth->getUser();//评论人ID
//$comment_user_id = 1;
        $parent_comment_id = $this->request->param('parent_comment_id');//文章ID
        $reply_comment_id  = $this->request->param('reply_comment_id');//上级评论ID
        $comment_content  = $this->request->param('comment_content');//评论内容

        $comment_user_header = Db::name('user')->where('id',$comment_user_id)->value('avatar');//评论人头像
        $user_name = Db::name('user')->where('id',$comment_user_id)->value('username');//评论人名称
        $comment_time =time();//评论时间

        $data = [];
        $data['parent_comment_id'] = $parent_comment_id;
        if (empty($reply_comment_id)){
            $data['reply_comment_id'] = $parent_comment_id;
        }else{
            $data['reply_comment_id'] = $reply_comment_id;
        }
        $data['comment_content'] = $comment_content;
        $data['comment_user_header'] = $comment_user_header;
        $data['user_name'] = $user_name;
        $data['comment_time'] =date('Y-m-d H:i:s',$comment_time) ;

       $res = Db::name('comment')->insert($data);
       if ($res){
           $this->success('评论成功');
       }else{
           $this->error('系统错误');
       }
    }


    public function delcomment(){
        $comment_id = $this->request->param('comment_id');
        $res = Db::name('comment')
            ->where('comment_id',$comment_id)
            ->delete();
        if ($res){
            $this->success('删除成功');
        }else{
            $this->error('系统错误');
        }
    }


}