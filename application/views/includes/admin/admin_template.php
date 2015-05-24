<?php
/** @var string $main_content */
/** @var \Base_Controller $CI */
$CI =& get_instance();
?><!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Giverhub Admin - <?php echo htmlspecialchars($CI->htmlTitle); ?></title>
        <link rel="stylesheet" href="/admin/css/foundation.css" />
        <script src="/admin/js/vendor/modernizr.js"></script>
        <style>
            .block { display: block !important; }
        </style>
    </head>
    <body>
        <nav style="padding: 0 10%; margin-bottom: 20px;" class="top-bar" data-topbar>
            <ul class="title-area">
                <li class="name">
                    <h1><a href="/admin/index">Giverhub Admin</a></h1>
                </li>
                <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
            </ul>

            <section class="top-bar-section">

                <ul class="left">
                    <li><a href="/admin/view_users/">Users</a></li>
                    <li class="has-dropdown">
                        <a href="#">Nonprofits</a>
                        <ul class="dropdown">
                            <li><a href="/admin/add_nonprofit/">Add Nonprofit</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/faqs/">FAQs</a></li>
                    <li><a href="/admin/closed_beta/">Closed Beta</a></li>
                    <li><a href="/admin/profanities/">Profanities</a></li>
                    <li><a href="/admin/causes_keywords/">Causes & Keywords</a></li>
                    <li class="has-dropdown">
                        <a href="#">Stats & Info</a>
                        <ul class="dropdown">
                            <li><a href="/admin/last_online/">Last Online</a></li>
                            <li><a href="/admin/users_graph/">Users Graph</a></li>
                            <li><a href="/admin/signed_up_from/">Signed up from url</a></li>
                            <li><a href="/admin/site_speed/">Site Speed</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/task_queue/">Task Queue</a></li>
                    <li class="has-dropdown">
                        <a href="#">Charity Admins</a>
                        <ul class="dropdown">
                            <li><a href="/admin/charity_admin_requests/">Charity Admin Requests</a></li>
                            <li><a href="/admin/charity_admins/">Charity Admins</a></li>
                            <li><a href="/admin/charity_admin_data/">Charity Admin Data</a></li>
                        </ul>
                    </li>
                    <li><a href="/admin/petition_signature_removal_requests/">Removal Requests</a></li>
                    <li><a href="/admin/givercoin/">GiverCoin</a></li>
                </ul>

                <ul class="right">
                    <li class="has-dropdown">
                        <a href="#"><?php echo $CI->user->getUsername(); ?></a>
                        <ul class="dropdown">
                            <li><a href="/home/logout/">Sign out</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
        </nav>

        <script src="/admin/js/vendor/jquery.js"></script>
        <script src="/admin/js/foundation.min.js"></script>
        <script>
            jQuery(document).ready(function() {

                $.fn.loading = function(loadingText) {
                    this.addClass('disabled');
                    var originalText;
                    var newText = loadingText !== undefined ? loadingText : this.data('loading-text');
                    if (this.prop('tagName') == 'INPUT') {
                        originalText = this.attr('value');
                        this.attr('value', newText);
                    } else {
                        originalText = this.html();
                        this.html(newText);
                    }

                    this.data('original-text', originalText);
                };

                $.fn.reset = function() {
                    if (this.prop('tagName') == 'INPUT') {
                        this.attr('value', this.data('original-text'));
                    } else {
                        this.html(this.data('original-text'));
                    }
                    this.removeClass('disabled');
                };

                window.adminError = function(options) {
                    if (options === undefined) {
                        options = {};
                    }

                    if (options.msg !== undefined) {
                        options.message = options.msg;
                    }

                    var $error_modal = jQuery('#error_modal');
                    var $subject = $error_modal.find('.subject');
                    var $message = $error_modal.find('.message');
                    var $exception = $error_modal.find('.exception');

                    $subject.html(options.subject === undefined ? $subject.data('default-subject') : options.subject);
                    $message.html(options.message === undefined ? $message.data('default-message') : options.message);
                    $exception.html(options.e === undefined ? '' : options.e);
                    if (options.e) {
                        console.dir(options.e);
                    }
                    $error_modal.foundation('reveal', 'open');
                };

                window.adminSuccess = function(options) {
                    if (options === undefined) {
                        options = {};
                    }

                    if (options.msg !== undefined) {
                        options.message = options.msg;
                    }

                    var $success_modal = jQuery('#success_modal');
                    var $subject = $success_modal.find('.subject');
                    var $message = $success_modal.find('.message');

                    $subject.html(options.subject === undefined ? $subject.data('default-subject') : options.subject);
                    $message.html(options.message === undefined ? $message.data('default-message') : options.message);

                    $success_modal.foundation('reveal', 'open');
                };
            });
        </script>

        <div id="error_modal" class="reveal-modal" data-reveal>
            <h2>Error!</h2>
            <p class="lead subject" data-default-subject="Unexpected error!"></p>
            <p class="message" data-default-message="There was an unexpected error."></p>
            <p class="exception"></p>
            <a class="close-reveal-modal">&#215;</a>
        </div>

        <div id="success_modal" class="reveal-modal" data-reveal>
            <h2>Success!</h2>
            <p class="lead subject" data-default-subject=""></p>
            <p class="message" data-default-message=""></p>
            <a class="close-reveal-modal">&#215;</a>
        </div>

        <?php $this->load->view('admin/'.$main_content); ?>

        <script>jQuery(document).foundation();</script>
    </body>
</html>