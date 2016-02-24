<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 6/19/15
 * Time: 13:35
 */

namespace mike\auth\tools;


trait Format
{
	public static function messages($status, $message = '', $data = [])
	{
		$arr = ['code'    => $status,
			    'message' => $message,
			    'data'    => $data
		       ];

		return $arr;
	}
}