<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();
/** @var \Entity\User[] $users */
/** @var \Entity\FacebookFriend[] $fb_friends */
?>
<?php if (!$users && !$fb_friends): ?>
    No users matching query..
<?php else: ?>
    <?php foreach($fb_friends as $fb_friend): ?>
        <?php $CI->load->view('bets/_bet_friends_friends_fb_friend', ['fb_friend' => $fb_friend]); ?>
    <?php endforeach; ?>
    <?php foreach($users as $user): ?>
        <?php $CI->load->view('bets/_bet_friends_friends_friend', ['user' => $user]); ?>
    <?php endforeach; ?>
<?php endif; ?>