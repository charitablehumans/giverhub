<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Retrieving password email config
$config['from'] = 'admin@giverhub.com';
$config['companyname'] = 'GiverHub';
$config['retriev_subject'] = 'Request to retrieve password.';
$config['retriev_body'] = '
<head>
</head>
<html>
<body>
<div style="margin:100px auto; color:black; width:600px; word-break:break-all;">
Hey there [name],<br>
<strong>Login User Name:</strong>[username]<br>
This email sent to you because we received a request to reset your password.<br>
If you are the one who requested password reset please click the link bellow:<br>
[link]<br>
Note: the above link will be available for the next 48 hours.<br><br>

Or copy the following to your navigation bar:<br>
[Text]<br>
If you are not the one who initiated this request please ignore this email.<br><br>


Best Regards<br>
[company name]<br>
</div>
</body>
</html>
';


// New user email
$config['register_subject'] = 'Welcome to [company name] Please confirm your registration.';
$config['register_body'] = '
<head>
</head>
<html>
<body>
<div style="margin:100px auto; color:black; width:600px; word-break:break-all;">
Thanks for signing up with GiverHub!<br><br>
Click the following link to complete the registration process:<br>
[link]<br><br>
The above link will be available for the next 2 weeks.<br><br>

Or copy the following to your navigation bar:<br>
[Text]<br><br>
Thanks for joining, and welcome to the "do good" network!
</div>
</body>
</html>
';


// Givercard receiver email
$config['givercard_receiver_subject'] = 'You have received a gift from [user_x]!';
$config['givercard_receiver_email_body'] = '
<head>
</head>
<html>
<body>
<div style="margin:100px auto; color:black; width:600px;">
<div style="width:100%">[givercard_mail_header]</div><br>
Hi [user_y], [user_x] has given you a GiverCard worth $[givercard_amount]! A GiverCard is a type of e-gift card that enables you to donate to any of the [num_of_nonprofits] nonprofits in GiverHub’s database, which is nearly EVERY nonprofit in the US! In other words, instead of donating to a nonprofit on your behalf, <b>[user_x_fname] is letting YOU choose which nonprofit, or nonprofits, you want to donate to with HIS/HER money!</b><br><br>

Before you start donating, please read this brief message from [user_x_fname]:<br>
<p style="text-indent:20px;"><b>[givercard_message]</b></p><br><br>

<b>To start donating</b> click the following link: [view_givercard_link].<br><br>

<b>What is GiverHub?</b><br>
<p style="text-indent:20px;"><b>GiverHub is the easiest and fastest way to discover, learn about, and donate to ANY nonprofit, and it automatically itemizes all of your past and recurring donations for you in a donation history.</b></p>
<p style="text-indent:20px;">But GiverHub is also much more than that. It also enables you to search for change.org petitions that suit your interests and sign them instantly, all without ever leaving GiverHub! You can create Challenges, or send GiverCards like this one. It\'s a centralized hub for all your online giving. Where you go to do good! But it’s also a social network for givers that enables them to communicate, collaborate, and compete with (just for fun of course ;) ) like-minded users to do more good. And this is all just the beginning for GiverHub. We’ve got so many more features in the pipeline that aren’t just going to change the way you give back, they’re going to change the way you THINK about giving back. Thank you for being a Giver!</p>
<br><br>
Andrew Levine<br>
Founder of GiverHub<br>
</div>
</body>
</html>
';

$config['giving_pot_recipient_email_body'] = '
<head>
</head>
<html>
<body>
<div style="margin:100px auto; color:black; width:600px;">
<div style="width:100%">[givercard_mail_header]</div><br>
Hi [user_y], [user_x] has given you a GiverCard worth $[givercard_amount] for participating in the [name_or_logo] Giving Pot. A GiverCard is a type of e-gift card that enables you to donate to any of the [num_of_nonprofits] nonprofits in GiverHub’s database, which is nearly EVERY nonprofit in the US! In other words, instead of donating to a nonprofit on your behalf, <b>[user_x_fname] is letting YOU choose which nonprofit, or nonprofits, you want to donate to with HIS/HER money!</b><br><br>

Before you start donating, please read this brief message from [user_x_fname]:<br>
<p style="text-indent:20px;"><b>[givercard_message]</b></p><br><br>

<b>To start donating</b> click the following link: [view_givercard_link].<br><br>

<b>What is GiverHub?</b><br>
<p style="text-indent:20px;"><b>GiverHub is the easiest and fastest way to discover, learn about, and donate to ANY nonprofit, and it automatically itemizes all of your past and recurring donations for you in a donation history.</b></p>
<p style="text-indent:20px;">But GiverHub is also much more than that. It also enables you to search for change.org petitions that suit your interests and sign them instantly, all without ever leaving GiverHub! You can create Challenges, or send GiverCards like this one. It\'s a centralized hub for all your online giving. Where you go to do good! But it’s also a social network for givers that enables them to communicate, collaborate, and compete with (just for fun of course ;) ) like-minded users to do more good. And this is all just the beginning for GiverHub. We’ve got so many more features in the pipeline that aren’t just going to change the way you give back, they’re going to change the way you THINK about giving back. Thank you for being a Giver!</p>
<br><br>
Andrew Levine<br>
Founder of GiverHub<br>
</div>
</body>
</html>
';



// CHALLENGE receiver email
$config['challenge_receiver_subject'] = 'You have been CHALLENGED on GiverHub.com';
$config['challenge_receiver_body'] = '
<head>
</head>
<html>
<body>
<div style="margin:100px auto; color:black; width:600px;">
Hi [receiver_name]!<br/>
[from_name] has CHALLENGED you on GiverHub.com!
Go check out the challenge here: [challenge_link]<br/><br/>
Trouble clicking the above link? try to copy/paste the following url into your browser:<br/>
[challenge_url]
</div>
</body>
</html>
';
