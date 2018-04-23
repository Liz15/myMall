<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/18
 * Time: 19:33
 */
namespace app\controllers;
use app\models\Product;
use app\models\Test;

class IndexController extends CommonController
{
    public function actionIndex(){
        $this->layout = "layout1";
        $data['tui'] = Product::find()->where('istui = "1" and ison = "1"')->orderby('createtime desc')->limit(4)->all();
        $data['new'] = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(4)->all();
        $data['hot'] = Product::find()->where('ison = "1" and ishot = "1"')->orderby('createtime desc')->limit(4)->all();
        $data['all'] = Product::find()->where('ison = "1"')->orderby('createtime desc')->limit(7)->all();
        return $this->render("index", ['data' => $data]);
//        $model = new Test();
//        $data = $model->find()->one();
//        return $this->render("index",array("row"=>$data));
    }
}