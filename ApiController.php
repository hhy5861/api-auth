<?php
namespace mike\auth;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\QueryParamAuth;

class ApiController extends Controller
{
	public $modelClass;

	public $authMethods = true;

	/**
	 * @var string|array the configuration for creating the serializer that formats the response data.
	 */
	public $serializer = ['class'               => 'yii\rest\Serializer',
	                      'collectionEnvelope'  => 'items'
	                     ];

	/**
	 * @inheritdoc
	 */
	public $enableCsrfValidation = false;


	public function init()
	{
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors['authenticator']['authMethods'] = !$this->authMethods ? [] : [QueryParamAuth::className(),];
		$behaviors['authenticator']['user']        = Yii::createObject(['class'         => 'mike\auth\auth\AuthApi',
			                                                            'identityClass' => 'mike\auth\auth\CheckToken']);

		return $behaviors;
	}
}
