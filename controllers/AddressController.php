<?php

namespace app\controllers;
use app\controllers\CommonController;

use Yii;
use app\models\User;
use app\models\Address;

class AddressController extends CommonController
{
    public function actionAdd()//添加地址
    {
        if (Yii::$app->session['isLogin'] != 1) {//判断是否登录
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        if (Yii::$app->request->isPost) {//得到post数据
            $post = Yii::$app->request->post();
            $post['userid'] = $userid;
            $post['address'] = $post['address1'].$post['address2'];
            $data['Address'] = $post;
            $model = new Address();
            $model->load($data);//批量赋值，填充属性
            $model->save();//保存数据库
        }
        return $this->redirect($_SERVER['HTTP_REFERER']);//上级请求来源
    }

    public function actionDel()//删除地址
    {
        if (Yii::$app->session['isLogin'] != 1) {//判断是否登录
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $addressid = Yii::$app->request->get('addressid');//get得到地址id
        if (!Address::find()->where('userid = :uid and addressid = :aid', [':uid' => $userid, ':aid' => $addressid])->one()) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        Address::deleteAll('addressid = :aid', [':aid' => $addressid]);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }










}
