<?php
/**
*
* @package phpBB Extension - Guestbook
* @copyright (c) 2015 rinsrans <karl.rinser@gmail.com>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace rinsrans\guestbook\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

class main
{
	/* @var \phpbb\auth\auth */
	protected $auth;
	/* @var \phpbb\config\config */
	protected $config;
	/* @var \phpbb\controller\helper */
	protected $helper;
	/* @var \phpbb\template\template */
	protected $template;
	/* @var \phpbb\user */
	protected $user;
	/* @var \phpbb\db\driver\driver */
	protected $db;
	/** @var ContainerInterface */
	protected $phpbb_container;
	/* @var string */
	protected $posts_table;
	/** @var string phpbb_root_path */
	protected $phpbb_root_path;
	/** @var string php_ext */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth			$auth
	* @param \phpbb\config\config		$config
	* @param \phpbb\controller\helper	$helper
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	* @param \phpbb\db\driver\driver	$db
	* @param ContainerInterface			$phpbb_container
	* @param string						$posts_table
	* @param string						$phpbb_root_path
	* @param string						$php_ext
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \phpbb\db\driver\driver_interface $db, ContainerInterface $phpbb_container, $posts_table, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->db = $db;
		$this->phpbb_container = $phpbb_container;
		$this->table_posts = $posts_table;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Guestbook controller for route /guestbook
	*
	* @param string		$name
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle()
	{
		$error = array();

		$this->user->add_lang_ext('rinsrans/guestbook', 'common');
		$base_url = $this->helper->route('rinsrans_guestbook_handle', array());

		include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);

		// Submit new post
		if ($this->auth->acl_get('u_guestbook_post'))
		{
			include($this->phpbb_root_path . 'includes/functions_posting.' . $this->php_ext);
			generate_smilies('inline', 0);
			display_custom_bbcodes();
			add_form_key('guestbook');
			$this->user->add_lang('posting');

			if ($this->request->is_set_post('preview') || $this->request->is_set_post('submit'))
			{
				$preview_text = $message = $this->request->variable('message', '', true);
				$title = $this->request->variable('title', '', true);

				// Store message length...
				$message_length = utf8_strlen($message);

				if (utf8_clean_string($title) === '')
				{
					$error[] = $this->user->lang['EMPTY_SUBJECT'];
				}

				if (utf8_clean_string($message) === '')
				{
					$error[] = $this->user->lang['TOO_FEW_CHARS'];
				}

				// Maximum message length check. 0 disables this check completely.
				if ((int) $this->config['max_post_chars'] > 0 && $message_length > (int) $this->config['max_post_chars'])
				{
					$error[] = $this->user->lang('CHARS_POST_CONTAINS', $message_length) . '<br />' . $this->user->lang('TOO_MANY_CHARS_LIMIT', (int) $this->config['max_post_chars']);
				}

				// Minimum message length check
				if (!$message_length || $message_length < (int) $this->config['min_post_chars'])
				{
					$error[] = (!$message_length) ? $this->user->lang['TOO_FEW_CHARS'] : ($this->user->lang('CHARS_POST_CONTAINS', $message_length) . '<br />' . $this->user->lang('TOO_FEW_CHARS_LIMIT', (int) $this->config['min_post_chars']));
				}

				if (sizeof($error))
				{
					$this->template->assign_vars(array(
						'TITLE'			=> $title,
						'MESSAGE'		=> $message,
					));
				}
			}

			// Preview
			if ($this->request->is_set_post('preview') && !sizeof($error))
			{
				generate_text_for_storage($preview_text, $uid, $bitfield, $options, true, true, true);
				$preview_text = generate_text_for_display($preview_text, $uid, $bitfield, $options);
				$this->template->assign_vars(array(
					'S_PREVIEW'				=> true,
					'TITLE'					=> $title,
					'PREVIEW_MESSAGE'		=> $preview_text,
					'MESSAGE'				=> $message,
				));
			}

			// Store to database
			if ($this->request->is_set_post('submit') && !sizeof($error))
			{
				if (!check_form_key('guestbook'))
				{
				   trigger_error($this->user->lang['FORM_INVALID']);
				}

				$username = $this->request->variable('username', $this->user->data['username'], true);
				$uid = $bitfield = $options = '';
				generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);
				$sql_data = array(
					'guestbook_title'			=> $title,
					'guestbook_text'			=> $message,
					'guestbook_name'			=> $username,
					'bbcode_uid'				=> $uid,
					'bbcode_bitfield'			=> $bitfield,
					'user_id'					=> $this->user->data['user_id'],
					'guestbook_time'			=> time(),
				);
				$sql = 'INSERT INTO ' . $this->table_posts . '
					' . $this->db->sql_build_array('INSERT', $sql_data);
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['POST_SUCCESS'] . '<br /><br /><a href="' . $base_url . '">' . $this->user->lang['BACK_TO_GUESTBOOK'] . '</a>');
			}
		}

		// Delete a post
		$delete = $this->request->variable('delete', 0);
		if ($delete && $this->auth->acl_get('u_guestbook_delete'))
		{
			if (confirm_box(true))
			{
				$sql = 'DELETE FROM ' . $this->table_posts . '
					WHERE guestbook_id = ' . (int) $delete;
				$this->db->sql_query($sql);
				trigger_error($this->user->lang['POST_DELETE_SUCCESS'] . '<br /><br /><a href="' . $base_url . '">' . $this->user->lang['BACK_TO_GUESTBOOK'] . '</a>');
			}
			else
			{
				$s_hidden_fields = build_hidden_fields(array(
					'delete'    => $delete,
				));
				confirm_box(false, $this->user->lang['CONFIRM_DELETE_POST'], $s_hidden_fields);
			}
		}

		// Add link to breadcrumbs
		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME'	=> $this->user->lang['GUESTBOOK'],
			'U_VIEW_FORUM'	=> $base_url,
		));

		// Generate pagination
		$pagination = $this->phpbb_container->get('pagination');
		$sql = 'SELECT COUNT(guestbook_id) AS num_posts
			FROM ' . $this->table_posts;
		$result = $this->db->sql_query($sql);
		$total_posts = (int) $this->db->sql_fetchfield('num_posts');
		$this->db->sql_freeresult($result);

		$sql_limit = $this->config['posts_per_page'];
		$start = request_var('start', 0);
		$start = $pagination->validate_start($start, $sql_limit, $total_posts);
		$pagination->generate_template_pagination($base_url, 'pagination', 'start', $total_posts, $sql_limit, $start);

		// Display posts
		$sql = 'SELECT g.*, u.*
			FROM ' . $this->table_posts . ' g, ' . USERS_TABLE . ' u
				WHERE g.user_id = u.user_id
			ORDER BY g.guestbook_time DESC';
		$result = $this->db->sql_query_limit($sql, $sql_limit, $start);
		while ($this->data = $this->db->sql_fetchrow($result))
		{
			$user_rank_data = phpbb_get_user_rank($this->data, $this->data['user_posts']);
			$this->template->assign_block_vars('posts', array(
				'U_DELETE'			=> append_sid($base_url, 'delete=' . $this->data['guestbook_id']),
				'TITLE'				=> censor_text($this->data['guestbook_title']),
				'POST'				=> generate_text_for_display($this->data['guestbook_text'], $this->data['bbcode_uid'], $this->data['bbcode_bitfield'], 3, true),
				'TIME'				=> $this->user->format_date($this->data['guestbook_time']),
				'POST_AUTHOR'		=> ($this->data['user_id'] == ANONYMOUS) ? $this->data['guestbook_name'] : get_username_string('full', $this->data['user_id'], $this->data['username'], $this->data['user_colour']),
				'RANK_TITLE'        => $user_rank_data['title'],
				'RANK_IMG'			=> $user_rank_data['img'],
				'RANK_IMG_SRC'		=> $user_rank_data['img_src'],
				'POSTER_JOINED'		=> ($this->data['user_id'] == ANONYMOUS) ? '' : $this->user->format_date($this->data['user_regdate']),
				'POSTER_POSTS'		=> ($this->data['user_id'] == ANONYMOUS) ? '' : $this->data['user_posts'],
				'POSTER_AVATAR'		=> phpbb_get_user_avatar($this->data),
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'ERROR'						=> (sizeof($error)) ? implode('<br />', $error) : '',
			'TOTAL_POSTS'				=> $this->user->lang('VIEW_TOPIC_POSTS', (int) $total_posts),
			'S_DISPLAY_USERNAME'		=> ($this->user->data['user_id'] == ANONYMOUS) ? true : false,
			'S_BBCODE_ALLOWED'			=> true,
			'U_ACTION'					=> $this->helper->route('rinsrans_guestbook_handle', array()),
			'S_AUTH_POST'				=> $this->auth->acl_get('u_guestbook_post'),
			'S_AUTH_DELETE'				=> $this->auth->acl_get('u_guestbook_delete'),
		));

		return $this->helper->render('guestbook_body.html');
	}
}
