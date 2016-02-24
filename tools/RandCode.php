<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 14-7-21
 * Time: 11:53
 */

namespace mike\auth\tools;


class RandCode
{
    private static $result;

    private static $tempCode;

    private static $_instance;

    /**
     * 防止创建对象
     */
    private function __construct(){}

    //单例方法
    public static function getInstance()
    {
        !self::$_instance && self::$_instance = new self();
        return self::$_instance;
    }

    //阻止用户复制对象实例
    private function __clone()
    {
        trigger_error('Clone is not allow' ,E_USER_ERROR);
    }

    /**
     *   函数名称:   createSnCode
     *   函数功能:   设置种子
     *   输入参数:   $size -------------- 要生成的数量
     *              $length ----------- 长度
     *              $mode ------------- 模式
     *   函数返回值: 返回值说明
     *   其它说明:   说明
     */
    public static function createCode($size,$length,$mode = 0)
    {
        $code_array = [];
        $offset = 1.5;// 为避免递归，采用取子集的办法
        $offsize = $size * $offset;
        for($count = 0; $count < $offsize; $count++)
        {
            self::seed($length,$mode);
            $code_array[] = self::$tempCode;
        }
        $unique_array = array_unique($code_array);
        self::$result = array_slice($unique_array, 0,$size);
        return self::$result;
    }

    /**
     *   函数名称:   seed
     *   函数功能:   设置种子
     *   输入参数:   $length ----------- 长度
     *              $mode ----------- 模式
     *   函数返回值: 返回值说明
     *   其它说明:   说明
     */
    private static function seed($length=7, $mode)
    {
        switch($mode)
        {
            case '1':
                $str = '1234567890';
                break;
            case '2':
                $str = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case '4':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case '5':
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '6':
                $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            default:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                break;
        }

        $resultArr = '';
        $seedlength = strlen($str)-1;
        for($i = 0; $i <= $length-1; $i++)
        {
            $num = mt_rand(0, $seedlength);
            $resultArr .= $str[$num];
        }
        self::$tempCode = $resultArr;
        return self::$tempCode;
    }
} 