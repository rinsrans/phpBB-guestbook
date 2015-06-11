<?php
/**
*
* @package phpBB Extension - Guestbook
* @copyright (c) 2015 rinsrans <karl.rinser@gmail.com>
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//
$lang = array_merge($lang, array(
    'GUESTBOOK'				=> 'Guestbook',
	'CONFIRM_DELETE_POST'	=> 'Are you sure you want to delete the entry from the guestbook?',
	'POST_DELETE_SUCCESS'	=> 'The entry was successfully deleted from the guestbook!',
	'NO_POSTS_IN_GUESTBOOK'	=> 'There are currently no guestbook entries available. Be the first to write a entry!',
	'BACK_TO_GUESTBOOK'		=> 'Back to guestbook',
	'POST_SUCCESS'			=> 'The entry was successfully added to the guestbook',
	'VIEW_TOPIC_POSTS'        => array(
		1    => '%d post',
		2    => '%d posts',
	),
));
