<?php

/**
 * Content debugger plugin
 *
 * @package content_debugger
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Andras Szepeshazi
 * @copyright Andras Szepeshazi
 * @link http://wamped.org
 */

// Global variable to hold profiling info while rendering views
$content_debugger_timestamps = new stdClass();

/**
 * Content debugger initialization
 */
function content_debugger_init() {

	if (elgg_is_admin_logged_in()) {

		run_function_once('content_debugger_first_run');
		elgg_register_page_handler('content_debugger', 'content_debugger_page_handler');

		$base_name = basename(dirname(__FILE__));
		$path = elgg_get_plugins_path().$base_name;
		elgg_register_library('content_debugger', $path.'/lib/content_debugger/content_debugger.php');
		elgg_load_library('content_debugger');

		elgg_register_js('poshytips', "mod/$base_name/vendors/poshytip-1.0/src/jquery.poshytip.js");

		$magicmarker_path = elgg_get_simplecache_url('js', 'content_debugger/magicmarker');
		elgg_register_simplecache_view('js/content_debugger/magicmarker');
		elgg_register_js('content_debugger.magicmarker', $magicmarker_path);

		$content_debugger_path = elgg_get_simplecache_url('js', 'content_debugger/content_debugger');
		elgg_register_simplecache_view('js/content_debugger/content_debugger');
		elgg_register_js('content_debugger.content_debugger', $content_debugger_path);

		$menu_icon_class = "elgg-icon elgg-icon-info";

		$ui = content_debugger_get_ui_type();
		if ($_SESSION['content_debugger'][$ui] === 'enabled') {
			$menu_icon_class .= '-active';

			elgg_load_js('poshytips');
			elgg_load_js('content_debugger.magicmarker');
			elgg_load_js('content_debugger.content_debugger');

			elgg_extend_view('css/elgg', 'content_debugger/css');
			elgg_extend_view('css/admin', 'content_debugger/css');

			elgg_register_event_handler('pagesetup', 'system', 'content_debugger_profiler_start');
			elgg_register_plugin_hook_handler('view', 'all', 'content_debugger_view_hook', 1000);
		}

		if ($ui === 'frontend') {
			elgg_register_menu_item('topbar', array(
				'name' => 'content_debugger',
				'href' => 'content_debugger/toggle',
				'text' => "<span class='$menu_icon_class'></span>",
				'priority' => 90,
				'title' => elgg_echo('content_debugger:toggle'),
				'section' => 'alt'
			));
		} else {
			$admin_status = 'enabled';
			if (!isset($_SESSION['content_debugger'][$ui]) || $_SESSION['content_debugger'][$ui] == 'disabled') {
				$admin_status = 'disabled';
			}
			elgg_register_menu_item('page', array(
				'name' => 'content_debugger',
				'href' => 'content_debugger/toggle',
				'text' => elgg_echo("content_debugger:toggle:$admin_status"),
				'section' => 'develop',
				'parent_name' => 'develop_tools'
			));
		}
	}
}

/**
 * Content debugger page handler
 *
 * @param array $page Array of page elements, forwarded by the page handling mechanism
 */
function content_debugger_page_handler($page) {
	if (isset($page[0]) && !empty($page[0])) {
		switch ($page[0]) {
		case 'toggle':
			$ui = content_debugger_get_ui_type();
			if (!isset($_SESSION['content_debugger'][$ui]) || $_SESSION['content_debugger'][$ui] == 'disabled') {
				$_SESSION['content_debugger'][$ui] = 'enabled';
			} else {
				$_SESSION['content_debugger'][$ui] = 'disabled';
			}
			forward($_SERVER['HTTP_REFERER']);
			break;
		}
	}
}

elgg_register_event_handler('init', 'system', 'content_debugger_init');
