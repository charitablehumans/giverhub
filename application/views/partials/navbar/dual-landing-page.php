<header class="navbar gh_header" role="banner">
    <div class="container">
        <a class="m-menu m-menu-lines" href="#"><i class="icon-align-justify icon-reorder icon-large"></i></a>
        <div class="gh_logo pull-left">
            <a class="use-in-mobile-menu" href="/">
                <img src="/img/logo.png" alt="GiverHub, Inc." />
            </a>
        </div>
        <?php $this->load->view('/partials/navbar/search-form'); ?>
        <button class="log-in gh-trigger-event pull-left"
                data-event-category="button"
                data-event-action="click"
                data-event-label="Log in (dual-navbar)"
                data-target="#sign-in-modal-2"
                data-toggle="modal" >Log In</button>

        <button class="sign-up gh-trigger-event pull-left"
                data-event-category="button"
                data-event-action="click"
                data-event-label="sign up (dual-navbar)"
                data-target="#sign-up-modal-2"
                data-toggle="modal" >Sign Up</button>
        <a class="gh-trigger-event use-in-mobile-menu hidden-outside-mobile-menu"
           data-mobile-menu-label="NAV"
           data-event-category="button"
           data-event-action="click"
           data-event-label="sign in (navbar)"
           data-toggle="modal"
           data-target="#sign-in-modal-2"
           href="#">Sign In</a>
        <a
            class="gh-trigger-event use-in-mobile-menu hidden-outside-mobile-menu"
            data-mobile-menu-label="NAV"
            data-event-category="button"
            data-event-action="click"
            data-event-label="join (navbar)"
            data-target="#sign-up-modal-2"
            data-toggle="modal"
            href="#">Join</a>
    </div>
</header>