<?php

namespace rinsrans\guestbook\tests;

abstract class database_test extends \phpbb_database_test_case
{
	static protected function setup_extensions()
	{
		return array('rinsrans/guestbook');
	}
	protected $db;
	public function setUp()
	{
		parent::setUp();
		global $db;
		$db = $this->db = $this->new_dbal();
	}
}