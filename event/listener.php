<?php
/**
*
* @package phpBB Extension - Guestbook
* @copyright (c) 2015 rinsrans <karl.rinser@gmail.com>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace rinsrans\guestbook\event;

/**
* @ignore
*/

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'						=> 'page_header',
			'core.permissions'						=> 'permissions',
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template			$template	Template object
	* @param \phpbb\user				$user		User object
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	public function permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions += array(
			'u_guestbook_post'		=> array(
				'lang'		=> 'ACL_U_GUESTBOOK_POST',
				'cat'		=> 'guestbook'
			),
			'u_guestbook_delete'	=> array(
				'lang'		=> 'ACL_U_GUESTBOOK_DELETE',
				'cat'		=> 'guestbook'
			),
		);
		$event['permissions'] = $permissions;

		$categories['guestbook'] = 'ACL_CAT_GUESTBOOK';
		$event['categories'] = array_merge($event['categories'], $categories);

	}

	public function page_header($event)
	{
		$this->user->add_lang_ext('rinsrans/guestbook', 'common');
		$this->template->assign_vars(array(
			'U_GUESTBOOK'	=> $this->helper->route('rinsrans_guestbook_handle', array()),
		));
	}
}
