<?php
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
    /** @var \Entity\Challenge $challenge */
    /** @var string $context */
?>
<div class="block challenge-page-info challenge-info-block">
    <p class="title">
        <?php if (isset($context)): ?>
            <?php echo $challenge->getFromUser()->getLink(); ?> sent
        <?php else: ?>
            You have <?php if ($CI->user && $challenge->getFromUser() == $CI->user): ?>sent<?php else: ?>received<?php endif; ?>
        <?php endif; ?>
        the <strong><?php echo htmlspecialchars($challenge->getNameWithChallenge()); ?></strong></p>
    <?php if ($challenge->getDedication()): ?>
        <p class="dedication"><?php echo htmlspecialchars($challenge->getDedication()); ?></p>
    <?php endif; ?>
    <?php if (isset($context) || ($CI->user && $challenge->getFromUser() == $CI->user)): ?>
        <div class="to-users">
            <?php foreach($challenge->getChallengeUsers() as $challenge_user): ?>
                <div class="from-user-container to-user-container">
                    <div class="from">to</div>
                    <div class="user-container">
                        <?php if ($challenge_user->getUserEntity()): ?>
                            <a href="<?php echo $challenge_user->getUserEntity()->getUrl(); ?>">
                                <img src="<?php echo $challenge_user->getUserEntity()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($challenge_user->getUserEntity()->getName()); ?>">
                                <span class="name"><?php echo htmlspecialchars($challenge_user->getUserEntity()->getName()); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="mailto:<?php echo htmlspecialchars($challenge_user->getEmail()); ?>"><?php echo htmlspecialchars($challenge_user->getEmail()); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="status">
                        Status: <strong><?php echo htmlspecialchars($challenge_user->getStatus()); ?></strong>
                        <?php if ($CI->user && $CI->user == $challenge->getFromUser() && !$challenge_user->getUser()): ?>
                            <button
                                type="button"
                                data-challenge-user-id="<?php echo $challenge_user->getId(); ?>"
                                class="btn btn-info btn-resend-email-challenge btn-xs"
                                data-loading-text="Resend">Resend</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="from-user-container">
            <div class="from">from</div>
            <div class="user-container">
                <a href="<?php echo $challenge->getFromUser()->getUrl(); ?>">
                    <img src="<?php echo $challenge->getFromUser()->getImageUrl(); ?>" alt="<?php echo htmlspecialchars($challenge->getFromUser()->getName()); ?>">
                    <span class="name"><?php echo htmlspecialchars($challenge->getFromUser()->getName()); ?></span>
                </a>
            </div>
        </div>
    <?php endif; ?>
    <p class="desc">
        <?php echo htmlspecialchars($challenge->getFromUser()->getFname()); ?> CHALLENGES you to
        <strong><?php echo htmlspecialchars($challenge->getDescription()); ?></strong>
    </p>
    <p class="chicken">If you're too chicken to perform the challenge, or simply would prefer to donate, <?php echo htmlspecialchars($challenge->getFromUser()->getFname()); ?> requests that you make a donation to <strong><?php echo $challenge->getCharity()->getLink(); ?></strong></p>
    <p class="video">Here's a video of <?php echo htmlspecialchars($challenge->getFromUser()->getFname()); ?> performing the CHALLENGE</p>
    <div
        class="youtube-preview-container"
        data-youtube-video-id="<?php echo htmlspecialchars($challenge->getYoutubeVideoId()); ?>">
        <iframe
            class="youtube-player youtube-preview-iframe"
            type="text/html"
            height=""
            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($challenge->getYoutubeVideoId()); ?>?controls=2"
            allowfullscreen
            frameborder="0"></iframe>
    </div>
    <?php if (!$CI->user || $challenge->getUserStatus($CI->user) === 'sent'): ?>
        <div class="accept-reject" data-challenge-id="<?php echo $challenge->getId(); ?>">
            <button class="btn btn-success btn-lg btn-accept-challenge" data-loading-text="ACCEPT CHALLENGE">ACCEPT CHALLENGE</button>
            <button class="btn btn-danger btn-lg btn-reject-challenge" data-loading-text="REJECT CHALLENGE">REJECT CHALLENGE</button>
        </div>
    <?php endif; ?>

    <?php if ($CI->user && $challenge->getUserStatus($CI->user) === 'accepted'): ?>
        <div class="upload-video-container">
            <p class="upload-video-title">Upload a Video that shows you completing the CHALLENGE</p>

            <?php $disk_free_space = disk_free_space(__DIR__.'/../../../videos/challenge/') / 1024 / 1024; ?>
            <?php if ($disk_free_space > 200): ?>
                <p class="from-your-phone">From your phone / computer. (MAX 100MB)</p>
                <?php $my_challenge_user_video = $challenge->getToChallengeUser($CI->user)->getChallengeUserVideo(); ?>
                <form class="upload-challenge-video-form" action="#ajax" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="challenge_id" value="<?php echo $challenge->getId(); ?>">
                    <button type="button" class="btn btn-primary"><input class="challenge-upload-video-input" type="file" name="video" accept="video/*"><?php echo $my_challenge_user_video ? 'CHANGE' : 'UPLOAD'; ?> VIDEO</button>
                    <button disabled="disabled" type="button" class="btn btn-success btn-upload-challenge-video" data-loading-text="UPLOADING">SUBMIT</button> <img alt="Loading..." class="upload-challenge-loader hide" src="/images/ajax-loaders/ajax-loader2.gif">
                    <div class="selected-challenge-video-filename"></div>
                </form>

                <p class="or-a-youtube-video">OR a video that you already uploaded to youtube</p>
            <?php else: ?>
                <?php mail('admin@giverhub.com', 'disk space is below 200mb!!! @' . @$_SERVER['SERVER_NAME'], 'Free: ' . $disk_free_space . 'MB'); ?>
            <?php endif; ?>

            <form class="youtube-challenge-form">
                <div class="youtube-preview-container"></div>
                <input
                    class="form-control youtube-url-challenge-input"
                    type="text"
                    name="youtube-url"
                    placeholder="http://www.youtube.com/watch?v=X">
                <button
                    data-challenge-id="<?php echo $challenge->getId(); ?>"
                    type="button"
                    class="btn btn-success btn-sm btn-submit-youtube-url-challenge"
                    disabled="disabled"
                    data-loading-text="SUBMIT">SUBMIT</button>
            </form>
        </div>
        <?php /* <p class="or-donate-to">or donate to</p> */ ?>
    <?php else: ?>
        <?php /* <p class="or-donate-to">failed the challenge? donate to:</p> */ ?>
    <?php endif; ?>

    <?php $charity = $challenge->getCharity(); ?>
    <div class="nonprofit">
        <div class="nonprofit-name"><?php echo $charity->getLink(); ?></div>
        <?php
            if ($charity->getOverallScore() !== null) {
                $progressBar                   = array();
                $progressBar['goalProgress']   = $charity->getOverallScore();
                $progressBar['tooltipMessage'] = \Entity\Charity::$overallScoreText;
                $progressBar['areaValueNow']   = round( $charity->getOverallScore() );
                $progressBar['subClasses']     = "gh_popover";
                $this->view( '/partials/_progress_bar', $progressBar );
            }
        ?>
        <?php if ($charity->getMissionSummary()): ?>
            <div class="nonprofit-tagline" title="<?php echo htmlspecialchars($charity->getMissionSummary()); ?>">
                <?php echo htmlspecialchars($charity->getMissionSummary()); ?>
            </div>
        <?php endif; ?>
        <div class="donate-container">
            <label id="activity-post-donate-amount-label" for="activity-post-donate-amount_<?php echo $challenge->getId(); ?>">Easy Donate: </label>
            <input id="activity-post-donate-amount_<?php echo $challenge->getId(); ?>" type="text" class="form-control charity-profile-donation-amount-input" placeholder="$ Amount"/>
            <a
                data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                data-charity-id="<?php echo $charity->getId(); ?>"
                class="btn-donate-using-cc-paypal-button-with-amount paypal gh-trigger-event gh_tooltip"
                data-event-category="button"
                data-event-action="click"
                data-event-label="paypal donate (from challenge)"
                title="Clicking this button will begin the process for making a donation using your paypal account. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                data-container="body"
                href="#"><img src="/img/button_paypal.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
            <a
                data-charity-name="<?php echo htmlspecialchars($charity->getName()); ?>"
                data-charity-id="<?php echo $charity->getId(); ?>"
                class="btn-donate-using-cc-paypal-button-with-amount cc gh-trigger-event gh_tooltip"
                data-event-category="button"
                data-event-action="click"
                data-event-label="cc donate (from challenge)"
                title="Clicking this button will begin the process of making a donation using your credit card. No donation will be made until you confirm your donation amount and click &quot;MAKE THE DONATION!&quot;"
                data-container="body"
                href="#"><img src="/img/button_cc.png" alt="Donate to <?php echo htmlspecialchars($charity->getName()); ?> using Credit Card"></a>
        </div>
    </div>

    <?php if ($CI->user && $challenge->getFromUser() != $CI->user && !$challenge->isToUser($CI->user)): ?>
    <div class="clone-challenge">
        <p>You weren't challenged, want to issue this challenge to others?</p>
        <a href="/challenge/reissue/<?php echo $challenge->getId(); ?>"
           class="btn btn-info btn-lg btn-clone-challenge">Issue This Challenge</a>
    </div>
    <?php endif; ?>

    <div class="uploaded-videos-container">
        <?php foreach($challenge->getChallengeUsers() as $challenge_user): ?>
            <div class="uploaded-video-container">
                <?php $challenge_user_video = $challenge_user->getChallengeUserVideo(); ?>
                <?php if ($challenge_user_video): ?>
                    <?php if (isset($my_challenge_user_video) && $challenge_user_video == $my_challenge_user_video): ?>
                        <p class="who-video your-video">Here's the video that YOU uploaded.</p>
                    <?php else: ?>
                        <p class="who-video someone-elses-video">Here's the video that <?php echo $challenge_user->getUserEntity()->getLink(); ?> uploaded.</p>
                    <?php endif; ?>
                    <?php if ($challenge_user_video->getYoutubeVideoId()): ?>
                        <div class="youtube-preview-container"
                             data-youtube-video-id="<?php echo htmlspecialchars($challenge_user_video->getYoutubeVideoId()); ?>">
                            <iframe
                                class="youtube-player youtube-preview-iframe"
                                type="text/html"
                                height=""
                                src="https://www.youtube.com/embed/<?php echo htmlspecialchars($challenge_user_video->getYoutubeVideoId()); ?>?controls=2"
                                allowfullscreen
                                frameborder="0"></iframe>
                        </div>
                    <?php else: ?>
                        <div class="uploaded-challenge-video">
                            <video
                                id="challenge_vid_<?php echo $challenge_user_video->getId(); ?>"
                                class="video-js vjs-default-skin"
                                controls
                                preload="auto">
                                <source src="<?php echo $challenge_user_video->getUrl(); ?>" type='<?php echo htmlspecialchars($challenge_user_video->getFiletype()); ?>' />
                                <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                            </video>
                            <span class="download-challenge-text">Do you have trouble playing the video in the player above? Try downloading the video instead: <?php echo $challenge_user_video->getLink(); ?></span>
                        </div>
                    <?php endif; ?>
                <?php elseif($challenge_user->getStatus() == 'rejected'): ?>
                    <p class="who-video someone-elses-video"><?php echo $challenge_user->getUserEntity()->getLink(); ?> has rejected the CHALLENGE.</p>
                <?php elseif($challenge_user->getStatus() == 'accepted'): ?>
                    <p class="who-video someone-elses-video"><?php echo $challenge_user->getUserEntity()->getLink(); ?> accepted the CHALLENGE but has not uploaded a video.</p>
                <?php elseif($challenge_user->getStatus() == 'sent'): ?>
                    <p class="who-video someone-elses-video">
                        <?php echo $challenge_user->getUserEntity() ? $challenge_user->getUserEntity()->getLink() : $challenge_user->getEmail(); ?> received the CHALLENGE but has not accepted yet.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>