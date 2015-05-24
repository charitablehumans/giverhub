<?php
/** @var \Entity\Page $page */
/** @var \Page $CI */
$CI =& get_instance();
?>
<?php $this->load->view('/page/_header', ['page' => $page]); ?>

    <main class="members_main" role="main" id="main_content_area">

        <?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); } ?>
        <section class="container">
            <div class="row">

                <?php if ($CI->user): ?>
                    <?php $this->load->view('/members/_member_new_nav'); ?>
                <?php endif; ?>
                <div class="<?php echo $CI->user ? 'col-md-10 col-sm-9' : 'col-md-12'; ?>">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="dashboard-buttons-wrapper">
                                <a class="block col-md-4 col-xs-4 dashboard-button-nonprofits" href="#"><span class="glyphicon glyphicon-search"></span>Search Nonprofits</a>
                                <a class="block col-md-4 col-xs-4 btn-create-petition-from-block" data-temp-id="<?php echo crc32(rand()); ?>" href="#"><span class="glyphicon glyphicon-plus"></span>Create a Petition</a>
                                <a class="block col-md-4 col-xs-4 dashboard-button-petitions" href="#"><span class="glyphicon glyphicon-search"></span>Search Petitions</a>
                            </div>

                            <div class="block members_main">
                                <?php if ($CI->user): ?>
                                    <div class="row">
                                        <button
                                            data-loading-text="POSTING..."
                                            data-to-user-id="<?php echo $page->getUser()->getId(); ?>"
                                            data-context="<?php echo $page->getUser()->getId() == $CI->user->getId() ? 'my' : 'other'; ?>"
                                            type="button"
                                            class="btn btn-primary btn-sm btn-post-activity-feed">POST</button>
                                        <?php $placeholder = $page->isAdmin() ? 'Post cause related content here for like-minded Givers to see' : 'Post something to ' . $page->getName() . '\'s feed...'; ?>
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
                                <?php endif; ?>

                                <div id="activity-table-wrapper" class="table-responsive">
                                    <?php $activities = $page->getUser()->getActivityFeed(0, 15, $page->isAdmin() ? 'my' : 'other'); ?>
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
                                                <?php $this->load->view('/members/_activity', array('activity' => $activity, 'context' => $page->isAdmin() ? 'my' : 'other')); ?>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    <?php endif; ?>
                                </div>

                                <div class="clearfix activity-load-more-wrapper">
                                    <a href="#"
                                       class="activity-load-more"
                                       data-offset="<?php echo \Entity\User::ACTIVITIES_PER_PAGE; ?>"
                                       data-activities-per-page="<?php echo \Entity\User::ACTIVITIES_PER_PAGE; ?>"
                                       data-user-id="<?php echo $page->getUser()->getId(); ?>"
                                       data-loading-text="Loading more...">Load More Activities</a>
                                </div>
                            </div>
                        </div>


                        <!-- COL #2 -->
                        <div class="col-md-5">
                            <?php $this->load->view('/partials/learn-more/alternate'); ?>

                            <?php $this->load->view('/partials/_fun_donations'); ?>

                            <?php if (!GIVERHUB_LIVE): ?>
                                <?php if ($CI->user): ?>
                                    <?php $this->load->view('/giverhub-petitions/_create_block', ['user' => $CI->user]); ?>
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="load-nonprofit-feed block"><img class="cntr" alt="Loading Nonprofit Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>

                            <div class="load-petition-feed block"><img class="cntr" alt="Loading Petition Feed" src="/images/ajax-loaders/ajax-loader.gif"></div>
                        </div>
                    </div>
                </div>
            </div><!-- row end -->
        </section><!-- container end -->
    </main>
<?php $CI->load->view('/partials/_display-photo-modal'); ?>
