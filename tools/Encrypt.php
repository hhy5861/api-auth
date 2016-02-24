<?php
namespace mike\auth\tools;

use yii;

/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 14-7-28
 * Time: 13:55
 */

class Encrypt
{
    private static $_key;

    private static $_instance;

    /**
     * 防止创建对象
     */
    private function __construct(){}

    //单例方法
    public static function getInstance($key = '')
    {
        self::$_key = $key;
        !self::$_key && self::$_key = 'hK8#ks^0J%1SxvR@1';

        !self::$_instance && self::$_instance = new self();
        return self::$_instance;
    }

    //阻止用户复制对象实例
    private function __clone()
    {
        trigger_error('Clone is not allow' ,E_USER_ERROR);
    }

    /**
     * 加密
     * @param $data 支持数据和字符串；
     * @return string 返回加密后的字符串
     */
    public function encrypt($data)
    {
        if(empty($data))
        {
            return '';
        }

        $data = igbinary_serialize($data);
        $data = Yii::$app->getSecurity()->encryptByKey($data,self::$_key);
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * 解密
     * @param $data 加密后的字符串；
     * @return bool|mixed|string 返回加密前的数据类型。
     */
    public function decrypt($data)
    {
        if(empty($data))
        {
            return '';
        }

        $data = explode('?',$data)[0];
        $data = base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        $data = Yii::$app->getSecurity()->decryptByKey($data,self::$_key);
        return igbinary_unserialize($data);
    }
}