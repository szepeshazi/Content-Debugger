<?php

function content_debugger_ignore_view($view) {
	$ignore_view = true;
	$whitelist = array('profile/icon');
	$blacklist = array(
		'icon',
		'input/securitytoken',
		'input/hidden',
		'output/url',
		'js',
		'page/default',
		'page/admin'
	);

	// Check for whitelisted views
	foreach ($whitelist as $current_view) {
		if (stripos($view, $current_view) !== false) {
			$ignore_view = false;
		}
	}

	//Check for blacklisted views (only if the view did not match any whitelisted items)
	if ($ignore_view) {
		$ignore_view = false;
		foreach ($blacklist as $current_view) {
			if (stripos($view, $current_view) !== false) {
				$ignore_view = true;
			}
		}
	}
	return $ignore_view;
}

function content_debugger_profiler_start($event, $object_type, $object) {
	global $content_debugger_timestamps;
	$content_debugger_timestamps->start = microtime(true);
	$content_debugger_timestamps->last = $content_debugger_timestamps->start;
}

/**
 * Plugin hook for content debugging, triggered by displaying any view
 *
 * @param string $hook The name of the hook
 * @param string $entity_type The name of the entity type
 * @param array $params
 * @param mixed $returnvalue
 * @return string A json encoded object wrapped in an html comment, holding information about the current view
 */
function content_debugger_view_hook($hook, $entity_type, $returnvalue, $params) {
	global $content_debugger_timestamps;
	if (!content_debugger_ignore_view($params['view'])) {
		$now = microtime(true);
		$render_time = $now - $content_debugger_timestamps->last;
		$total_time = $now - $content_debugger_timestamps->start;
		$content_debugger_timestamps->last = $now;
		if ($returnvalue) {
			$unique_id = sha1(rand());

			$view_info_begin = new stdClass();
			$view_info_begin->view = $params['view'];
			$view_info_begin->id = $unique_id;
			$view_info_begin->renderTime = number_format($render_time, 6).'ms';
			$view_info_begin->totalTime = number_format($total_time, 6).'ms';
			$view_info_begin->viewDetails = content_debugger_view_details($params['view']);

			$view_info_start = json_encode($view_info_begin);
			$wrap_before = "<!-- content_debugger_view_start::{$view_info_start}  -->";

			$view_info_stop = new stdClass();
			$view_info_stop->view = $params['view'];
			$view_info_stop->id = $unique_id;

			$view_info_end = json_encode($view_info_stop);
			$wrap_after = "<!-- content_debugger_view_end::{$view_info_end} -->";

			return PHP_EOL.$wrap_before.PHP_EOL.$returnvalue.PHP_EOL.$wrap_after.PHP_EOL;
		}
	}
}

function content_debugger_view_details($view) {

	global $CONFIG;
	$results = array();

	$viewtype = elgg_get_viewtype();
	if (isset($CONFIG->views->extensions[$view])) {
		$viewlist = $CONFIG->views->extensions[$view];
	} else {
		$viewlist = array(500 => $view);
	}

	foreach ($viewlist as $priority => $view) {
		$view_location = elgg_get_view_location($view, $viewtype);
		$view_file = "$view_location$viewtype/$view.php";

		$default_location = elgg_get_view_location($view, 'default');
		$default_view_file = "{$default_location}default/$view.php";

		// Check if the view exists
		if (file_exists($view_file)) {
			$results[] = str_replace($CONFIG->path, '', $view_file);
		} else if (($viewtype != 'default') && (elgg_does_viewtype_fallback($viewtype)) && (file_exists($default_view_file))) {
			$results[] = str_replace($CONFIG->path, '', $default_view_file);
		}
	}

	return $results;
}

function content_debugger_get_ui_type() {
	return elgg_in_context('admin') ? 'admin' : 'frontend';
}
