<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mike\auth;

use Yii;
use yii\rest\Action;
use yii\helpers\Json;
use mike\auth\tools\Format;
use mike\auth\tools\Encrypt;

/**
 * Action is the base class for action classes that implement RESTful API.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ApiAction extends Action
{
    private $token;

    private $status = true;

    protected $data;

    public $tokenArr;

    public $modelClass = [];

    /**
     * @var callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($action, $model = null) {
     *     // $model is the requested model instance.
     *     // If null, it means no specific model (e.g. IndexAction)
     * }
     * ```
     */
    public $checkAccess;

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function checkParams()
    {
        return [];
    }

    /**
     * @return array|int
     */
    protected function check()
    {
        $str = '';
        foreach ($this->checkParams() as $k => $v)
        {
            if(!isset($this->data[$v]) || $this->data[$v] === '')
            {
                $str .= ','.$v;
            }
        }

        if($str)
        {
            return Format::messages(-1,'parameters:'.substr($str,1) .' to pass parameters will not be empty');
        }

        return true;
    }

	/**
	 * Parameter Handling
	 */
	protected function beforeRun()
	{
        $meg = '';
        if(Yii::$app->request->isGet)
        {
            $this->data  = Yii::$app->getRequest()->getQueryParams();
            isset($this->tokenArr['token']) && $this->token = $this->data['token'];
        }
        else
        {
            $param = Yii::$app->getRequest()->getRawBody();
            if($param)
            {
                $param = json_decode($param, true);
                if(json_last_error() !== JSON_ERROR_NONE)
                {
                    $this->status = false;
                    $meg = Format::messages(-2,'Json format error');
                }
                else
                {
                    $this->data = $param;
                }
            }

            $this->token = Yii::$app->getRequest()->getQueryParam('token');
        }

        if($this->token)
        {
            $this->tokenArr = Encrypt::getInstance()->decrypt($this->token);
        }

        if(isset($this->tokenArr['token']))
        {
            unset($this->tokenArr['token']);
        }

        if($this->status === true && ($meg = $this->check()) !== true)
        {
            $this->status = false;
        }

        if($this->status === false)
        {
            echo Json::encode($meg);
        }

		return $this->status;
	}
}
