<?php
$base_name = basename(dirname(dirname(dirname(dirname(__FILE__)))));
$css_path = '/vendors/poshytip-1.0/src/tip-yellow/';
$img_baseurl = elgg_get_site_url().'mod/'.$base_name.$css_path;
?>

<?php if (false) : ?><style><?php endif; ?>

.tip-yellow {
	z-index:1000;
	text-align:left;
	border:1px solid #939393;
	padding:7px;
	min-width:50px;
	max-width:530px;
	color:#8c3901;
	background-color:#fef9d9;
	background-image:url("<?php echo $img_baseurl; ?>tip-yellow.png"); /* bgImageFrameSize >= 10 should work fine */
	/**
	 * - If you set a background-image, border/padding/background-color will be ingnored.
	 *   You can set any padding to .tip-inner instead if you need.
	 * - If you want a tiled background-image and border/padding for the tip,
	 *   set the background-image to .tip-inner instead.
	 */
}

.tip-yellow .tip-inner {
	font:bold 13px/18px 'trebuchet ms',arial,helvetica,sans-serif;
	margin-top:-2px;
	padding:0 3px 1px 3px;
}

/* Configure an arrow image - the script will automatically position it on the correct side of the tip */
.tip-yellow .tip-arrow-top {
	margin-top:-7px;
	margin-left:15px;
	top:0;
	left:0;
	width:16px;
	height:10px;
	background:url("<?php echo $img_baseurl; ?>tip-yellow_arrows.png") no-repeat;
}

.tip-yellow .tip-arrow-right {
	margin-top:-9px; /* approx. half the height to center it */
	margin-left:-4px;
	top:50%;
	left:100%;
	width:10px;
	height:20px;
	background:url("<?php echo $img_baseurl; ?>tip-yellow_arrows.png") no-repeat -16px 0;
}

.tip-yellow .tip-arrow-bottom {
	margin-top:-6px;
	margin-left:15px;
	top:100%;
	left:0;
	width:16px;
	height:13px;
	background:url("<?php echo $img_baseurl; ?>tip-yellow_arrows.png") no-repeat -32px 0;
}

.tip-yellow .tip-arrow-left {
	margin-top:-9px; /* approx. half the height to center it */
	margin-left:-6px;
	top:50%;
	left:0;
	width:10px;
	height:20px;
	background:url("<?php echo $img_baseurl; ?>tip-yellow_arrows.png") no-repeat -48px 0;
}

.tip-yellow .tip-inner {
    padding: 0px;
    margin: 0px;
    font-size: 11px;
    font-weight: normal;
}

.tip-yellow .tip-inner ul {
    margin: 0px 0px 0px 12px;
    font-style: italic;
    list-style: square;
}

.elgg-icon-info-active {
	background-position: 0 -468px;
}

.elgg-icon-info-active:hover {
	background-position: 0 -486px;
}

<?php if (false) : ?></style><?php endif; ?>
