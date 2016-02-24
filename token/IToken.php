<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 6/19/15
 * Time: 15:58
 */

namespace mike\auth\token;


interface IToken
{
	public function createdToken();

	public function saveAuthApp($module);
}