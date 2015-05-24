<?php
/** @var \Entity\Challenge|null $challenge */
if (!isset($challenge)) {
    $challenge = null;
}
/** @var \Base_Controller $this */
?>
<form class="create-challenge-form"
      data-from-user-name="<?php echo htmlspecialchars($this->user->getName()); ?>"
      data-from-user-img-url="<?php echo htmlspecialchars($this->user->getImageUrl()); ?>"
      data-from-user-first-name="<?php echo htmlspecialchars($this->user->getFname()); ?>"
      <?php if ($challenge): ?>data-challenge-id="<?php echo $challenge->getId(); ?>"<?php endif; ?>
      action="#">
    <p class="lead txtCntr">Step 1: Name your CHALLENGE</p>
    <div class="form-group">
        <input type="text"
               class="form-control challenge_name trigger-review"
               name="name"
               placeholder="Create a name for this CHALLENGE: e.g. ALS Ice-Bucket"
               <?php if ($challenge): ?>value="<?php echo htmlspecialchars($challenge->getName()); ?>"<?php endif; ?>
               tabindex="1">
    </div>

    <p class="lead txtCntr">Step 2: Describe the CHALLENGE</p>
    <div class="form-group">
        <textarea
            class="form-control challenge_description gh_tooltip trigger-review"
            name="description"
            tabindex="2"><?php if ($challenge): ?>I CHALLENGE you to: <?php echo htmlspecialchars($challenge->getDescription()); ?><?php endif; ?></textarea>
    </div>

    <p class="lead txtCntr">Step 3: Pick 1 to 3 friends to CHALLENGE</p>
    <div class="form-group">
        <ul class="emails">
            <?php if ($challenge): ?>
                <?php foreach($challenge->getChallengeUsers() as $challenge_user): ?>
                    <?php if ($challenge_user->getEmail()): ?>
                        <li data-email="<?php echo htmlspecialchars($challenge_user->getEmail()); ?>"><?php echo htmlspecialchars($challenge_user->getEmail()); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <div class="email-wrapper">
            <input type="text"
                   class="form-control challenge_friend"
                   name="friend"
                   placeholder="Email"
                   value=""
                   tabindex="3"><button type="button"
                                        class="btn btn-primary btn-challenge-add-email">ADD</button>
        </div>
    </div>

    <p class="lead txtCntr">Step 4: Enter a recommended nonprofit for donations</p>
    <div class="form-group charity-search-container">
        <?php if ($challenge): ?>
            <ul class="challenge_charity_chosen bet_charity_chosen" style="">
                <li class="friend-challenge-search-result-li"
                    data-charity-score="<?php echo round($challenge->getCharity()->getOverallScore()); ?>"
                    data-charity-tagline="<?php echo htmlspecialchars($challenge->getCharity()->getMissionSummary()); ?>"
                    data-charity-id="<?php echo $challenge->getCharity()->getId(); ?>">
                    <a class="select-charity" href="#" title="<?php echo htmlspecialchars($challenge->getCharity()->getName()); ?>">
                        <?php echo htmlspecialchars($challenge->getCharity()->getName()); ?><br>
                        <span class="desc"><?php echo htmlspecialchars($challenge->getCharity()->getSearchDesc()); ?></span>
                        <button type="button" class="btn btn-xs btn-danger btn-clear-charity">x</button>
                    </a>
                </li>
            </ul>
            <input style="display:none;" type="text" class="form-control challenge_charity" name="charity" placeholder="Start typing a non-profit's name: e.g. An..." value="" tabindex="4">
        <?php else: ?>
            <ul class="challenge_charity_chosen bet_charity_chosen" style="display: none;"></ul>
            <input type="text" class="form-control challenge_charity" name="charity" placeholder="Start typing a non-profit's name: e.g. An..." value="" tabindex="4">
        <?php endif; ?>

        <ul class="challenge_charity_results bet_charity_results" style="display: none;"></ul>
    </div>

    <p class="lead txtCntr">Step 5: Add a video of you performing the CHALLENGE by entering the URL below (youtube)</p>

    <div <?php if ($challenge): ?>data-youtube-video-id="<?php echo htmlspecialchars($challenge->getYoutubeVideoId()); ?>"<?php endif; ?>
         class="youtube-preview-container">
        <?php if ($challenge): ?>
            <iframe class="youtube-player youtube-preview-iframe"
                    type="text/html"
                    width="100%"
                    height=""
                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($challenge->getYoutubeVideoId()); ?>?controls=2"
                    allowfullscreen
                    frameborder="0"></iframe>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <input type="text"
               class="form-control challenge_video"
               name="video"
               placeholder="http://"
               <?php if ($challenge): ?>value="http://youtube.com/watch?v=<?php echo htmlspecialchars($challenge->getYoutubeVideoId()); ?>"<?php endif; ?>
               tabindex="5">
    </div>

    <div class="form-group">
        <label class="dedicate-challenge-label">
            <input type="checkbox"
                   <?php if ($challenge && $challenge->getDedication()): ?>checked="checked"<?php endif; ?>
                   class="form-control challenge_dedication_checkbox"
                   name="challenge_dedication_checkbox"> Dedicate this CHALLENGE</label>
        <input type="text"
               class="<?php if (!$challenge || !$challenge->getDedication()): ?>hide<?php endif; ?> form-control challenge_dedication"
               name="dedication"
               placeholder="Type your message here, e.g. &quot;In honor of Robin Williams&quot;"
               value="<?php if ($challenge && $challenge->getDedication()): ?><?php echo htmlspecialchars($challenge->getDedication()); ?><?php endif; ?>">
    </div>

    <footer>
        <button type="button"
                class="btn btn-primary btn-challenge-publish"
                tabindex="7"
                data-loading-text="PUBLISH">PUBLISH</button>
        <button type="button"
                tabindex="6"
                data-loading-text="Save as draft"
                class="btn btn-default btn-challenge-save-draft">Save as draft</button>
    </footer>
</form>