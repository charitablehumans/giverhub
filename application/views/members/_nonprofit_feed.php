<?php
/** @var \Entity\User $user */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php $GLOBALS['super_timers']['nnnn1'] = microtime(true) - $GLOBALS['super_start']; ?>
<div class="block bet-a-friend show-non-profits <?php if(isset($extra_classes)): echo join($extra_classes, ' '); endif; ?>">
    <?php if (isset($preHtml) && $preHtml): ?>
        <?php $CI->load->view($preHtml); ?>
    <?php endif; ?>

    <div class="numero2"></div>
    <div class="row">
        <div class="col-xl-12 search_charity ">
            <form action="#" id="nonprofits_search" method="post">
                <input autocomplete="off" type="text" name="find_nonprofits" class="find_nonprofits" placeholder="Enter text here to search nonprofits by name or keyword" style="display: inline" value="">
            </form>
        </div>
    </div>

    <div class="nonprofits-feed">
        <?php
        $GLOBALS['super_timers']['nnnn2'] = microtime(true) - $GLOBALS['super_start'];
        if ($user) {
            $GLOBALS['super_timers']['nnnn3'] = microtime(true) - $GLOBALS['super_start'];
            $recommended_charities = $user->getRecommendedCharities(4);
            $GLOBALS['super_timers']['nnnn4'] = microtime(true) - $GLOBALS['super_start'];
        } else {
            $GLOBALS['super_timers']['nnnn5'] = microtime(true) - $GLOBALS['super_start'];
            $recommended_charities = \Entity\Charity::getFeaturedCharities(4);
            $GLOBALS['super_timers']['nnnn6'] = microtime(true) - $GLOBALS['super_start'];
        }

        $this->load->view(
                   '/members/_member_nonprofits',
                       [
                           'nonprofits' => $recommended_charities ? [array_values($recommended_charities)[0]] : [],
                           'search_text' => ''
                       ]
        );
        $GLOBALS['super_timers']['nnnn7'] = microtime(true) - $GLOBALS['super_start'];
        ?>
    </div>
    <div class="nonprofits-feed-hover" style="display:none">
        <?php
        $this->load->view(
                   '/members/_member_nonprofits',
                       [
                           'nonprofits' => $recommended_charities,
                           'search_text' => ''
                       ]
        );
        $GLOBALS['super_timers']['nnnn8'] = microtime(true) - $GLOBALS['super_start'];
        ?>
    </div>
</div>