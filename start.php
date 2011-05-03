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
        global $CONFIG;

        if (isadminloggedin()) {
            run_function_once('content_debugger_first_run');
            register_page_handler('content_debugger','content_debugger_page_handler');
            elgg_extend_view('metatags', 'content_debugger/metatags');
            elgg_extend_view('elgg_topbar/extend', 'content_debugger/menu');

            if ($_SESSION['content_debugger'] === 'enabled') {
                elgg_extend_view('metatags', 'content_debugger/active_metatags');
                register_elgg_event_handler('pagesetup','system','content_debugger_profiler_start');
                register_plugin_hook('display', 'view', 'content_debugger_view_hook', 1000);
                set_view_location('page_elements/header', $CONFIG->pluginspath . 'content_debugger/views/mod/');
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

    global $CONFIG;
    register_elgg_event_handler('init','system','content_debugger_init');

?>