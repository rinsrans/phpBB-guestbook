<?php

namespace rinsrans\guestbook\tests\controller;

class main_test extends \phpbb_database_test_case
{

	static protected function setup_extensions()
	{
		return array('rinsrans/guestbook');
	}

	public function setUp()
	{
		parent::setUp();

	}

	public function test_install()
	{
		$this->db = $this->new_dbal();
		$db_tools = new \phpbb\db\tools($this->db);
		$this->assertTrue($db_tools->sql_table_exists('phpbb_guestbook'));
	}
}
