<?php

namespace rinsrans\guestbook\tests\controller;

abstract class main_test extends \rinsrans\guestbook\tests\database_test
{

	public function setUp()
	{
		parent::setUp();
	}

	public function test_install()
	{
		$db_tools = new \phpbb\db\tools($this->db);
		$this->assertTrue($db_tools->sql_table_exists('phpbb_guestbook'));
		$this->assertTrue($db_tools->sql_table_exists('phpbb_guestbook1'));
	}

	public function test_display_page()
	{
		$this->assertNull($this->controller_main->handle());
	}

}
