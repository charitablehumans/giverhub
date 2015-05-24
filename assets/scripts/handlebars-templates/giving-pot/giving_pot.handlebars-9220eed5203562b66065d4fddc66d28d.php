<?php return function ($in, $debugopt = 1) {
    $cx = array(
        'flags' => array(
            'jstrue' => false,
            'jsobj' => false,
            'spvar' => false,
            'prop' => false,
            'method' => false,
            'mustlok' => false,
            'mustsec' => false,
            'echo' => true,
            'debug' => $debugopt,
        ),
        'constants' => array(),
        'helpers' => array(),
        'blockhelpers' => array(),
        'hbhelpers' => array(),
        'partials' => array(),
        'scopes' => array($in),
        'sp_vars' => array('root' => $in),

    );
    
    ob_start();echo '<div class="block giving-pot-block">
	<header>
		<span class="scope">',htmlentities((string)((isset($in['scope']) && is_array($in)) ? $in['scope'] : null), ENT_QUOTES, 'UTF-8'),'</span>
		The
		';if (LCRun3::ifvar($cx, ((isset($in['companyLogo']) && is_array($in)) ? $in['companyLogo'] : null))){echo '
			<img src="',htmlentities((string)((isset($in['companyLogo']) && is_array($in)) ? $in['companyLogo'] : null), ENT_QUOTES, 'UTF-8'),'">
		';}else{echo '
			<span class="company-name">';if (LCRun3::ifvar($cx, ((isset($in['companyName']) && is_array($in)) ? $in['companyName'] : null))){echo '',htmlentities((string)((isset($in['companyName']) && is_array($in)) ? $in['companyName'] : null), ENT_QUOTES, 'UTF-8'),'';}else{echo '[logo/name]';}echo '</span>
		';}echo ' Giving Pot
	</header>
	<header class="second">
		<div class="col-sm-6 left-col">Original Pot: $',htmlentities((string)((isset($in['potSize']) && is_array($in)) ? $in['potSize'] : null), ENT_QUOTES, 'UTF-8'),'</div>
        <div class="col-sm-6 right-col">Amount Remaining: $',htmlentities((string)((isset($in['amountRemaining']) && is_array($in)) ? $in['amountRemaining'] : null), ENT_QUOTES, 'UTF-8'),'</div>
	</header>
    <p class="summary">';if (LCRun3::ifvar($cx, ((isset($in['summary']) && is_array($in)) ? $in['summary'] : null))){echo '',htmlentities((string)((isset($in['summary']) && is_array($in)) ? $in['summary'] : null), ENT_QUOTES, 'UTF-8'),'';}else{echo '[Short Description]';}echo '</p>
	<p class="body">';if (LCRun3::ifvar($cx, ((isset($in['body']) && is_array($in)) ? $in['body'] : null))){echo '',((isset($in['body']) && is_array($in)) ? $in['body'] : null),'';}else{echo '[Body]';}echo '</p>

	<a href="';if (LCRun3::ifvar($cx, ((isset($in['buttonUrl']) && is_array($in)) ? $in['buttonUrl'] : null))){echo '',htmlentities((string)((isset($in['buttonUrl']) && is_array($in)) ? $in['buttonUrl'] : null), ENT_QUOTES, 'UTF-8'),'';}else{echo '#';}echo '"
	   ';if (LCRun3::ifvar($cx, ((isset($in['buttonUrl']) && is_array($in)) ? $in['buttonUrl'] : null))){echo 'target="_blank"';}else{echo '';}echo '
	   type="button"
	   class="btn btn-primary">';if (LCRun3::ifvar($cx, ((isset($in['buttonText']) && is_array($in)) ? $in['buttonText'] : null))){echo '',htmlentities((string)((isset($in['buttonText']) && is_array($in)) ? $in['buttonText'] : null), ENT_QUOTES, 'UTF-8'),'';}else{echo '[BUTTON TEXT]';}echo '</a>
	<p class="real-promotion">THIS IS NOT A REAL PROMOTION</p>
</div>';return ob_get_clean();
}
?>