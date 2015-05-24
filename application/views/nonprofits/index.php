<?php
/** @var \Entity\Charity $charity */
/** @var integer $avg_review */
/** @var \Charity $CI */
$CI =& get_instance();
?>
<?php $GLOBALS['super_timers']['npi1'] = microtime(true) - $GLOBALS['super_start']; ?>
<?php $this->load->view('/nonprofits/_charity_header', array('charity' => $charity)); ?>
<?php $GLOBALS['super_timers']['npi2'] = microtime(true) - $GLOBALS['super_start']; ?>
<main class="petition_main" role="main" id="main_content_area">
    <section class="container">
        <div class="row">

            <?php $this->load->view('/nonprofits/_nonprofit_new_nav'); ?>

            <!-- COL #1 -->
            <div class="col-md-10 col-sm-9">
                <div class="row">
                    <div class="col-md-6">
                        <?php if (!$charity->hasFakeEin()): ?>
                            <div class="block charity_donation_box_block nonprofit-donation-box-block">
                                <form action="#" class="gh_donation donate-container">
                                    <div class="donation-amount-wrapper">
                                        <input type="text" class="form-control charity-profile-donation-amount-input" placeholder="Enter Donation Amount $"/>
                                    </div>
                                    <div class="donation-buttons-wrapper">
                                        <a
                                            data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                                            data-charity-id="<?php echo $charity->getId(); ?>"
                                            class="btn-donate-using-cc-paypal-button-with-amount paypal gh-trigger-event gh_tooltip"
                                            data-event-category="button"
                                            data-event-action="click"
                                            data-event-label="paypal donate (from nonprofit overview)"
                                            data-container="body"
                                            title="Clicking this button will begin the process for making a donation using your paypal account. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                                            href="#"><img src="/img/button_paypal.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
                                        <a
                                            data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                                            data-charity-id="<?php echo $charity->getId(); ?>"
                                            class="btn-donate-using-cc-paypal-button-with-amount cc gh-trigger-event gh_tooltip"
                                            data-event-category="button"
                                            data-event-action="click"
                                            data-event-label="cc donate (from nonprofit overview)"
                                            data-container="body"
                                            title="Clicking this button will begin the process of making a donation using your credit card. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                                            href="#"><img src="/img/button_cc.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
                                    </div>
                                    <!-- clearfix end -->
                                </form>
                                <!-- gh_donation end -->
                            </div>
                        <?php endif; ?>

                        <!-- block end -->
                        <?php $GLOBALS['super_timers']['npi3'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <div class="block charity_donation_box_block nonprofit-info-block">
                            <h3 class="gh_block_title gh_spacer_21">Nonprofit Information <a class="pull-right" href="/members/settings/#nonprofit-data"><i class="glyphicon glyphicon-cog"></i></a></h3>

                            <?php $mission_summary_user = $charity->getMissionSummaryUser(); ?>
                            <span class="color_title gh_spacer_7 blk">TAGLINE: <?php if ($mission_summary_user): ?> <span class="unofficial gh_tooltip" title="This means that the tagline displayed below is user provided.">(unofficial)</span><?php endif; ?><?php if ($CI->user && $CI->user->isCharityAdmin($charity)): ?><button class="btn btn-primary btn-xs btn-edit-charity-admin-data" type="button">Edit</button><?php endif; ?></span>

                            <div class="blk gh_spacer_21 mission-blk">
                                <div id="mission-summary-show-div" class="<?php if (!$charity->getMissionSummary()): ?>hide<?php endif; ?>">
                                    <p id="mission-summary-p"><?php echo htmlspecialchars($charity->getMissionSummary()); ?></p>
                                    <div id="mission-summary-submitted-by-container" class="submitted-by-container pull-left">
                                        <?php if ($mission_summary_user): ?>
                                            <?php $CI->load->view('nonprofits/_submitted_by_user', ['user' => $mission_summary_user]); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($CI->user && !$charity->getMissionSummary()): ?>
                                        <button type="button" data-field="mission-summary" class="btn btn-xs btn-info btn-edit-mission pull-right">EDIT</button>
                                    <?php elseif (!$CI->user): ?>
                                        <button data-toggle="modal" data-target="#signin-or-join-first-modal" type="button" class="btn btn-xs btn-info pull-right">EDIT</button>
                                    <?php endif; ?>
                                </div>
                                <?php if (!$charity->getMissionSummary()): ?>
                                    <div id="mission-summary-div" class="<?php if ($charity->getMissionSummary()): ?>hide<?php endif; ?>">
                                        <input class="form-control" type="text" id="mission-summary-input" placeholder="We don't have this data :(. You can add it here to benefit the GiverHub community and earn GiverCoin! Use a source like wikipedia or your personal knowledge." value="<?php echo htmlspecialchars($charity->getMissionSummary()); ?>">
                                        <button data-charity-id="<?php echo $charity->getId(); ?>" type="button" class="pull-right btn btn-xs btn-info btn-submit-mission" data-field="mission-summary" data-loading-text="ADD">ADD</button>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                                /** @var \Entity\Mission|string $mission */
                                $mission = $charity->getMission();
                            ?>
                            <span class="color_title gh_spacer_7 blk">MISSION:<?php if ($mission instanceof \Entity\Mission): ?> <span class="unofficial gh_tooltip" title="This means that the mission statement displayed below is user provided.">(unofficial)</span><?php endif; ?><?php if ($CI->user && $CI->user->isCharityAdmin($charity)): ?><button class="btn btn-primary btn-xs btn-edit-charity-admin-data" type="button">Edit</button><?php endif; ?></span>

                            <div class="blk gh_spacer_21 mission-blk">
                                <?php if ($mission): ?>
                                    <div id="mission-show-div">
                                        <p class="mission-p"><?php echo $mission instanceof \Entity\Mission ? $mission->getMission() : $mission; ?></p>
                                        <p class="mission-source-p">Source: <?php echo $mission instanceof \Entity\Mission ? $mission->getSourceFormatted() : 'Official'; ?></p>
                                        <?php if ($mission instanceof \Entity\Mission): ?>
                                            <div id="mission-submitted-by-container" class="submitted-by-container pull-left">
                                                <?php $mission_user = $mission->getUser(); ?>
                                                <?php if ($mission_user): ?>
                                                    <?php if ($mission_user->isCharityAdmin($charity)): ?>
                                                        <span class="submitted-by-admin">Submitted by admin</span>
                                                    <?php else: ?>
                                                        <?php $CI->load->view('nonprofits/_submitted_by_user', ['user' => $mission_user]); ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!$CI->user || !$CI->user->isCharityAdmin($charity)): ?>
                                                <?php if ($CI->user): ?>
                                                    <a href="<?php echo $charity->getUrl(); ?>/missions" class="btn btn-xs btn-info pull-right">EDIT</a>
                                                <?php else: ?>
                                                    <a href="#" data-toggle="modal" data-target="#signin-or-join-first-modal" class="btn btn-xs btn-info pull-right">EDIT</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div id="mission-submitted-by-container" class="submitted-by-container pull-left">
                                                <span class="submitted-by-admin">Submitted by admin</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div id="mission-div">
                                        <span class="we-dont-have-mission">We don't have this data :(. You can add it here to benefit the GiverHub community and earn GiverCoin! Use a source like wikipedia or your personal knowledge.</span>
                                        <?php if (!$CI->user || !$CI->user->isCharityAdmin($charity)): ?>
                                            <?php if ($CI->user): ?>
                                                <a href="<?php echo $charity->getUrl(); ?>/missions" class="btn btn-xs btn-info pull-right">ADD</a>
                                            <?php else: ?>
                                                <a href="#" data-toggle="modal" data-target="#signin-or-join-first-modal" class="btn btn-xs btn-info pull-right">ADD</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($CI->user): ?>
                                <div id="signed_in_user_submitted_by" class="hide">
                                    <?php $CI->load->view('nonprofits/_submitted_by_user', ['user' => $CI->user]); ?>
                                </div>
                            <?php endif; ?>

                            <?php $info_from = $charity->getIrsInfoFromDesc(); ?>
                            <span class="color_title gh_spacer_7 blk nonprofit-info-label">INFO:<?php if ($info_from !== null): ?><span class="nonprofit-info-from pull-right"><?php echo htmlspecialchars($info_from); ?></span><?php endif; ?></span>
                            <table class="table nonprofit-info-table">
                                <tbody>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_revenue')): ?>
                                        <tr>
                                            <td>Revenue</td>
                                            <td colspan="2"><?php echo htmlspecialchars($charity->getIrsTotalRevenue()); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_program_services')): ?>
                                        <tr>
                                            <td>Program Services</td>
                                            <td><?php echo $charity->getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('program_services', $charity->getIrsProgramServicesAsPercentageOfTotalFunctionalExpenses(true))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_fundraising_expenses')): ?>
                                        <tr>
                                            <td>Fundraising Expenses</td>
                                            <td><?php echo $charity->getIrsFundraisingExpensesPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('fundraising_expenses', $charity->getIrsFundraisingExpensesPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_executive_compensation')): ?>
                                        <tr>
                                            <td>Executive Compensation</td>
                                            <td><?php echo $charity->getIrsCompensationOfCurrentOfficersPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Executive Compensation', $charity->getIrsCompensationOfCurrentOfficersPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_advertising_and_promotion')): ?>
                                        <tr>
                                            <td>Advertising and Promotion</td>
                                            <td><?php echo $charity->getIrsAdvertisingAndPromotionsPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Advertising and Promotion', $charity->getIrsAdvertisingAndPromotionsPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_office_expenses')): ?>
                                        <tr>
                                            <td>Office Expenses</td>
                                            <td><?php echo $charity->getIrsOfficeExpensesPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Office Expenses', $charity->getIrsOfficeExpensesPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_information_technology')): ?>
                                        <tr>
                                            <td>Information Technology</td>
                                            <td><?php echo $charity->getIrsInformationTechnologyPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Information Technology', $charity->getIrsInformationTechnologyPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_travel')): ?>
                                        <tr>
                                            <td>Travel</td>
                                            <td><?php echo $charity->getIrsTravelPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Travel', $charity->getIrsTravelPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!$CI->user || $CI->user->getSetting('show_conferences_conventions_meetings')): ?>
                                        <tr>
                                            <td>Conferences, conventions, meetings</td>
                                            <td><?php echo $charity->getIrsConferencesConventionsMeetingsPercentageOfRevenue(); ?></td>
                                            <td><?php echo htmlspecialchars($charity->getHighLow('Conferences, conventions, meetings', $charity->getIrsConferencesConventionsMeetingsPercentageOfRevenue(false,false))); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($CI->user && $CI->user->isAdmin()): ?>
                                        <tr>
                                            <td colspan="3"><a href="#" data-toggle="modal" data-target="#high-low-markers-modal">View markers (displayed for admins only)</a></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="view-more-td">
                                            <a
                                                data-charity-id="<?php echo $charity->getId(); ?>"
                                                href="#"
                                                data-loading-text="View More"
                                                class="btn-admin-citizen">View More</a>
                                        </td>
                                    </tr>
                                </tbody>


                            </table>

                        </div>
                        <!-- block end -->

                        <?php $GLOBALS['super_timers']['npi4'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <div class="block charity_donation_box_block nonprofit-updates-block">
                            <h3 class="gh_block_title gh_spacer_21">Updates <i class="icon-updates pull-right"></i></h3>

                            <form action="#" class="gh_updates gh_block_section gh_spacer_21" style="padding-top:0;">
                                <div class="form-group clearfix">
                                    <textarea id="charity-update-textarea" class="form-control" style="height:110px !important; resize:none;"
                                              placeholder="Enter your update..."></textarea>
                                </div>
                                <!-- form-group end -->

                                <button data-charity-id="<?php echo htmlspecialchars($charity->getId()); ?>" data-loading-text="SUBMITTING..." type="submit" class="btn btn-primary btn-submit-charity-update cntr gh_spacer_21">SUBMIT</button>
                            </form>

                            <small>SOME RECENT UPDATES ABOUT THIS CHARITY:</small>

                            <div class="table-responsive">
                                <?php $this->load->view('nonprofits/_charity_updates_table', array('charity' => $charity)); ?>
                            </div>
                            <!-- table-responsive end -->
                        </div>
                        <!-- block end -->
                        <?php $GLOBALS['super_timers']['npi5'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </div>

                    <div class="col-md-6">

                        <div class="charity-page-social-box charity_donation_box_block">
                            <div class="row">
                                <div class="col-md-3 col-xs-3">
                                    <div class="fb-share-button" data-type="button"></div>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    <div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    <a href="https://twitter.com/share" class="twitter-share-button" data-text="I just donated to <?php echo htmlspecialchars($charity->getName()); ?> through GiverHub" data-hashtags="giverhub">Tweet</a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                </div>
                                <div class="col-md-3 col-xs-3">
                                    <a id="email-charity-link" href="#" data-toggle="modal" data-target="#email-charity-modal">&nbsp;</a>
                                </div>
                            </div>
                        </div>

                        <?php if ($charity->getAdminDataValue('facebook_page')): ?>
                            <div class="fb-like-box fb-like-box-nonprofit" data-href="https://www.facebook.com/<?php echo htmlspecialchars($charity->getAdminDataValue('facebook_page')); ?>" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
                        <?php endif; ?>

                        <div class="block charity_donation_box_block nonprofit-upload-photos-block">
                            <h3 class="gh_block_title">Upload Photos
                                <small>related to this charity</small>
                                <i class="icon-picture pull-right"></i></h3>

                            <div class="uploaded-charity-photos photo_grid gh_spacer_14">
                                <?php $charityImages = $charity->getImages(); ?>
                                <?php if(empty($charityImages)): ?>
                                    <p class="no-photos-yet">No photos yet.</p>
                                <?php else: ?>
                                    <?php foreach($charityImages as $charityImage): ?>
                                        <a
                                            data-large-src="<?php echo $charityImage->getUrl(); ?>"
                                            class="show-photo-in-modal"
                                            href="#">
                                            <img
                                                src="<?php echo $charityImage->getThumbUrl(); ?>"
                                                alt="<?php echo htmlspecialchars($charity->getName()); ?>"
                                                class="img-rounded"/>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <!-- photo_grid end -->

                            <?php if ($CI->user): ?>
                                <div class="uploading-charity-photos pull-left hide">UPLOADING ...<img alt="Loading" src="/images/ajax-loaders/kit.gif"></div>
                                <form id="upload-charity-photos-form" action="/upload/charity_photos" method="POST" enctype="multipart/form-data">
                                    <a href="#" class="footer_block_btn pull-right">UPLOAD <i class="icon-upload"></i></a>
                                    <input type="file" name="charity-photos-input" multiple>
                                    <input type="hidden" name="charityId" value="<?php echo $charity->getId(); ?>">
                                </form>
                            <?php else: ?>
                                <a href="#" data-toggle="modal" data-target="#signin-or-join-first-modal" class="footer_block_btn pull-right">UPLOAD <i class="icon-upload"></i></a>
                            <?php endif; ?>
                        </div>
                        <!-- block end -->
                        <?php $GLOBALS['super_timers']['npi6'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <?php $this->load->view('/volunteering/_volunteering_list_add', ['charity' => $charity]); ?>

                        <div class="block vol-cal-block">
                            <select class="select-timezone hide">
                                <?php foreach(\Entity\CharityVolunteeringOpportunity::$time_zones as $key => $label): ?>
                                    <option
                                        <?php if ($key == 'UTC-05:00'): ?>selected="selected"<?php endif; ?>
                                        value="<?php echo htmlspecialchars(\Entity\CharityVolunteeringOpportunity::$php_time_zones[$key]); ?>"><?php echo htmlspecialchars($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="vol-cal"
                                 data-timezone="<?php echo htmlspecialchars(\Entity\CharityVolunteeringOpportunity::$php_time_zones['UTC-05:00']); ?>"
                                 data-nonprofit-id="<?php echo $charity->getId(); ?>"
                                 data-events="<?php echo htmlspecialchars(json_encode($charity->getVolunteeringOpportunities())); ?>"></div>
                            <a class="footer-link" href="/volunteering-opportunities/<?php echo $charity->getUrlSlug(); ?>">
                                View full calendar
                            </a>
                            <div class="hide">
                                <?php $this->load->view('/volunteering/_volunteering_list'); ?>
                            </div>
                        </div>

                        <?php
                        $CI->modal('vol-event-modal', [
                            'header' => 'Volunteering Event',
                            'body' => '<div class="wrapper"></div>',
                            'body_string' => true,
                            'footer' => '',
                            'modal_size' => 'col-md-3',
                        ]);
                        ?>

                        <?php $this->load->view('/volunteering/_message_modal', ['charity' => $charity]); ?>


                        <div class="block embed-vol-cal-block">
                            <header>Embed Calendar</header>
                            <button type="button" class="btn btn-primary btn-vol-cal-embed">Get Code</button>
                        </div>

                        <?php
                        $CI->modal('embed-vol-cal-modal', [
                            'header' => 'Embed Calendar',
                            'body' => '<textarea
                                            class="form-control"
                                            readonly="1"
                                            spellcheck="false"
                                            onclick="this.focus(); this.select()"
                                            tabindex="0">'.
                                        htmlspecialchars('<iframe height="600" width="500" frameborder="0" src="'.base_url('/volunteering_opportunity/embed/'.$charity->getId()).'"></iframe>').
                                      '</textarea>',
                            'body_string' => true,
                            'footer' => '',
                            'modal_size' => 'col-md-3',
                        ]);
                        ?>

                        <div class="block charity_donation_box_block nonprofit-users-following-block">
                            <h3 class="gh_block_title">Users following this charity <i class="icon-follower pull-right"></i></h3>

                            <div class="users-following-charity photo_grid grid_description">
                                <?php if(!$charity->followersCount()): ?>
                                    No Followers Yet.
                                <?php else: ?>
                                    <?php foreach($charity->getFollowers() as $follower): ?>
                                        <a href="#">
                                            <img src="<?php echo $follower->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($follower->getName()); ?>" class="img-rounded"/>
                                            <span class="overlay"><?php echo htmlspecialchars($follower->getName()); ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <!-- photo_grid end -->
                        </div>
                        <!-- block end -->

                        <?php $GLOBALS['super_timers']['npi7'] = microtime(true) - $GLOBALS['super_start']; ?>
                        <div class="block charity_donation_box_block">
                            <h3 class="gh_block_title">Similar Nonprofits <i class="icon-3users pull-right"></i></h3>

                            <div class="row">
                                <table class="table table-hover">
                                    <tbody>
                                        <?php foreach($charity->similar(3) as $similarCharity): ?>
                                            <tr>
                                                <td class="col-xs-12 col-md-6" style="border-top:none;">
                                                    <div class="col-xs-12 col-md-6">
                                                        <strong><a href="<?php echo $similarCharity; ?>"><?php echo htmlspecialchars($similarCharity->getName()); ?></a></strong>
                                                    </div>

                                                    <?php if ($similarCharity->getOverallScore() !== null): ?>
                                                        <div class="col-xs-12 col-md-6 clearfix gh_spacer_14">
                                                            <div class="col-xs-10 col-md-10 progress progress-secondary" data-trigger="hover" data-placement="bottom" data-toggle="popover"
                                                                 data-content="<?php echo htmlspecialchars(\Entity\Charity::$overallScoreText); ?>">
                                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($similarCharity->getOverallScore()); ?>"
                                                                     aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $similarCharity->getOverallScore(); ?>%">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2 progress-secondary-percent progress-bar-resize">
                                                                <?php echo round($similarCharity->getOverallScore()); ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- table-responsive end -->
                        </div>
                        <!-- block end -->
                        <?php $GLOBALS['super_timers']['npi8'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <div class="block charity_donation_box_block nonprofit-leave-review-block">
                            <h3 class="gh_block_title">Leave a Review <i class="icon-leave-review pull-right"></i></h3>

                            <form action="#" method="post" class="gh_leave_review gh_block_section gh_spacer_21" id="review-submit-form" style="padding-top:0;">
                                <div class="gh_spacer_14">
                                    <small class="color_light pull-left" style="margin-top:5px; margin-right:15px;">YOUR RATING:</small>
                                    <div class="rating rate">
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                        <a class="icon-star" href="#"></a>
                                    </div>
                                </div>
                                <!-- task 00365 -->
                                <div class="form-group clearfix">
                                    <textarea
                                              <?php if (!$this->user || $this->user->hasReviewedCharity($charity)): ?>disabled="disabled"<?php endif; ?>
                                              id="leave-review-textarea"
                                              name="review-desc"
                                              class="input-block-level form-control"
                                              data-initial-value="<?php if ($this->user && $this->user->hasReviewedCharity($charity)): ?>Thank you for your review!<?php endif; ?>"
                                              placeholder="Leave a review..."
                                              style="height:110px !important; resize:none;"></textarea>
                                </div>
                                <!-- form-group end -->
                                <div id="review-message-container" class="alert alert-danger">
                                    <span id="review-message"></span>
                                </div>
                                <button type="submit"
                                        data-loading-text="Saving.."
                                        <?php if ($CI->user && $CI->user->hasReviewedCharity($charity)): ?>disabled="disabled"<?php endif; ?>
                                        class="btn btn-primary cntr gh_spacer_21"
                                        id="review-form-submit">SUBMIT</button>
                                <button
                                    type="button"
                                    data-current-review="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? htmlspecialchars($CI->user->getCharityReview($charity)->getText()) : ''; ?>"
                                    data-current-rating="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? htmlspecialchars($CI->user->getCharityReview($charity)->getRating()) : ''; ?>"
                                    class="btn btn-success cntr gh_spacer_21 btn-edit-nonprofit-review <?php if (!$CI->user || !$CI->user->hasReviewedCharity($charity)): ?>hide<?php endif; ?>">EDIT</button>

                                <button
                                    type="button"
                                    data-loading-text="REMOVE"
                                    data-charity-id="<?php echo $charity->getId(); ?>"
                                    data-review-info-id="<?php echo $CI->user && $CI->user->hasReviewedCharity($charity) ? '#review-id-'.$CI->user->getCharityReview($charity)->getId() : ''; ?>"
                                    class="btn btn-danger cntr gh_spacer_21 btn-remove-nonprofit-review <?php if (!$CI->user || !$CI->user->hasReviewedCharity($charity)): ?>hide<?php endif; ?>">REMOVE</button>
                            </form>


                            <div class="row">
                                <div class="col-md-6">
                                    <span class="big">Reviews (<span class="charity-review-count"><?php echo count($charity->getReviews()); ?></span>)</span>
                                </div>
                                <!-- task 00365 End-->
                                <div class="col-md-6 text-right">
                                    <a href="<?php echo $charity->getUrl(); ?>/reviews" class="footer_block_btn">VIEW ALL REVIEWS</a>
                                </div>
                            </div>
                        </div>
                        <!-- block end -->
                        <?php $GLOBALS['super_timers']['npi9'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->
    </section>
    <!-- container end -->
</main>

<?php
$CI->load->view('/partials/_display-photo-modal');

if ($CI->user && $CI->user->isAdmin()) {
    $CI->load->view('/partials/_admin_high_low_markers_modal');
}

$GLOBALS['super_timers']['npi10'] = microtime(true) - $GLOBALS['super_start'];