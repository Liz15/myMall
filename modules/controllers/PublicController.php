<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/19
 * Time: 21:56
 */

namespace app\modules\controllers;
use app\modules\models\Admin;
use yii\web\Controller;
use Yii;

class PublicController extends Controller
{
    public function actionLogin(){
//        session_start();//验证session是否清空
//        var_dump($_SESSION);
        $this->layout = false;//去掉默认头尾
        $model = new Admin();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->login($post)){
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout(){
        Yii::$app->session->removeAll();
        if(!isset(Yii::$app->session['admin']['isLogin'])){
            $this->redirect(['public/login']);
            Yii::$app->end();
        }
        $this->goBack();
    }
    public function actionFindpass(){
        $this->layout = false;
        $model = new Admin();
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if ($model->findPass($post)) {
                Yii::$app->session->setFlash('info', '电子邮件已经发送成功，请查收');
            }
        }
        return $this->render('findpass',['model'=>$model]);
    }
}