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
	'GUESTBOOK'				=> 'Gästebuch',
	'CONFIRM_DELETE_POST'	=> 'Bist du sicher das du den Eintrag aus dem Gästebuch löschen möchtest?',
	'POST_DELETE_SUCCESS'	=> 'Der Eintrag wurde erfolgreich aus dem Gästebuch gelöscht!',
	'NO_POSTS_IN_GUESTBOOK'	=> 'Es sind noch keine Einträge im Gästebuch vorhanden. Sei der erste der einen Eintrag schreibt!',
	'BACK_TO_GUESTBOOK'		=> 'Zurück zum Gästebuch',
	'POST_SUCCESS'			=> 'Der Eintrag wurde erfolgreich zum Gästebuch hinzugefügt',
	'VIEW_TOPIC_POSTS'		=> array(
		1    => '%d Beitrag',
		2    => '%d Beiträge',
	),
));
