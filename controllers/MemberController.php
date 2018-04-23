<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/18
 * Time: 23:24
 */

namespace app\controllers;
use app\models\User;
use yii\web\Controller;
use Yii;


class MemberController extends CommonController
{
    public function actionAuth(){
        $this->layout = "layout2";
        $model = new User();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->login($post)){
//                return $this->goBack(Yii::$app->request->referrer);
                $this->redirect(['index/index']);
                Yii::$app->end();
            }
        }
        return $this->render('auth',['model'=>$model]);
    }
    public function actionLogout(){
        Yii::$app->session->remove('loginname');
        Yii::$app->session->remove('isLogin');
        if(!isset(Yii::$app->session['isLogin'])){
            return $this->goBack(Yii::$app->request->referrer);
        }
    }
    public function actionReg(){
        $model = new User();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->regByMail($post)){
                Yii::$app->session->setFlash('info','电子邮件发送成功');
            }
        }
        $this->layout = 'layout2';
        return $this->render('auth',['model'=>$model]);
    }
    public function actionQqlogin()//qq登录
    {
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();
    }

    public function actionQqcallback()//先判断有没有绑定QQ用户
    {
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $auth = new \OAuth();
        $accessToken = $auth->qq_callback();
        $openid = $auth->get_openid();
        $qc = new \QC($accessToken, $openid);
        $userinfo = $qc->get_user_info;
//        var_dump($userinfo);exit;
        $session = Yii::$app->session;
        $session['userinfo'] = $userinfo;//得到用户信息
        $session['openid'] = $openid;//qq唯一标识
        if ($model = User::find()->where('openid = :openid', [':openid' => $openid])->one()) {//是否已存在该用户
            $session['loginname'] = $model->username;
            $session['isLogin'] = 1;
            return $this->redirect(['index/index']);
        }
        return $this->redirect(['member/qqreg']);//注册
    }

    public function actionQqreg()//新建qq用户
    {
        $this->layout = "layout2";
        $model = new User();
        if (Yii::$app->request->isPost) {//如果post
            $post = Yii::$app->request->post();//接受post参数
            $session = Yii::$app->session;
            $post['User']['openid'] = $session['openid'];
            if ($model->reg($post, 'qqreg')) {//如果注册成功
                $session['loginname'] = $post['User']['username'];//用户名存入session
                $session['isLogin'] = 1;
                return $this->redirect(['index/index']);//回到首页
            }
        }
        return $this->render('qqreg', ['model' => $model]);
    }
}