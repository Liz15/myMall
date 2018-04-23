<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/28
 * Time: 21:49
 */

namespace app\models;

use yii\db\ActiveRecord;

class Userinfo extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%userinfo}}";
    }
}