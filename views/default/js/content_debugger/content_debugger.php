<?php $plugin = elgg_get_plugin_from_id('content_debugger'); ?>

<?php if (false) : ?><script type="text/javascript"><?php endif; ?>

elgg.provide('elgg.content_debugger');

elgg.content_debugger.options = {
	activeNode: null,
	tipContent: 'Content Debugger',
	lastTipContent: 'Content Debugger',
	inTransition: false,
    displayDepth : <?php echo $plugin->display_depth; ?>,
	showViewName : <?php echo $plugin->show_view_name; ?>,
	showViewFiles : <?php echo $plugin->show_view_files; ?>,
	showProfiling : <?php echo $plugin->show_profiling; ?>,
	tabStop : <?php echo $plugin->tab_stop; ?>,
	magicMarker : <?php echo $plugin->magicmarker; ?>,
	cssHighlight : '<?php echo $plugin->css_highlight; ?>',
	cssMarkers: {}
};

/**
 * Builds the html content of the tooltip to be displayed when hovering over 
 */
elgg.content_debugger.renderTooltip = function(viewInfo, depth) {

	// Build displayable view info
    var tipContent = '<div style="padding-left: ' + (depth * elgg.content_debugger.options.tabStop) + 'px">';
    if (elgg.content_debugger.options.showViewName) {
    	tipContent += '<span><strong>' + viewInfo.view + '</strong></span>';
    }
    if (elgg.content_debugger.options.showViewFiles) {
    	tipContent += '<ul>';
        $.each(viewInfo.viewDetails, function(index, element) {
        	tipContent += '<li>' + element + '</li>';
        });
        tipContent += '</ul>';
    }
    if (elgg.content_debugger.options.showProfiling) {
    	tipContent += '<span>Render time: ' + viewInfo.renderTime + '</span> | ';
    	tipContent += '<span>Total time: ' + viewInfo.totalTime + '</span>';
    }
    tipContent += '</div>';

    return tipContent;
};

elgg.content_debugger.prepareTooltip = function(node) {
    var infoArray = [];
    var tipContent = '';

    var nodeInfo = node.data('content_debugger_info');
    if (nodeInfo && nodeInfo.length > 0) {
        $.each(nodeInfo, function(index, infoElement) {
            infoArray.unshift(infoElement);
        });
    }
    node.parents().each(function(i, parentElement) {
        var nodeInfo = $(parentElement).data('content_debugger_info');
        if (nodeInfo && (nodeInfo.length > 0) && ((elgg.content_debugger.options.displayDepth == 0) || (elgg.content_debugger.options.displayDepth > i + 1))) {
            $.each(nodeInfo, function(index, infoElement) {
                infoArray.unshift(infoElement);
            });
        }
    });

    $.each(infoArray, function(index, element) {
        tipContent += elgg.content_debugger.renderTooltip(element, index);
    });

    if (infoArray.length) {
        tipContent += '<span>Current view depth: ' + infoArray.length + '</span>';
    }

    return tipContent;
};

elgg.content_debugger.walkCallback = function(node, viewInfo, level) {
    var nodeInfoTree = $(node).data('content_debugger_info');
    if (!nodeInfoTree) {
    	nodeInfoTree = [];
    }
    $(node).data('content_debugger_info', nodeInfoTree.concat(viewInfo));

    var zLevel = 10000 + level * 10;
    // Generate a unique id for the magicmarker object
    var mId = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        return v.toString(16);
    });

    // Create "magicmarker" object to handle highlighting of the target DOM node
    $(node).magicmarker({
        maskId: mId,
        opacity: 0.3,
        startOpacity: 0.3,
        color: '#000',
        zIndex: zLevel,
        css_markers: elgg.content_debugger.options.cssMarkers,
        apply_mask: elgg.content_debugger.options.magicMarker
    });

    // Bind mouseEnter/Exit to display / hide node highlighting w/ magicmarker
    $(node).hover(
        function () {
            var $this = $(this);
            $this.magicmarker('show');
        },
        function () {
            var $this = $(this);
            $this.magicmarker('hide');
        }
    );
};
    
elgg.content_debugger.walkNodes = function(parentNode, level, callback) {
    var node = parentNode.firstChild;

    while (node) {

        // Check if current node is a comment generated by the content_debugger plugin
        if ((node.nodeType === 8) && ($.trim(node.nodeValue).indexOf('content_debugger_view_start::') === 0)) {

            level++;
            try {
                var viewInfo = jQuery.parseJSON(node.nodeValue.replace('content_debugger_view_start::',''));
            // Find the node(s) that we need to bind the info tooltip to
            } catch(err) {
				if (typeof console != 'undefined') {
            		console.error('could not parse ', node.nodeValue.replace('content_debugger_view_end::',''));
				}
            }

            var targetNode = node.nextSibling;
            var viewStop = {id: null};
            while (targetNode) {
                if ((targetNode.nodeType === 8) && ($.trim(targetNode.nodeValue).indexOf('content_debugger_view_end::') === 0)) {
                    try {
                        viewStop = $.parseJSON(targetNode.nodeValue.replace('content_debugger_view_end::',''));
                    } catch(err) {
                        console.error('could not parse ', targetNode.nodeValue.replace('content_debugger_view_end::',''));
                    }

                    // Stop assigning this view info to siblings, as we have found the "end of view" mark
                    if (viewInfo.id === viewStop.id) {
                        targetNode = null;
                    }
                } else if (targetNode.nodeType === 1) {
                    callback(targetNode, viewInfo, level);
                }
                if (targetNode) {
                    targetNode = targetNode.nextSibling;
                }
            }
        } else if (node.nodeType === 1) {
        	elgg.content_debugger.walkNodes(node, level + 1, callback);
        }
        node = node.nextSibling;
    }
};

elgg.content_debugger.init = function() {

    $('body').poshytip({
        content: function(updateCallback) {
            return elgg.content_debugger.options.tipContent;
        },
        slide: false,
        followCursor: true
    });

    // Preprocess css highlight parameters
    if (elgg.content_debugger.options.cssHighlight.length) {
        var cssParts = elgg.content_debugger.options.cssHighlight.split(';');
        $.each(cssParts, function (index, cssDefinition) {
            var definitionParts = cssDefinition.split(':');
            if ($.trim(definitionParts[0]).length && $.trim(definitionParts[1]).length) {
            	elgg.content_debugger.options.cssMarkers[$.trim(definitionParts[0])] = $.trim(definitionParts[1]);
            }
        });
    }

    // Walk the DOM tree and parse all comments generated by the content_debugger plugin
    // Using those comments, create js overlays and info tooltips for each displayed Elgg view
    elgg.content_debugger.walkNodes(document.body, 0, elgg.content_debugger.walkCallback);

	
    $('body').mousemove(function(event) {
        if (!elgg.content_debugger.options.inTransition && $(event.target) !== elgg.content_debugger.options.activeNode) {
        	elgg.content_debugger.options.inTransition = true;
            elgg.content_debugger.options.activeNode = $(event.target);
            elgg.content_debugger.options.tipContent = elgg.content_debugger.prepareTooltip(elgg.content_debugger.options.activeNode);
            if (elgg.content_debugger.options.tipContent.length && elgg.content_debugger.options.tipContent !== elgg.content_debugger.options.lastTipContent) {
            	elgg.content_debugger.options.lastTipContent = elgg.content_debugger.options.tipContent;
                $('body').poshytip('update');
                $('body').poshytip('show');
            }
            elgg.content_debugger.options.inTransition = false;
        }
    });    
};

elgg.register_hook_handler('init', 'system', elgg.content_debugger.init);

<?php if (false): ?><script type="text/javascript"><?php endif; ?>