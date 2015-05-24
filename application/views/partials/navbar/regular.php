<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
?>
<?php $GLOBALS['super_timers']['nrnr1'] = microtime(true) - $GLOBALS['super_start']; ?>
<header class="navbar gh_header" role="banner" id="new_navigation_menu">
    <?php if ($this->user && $this->user->isAdmin()): ?>
        <?php $GLOBALS['super_timers']['nrnr2'] = microtime(true) - $GLOBALS['super_start']; ?>
        <style>
            #admin-toolbar {
                position: absolute;
                padding-left: 5px;
            }
            #admin-toolbar-close {
                float: right;
                display: inline-block;
                position: relative;
                top: -5px;
            }
        </style>
        <div id="admin-toolbar">
            <a id="admin-toolbar-close" href="#">x</a>
            <a href="/admin">ADMIN</a><br/>
            <a href="/admin/users_graph/">Users: <?php echo htmlspecialchars($CI->getUserCount()); ?> (<?php echo \Entity\User::getGrowthRateData()['growth_rate']; ?>%)</a>
        </div>
        <?php $GLOBALS['super_timers']['nrnr3'] = microtime(true) - $GLOBALS['super_start']; ?>
    <?php endif; ?>
    <?php $GLOBALS['super_timers']['nrnr4'] = microtime(true) - $GLOBALS['super_start']; ?>
    <div class="container">
        <div class="col-md-12">
            <div class="gh_logo pull-left">
                <a class="use-in-mobile-menu" href="/">
                    <img src="/img/navbar-logo.png" alt="GiverHub, Inc."/>
                </a>
            </div>
            <a
                href="#"
                data-toggle="modal"
                data-target="#giverhub-debug-modal"><?php if (GIVERHUB_DEBUG): ?>DEBUG<?php else: ?>&nbsp;<?php endif; ?></a>
        </div>
        <?php $GLOBALS['super_timers']['nrnr5'] = microtime(true) - $GLOBALS['super_start']; ?>
        <div class="col-md-14">
            <nav class="pull-right" role="navigation">
                <ul class="list-inline new-nav-ul-list-inline">
                    <?php $GLOBALS['super_timers']['nrnr6'] = microtime(true) - $GLOBALS['super_start']; ?>
                    <?php if (!$this->user): ?>
                        <li class="new-nav-sign-in">
                            <a class="gh-trigger-event use-in-mobile-menu"
                               data-mobile-menu-label="NAV"
                               data-event-category="button"
                               data-event-action="click"
                               data-event-label="sign in (navbar)"
                               data-toggle="modal"
                               data-target="#sign-in-modal-2"
                               href="#">Sign In</a>
                        </li>
                        <li>
                            <a
                                class="gh-trigger-event use-in-mobile-menu"
                                data-mobile-menu-label="NAV"
                                data-event-category="button"
                                data-event-action="click"
                                data-event-label="join (navbar)"
                                data-target="#sign-up-modal-2"
                                data-toggle="modal"
                                href="#">Join</a>
                        </li>
                        <?php $GLOBALS['super_timers']['nrnr7'] = microtime(true) - $GLOBALS['super_start']; ?>
                    <?php endif; ?>
                    <?php if ($this->user): ?>
                        <?php $GLOBALS['super_timers']['nrnr8'] = microtime(true) - $GLOBALS['super_start']; ?>

                        <li class="<?php active('home/index'); ?> dashboard-icon">
                            <a href="#" data-toggle="dropdown" class="new-dashboard-icon"><img
                                    alt="Dashboard"
                                    src="<?php echo $this->user->getImageUrl(); ?>"></a>
                            <a href="#" data-toggle="dropdown" class="use-in-mobile-menu hidden-outside-mobile-menu" data-mobile-menu-label="NAV">Dashboard</a>
                            <ul id="menu4" class="dropdown-menu" aria-labelledby="drop4" role="menu">
                                <li class="<?php active(['home/index'], true, true); ?>">
                                    <a href="/">Dashboard</a>
                                </li>
                                <li class="<?php active(['members/settings'], true, true); ?>">
                                    <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/settings">Settings</a>
                                </li>
                                <li class="<?php active(['members/messages'], true, true); ?>">
                                    <a class="use-in-mobile-menu"
                                       data-mobile-menu-label="DASHBOARD"
                                       href="/members/messages">Messages<?php if ($this->user && $this->user->hasUnreadMessages()): ?><span class="badge badge-red"><?php echo count($this->user->getUnreadMessages()); ?></span><?php endif; ?></a>
                                </li>
                                <?php if($this->user->getCharityReviews()): ?>
                                    <li class="<?php active(['members/reviews'], true, true); ?>">
                                        <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/reviews">My Reviews</a>
                                    </li>
                                <?php endif; ?>

                                <li class="<?php active(['members/activity'], true, true); ?>">
                                    <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/activity">Activity</a>
                                </li>

                                <li class="<?php active(['members/followers'], true, true); ?>">
                                    <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/followers">Followers</a>
                                </li>

                                <?php if($this->user->getMyChallenges()): ?>
                                    <li class="<?php active(['members/my_challenges'], true, true); ?>">
                                        <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/my_challenges">My CHALLENGES</a>
                                    </li>
                                <?php endif; ?>

                                <?php if (!GIVERHUB_LIVE): ?>
                                    <?php if ($this->user->getMyPetitions()): ?>
                                        <li class="<?php active(['members/my_petitions'], true, true); ?>">
                                            <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/my_petitions">My Petitions</a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <li class="<?php active(['members/donations'], true, true); ?>">
                                    <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/donations">Donation History</a>
                                </li>

                                <?php if ($this->user->getMySignedChangeOrgPetitions()): ?>
                                    <li class="<?php active(['members/petition-history'], true, true); ?>">
                                        <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/petition-history">Petition History</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->user->getBets()): ?>
                                    <li class="<?php active(['members/bets'], true, true); ?>">
                                        <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/bets">Bets</a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($this->user->hasGiverCardsOrGivingPots()): ?>
                                    <li class="<?php active(['giver_cards/index'], true, true); ?>">
                                        <a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/giver-cards" class="giver_cards">GiverCards</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                        <?php if (false): ?>
                        <li class="<?php active("home/friends"); ?>">
                            <a data-mobile-menu-label="NAV" class="use-in-mobile-menu" href="/home/friends">Find Friends</a>
                        </li>
                        <?php endif; ?>
                        <?php $GLOBALS['super_timers']['nrnr9'] = microtime(true) - $GLOBALS['super_start']; ?>
                        <li class="<?php active(['home/index', 'members/donations']); ?>">
                            <?php $GLOBALS['super_timers']['nrnr10'] = microtime(true) - $GLOBALS['super_start']; ?>
                            <a href="#" data-toggle="dropdown">You</a>
                            <ul id="menu4" class="dropdown-menu" aria-labelledby="drop4" role="menu">
                                <li class="<?php active(['home/index'], true, true); ?>">
                                    <a href="/">Profile</a>
                                </li>
                                <li class="<?php active(['members/donations'], true, true); ?>">
                                    <a data-mobile-menu-label="NAV" class="use-in-mobile-menu" href="/members/donations">Past Donations</a>
                                </li>
                                <li>
                                    <a class="logout-link" href="/home/logout">Sign Out</a>
                                </li>
                            </ul>
                            <?php $GLOBALS['super_timers']['nrnr11'] = microtime(true) - $GLOBALS['super_start']; ?>
                        </li>
                    <?php endif; ?>
                    <?php $GLOBALS['super_timers']['nrnr14'] = microtime(true) - $GLOBALS['super_start']; ?>
                    <li class="<?php active(['home/contact', 'faqs/index']); ?> <?php if ($this->user): ?>menu_us<?php endif; ?>">
                        <a href="#" data-toggle="dropdown">Us</a>
                        <ul class="dropdown-menu">
                            <?php $GLOBALS['super_timers']['nrnr15'] = microtime(true) - $GLOBALS['super_start']; ?>
                            <li class="<?php active(['home/contact'], true, true); ?>">
                                <a data-mobile-menu-label="NAV" class="use-in-mobile-menu" href="/home/contact">Contact</a>
                            </li>
                            <?php $GLOBALS['super_timers']['nrnr16'] = microtime(true) - $GLOBALS['super_start']; ?>
                            <li class="<?php active(['faqs/index'], true, true); ?>">
                                <a data-mobile-menu-label="NAV" class="use-in-mobile-menu" href="/faqs">FAQs</a>
                            </li>
                            <?php $GLOBALS['super_timers']['nrnr17'] = microtime(true) - $GLOBALS['super_start']; ?>
                            <?php if ($this->user): ?>
                                <li>
                                    <a data-mobile-menu-label="NAV" class="use-in-mobile-menu logout-link" href="/home/logout">Sign Out</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <?php $GLOBALS['super_timers']['nrnr18'] = microtime(true) - $GLOBALS['super_start']; ?>
        <?php if (!$this->user) : ?>
            <div class="col-md-2 col-md-offset-1 navbar-take-tour-link">
                <a data-mobile-menu-label="NAV" class="use-in-mobile-menu" href="/tour">Take a Tour of GiverHub</a>
            </div>
        <?php endif; ?>
        <?php $GLOBALS['super_timers']['nrnr19'] = microtime(true) - $GLOBALS['super_start']; ?>
        <div class="col-md-12">
            <?php $this->load->view('/partials/navbar/search-form'); ?>
        </div>
    </div>
    <!-- container end -->
    <a class="m-menu m-menu-lines" href="#"><i class="icon-align-justify icon-reorder icon-large"></i></a>
    <a class="btn btn-feedback btn-primary btn-xs" data-placement="bottom" title="Leave us feedback! We're eager to hear what you like about GiverHub and what we can do to make it better.">FEEDBACK</a>
</header>
<?php $GLOBALS['super_timers']['nrnr20'] = microtime(true) - $GLOBALS['super_start']; ?>