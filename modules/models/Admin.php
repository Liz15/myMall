<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2018/3/21
 * Time: 22:05
 */
namespace app\modules\models;
use yii\db\ActiveRecord;
use Yii;

class Admin extends ActiveRecord
{
    public $remember_me = true;
    public $repass;
    public static function tableName()//操作哪张表
    {
        return "{{%admin}}"; // mall_admin
    }
    public function attributeLabels()//lable标签名称
    {
        return [
            'adminname' => '管理员账号',
            'adminemail' => '管理员邮箱',
            'adminpass' => '管理员密码',
            'repass' => '确认密码',
        ];
    }
    public function rules()//数组错误返回
    {
        return [//设置不同场景
            ['adminname','required','message'=>'管理员账号不能为空','on'=>['login','findpass','changepass','adminadd','changeemail']],
            ['adminpass','required','message'=>'管理员密码不能为空','on'=>['login','changepass','adminadd','changeemail']],
            ['remember_me','boolean','on'=>'login'],
            ['adminpass','validatePass','on'=>['login','changeemail']],
            ['adminemail','required','message'=>'电子邮箱不能为空','on'=>['findpass','adminadd','changeemail']],
            ['adminemail','email','message'=>'电子邮箱格式不正确','on'=>['findpass','adminadd','changeemail']],
            ['adminemail','unique','message'=>'电子邮箱格式已被注册','on'=>['adminadd','changeemail']],
            ['adminname','unique','message'=>'帐号已被注册','on'=>['adminadd']],
            ['adminemail','validateEmail','on'=>'findpass'],
            ['repass','required','message'=>'确认密码不能为空','on'=>['changepass','adminadd']],
            ['repass','compare','compareAttribute'=>'adminpass','message'=>'两次密码输入不一致','on'=>['changepass','adminadd']]

        ];
    }
    public function validatePass(){
        if(!$this->hasErrors()){//没有错误
            $data = self::find()->where('adminname = :name and adminpass = :pass',[':name'=>$this->adminname,':pass'=>md5($this->adminpass)])->one();
            if(is_null($data)){
                $this->addError('adminpass','用户名或密码错误');
            }
        }
    }
    public function validateEmail(){//验证邮箱
        if(!$this->hasErrors()){
            $data = self::find()->where('adminname = :name and adminemail = :email',[':name'=> $this->adminname,':email'=>$this->adminemail])->one();

            if(is_null($data)){
                $this->addError('adminemail','管理员邮箱不匹配');
            }
        }
    }
    public function login($data){//登录验证，session写入
        $this->scenario = "login";//和修改密码 不同场景设置
        if($this->load($data) && $this->validate()){
            //登录成功，session写入，记住我，登陆信息保留一天
            $lifetime = $this->remember_me ? 24*3600 : 0;
            $session = Yii::$app->session;
            session_set_cookie_params($lifetime);
            $session['admin'] =[
                'adminname'=>$this->adminname,
                'isLogin'=>1
            ];
            $this->updateAll(['logintime'=>time(),'loginip'=>ip2long(Yii::$app->request->userIP)],'adminname = :name',[':name'=>$this->adminname]);
            return(bool)$session['admin']['isLogin'];
        }
        return false;

    }
    public function findPass($data){//找回密码
        $this->scenario = "findpass";//和登录 不同场景设置
        if($this->load($data) && $this->validate())//验证数据
        {
            $time = time();
            $token = $this->createToken($data['Admin']['adminname'], $time);
            $mailer = Yii::$app->mailer->compose('findpass', ['adminname' => $data['Admin']['adminname'], 'time' => $time, 'token' => $token]);
            $mailer->setFrom("isWenli@163.com");
            $mailer->setTo($data['Admin']['adminemail']);
            $mailer->setSubject("文文数码商城-找回密码");
            if ($mailer->send()) {
                return true;
            }
        }
        return false;
    }

    public function createToken($adminname, $time)
    {
        return md5(md5($adminname).base64_encode(Yii::$app->request->userIP).md5($time));
    }

    public function changePass($data)//修改密码
    {
        $this->scenario = "changepass";//场景
        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminpass' => md5($this->adminpass)], 'adminname = :name', [':name' => $this->adminname]);
        }
        return false;
    }
    public function addadmin($data)
    {
        $this->scenario = 'adminadd';
        if ($this->load($data) && $this->validate()) {
            $this->adminpass = md5($this->adminpass);
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function changeemail($data)
    {
        $this->scenario = "changeemail";
        if ($this->load($data) && $this->validate()) {
            return (bool)$this->updateAll(['adminemail' => $this->adminemail], 'adminname = :name', [':name' => $this->adminname]);
        }
        return false;
    }
}