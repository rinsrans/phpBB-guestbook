<?php
/**
*
* @package phpBB Extension - Guestbook
* @copyright (c) 2015 rinsrans <karl.rinser@gmail.com>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace rinsrans\guestbook\migrations;

class initial_module extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'guestbook'	=> array(
					'COLUMNS'		=> array(
						'guestbook_id'			=> array('UINT', null, 'auto_increment'),
						'guestbook_title'		=> array('VCHAR:255', ''),
						'guestbook_text'		=> array('MTEXT_UNI', ''),
						'guestbook_name'		=> array('VCHAR:255', ''),
						'bbcode_uid'			=> array('VCHAR:10', ''),
						'bbcode_bitfield'		=> array('VCHAR:32', ''),
						'user_id'				=> array('UINT', 0),
						'guestbook_time'		=> array('TIMESTAMP', 0),
					),
					'PRIMARY_KEY'	=> 'guestbook_id',
				),
			),
		);
	}
	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'guestbook',
			),
		);
	}
	public function update_data()
	{
		return array(
			array('permission.add', array('u_guestbook_post', true, 'u_search')),
			array('permission.add', array('u_guestbook_delete', true, 'a_board')),
		);
	}
}
