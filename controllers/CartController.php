<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/18
 * Time: 22:05
 */

namespace app\controllers;
use app\models\Cart;
use app\models\Product;
use app\models\User;
use yii\web\Controller;
use Yii;

class CartController extends CommonController
{
    public function actionIndex(){
        $this->layout = "layout1";
        if(Yii::$app->session['isLogin'] !=1){
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name or useremail = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
        $cart = Cart::find()->where('userid = :uid',[':uid'=>$userid])->asArray()->all();
        $data = [];
        foreach ($cart as $k=>$pro){
            $product = Product::find()->where('productid = :pid',[':pid'=>$pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
        }
        return $this->render('index',['data'=>$data]);
    }
    public function actionAdd(){
        if(Yii::$app->session['isLogin'] != 1){//判断是否登录
            return $this->redirect(['member/auth']);//未登录，回到登录页面
        }
        //得到userid
        $userid = User::find()->where('username = :name or useremail = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
        if(Yii::$app->request->isPost){//post提交
            $post = Yii::$app->request->post();
            $num = Yii::$app->request->post()['productnum'];//productnum,productid,price
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userid;
        }
        if(Yii::$app->request->isGet){//get提交
            $productid = Yii::$app->request->get('productid');//productid
            $model = Product::find()->where('productid = :pid',[':pid'=>$productid])->one();
            $price = $model->issale?$model->saleprice:$model->price;//判断是否促销，促销价格
            $num = 1;
            $data['Cart'] = ['productid' => $productid,'productnum' => $num,'price' =>$price,'userid' =>$userid];
        }
        //判断购物车是否已有，有+1，无添加
        if(!$model = Cart::find()->where('productid = :pid and userid = :uid',[':pid'=>$data['Cart']['productid'],':uid'=>$data['Cart']['userid']])->one()){
            $model = new Cart();
        }else{
            $data['Cart']['productnum'] = $model->productnum + $num;//更新+1
        }
        $data['Cart']['createtime'] = time();
        $model->load($data);
        $model->save();//保存购物车，如果是cart::find产生的model，只更新；如果是new cart，则添加
        return $this->redirect(['cart/index']);
    }
    public function actionMod()//修改购物车数量
    {
        $cartid = Yii::$app->request->get("cartid");
        $productnum = Yii::$app->request->get("productnum");
        Cart::updateAll(['productnum' => $productnum], 'cartid = :cid', [':cid' => $cartid]);
    }

    public function actionDel()
    {
        $cartid = Yii::$app->request->get("cartid");
        Cart::deleteAll('cartid = :cid', [':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }
}