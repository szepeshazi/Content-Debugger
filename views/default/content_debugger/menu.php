<?php
    global $CONFIG;
    $class = ($_SESSION['content_debugger'] === 'enabled') ? 'class="active"' : '';
?>

<?php if (isadminloggedin()) : ?>
    <ul class="topbardropdownmenu">
        <li class="drop"><a href="#" class="menuitemtools"><?php echo elgg_echo("Developer"); ?></a>
        <ul>
            <li>
                <a id="toggle_content_debugger" <?php echo $class; ?> href="<?php echo $CONFIG->wwwroot?>pg/content_debugger/toggle">
                    <?php echo elgg_echo('content_debugger:toggle'); ?>
                </a>
            </li>
        </ul>
        </li>
    </ul>
<?php endif; ?>
