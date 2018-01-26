<?php
namespace app\admin\controller;

use app\admin\model\ArticleCate;
use app\admin\model\Article as ArticleModel;
use think\Request;
use think\Validate;

/**
 * 文章模块管理
 */

class Article extends Base
{
     // 文章列表
    public function index()
    {
        $list = ArticleModel::order('id desc')->paginate(10);
        // dump($list);exit();
        $this->assign('page',$list->render());
        $this->assign('list',$list);

        return $this->fetch('article/index');
    }
    // 添加文章
    public function add()
    {   
        if($this->request->isPost()){
            // 验证
            $valid_res = $this->validate($_POST, 'Article');
            if($valid_res !== true) {
                return ['status'=>'0','msg'=>$valid_res];
            }
            $ArticleModelModel = new ArticleModel($_POST);

            $data_res = $ArticleModelModel->allowField(true)->save();

            if (!$data_res) {
                return ['status'=>'0','msg'=>$this->getError()];
            }
            return ['status'=>'1','msg'=>'添加成功'];
        }

        $cate = new ArticleCate();
        $list = $cate ->cateTree();
        $this->assign('list',$list);

        return $this->fetch('article/add');
    }
    // 编辑文章
    public function edit()
    {

        if($this->request->isPost()){


            if(!isset($_POST['status'])){
                $result = $this->validate($_POST, 'Article.edit');

            }else{
                $result = true;
            }
            if( $result === true){
                if(ArticleModel::update($_POST)){
                    return ['status'=>'1','msg'=>'更新成功'];
                }else{
                    return ['status'=>'0','msg'=>$this->getError()];
                }
            }else{
                return ['status'=>'0','msg'=>$result];
            }
            
        }

        $cate = new ArticleCate();
        $list = $cate ->cateTree();
        $id = $this->request->param('id');
        $info = ArticleModel::get($id);
        $this->assign('info',$info);
        $this->assign('list',$list);
        return $this->fetch('article/edit');
    }
    // 删除文章
    public function del()
    {
        $id = $this->request->param('id');
        $user = ArticleModel::get($id);
        if($user->delete()){
            return ['status'=>'1','msg'=>'删除成功'];
        }else{
            return ['status'=>'1','msg'=>$this->getError()];
        }
    }













    // 分类列表
    public function cate_index()
    {
        $cate = new ArticleCate();
        $list = $cate ->cateTree();
        $this->assign('list',$list);
        return $this->fetch('article/cate_index');
    }
    // 添加分类
    public function cate_add()
    {   
        if($this->request->isPost()){
            // 验证
            $valid_res = $this->validate($_POST, 'ArticleCate');
            if($valid_res !== true) {
                return ['status'=>'0','msg'=>$valid_res];
            }
            $ArticleCateModel = new ArticleCate($_POST);

            $data_res = $ArticleCateModel->allowField(true)->save();

            if (!$data_res) {
                return ['status'=>'0','msg'=>$this->getError()];
            }
            return ['status'=>'1','msg'=>'添加成功'];
        }
        $cate = new ArticleCate();
        $list = $cate ->cateTree();
        $this->assign('list',$list);

        return $this->fetch('article/cate_add');
    }
    // 编辑分类

    public function cate_edit()
    {

        $cate = new ArticleCate();

        $list = $cate ->cateTree();
        $id = $this->request->param('id');
        $data = ArticleCate::get($id);
        $this->assign('list',$list);
        $this->assign('info',$data);

        if($this->request->isPost()){
            $result = $this->validate($_POST, 'ArticleCate.update');

            if( $result === true){
                    if(ArticleCate::update($_POST)){
                        return ['status'=>'1','msg'=>'更新成功'];
                    }else{
                        return ['status'=>'0','msg'=>$this->getError()];
                    }
            }else{
                return ['status'=>'0','msg'=>$result];
            }
            
        }
        return $this->fetch('article/cate_edit');
    }
    // 删除分类
    public function cateDel()
    {
        $id = $this->request->param('id');
        $list = ArticleCate::where('parent_id',$id)->select();
        if($list){
            return ['status'=>'0','msg'=>'该分类下存在子分类,不能被删除!'];
        }

        $user = ArticleCate::get($id );

        if($user->delete()){
            return ['status'=>'1','msg'=>'删除成功'];
        }else{
            return ['status'=>'1','msg'=>$this->getError()];
        }
    }
}
