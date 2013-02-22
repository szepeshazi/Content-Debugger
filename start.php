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

    require_once dirname(__FILE__) . '/lib/lib.content_debugger.php';

    $timestamps = new stdClass();

    /**
     * Content debugger initialization
     */
    function content_debugger_init() {

        if (elgg_is_admin_logged_in()) {
            run_function_once('content_debugger_first_run');
            elgg_register_page_handler('content_debugger','content_debugger_page_handler');
			
			$item = new ElggMenuItem('content_debugger', elgg_echo('content_debugger:toggle'), 'content_debugger/toggle');
			$item->setPriority(1000);
			elgg_register_menu_item('topbar', $item);

            if ($_SESSION['content_debugger'] === 'enabled') {
                elgg_extend_view('page/elements/head', 'content_debugger/active_metatags');
                elgg_register_event_handler('pagesetup','system','content_debugger_profiler_start');
                elgg_register_plugin_hook_handler('view', 'all', 'content_debugger_view_hook', 1000);
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
            switch($page[0]) {
                case 'toggle':
                    if (!isset($_SESSION['content_debugger']) || $_SESSION['content_debugger'] == 'disabled') {
                        $_SESSION['content_debugger'] = 'enabled';
                    } else {
                        $_SESSION['content_debugger'] = 'disabled';
                    }
                    forward($_SERVER['HTTP_REFERER']);
                    break;
            }
        }
    }


    elgg_register_event_handler('init','system','content_debugger_init');
