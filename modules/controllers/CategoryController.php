<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/4/4
 * Time: 22:20
 */

namespace app\modules\controllers;


use app\models\Category;
use yii\web\Controller;
use Yii;

class CategoryController extends Controller
{
    public function actionList()//商品类别列表
    {
        $this->layout = 'main';
        $model = new Category();
        $cates = $model->getTreeList();
        return $this->render('cates',['cates'=>$cates]);
    }

    public function actionAdd()//添加商品类别
    {
        $this->layout = 'main';
        $model = new Category();
        $list = $model->getOptions();
//        $list = array_merge([0=>'添加顶级分类'],$list);//造成数组重编，value不正确
//        $list[0] = '添加顶级分类';
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if ($model->add($post))
            {
                Yii::$app->session->setFlash("info", "添加成功");
            }
        }
        return $this->render('add',['list'=>$list,'model'=>$model]);
    }

    public function actionMod()//编辑类别
    {
        $this->layout = "main";
        $cateid = Yii::$app->request->get("cateid");//get获得类别id
        $model = Category::find()->where('cateid = :id', [':id' => $cateid])->one();//查找类别数据
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {//载入，保存不为空
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }
        $list = $model->getOptions();//获得分类列表
        return $this->render('add', ['model' => $model, 'list' => $list]);
    }

    public function actionDel()//删除类别
    {
        try {
            $cateid = Yii::$app->request->get('cateid');//得到类别id
            if (empty($cateid)) {//若空
                throw new \Exception('参数错误');
            }
            $data = Category::find()->where('parentid = :pid', [":pid" => $cateid])->one();
            if ($data) {
                throw new \Exception('该分类下有子类，不允许删除');
            }
            if (!Category::deleteAll('cateid = :id', [":id" => $cateid])) {
                throw new \Exception('删除失败');
            }
        } catch(\Exception $e) {
            Yii::$app->session->setFlash('info', $e->getMessage());
        }
        return $this->redirect(['category/list']);
    }
}