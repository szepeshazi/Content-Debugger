<?php

/**
 * Creates default plugin settings upon first run
 */
$base_name = basename(dirname(__FILE__));
elgg_set_plugin_setting('display_depth', '0', $base_name);
elgg_set_plugin_setting('show_view_name', '1', $base_name);
elgg_set_plugin_setting('show_view_files', '1', $base_name);
elgg_set_plugin_setting('show_profiling', '1', $base_name);
elgg_set_plugin_setting('tab_stop', '10', $base_name);
elgg_set_plugin_setting('magicmarker', '1', $base_name);
elgg_set_plugin_setting('css_highlight', '', $base_name);
?>
