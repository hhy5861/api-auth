<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 6/18/15
 * Time: 16:29
 */

namespace mike\auth\auth;


interface IdentityInterface
{
	public static function checkAccessToken($token);
}