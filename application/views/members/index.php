<?php
/** @var \Entity\User $user */
/** @var bool $my_dashboard */
/** @var \Members $CI */
$CI =& get_instance();

if (!isset($tour)) {
    $tour = false;
}
?>
<?php $GLOBALS['super_timers']['rrrr1'] = microtime(true) - $GLOBALS['super_start']; ?>
<?php $this->load->view('/members/_header', array('user' => $user, 'my_dashboard' => $my_dashboard)); ?>
<?php $GLOBALS['super_timers']['rrrr2'] = microtime(true) - $GLOBALS['super_start']; ?>

<main class="members_main" role="main" id="main_content_area">

    <?php $GLOBALS['super_timers']['rrrr3'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); } ?>
    <?php $GLOBALS['super_timers']['rrrr4'] = microtime(true) - $GLOBALS['super_start']; ?>
    <section class="container">
        <div class="row">


            <!-- COL #1 -->

            <?php if ($my_dashboard): ?>
                <!-- Include new left side bar menu -->
                <?php //$this->load->view('/members/_member_new_nav', array('user' => $user)); ?>
            <?php endif; ?>
            <?php $GLOBALS['super_timers']['rrrr5'] = microtime(true) - $GLOBALS['super_start']; ?>
            <div class="<?php echo $my_dashboard&&false ? 'col-md-10 col-sm-9' : 'col-md-12'; ?>">
                <div class="row">
                    <div class="col-md-7">

                        <div class="dashboard-buttons-wrapper">
                            <a class="block col-md-4 col-xs-4 dashboard-button-nonprofits" href="#"><span class="glyphicon glyphicon-search"></span>Search Nonprofits</a>
                            <a class="block col-md-4 col-xs-4 btn-create-petition-from-block" data-temp-id="<?php echo crc32(rand()); ?>" href="#"><span class="glyphicon glyphicon-plus"></span>Create a Petition</a>
                            <a class="block col-md-4 col-xs-4 dashboard-button-petitions" href="#"><span class="glyphicon glyphicon-search"></span>Search Petitions</a>
                        </div>

                        <div class="block members_main">
                            <div class="numero4"></div>
                            <div class="row">
                                <button
                                    data-loading-text="POSTING..."
                                    data-to-user-id="<?php echo $user->getId(); ?>"
                                    data-context="<?php echo !$tour && $user->getId() == $CI->user->getId() ? 'my' : 'other'; ?>"
                                    type="button"
                                    class="btn btn-primary btn-sm btn-post-activity-feed">POST</button>
                                <?php $placeholder = $my_dashboard ? 'Post cause related content here for like-minded Givers to see' : 'Post something to ' . $user->getName() . '\'s feed...'; ?>
                                <textarea class="activity-feed-textarea" placeholder="<?php echo htmlspecialchars($placeholder); ?>" ></textarea><br/>

                                <div id="post-buttons-container" class="hide">
                                    <div id="link-nonprofit-container" class="hide">
                                        <ul id="link-nonprofit-chosen" class="hide"></ul>
                                        <input id="link-nonprofit-text" type="text" placeholder="Type nonprofit name...">
                                        <ul id="link-nonprofit-results" class="hide"></ul>
                                    </div>
                                    <div id="upload-activity-feed-post-image-container" class="hide">
                                        <img alt="Loading" id="upload-activity-feed-post-image-loading" src="/images/ajax-loaders/ajax-loader2.gif">
                                        <div id="activity-feed-post-images-container"></div>
                                    </div>
                                    <div id="youtube-previews-container"></div>
                                    <div id="activity-feed-url-previews-container" class="hide"></div>
                                    <button type="button" class="btn-activity-link-nonprofit btn btn-primary btn-xs">link a nonprofit</button>
                                    <form id="upload-activity-post-image-form" action="/upload/activity_post_image" method="POST" enctype="multipart/form-data">
                                        <button type="button" class="btn-activity-picture btn btn-primary btn-xs"><img src="/assets/images/camera-glyph.png" class="camera-glyph" alt="Attach Images"/></button>
                                        <input type="file" name="activity-post-image-input" multiple>
                                        <input id="upload-activity-post-image-temp-id" class="temp-id" type="hidden" name="tempId" value="<?php echo crc32(rand()); ?>">
                                    </form>
                                    <input id="activity-feed-share-facebook-button" type="checkbox" checked="checked">
                                    <label id="activity-feed-share-facebook-label" for="activity-feed-share-facebook-button"><img alt="Share on Facebook" src="/images/share_on_facebook.png"></label>
                                </div>

                            </div>

                            <?php $GLOBALS['super_timers']['rrrr6'] = microtime(true) - $GLOBALS['super_start']; ?>

                            <div id="activity-table-wrapper" class="table-responsive">
                                <?php if (!$tour): ?>
                                    <?php $activities = $user->getActivityFeed(0, \Entity\User::ACTIVITIES_PER_PAGE, $CI->user->getId() == $user->getId() ? 'my' : 'other'); ?>
                                    <?php $GLOBALS['super_timers']['rrrr6_1'] = microtime(true) - $GLOBALS['super_start']; ?>
                                <?php endif; ?>
                                <?php if(empty($activities)): ?>
                                    <h4>No activity.</h4>
                                <?php else: ?>
                                    <table class="table table-hover activity-table small-activity-table secret-code-activity-area">
                                        <colgroup>
                                            <col class="activity">
                                            <col class="activity_cf_std">
                                        </colgroup>
                                        <tbody class="activity-table-tbody" data-context="<?php echo $CI->user->getId() == $user->getId() ? 'my' : 'other'; ?>" data-user-id="<?php echo $user->getId(); ?>">
                                        <?php foreach($activities as $activity): ?>
                                            <?php $this->load->view('/members/_activity', array('activity' => $activity, 'context' => $CI->user->getId() == $user->getId() ? 'my' : 'other')); ?>
                                        <?php endforeach; ?>

                                        </tbody>
                                    </table>

                                <?php endif; ?>
                            </div><!-- table-responsive end -->

                            <?php $GLOBALS['super_timers']['rrrr7'] = microtime(true) - $GLOBALS['super_start']; ?>

                            <?php if (!$tour): ?>
                            <div class="clearfix activity-load-more-wrapper">
                                <a href="#"
                                   class="activity-load-more"
                                   data-offset="15"
                                   data-activities-per-page="<?php echo \Entity\User::ACTIVITIES_PER_PAGE; ?>"
                                   data-user-id="<?php echo $user->getId(); ?>"
                                   data-loading-text="Loading more...">Load More Activities</a>
                            </div>
                            <?php endif; ?>

                        </div><!-- block end -->
                    </div>

                    <?php $GLOBALS['super_timers']['rrrr8'] = microtime(true) - $GLOBALS['super_start']; ?>

                    <!-- COL #2 -->
                    <div class="col-md-5">
                        <?php $this->load->view('/partials/trending-petitions'); ?>

                        <?php $GLOBALS['super_timers']['rrrr8_1'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <?php $this->load->view('/partials/learn-more/alternate'); ?>

                        <?php $GLOBALS['super_timers']['rrrr8_2'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <?php $this->load->view('/partials/_fun_donations', ['my_dashboard' => $my_dashboard, 'user' => $user]); ?>

                        <?php $GLOBALS['super_timers']['rrrr8_3'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <?php if (!GIVERHUB_LIVE): $this->load->view('/giverhub-petitions/_create_block', ['user' => $user]); endif; ?>

                        <div class="load-nonprofit-feed block"><img class="cntr" alt="Loading Nonprofit Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>


                        <div class="numero6"></div>
                        <div class="load-petition-feed block"><img class="cntr" alt="Loading Petition Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>


                        <?php if ($my_dashboard): ?>
                            <div class="block recent-donations-block">
                                <h3 class="gh_block_title">Recent Donations <i class="icon-heart pull-right"></i></h3>

                                <?php if ($user->hasDonations()): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th class="col-md-8">NONPROFIT NAME</th>
                                                <th class="col-md-2">AMOUNT</th>
                                                <th class="col-md-2">DATE</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($user->getDonations(3) as $donation): ?>
                                                <tr>
                                                    <td class="name"><a href="<?php echo htmlspecialchars($donation->getCharity()); ?>"><?php echo htmlspecialchars($donation->getCharity()->getName()); ?></a></td>
                                                    <td class="amount">$<?php echo htmlspecialchars($donation->getAmount()); ?></td>
                                                    <td class="date"><?php echo htmlspecialchars($donation->getDateTime()->format('m/d/Y')); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div><!-- table-responsive end -->
                                <?php else: ?>
                                    <p class="no-donations-yet">No Donations Yet.</p>
                                <?php endif; ?>

                                <div class="clearfix view-all-donations-wrapper">
                                    <a href="/members/donations" class="pull-right view-all-donations">VIEW ALL DONATIONS</a>
                                </div>

                                <?php $GLOBALS['super_timers']['rrrr8_4'] = microtime(true) - $GLOBALS['super_start']; ?>

                            </div><!-- block end -->
                        <?php endif; ?>
                        <?php $GLOBALS['super_timers']['rrrr14'] = microtime(true) - $GLOBALS['super_start']; ?>
                    </div>
                </div>
            </div>
        </div><!-- row end -->
    </section><!-- container end -->
</main>
<?php $GLOBALS['super_timers']['rrrr15'] = microtime(true) - $GLOBALS['super_start']; ?>
<?php $CI->load->view('/partials/_display-photo-modal'); ?>
<?php $GLOBALS['super_timers']['rrrr16'] = microtime(true) - $GLOBALS['super_start']; ?>

<?php if ($tour): ?>
    <ol id="joyRideTipContentForTour" class="hide">
        <li data-class="so-awesome" data-text="Next: Nonprofit Feed" data-options="tipLocation:right;nubPosition:hide">
            <p><u>Welcome to GiverHub!</u><br/><br/>GiverHub makes donating fast, easy, and fun. You can use it to donate
                instantly to all the nonprofits you already know and love (or almost any other nonprofit in the US) and
                automatically keep track of your donations, or you can use features like "Bet-a-Friend" to add some
                excitement to your charitable contributions! Click the button below to continue this tour of your dashboard
                and GiverHub's various features.</p>
        </li>
        <li data-class="numero2" data-button="Next: Bet-a-Friend" data-options="tipLocation:top;tipAnimation:fade">
            <p>This is your <u>Nonprofit Feed</u>. At the top is a field that allows you to search for nonprofits by name or
                discover nonprofits using keywords. If you don't enter anything we use the feed to display nonprofits we
                think you'll be interested in. "Add Causes" above to help us improve our recommendations.</p>
        </li>
        <li data-class="numero3" data-button="Next: Activity Feed" data-options="tipLocation:top">
            <!--<p>Bet-a-Friend is a fun new way to give back! You make a bet with a friend of yours and whoever loses has to donate the agreed upon amount to the winner's charity of choice.</p>-->
            <p>Bet-a-Friend is a fun new way to give back. Make a bet with a friend and whoever loses donates the agreed
                upon amount to the winner's charity of choice!</p>
        </li>
        <li data-class="numero4" data-text="Next: Donation History" data-options="tipLocation:top;nubPosition:bottom">
            <p>This is your <u>Activity Feed</u>, it's how we keep you informed of what your friends are up to on Giverhub,
                whether it's the nonprofits they support, the petitions they've signed, and more. It also displays your
                recent activity so you can be reminded of all the ways you've given back recently.</p>
        </li>
        <li data-class="numero5" data-button="Next: Petition Feed" data-options="tipLocation:right">
            <p>Your Donation History makes itemizing your charitable deductions on your tax returns an absolute breeze. Go
                there to view all of your past donations, along with the details of those donations, in chronological order,
                grouped by month and year.</p>
        </li>
        <li data-class="numero6" data-button="Close" data-options="tipLocation:left">
            <p>This is your <u>Petition Feed</u>. At the top is a field that allows you to search for petitions by name or
                discover petitions using keywords. If you don't enter anything we use the feed to display petitions we think
                you'll be interested in. "Add Causes" above to help us improve our recommendations.</p>
        </li>
    </ol>

    <script>
        jQuery(document).ready(function () {
            jQuery('#joyRideTipContentForTour').joyride({
                autoStart : true,
                postStepCallback : function (index, tip) {
                    if (index == 5) {
                        jQuery(this).joyride('set_li', false, 1);
                        location.href = '<?php echo base_url();?>';
                    }
                },
                modal : true,
                expose : true
            });
        });
    </script>
<?php elseif($my_dashboard && !$CI->user->getIsDashboardTourTaken()): ?>
    <ol id="joyRideTipContent">

        <li data-class="so-awesome" data-text="Next: Nonprofit Feed" data-options="tipLocation:right;nubPosition:hide">
            <p><u>Welcome to GiverHub!</u><br/><br/>GiverHub makes donating fast, easy, and fun. You can use it to donate instantly to all the nonprofits you already know and love (or almost any other nonprofit in the US) and automatically keep track of your donations, or you can use features like "Bet-a-Friend" to add some excitement to your charitable contributions! Click the button below to continue this tour of your dashboard and GiverHub's various features.</p>
        </li>
        <li data-class="numero2" data-button="Next: Bet-a-Friend" data-options="tipLocation:top;tipAnimation:fade">
            <p>This is your <u>Nonprofit Feed</u>. At the top is a field that allows you to search for nonprofits by name or discover nonprofits using keywords. If you don't enter anything we use the feed to display nonprofits we think you'll be interested in. "Add Causes" above to help us improve our recommendations.</p>
        </li>
        <li data-class="numero3" data-button="Next: Activity Feed" data-options="tipLocation:top">
            <!--<p>Bet-a-Friend is a fun new way to give back! You make a bet with a friend of yours and whoever loses has to donate the agreed upon amount to the winner's charity of choice.</p>-->
            <p>Bet-a-Friend is a fun new way to give back. Make a bet with a friend and whoever loses donates the agreed upon amount to the winner's charity of choice!</p>
        </li>
        <li data-class="numero4" data-text="Next: Donation History" data-options="tipLocation:top;nubPosition:bottom">
            <p>This is your <u>Activity Feed</u>, it's how we keep you informed of what your friends are up to on Giverhub, whether it's the nonprofits they support, the petitions they've signed, and more. It also displays your recent activity so you can be reminded of all the ways you've given back recently.</p>
        </li>
        <li data-class="numero5" data-button="Next: Petition Feed" data-options="tipLocation:right">
            <p>Your Donation History makes itemizing your charitable deductions on your tax returns an absolute breeze. Go there to view all of your past donations, along with the details of those donations, in chronological order, grouped by month and year.</p>
        </li>
        <li data-class="numero6" data-button="Close" data-options="tipLocation:left">
            <p>This is your <u>Petition Feed</u>. At the top is a field that allows you to search for petitions by name or discover petitions using keywords. If you don't enter anything we use the feed to display petitions we think you'll be interested in. "Add Causes" above to help us improve our recommendations.</p>
        </li>
    </ol>
<?php endif; ?>
<?php $GLOBALS['super_timers']['rrrr17'] = microtime(true) - $GLOBALS['super_start']; ?>