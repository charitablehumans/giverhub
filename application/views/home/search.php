<?php
/** @var \Base_Controller $CI */
$CI =& get_instance();

/** @var string $current_text */
/** @var array $views */
?>
<section class="gh_secondary_header clearfix search-secondary-header">
    <span class="lftgrad"></span>
    <span class="rgtgrad"></span>
    <section class="container slideshow_content">

        <div class="row clearbothnow row-spacing-xs-resolutions first-row">
            <h3 class="startpage-h3 vegur_regular">Instantly Donate and Automatically Itemize</h3>
            <h3 class="startpage-h3 vegur_light">GiverHub is the easiest way to donate, and keep track of your donations, period</h3>

            <?php if (!$CI->user): ?>
                <div class="col-md-6 gh_spacer_28" id="startpage-join-btn-wrapper">
                    <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#sign-up-modal-2" href="#">JOIN</a>
                </div>
            <?php endif; ?>
            <?php if (!$CI->user): ?>
                <div class="col-md-6 gh_spacer_28" id="startpage-signin-btn-wrapper">
                    <a class="btn btn-primary pull-left" data-toggle="modal" data-target="#sign-in-modal-2" href="#">SIGN IN</a>
                </div>
            <?php endif; ?>

        </div>
        <div class="row col-md-offset-1 second-row">
            <form class="form-inline col-md-12 clearfix search-box-responsive" role="form" method="post">
                <div class="col-md-7 form-group donation_amount">
                    <input name="search_text" type="search" class="gh_general_search_field form-control input-lg" id="search"
                           placeholder="Search by keyword or name..." value="<?php echo @htmlspecialchars(@$current_text); ?>">
                </div>

                <div class="col-md-3 form-group">
                    <label class="sr-only" for="zip_code">Zip Code</label>
                    <input type="text" class="form-control input-lg" id="zip_code" placeholder="Zip code" name="search_zip" value="<?php echo htmlspecialchars(@$_POST['search_zip']); ?>">
                </div>

                <div class="col-md-1 form-group">
                    <button type="submit" class="btn btn-success rounded"><span><i class="icon-search"></i></span></button>
                </div>
            </form>
        </div>
    </section>
</section>


<main class="no-bottom search-main" role="main" id="main_content_area">
    <section class="filter clearfix display-none" id="home-filter-menu">
        <section class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-5 gh_spacer_7">
                    <span class="title">View:</span>

                    <ul id="views-nav-ul" class="nav nav-pills">
                    <?php foreach($views as $view): ?>
                        <?php if ($selected_view != ""): ?>
                            <li <?php echo $view['id'] == $selected_view ? 'class="active"' : '' ?>>
                                <a class="view_tab" data-tabs-target="#view-tabs-<?php echo $view['id']; ?>" href="#view_<?php echo $view['id']; ?>" data-toggle="tab"><?php echo htmlspecialchars($view['name']); ?></a>
                            </li>
                        <?php else: ?>
                            <li <?php echo $view['id'] == 'mixed' ? 'class="active"' : '' ?>>
                                <a class="view_tab" data-tabs-target="#view-tabs-<?php echo $view['id']; ?>" href="#view_<?php echo $view['id']; ?>" data-toggle="tab"><?php echo htmlspecialchars($view['name']); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-7">
                    <div id="sort_by_small_screen">
                        <span class="title">Sort by:</span>
                    </div>

                    <?php foreach($views as $view): ?>
                        <?php if ($selected_view != ""): ?>
                            <ul id="view-tabs-<?php echo $view['id']; ?>" class="<?php if ($view['id'] != $selected_view): ?>hide<?php endif; ?> nav nav-pills tabs-nav-ul">
                        <?php else: ?>
                            <ul id="view-tabs-<?php echo $view['id']; ?>" class="<?php if ($view['id'] != 'mixed'): ?>hide<?php endif; ?> nav nav-pills tabs-nav-ul">
                        <?php endif; ?>

                                <?php foreach ($view['tabs'] as $nr => $tab): ?>
                                    <li <?php echo $tab['id'] == 'relevance' ? 'class="active"' : '' ?>>
                                        <a
                                            class="page_tab show-more"
                                            href="#<?php echo $view['id'].$tab['id']; ?>"
                                            data-toggle="tab"
                                            data-search-text="<?php echo htmlspecialchars(@$current_text); ?>"
                                            data-search-zip="<?php echo htmlspecialchars(@$_POST['search_zip']); ?>"
                                            data-offset="0"
                                            data-search-type="<?php echo $view['id']; ?>"
                                            data-tab="<?php echo $tab['id']; ?>"
                                            data-target-tab="#tab-<?php echo $view['id'].$tab['id']; ?>"
                                            ><?php echo htmlspecialchars($tab['name']); ?></a>
                                    </li>
                                <?php endforeach; ?>

                                <?php if (isset($view['customTabs']) && $view['customTabs']): ?>
                                    <li class="dropdown">
                                        <a href="#" class="page_tab custom-drop dropdown-toggle" data-toggle="dropdown">Custom</a>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($view['customTabs'] as $tab): ?>
                                                <li>
                                                    <a
                                                        class="page_tab custom_page_tab show-more"
                                                        href="#<?php echo $view['id'].$tab['id']; ?>"
                                                        data-toggle="tab"
                                                        data-search-text="<?php echo htmlspecialchars(@$_POST['search_text']); ?>"
                                                        data-search-zip="<?php echo htmlspecialchars(@$_POST['search_zip']); ?>"
                                                        data-offset="0"
                                                        data-search-type="<?php echo $view['id']; ?>"
                                                        data-tab="<?php echo $tab['id']; ?>"
                                                        data-target-tab="#tab-<?php echo $view['id'].$tab['id']; ?>"
                                                        ><?php echo htmlspecialchars($tab['name']); ?></a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                        </ul>
                    <?php endforeach; ?>
                    <div id="sort_by_big_screen">
                        <span id="sort-by-span" class="title">Sort by:</span>
                    </div>

                </div>
            </div>
        </section>
    </section>

    <?php if (!@$current_text && !@$_POST['search_zip']): ?>
        <?php
            $featuredCharities = \Entity\Charity::getFeaturedCharities();
            $featuredPetitions = \Entity\ChangeOrgPetition::getFeaturedPetitions();
            $featured = [];
            for ($x = 0; $x < 4; $x++) {
                if (($x == 0 || $x == 2 || !count($featuredPetitions)) && count($featuredCharities)) {
                    $featured[] = array_pop($featuredCharities);
                } elseif (count($featuredPetitions)) {
                    $featured[] = array_pop($featuredPetitions);
                }
            }
        ?>
        <section class="container clearfix featured-container">
            <div class="row">
                <h3 class="txtCntr">Featured Charities and Petitions</h3>
                <?php foreach($featured as $item): ?>
                    <div class="featured-charity-item col-md-3 col-sm-6">
                        <?php if ($item instanceof \Entity\Charity): ?>
                            <?php $this->load->view('/nonprofits/charity-item', array('charity' => $item)); ?>
                        <?php elseif ($item instanceof \Entity\ChangeOrgPetition): ?>
                            <?php $itemHtml = $this->load->view('/petitions/petition-item', array('petition' => $item)); ?>
                        <?php else: ?>
                            <?php throw new Exception('Unknown item type. ' . get_class($item)); ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <span class="gh_lightbox_separator"></span>
        </section>
    <?php endif; ?>

    <div class="tab-content">
        <input type="hidden" name="selected-tab" id="selected-tab" value="<?php echo $selected_view; ?>">
        <?php foreach($views as $view): ?>
            <?php if ($selected_view != ""): ?>
                <div class="search-res-tab-pane tab-pane <?php echo $view['id'] == $selected_view ? 'active' : '' ?>" id="view_<?php echo $view['id']; ?>">
            <?php else: ?>
                <div class="search-res-tab-pane tab-pane <?php echo $view['id'] == 'mixed' ? 'active' : '' ?>" id="view_<?php echo $view['id']; ?>">
            <?php endif; ?>

                <div class="tab-content">
                    <?php foreach (array_merge($view['tabs'], $view['customTabs']) as $nr => $tab): ?>
                        <div class="search-res-tab-pane tab-pane <?php echo $tab['id'] == 'relevance' ? 'active' : '' ?>" id="<?php echo $view['id'].$tab['id']; ?>">
                            <section id="tab-<?php echo $view['id'].$tab['id']; ?>" class="container clearfix">
                                <?php $this->load->view('/home/search-items', array('items' => $tab['items'])); ?>
                                <?php if (!$view['result_count']): ?>
                                    <div class="failed_search">Your search for
                                        <?php if (@$_POST['search_text']): ?><i><?php echo htmlspecialchars($_POST['search_text']); ?></i><?php endif; ?>
                                        <?php echo htmlspecialchars((@$_POST['search_zip'] ? ' in zipcode ' . @$_POST['search_zip'] : '')); ?> did not match any documents.<br/>
                                        Please try with different search terms.<br/><br/>

                                        If what you are looking for is missing, please let us know by clicking the <a href="#"
                                                                                                                          data-placement="top"
                                                                                                                          title="Leave us feedback! We're eager to hear what you like about GiverHub and what we can do to make it better."
                                                                                                                          class="btn-feedback">feedback</a> button or dropping an email to <a href="mailto:admin@giverhub.com">admin@giverhub.com</a></div>
                                <?php endif; ?>
                            </section>
                            <?php if ($view['result_count']): ?>
                                <section class="bottom txtCntr">
                                    <div class="gh_btn_showmore">
                                        <a
                                            href="#"
                                            class="btn cntr show-more"
                                            data-search-text="<?php echo htmlspecialchars(@$current_text); ?>"
                                            data-search-zip="<?php echo htmlspecialchars(@$_POST['search_zip']); ?>"
                                            data-offset="20"
                                            data-search-type="<?php echo $view['id']; ?>"
                                            data-tab="<?php echo $tab['id']; ?>"
                                            data-target-tab="#tab-<?php echo $view['id'].$tab['id']; ?>">Show More</a>
                                    </div>
                                </section>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); }
