<?php
/** @var \Entity\Mission $mission */
/** @var \Base_Controller $CI */
$CI =& get_instance();

$vote_sum = $mission->getVoteSum();
$my_vote = $mission->getMyVote($CI->user);
?>
<span class="vote-sum"><?php echo $vote_sum; ?></span>
<button type="button" data-mission-id="<?php echo $mission->getId(); ?>" class="btn btn-sm btn-mission-vote up <?php echo $my_vote && $my_vote->getVote() > 0 ? 'selected' : ''; ?>"><span class="glyphicon glyphicon-thumbs-up"></span></button>
<button type="button" data-mission-id="<?php echo $mission->getId(); ?>" class="btn btn-sm btn-mission-vote down <?php echo $my_vote && $my_vote->getVote() < 0 ? 'selected' : ''; ?>"><span class="glyphicon glyphicon-thumbs-down"></span></button>