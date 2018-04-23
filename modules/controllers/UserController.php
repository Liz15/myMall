<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/28
 * Time: 21:46
 */

namespace app\modules\controllers;
use app\models\Userinfo;
use yii\data\Pagination;
use app\models\User;
use Yii;

class UserController extends CommonController
{
    public function actionUsers()
    {
        $model = User::find()->joinWith('userinfo');//user关联userinfo表
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['user']; //分页
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $users = $model->offset($pager->offset)->limit($pager->limit)->all();
        $this->layout = "main";
        return $this->render('users', ['users' => $users, 'pager' => $pager]);
    }

    public function actionReg()
    {
        $this->layout = "main";
        $model = new User();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            }
        }
        $model->userpass = '';
        $model->repass = '';
        return $this->render("reg", ['model' => $model]);
    }

    public function actionDel()//先删除userinfo再删除user
    {
        try{
            $userid = (int)Yii::$app->request->get('userid');
            if (empty($userid)) {
                throw new \Exception();
            }
            $trans = Yii::$app->db->beginTransaction();//创建事务
            if ($obj = Userinfo::find()->where('userid = :id', [':id' => $userid])->one()) //判断有无该数据
            {
                $res = Userinfo::deleteAll('userid = :id', [':id' => $userid]);//删除
                if (empty($res)) {
                    throw new \Exception();
                }
            }
            if (!User::deleteAll('userid = :id', [':id' => $userid])) {
                throw new \Exception();
            }
            $trans->commit();//没有抛异常 提交
        } catch(\Exception $e) {
            if (Yii::$app->db->getTransaction()) {
                $trans->rollback(); //回
            }
        }
        $this->redirect(['user/users']);
    }

}