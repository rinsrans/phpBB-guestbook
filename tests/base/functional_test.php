<?php

namespace rinsrans\guestbook\tests\base;
abstract class functional_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('rinsrans/guestbook');
	}
}
