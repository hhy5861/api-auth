<?php

namespace mike\auth\model;

use Yii;

/**
 * This is the model class for table "{{%t_api_auth}}".
 *
 * @property integer $id
 * @property string $rand
 * @property string $appid
 * @property string $secretid
 * @property integer $ctime
 * @property integer $utime
 * @property integer $module
 * @property string $valid
 */
class ApiAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%api_auth}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('auth');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['secretid', 'ctime', 'utime'], 'required'],
            [['ctime', 'utime', 'module'], 'integer'],
            [['valid'], 'string'],
            [['rand'], 'string', 'max' => 6],
            [['appid'], 'string', 'max' => 32],
            [['secretid'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rand' => Yii::t('app', '随机码'),
            'appid' => Yii::t('app', 'appid'),
            'secretid' => Yii::t('app', 'secretid'),
            'ctime' => Yii::t('app', '创建时间'),
            'utime' => Yii::t('app', '更新时间'),
            'module' => Yii::t('app', '注册平台模块id'),
            'valid' => Yii::t('app', '数据在效性（0：有效，1：无效）'),
        ];
    }
}
