<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/4/8
 * Time: 21:16
 */

namespace app\modules\controllers;


use app\models\Category;
use app\models\Product;
use yii\data\Pagination;
use Yii;
use crazyfd\qiniu\Qiniu;


class ProductController extends CommonController
{

    public function actionList()//商品列表
    {
        $this->layout = "main";
        $model = Product::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['product'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);//分页，总数，页数
        $products = $model->offset($pager->offset)->limit($pager->limit)->all();//？分页显示
        return $this->render("products", ['pager' => $pager, 'products' => $products]);
    }

    public function actionAdd()//添加商品操作
    {
        $this->layout = 'main';
        $model = new Product();
        $cate = new Category();
        $list = $cate->getOptions();//类别选项
        unset($list[0]);

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $pics = $this->upload();
            if(!$pics){
                $model->addError('cover','封面不能为空');
            }
            else{
                $post['Product']['cover'] = $pics['cover'];//封面图片
                $post['Product']['pics'] = $pics['pics'];//商品头像
            }
            if($pics && $model->add($post)){
                Yii::$app->session->setFlash('info','添加成功');
            }else{
                Yii::$app->session->setFlash('info','添加失败');
            }
        }

        return $this->render('add',['opts'=>$list,'model'=>$model]);
    }

    private function upload()//内部访问，上传图片
    {
        if($_FILES['Product']['error']['cover'] > 0)
        {
            return false;
        }
        $qiniu = new Qiniu(Product::AK, Product::SK,Product::DOMAIN,Product::BUCKET,Product::zone);
        $key = uniqid();
        $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'],$key);
        $cover = $qiniu->getLink($key);//图片外链
        $pics = [];
        foreach ($_FILES['Product']['tmp_name']['pics'] as $k=>$file)
        {
            if($_FILES['Product']['error']['pics'][$k]>0){//有错
                continue;
            }
            $key = uniqid();
            $qiniu->uploadFile($file,$key);
            $pics[$key] = $qiniu->getLink($key);
        }
        return ['cover'=>$cover,'pics'=>json_encode($pics)];
    }

    public function actionMod()//修改商品
    {
        $this->layout = "main";
        $cate = new Category();
        $list = $cate->getOptions();//得到分类
        unset($list[0]);

        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            $post['Product']['cover'] = $model->cover;
            if ($_FILES['Product']['error']['cover'] == 0) {
                $key = uniqid();
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));

            }
            $pics = [];
            foreach($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;

                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);

            }
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');

            }

        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);

    }

    public function actionRemovepic()//删除七牛里面的图片
    {
        $key = Yii::$app->request->get("key");//得到图片
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);//删除
        $pics = json_decode($model->pics, true);
        unset($pics[$key]);
        Product::updateAll(['pics' => json_encode($pics)], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/mod', 'productid' => $productid]);
    }

    public function actionDel()//商品的删除
    {
        $productid = Yii::$app->request->get("productid");//get得到id
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();//找到商品
        $key = basename($model->cover);//图片key
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach($pics as $key=>$file) {
            $qiniu->delete($key);
        }
        Product::deleteAll('productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);//删除后跳回列表
    }

    public function actionOn()//商品上架处理
    {
        $productid = Yii::$app->request->get("productid");//得到id
        Product::updateAll(['ison' => '1'], 'productid = :pid', [':pid' => $productid]);//更新ison状态，上架
        return $this->redirect(['product/list']);//返回列表
    }

    public function actionOff()//下架处理
    {
        $productid = Yii::$app->request->get("productid");
        Product::updateAll(['ison' => '0'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }
}