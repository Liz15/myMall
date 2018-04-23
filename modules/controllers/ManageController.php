<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/25
 * Time: 19:39
 */

namespace app\modules\controllers;
use app\modules\models\Admin;
use Yii;

use yii\data\Pagination;
use yii\web\Controller;

class ManageController extends Controller//管理员操作
{
    public function actionMailchangepass()//修改密码
    {
        $this->layout = false;
        $time = Yii::$app->request->get('timestamp');
        $adminname = Yii::$app->request->get('adminname');
        $token = Yii::$app->request->get('token');
        $model = new Admin();
        $myToken = $model->createToken($adminname,$time);
        if($token != $myToken){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        if(time() - $time > 300){//链接五分钟内有效
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->changePass($post)){
                Yii::$app->session->setFlash('info','密码修改成功');
            }
        }
        $model->adminname = $adminname;
        return $this->render('mailchangepass',['model'=>$model]);
    }
    public function actionManagers()//管理员列表
    {
        $this->layout = 'main';
//        $managers = Admin::find()->all();//获取管理员列表
        $model = Admin::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['manage'];
        $pager = new Pagination(['totalCount'=>$count,'pageSize'=>$pageSize]);
        $managers = $model->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('managers',['managers'=>$managers,'pager'=>$pager]);
    }
    public function actionAddadmin()//添加管理员
    {
        $this->layout = 'main';
        $model = new Admin();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->addadmin($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }
            else{
                Yii::$app->session->setFlash('info','添加失败');
            }
        }
        return $this->render('addadmin',['model'=>$model]);
    }
    public function actionDeladmin()//删除管理员
    {
        $adminid = (int)Yii::$app->request->get('adminid');
        if(empty($adminid) || $adminid == 1){
            $this->redirect(['manage/managers']);
        }
        $model = new Admin();
        if($model->deleteAll('adminid = :id',[':id'=>$adminid])){
            Yii::$app->session->setFlash('info','删除成功');
            $this->redirect(['manage/managers']);
        }
    }
    public function actionChangeemail()//修改邮箱(个人信息)
    {
        $this->layout = 'main';
        $model = Admin::find()->where('adminname = :name', [':name' => Yii::$app->session['admin']['adminname']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changeemail($post)) {
                Yii::$app->session->setFlash('info', '修改成功');
            }else{
                Yii::$app->session->setFlash('info', '修改失败');
            }
        }
        $model->adminpass = "";
        return $this->render('changeemail', ['model' => $model]);
    }
    public function actionChangepass()
    {
        $this->layout = 'main';
        $model = Admin::find()->where('adminname = :name', [':name' => Yii::$app->session['admin']['adminname']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changepass($post)) {
                Yii::$app->session->setFlash('info', '密码修改成功');
            }
        }
        $model->adminpass = '';
        $model->repass = '';
        return $this->render('changepass', ['model' => $model]);
    }
}