<?php

/**
 * @group functional
 */
class phpbb_functional_test extends \phpbb_functional_test_case
{
	public function setUp()
	{
		parent::setUp();
		$this->purge_cache();
		$this->login();
	}

    public function test_index()
    {
        $crawler = $this->request('GET', 'app.php/guestbook');
        $this->assertGreaterThan(0, $crawler->filter('.posts')->count());
    }
}
