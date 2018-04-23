<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/18
 * Time: 22:43
 */

namespace app\controllers;
use app\models\Address;
use app\models\Cart;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;
use app\models\User;
use yii\web\Controller;
use Yii;

class OrderController extends CommonController
{
    public function actionCheck(){
        $this->layout = "layout1";
        if (Yii::$app->session['isLogin'] != 1) {//判断是否登录
            return $this->redirect(['member/auth']);//未登录，回到登录页面
        }
        $orderid = Yii::$app->request->get('orderid');//获得订单id
        $status = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one()->status;//获得订单状态
        if ($status != Order::CREATEORDER && $status != Order::CHECKORDER) {//不是订单初始化和待支付状态时
            return $this->redirect(['order/index']);//回到订单页面
        }
        $loginname = Yii::$app->session['loginname'];//session记录的登录名
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;//查找用户
        $addresses = Address::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();//该用户地址信息
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();//订单详情
        $data = [];
        foreach($details as $detail) {//查询订单里的商品信息
            //查询商品
            $model = Product::find()->where('productid = :pid' , [':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;//商品名称
            $detail['cover'] = $model->cover;//商品图片
            $data[] = $detail;
        }
        $express = Yii::$app->params['express'];//快递信息
        $expressPrice = Yii::$app->params['expressPrice'];//快递价格
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
    }
    public function actionIndex(){
        $this->layout = "layout2";
        if (Yii::$app->session['isLogin'] != 1) {//判断是否登录
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $orders = Order::getProducts($userid);
        return $this->render("index", ['orders' => $orders]);
    }

    public function actionAdd()//加入订单
    {
        if (Yii::$app->session['isLogin'] != 1) {//判断是否登录
            return $this->redirect(['member/auth']);
        }
        $transaction = Yii::$app->db->beginTransaction();//创建事务
        try {//异常处理
            if (Yii::$app->request->isPost) {///是否有提交
                $post = Yii::$app->request->post();
                $ordermodel = new Order();
                $ordermodel->scenario = 'add';//场景
                $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => Yii::$app->session['loginname'], ':email' => Yii::$app->session['loginname']])->one();//获得用户
                if (!$usermodel) {//空
                    throw new \Exception();
                }
                $userid = $usermodel->userid;//用户id
                $ordermodel->userid = $userid;
                $ordermodel->status = Order::CREATEORDER;//订单状态：初始
                $ordermodel->createtime = time();//创建时间
                if (!$ordermodel->save()) {//订单保存是否成功
                    throw new \Exception();
                }
                $orderid = $ordermodel->getPrimaryKey();//获得主键
                foreach ($post['OrderDetail'] as $product) {//接收订单详情列表
                    $model = new OrderDetail();
                    $product['orderid'] = $orderid;
                    $product['createtime'] = time();
                    $data['OrderDetail'] = $product;
                    if (!$model->add($data)) {
                        throw new \Exception();
                    }
                    Cart::deleteAll('productid = :pid' , [':pid' => $product['productid']]);//订单写入成功后，购物车清空
                    Product::updateAllCounters(['num' => -$product['productnum']], 'productid = :pid', [':pid' => $product['productid']]);//商品表，商品库存-1
                }
            }
            $transaction->commit();
        }catch(\Exception $e) {
            $transaction->rollback();
            return $this->redirect(['cart/index']);//异常，回到购物车页面
        }
        return $this->redirect(['order/check', 'orderid' => $orderid]);//如果完成，回到确认页
    }
}