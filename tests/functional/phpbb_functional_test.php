<?php

/**
 * @group functional
 */
class phpbb_functional_test extends \rinsrans\guestbook\tests\base\functional_test
{
	public function setUp()
	{
		parent::setUp();
		$this->purge_cache();
		$this->login();
	}

    public function test_guestbook()
    {
		$crawler = $this->request('GET', 'app.php/guestbook');
		
		$form = $crawler->selectButton('submit')->form();
		$form->setValues(array(
			'message'	=> 'test message',
			'title'		=> 'test'
		));
		$crawler = self::submit($form);



        $this->assertGreaterThan(0, $crawler->filter('.posts')->count());
    }
}
