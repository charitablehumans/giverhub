<?php
/** @var \Base_Controller $this */
if (!$this->user && !isset($user)) {
    throw new Exception('user not set.');
}
if ($this->user) {
    $user = $this->user;
}
?>
<div class="col-md-2 col-sm-3 hidden-xs members-left-side-nav">
    <div class="row">
        <div class="col-sm-12 col-xs-6 members_left_menu menu1">
            <div class="block">
                <table class="table-hover" style="width: 100%">
                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/settings">Settings</a></td></tr>

                    <tr>
                        <td class="new_nav_td">
                            <a class="use-in-mobile-menu"
                               data-mobile-menu-label="DASHBOARD"
                               href="/members/messages">Messages<?php if ($this->user && $this->user->hasUnreadMessages()): ?><span class="badge badge-red"><?php echo count($this->user->getUnreadMessages()); ?></span><?php endif; ?></a>
                        </td>
                    </tr>

                    <?php if($user->getCharityReviews()): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/reviews">My Reviews</a></td></tr>
                    <?php endif; ?>

                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/activity">Activity</a></td></tr>

                    <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/followers">Followers</a></td></tr>

                    <?php if($user->getMyChallenges()): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/my_challenges">My CHALLENGES</a></td></tr>
                    <?php endif; ?>

                    <?php if (!GIVERHUB_LIVE): ?>
                        <?php if ($user->getMyPetitions()): ?>
                            <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/my_petitions">My Petitions</a></td></tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    <tr colspan="2"><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/donations">Donation History</a></td><td class="numero5"></td></tr>

                    <?php if ($user->getMySignedChangeOrgPetitions()): ?>
                        <tr colspan="2"><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/petition-history">Petition History</a></td></tr>
                    <?php endif; ?>

                    <?php if ($user->getBets()): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/members/bets">Bets</a></td></tr>
                    <?php endif; ?>

                    <?php if ($user->hasGiverCardsOrGivingPots()): ?>
                        <tr><td class="new_nav_td"><a class="use-in-mobile-menu" data-mobile-menu-label="DASHBOARD" href="/giver-cards" class="giver_cards">GiverCards</a></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php if ($this->user && $this->user->isAdmin()): ?>
            How often people have visited their own dashboard
            <?php $this->load->view('/partials/stat', ['name' => 'dashboard-views']); ?>
        <?php endif; ?>
    </div>
</div>
