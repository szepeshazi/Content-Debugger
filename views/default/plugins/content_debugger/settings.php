<?php

    /**
     * Content debugger plugin settings
     *
     * @package content_debugger
     * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
     * @author Andras Szepeshazi
     * @copyright Andras Szepeshazi
     * @link http://wamped.org
     */

?>

<p>
    <label><?php echo elgg_echo('content_debugger:display_depth'); ?></label>
    <br />
    <select name="params[display_depth]">
        <option value="1" <?php echo ($vars['entity']->display_depth == '1' ? 'selected="selected"' : ''); ?>>1</option>
        <option value="3" <?php echo ($vars['entity']->display_depth == '3' ? 'selected="selected"' : ''); ?>>3</option>
        <option value="6" <?php echo ($vars['entity']->display_depth == '6' ? 'selected="selected"' : ''); ?>>6</option>
        <option value="10" <?php echo ($vars['entity']->display_depth == '10' ? 'selected="selected"' : ''); ?>>10</option>
        <option value="0" <?php echo ($vars['entity']->display_depth == '0' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:display_depth:full'); ?></option>
    </select>
    <br /><br />

    <label><?php echo elgg_echo('content_debugger:show_view_name'); ?></label>
    <br />
    <select name="params[show_view_name]">
        <option value="1" <?php echo ($vars['entity']->show_view_name == '1' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:show'); ?></option>
        <option value="0" <?php echo ($vars['entity']->show_view_name == '0' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:hide'); ?></option>
    </select>
    <br /><br />

    <label><?php echo elgg_echo('content_debugger:show_view_files'); ?></label>
    <br />
    <select name="params[show_view_files]">
        <option value="1" <?php echo ($vars['entity']->show_view_files == '1' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:show'); ?></option>
        <option value="0" <?php echo ($vars['entity']->show_view_files == '0' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:hide'); ?></option>
    </select>
    <br /><br />

    <label><?php echo elgg_echo('content_debugger:show_profiling'); ?></label>
    <br />
    <select name="params[show_profiling]">
        <option value="1" <?php echo ($vars['entity']->show_profiling == '1' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:show'); ?></option>
        <option value="0" <?php echo ($vars['entity']->show_profiling == '0' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:hide'); ?></option>
    </select>
    <br /><br />


    <label><?php echo elgg_echo('content_debugger:tab_stop'); ?></label>
    <br />
    <?php echo elgg_view('input/text',array('name' => 'params[tab_stop]', 'value' => $vars['entity']->tab_stop)); ?>
    <br /><br />

    <label><?php echo elgg_echo('content_debugger:magicmarker'); ?></label>
    <br />
    <select name="params[magicmarker]">
        <option value="1" <?php echo ($vars['entity']->magicmarker == '1' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:enable'); ?></option>
        <option value="0" <?php echo ($vars['entity']->magicmarker == '0' ? 'selected="selected"' : ''); ?>><?php echo elgg_echo('content_debugger:disable'); ?></option>
    </select>
    <br /><br />

    <label><?php echo elgg_echo('content_debugger:css_highlight'); ?></label>
    <br />
    <?php echo elgg_view('input/text',array('name' => 'params[css_highlight]', 'value' => $vars['entity']->css_highlight)); ?>
    <br /><br />

</p>
