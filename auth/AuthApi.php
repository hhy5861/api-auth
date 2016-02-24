<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mike\auth\auth;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class AuthApi extends Component
{

    /**
     * @var string the class name of the [[identity]] object.
     */
    public $identityClass;

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();
        if ($this->identityClass === null) {
            throw new InvalidConfigException('User::identityClass must be set.');
        }
    }

    /**
     * Logs in a user by the given access token.
     * This method will first authenticate the user by calling [[IdentityInterface::findIdentityByAccessToken()]]
     * with the provided access token. If successful, it will call [[login()]] to log in the authenticated user.
     * If authentication fails or [[login()]] is unsuccessful, it will return null.
     * @param string $token the access token
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface|null the identity associated with the given access token. Null is returned if
     * the access token is invalid or [[login()]] is unsuccessful.
     */
    public function loginByAccessToken($token, $type = null)
    {
        /* @var $class IdentityInterface */
        $class = $this->identityClass;
        $identity = $class::checkAccessToken($token, $type);
        if ($identity) {
            return $identity;
        } else {
            return null;
        }
    }

}
