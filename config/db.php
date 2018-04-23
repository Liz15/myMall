<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=mymall', //数据源
    'username' => 'root', //数据库用户名
    'password' => 'root', //数据库密码
    'charset' => 'utf8', //字符集
    'tablePrefix' =>'mall_' //表前缀

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
