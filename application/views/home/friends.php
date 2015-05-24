<?php
    /** @var Home $CI */
    $CI =& get_instance();
    /** @var array $fbFriends */
    /** @var array $fbAlready */
    /** @var boolean $google */
    /** @var array $gmail_contacts */
?>
<!-- Secondary header include -->
<section class="gh_secondary_header clearfix">

    <section class="container empty_header">

        <div class="row">
            <h2 class="col-md-6">
                Find<br/> Friends

                <small class="blk"></small>
            </h2>
            <div class="col-md-6">
                <div class="find_friend_select text-right">
                    <span>on</span>
                    <a href="/home/friends"
                        class="fb_logo_btn <?php echo !$google ? 'active' : ''; ?>"
                        data-showbtn="fb_btn"
                        data-signin-message="You need to be signed in using facebook to be able to find friends.">facebook</a>
                    <span>or</span>
                    <a href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $this->config->item('google_client_id');?>&redirect_uri=<?php echo $this->config->item('google_redirect_uri');?>&scope=https://www.google.com/m8/feeds/&response_type=code"
                       class="goo_logo_btn <?php echo $google ? 'active' : ''; ?>"
                       data-signin-message="You need to be signed in using Google to be able to find friends.">google</a>
                </div>
            </div>
        </div>
        <!-- row end -->

    </section>
    <!-- empty_header end -->
</section><!-- gh_secondary_header end -->


<main class="no-scroll-navbar" role="main">
    <?php if (!$google && empty($fbAlready) && empty($fbFriends)): ?>
        <section class="filter clearfix">
            <section class="container">


                <div class="col-md-10 col-md-offset-1">
                    <div class="alert alert-warning text-center">You need to be signed in using facebook to be able to find friends.</div>
                </div>


            </section>
            <!-- container end -->
        </section>
        <!-- filter end -->
    <?php endif; ?>



    <section class="container">
        <div class="row">
            <?php if (!$google && empty($fbAlready) && empty($fbFriends)): ?>
                <div class="gh_spacer_28" id="signin_buttons">
                    <a href="#" class="facebook-sign-in fb_btn cntr">Sign in with facebook</a>
                </div>
            <?php else: ?>
                <br/>
                <div class="col-md-12">

                    <div class="block">
                        <?php if ($google): ?>
                            <?php if($gmail_contacts): ?>
                                <h3 class="gh_block_title gh_spacer_28">Invite Friends to Giverhub <i class="icon-user-following pull-right"></i></h3>

                                <form action="#" class="gh_find_friens gh_spacer_35">
                                    <div class="form-group clearfix">
                                        <div class="col-md-6">
                                            <input id="search-field-follow-friends" type="text" class="form-control col-md-6"
                                                   placeholder="Search GMail Contacts"/>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="color_light">CLICK ON A FRIEND TO INVITE HIM OR HER TO GIVERHUB</small>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <ul class="friends_select">
                                            <?php foreach ($gmail_contacts as $contact): ?>
                                                <?php if(is_object($contact) && isset($contact->address) && $contact->address!=""): ?>
                                                    <li class="searchable-follow-friend-item" data-name="<?php echo htmlspecialchars($contact->username); ?>">
                                                        <a class="gmail-invite" href="#" data-address="<?php echo htmlspecialchars($contact->address); ?>">
                                                            <img alt="Google" src="/home/display_gmail_photo?href=<?php echo urlencode($contact->photo_href); ?>" class="img-rounded"/>
                                                            <span><?php echo htmlspecialchars(substr($contact->username,0,14)); ?></span>
                                                            <i class="icon-ok"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </form>
                                <!-- gh_find_friens end -->
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if($fbAlready): ?>
                                <h3 class="gh_block_title gh_spacer_28">Find Friends <i class="icon-user-following pull-right"></i></h3>

                                <form action="#" class="gh_find_friens gh_spacer_42">
                                    <div class="form-group clearfix">
                                        <div class="col-md-6">
                                            <input id="search-field-invite-friends" type="text" class="form-control col-md-6"
                                                   placeholder="Search Facebook friends already using GiverHub"/>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="color_light">CLICK ON A FRIEND TO FOLLOW HIM OR HER</small>
                                        </div>
                                    </div>
                                    <!-- form-group end -->

                                    <div class="form-group clearfix">
                                        <ul class="friends_select">
                                            <?php foreach ($fbAlready as $fbFriend): ?>
                                                <li class="searchable-friend-item" data-name="<?php echo htmlspecialchars($fbFriend['name']); ?>">
                                                    <a class="btn-follow-user find-friends <?php if($fbFriend['following']): ?>selected<?php endif; ?>" href="#" data-user-id="<?php echo $fbFriend['userId']; ?>" data-fb-id="<?php echo $fbFriend['id']; ?>">
                                                        <img src="https://graph.facebook.com/<?php echo $fbFriend['id'] ?>/picture" alt="<?php echo htmlspecialchars($fbFriend['name']); ?>" class="img-rounded"/>
                                                        <span><?php echo htmlspecialchars(substr($fbFriend['name'],0,16)); ?></span>
                                                        <i class="icon-ok"></i>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <!-- form-group end -->
                                </form>
                                <!-- gh_find_friens end -->
                            <?php endif; ?>

                            <?php if($fbFriends): ?>
                                <h3 class="gh_block_title gh_spacer_28">Invite Friends to Giverhub <i class="icon-user-following pull-right"></i></h3>

                                <form action="#" class="gh_find_friens gh_spacer_35">
                                    <div class="form-group clearfix">
                                        <div class="col-md-6">
                                            <input id="search-field-follow-friends" type="text" class="form-control col-md-6"
                                                   placeholder="Search Facebook friends"/>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="color_light">CLICK ON A FRIEND TO INVITE HIM OR HER TO GIVERHUB</small>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <ul class="friends_select">
                                            <?php foreach ($fbFriends as $fbFriend): ?>
                                                <li class="searchable-follow-friend-item" data-name="<?php echo htmlspecialchars($fbFriend['name']); ?>">
                                                    <a class="fb-invite" href="#" data-fb-id="<?php echo $fbFriend['id']; ?>" data-link="<?php echo htmlspecialchars(($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME']); ?>">
                                                        <img src="https://graph.facebook.com/<?php echo $fbFriend['id'] ?>/picture" alt="<?php echo htmlspecialchars($fbFriend['name']); ?>" class="img-rounded"/>
                                                        <span><?php echo htmlspecialchars(substr($fbFriend['name'],0,16)); ?></span>
                                                        <i class="icon-ok"></i>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </form>
                                <!-- gh_find_friens end -->
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <!-- block end -->

                </div>
                <!-- col-md-12 end -->
            <?php endif; ?>

        </div>
        <!-- row end -->
    </section>
    <!-- container end -->
</main>
