<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/4/8
 * Time: 21:18
 */

namespace app\models;


use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    const AK = 'U9MB4u7oBN5SY8Ft9lNd_g4H01tYt3vDetIrcOrf';
    const SK = 'WNrJwPg95JRAzCvFfv0hjD_he-ydiRgIgYNr6miw';
    const DOMAIN = 'http://p70fbazax.bkt.clouddn.com';
    const BUCKET = 'wenwen';
    const zone = 'north_china';
    public $cate;

    public static function tableName()
    {
        return "{{%product}}";
    }

    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空'],
            ['des', 'required', 'message' => '描述不能为空'],
            ['cateid', 'required', 'message' => '分类不能为空'],
            ['price', 'required', 'message' => '单价不能为空'],
            [['price','saleprice'], 'number', 'min' => 0.01, 'message' => '价格必须是数字'],
            ['num', 'integer', 'min' => 0, 'message' => '库存必须是数字'],
            [['issale','ishot', 'pics', 'istui'],'safe'],
            [['cover'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cateid' => '分类名称',
            'title'  => '商品名称',
            'des'  => '商品描述',
            'price'  => '商品价格',
            'ishot'  => '是否热卖',
            'issale' => '是否促销',
            'saleprice' => '促销价格',
            'num'    => '库存',
            'cover'  => '图片封面',
            'pics'   => '商品图片',
            'ison'   => '是否上架',
            'istui'   => '是否推荐',
        ];
    }

    public function add($data){
        if($this->load($data) && $this->save()){
            return true;
        }
        return false;
    }
}