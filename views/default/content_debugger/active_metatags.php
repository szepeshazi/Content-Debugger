<?php
    $plugin  = elgg_get_plugin_from_id('content_debugger');
?>
<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>mod/content_debugger/js/poshytip-1.0/src/tip-yellow/tip-yellow.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo $vars['url']; ?>mod/content_debugger/_css/poshytip_custom.css" media="screen" />
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/content_debugger/js/poshytip-1.0/src/jquery.poshytip.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/content_debugger/js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/content_debugger/js/magicmarker.js"></script>
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/content_debugger/js/content_debugger.js"></script>
<script type="text/javascript">

    var  contentDebugger = {
        wwwroot : '<?php echo $vars['url']; ?>',
        display_depth : <?php echo $plugin->display_depth; ?>,
        show_view_name : <?php echo $plugin->show_view_name; ?>,
        show_view_files : <?php echo $plugin->show_view_files; ?>,
        show_profiling : <?php echo $plugin->show_profiling; ?>,
        tab_stop : <?php echo $plugin->tab_stop; ?>,
        magicmarker : <?php echo $plugin->magicmarker; ?>,
        css_highlight : '<?php echo $plugin->css_highlight; ?>'
    };
</script>
