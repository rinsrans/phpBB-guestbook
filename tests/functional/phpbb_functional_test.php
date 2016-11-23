<?php

/**
 * @group functional
 */
class phpbb_functional_test extends \phpbb_functional_test_case
{
    public function test_index()
    {
        $crawler = $this->request('GET', 'index.php');
        $this->assertGreaterThan(0, $crawler->filter('.topiclist')->count());
    }
}
