<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/4/4
 * Time: 22:26
 */

namespace app\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%category}}";
    }

    public function attributeLabels()
    {
        return [
            'parentid'=>'上级分类',
            'title'=>'分类名称'
        ];
    }

    public function rules(){//字段规则
        return[
            ['parentid','required','message'=>'上级分类不能为空'],
            ['title','required','message'=>'类别名称不能为空'],
            ['createtime', 'safe']//创建时间 规则（安全）
        ];
    }

    public function add($data)//添加
    {
        $data['Category']['createtime'] = time();//类别添加的时间，获取当前时间
        if($this->load($data) && $this->save()){//载入并且添加成功
            return true;
        }
        return false;
    }

    public function getData()//获得类别，显示级别
    {
        $cates = self::find()->all();//获得所有数据
        $cates = ArrayHelper::toArray($cates);//转成数组
        return $cates;
    }

    public function getTree($cates, $pid = 0)//递归。级别树，默认获得第一级类别，后取出子类
    {
        $tree = [];
        foreach($cates as $cate) {//遍历类别
            if ($cate['parentid'] == $pid) {//获得顶级分类
                $tree[] = $cate;
                //合并数组
                $tree = array_merge($tree, $this->getTree($cates, $cate['cateid']));
            }
        }
        return $tree;
    }

    public function setPrefix($data, $p = "|__")//设置类别前缀，不同级别添加不同数量
    {
        $tree = [];
        $num = 1;
        $prefix = [0 => 1]; //parentid=0,一个前缀
        while($val = current($data)) {//获得值
            $key = key($data);//获得key
            if ($key > 0) {//第二次循环
                if ($data[$key - 1]['parentid'] != $val['parentid']) {//上一个元素key-1，级别改变
                    $num ++;
                }
            }
            if (array_key_exists($val['parentid'], $prefix)) {//级别是否已有前缀
                $num = $prefix[$val['parentid']];
            }
            $val['title'] = str_repeat($p, $num).$val['title'];//添加前缀
            $prefix[$val['parentid']] = $num;//级别
            $tree[] = $val;
            next($data);//往下走一个指针
        }
        return $tree;
    }

    public function getOptions()//获得option分类
    {
        $data = $this->getData();//获得数据
        $tree = $this->getTree($data);//得到分类树，级别
        $tree = $this->setPrefix($tree);//添加前缀
        $options = ['添加顶级分类'];
        foreach($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }

    public function getTreeList()//获得类别列表
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        return $tree = $this->setPrefix($tree);
    }

    public static function getMenu()
    {
        $top = self::find()->where('parentid = :pid', [":pid" => 0])->limit(11)->orderby('createtime asc')->asArray()->all();
        $data = [];
        foreach((array)$top as $k=>$cate) {
            $cate['children'] = self::find()->where("parentid = :pid", [":pid" => $cate['cateid']])->limit(10)->asArray()->all();
            $data[$k] = $cate;
        }
        return $data;
    }
}