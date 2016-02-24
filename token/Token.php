<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 6/19/15
 * Time: 09:16
 */

namespace mike\auth\token;

use Yii;
use yii\db\Query;
use yii\base\Object;
use mike\auth\tools\Format;
use mike\auth\tools\Encrypt;
use mike\auth\model\ApiAuth;
use mike\auth\tools\RandCode;

class Token extends Object implements IToken
{
	private $id;

	private $rand;

	public $appid;

	public $secretid;

	public function __construct(array $config = [])
	{
		parent::__construct($config);
	}

	/**
	 * created token
	 *
	 * @return bool|string
	 */
	public function createdToken()
	{
		if($data = $this->getAccountInfo())
		{
			if($this->secretid !== $data['secretid'])
			{
				return Format::messages(100002,'secret incorrect, please review the management center');
			}

			$tokenArr['uid']   = $data['id'];
			$tokenArr['code']  = RandCode::getInstance()->createCode(1,6,1)[0];
			$tokenArr['token'] = RandCode::getInstance()->createCode(1,32)[0];

			$accessToken = Encrypt::getInstance()->encrypt($tokenArr);

			$key    = TOKEN_KEY . $data['id'];
			$expire = TOKEN_EXPIRE;

			$status = Yii::$app->memCache->set($key,$tokenArr,$expire);

			if($status)
				return Format::messages(0,'get token success',['access_token' => $accessToken, 'expire' => TOKEN_EXPIRE]);
			else
				return Format::messages('100005','set memcache fail, check service');
		}

		return Format::messages(100001,'the user has not authorized');
	}

	/**
	 * 获取授权
	 *
	 * @return array|null|\yii\db\ActiveRecord
	 */
	protected function getAccountInfo()
	{
		$data = (new Query())->from(ApiAuth::tableName())
			    ->where('appid = :appid AND valid = :valid',
					   [':appid' => $this->appid, ':valid' => '0'])
			    ->one(Yii::$app->auth);

		$this->rand = $data['rand'];
		return $data;
	}

	/**
	 * set secret
	 *
	 * @return string
	 */
	private function setSecret()
	{
		!$this->rand && $this->rand = RandCode::getInstance()->createCode(1,6,1)[0];
		return md5(sha1($this->secretid) . $this->rand);
	}

	/**
	 * set appid
	 *
	 * @param $module
	 * @return $this
	 */
	private function setAppId($module)
	{
		list($t1, $t2) = explode(' ', microtime());
		$_appId        = (float) sprintf('%.0f',(floatval($t1) + floatval($t2)) * 1000);
		$_appId       .= $module;

		$this->id      = 'CYA';
		$this->id     .= $_appId;

		return $this;
	}

	/**
	 * save appid user info
	 *
	 * @param $module
	 * @return mixed
	 */
	public function saveAuthApp($module)
	{
		$apiAuth = new ApiAuth();

		$apiAuth->ctime    = TIME;
		$apiAuth->utime    = TIME;
		$apiAuth->module   = $module;
		$apiAuth->appid    = $this->setAppId($module)->id;
		$apiAuth->secretid = $this->setSecret();
		$apiAuth->rand     = $this->rand;

		if($apiAuth->save())
		{
			$code = 0;
			$meg  = 'created auth id success';
			$data = ['id'       => $apiAuth->primaryKey,
					 'appid'    => $apiAuth->appid,
				     'secretid' => $apiAuth->secretid
			        ];
		}
		else
		{
			$code = 100003;
			$meg  = 'created auth id failure';
			$data = [];
		}

		return Format::messages($code, $meg, $data);
	}
}