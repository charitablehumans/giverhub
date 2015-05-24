<?php
/** @var \Entity\ChangeOrgPetition $petition */
/** @var integer $current_page */

/** @var \Petitions $CI */
$CI =& get_instance();
$user = \Base_Controller::$staticUser;
?>

<?php $this->load->view('/petitions/_petition_header', array('petition' => $petition)); ?>

<main class="petition_main change-org-petition-main" role="main">
    <section class="container">
        <div class="row">
            <?php $this->load->view('/petitions/_petition_new_nav'); ?>
            
            <div class="col-md-6 col-sm-5">
                <?php $this->load->view('/petitions/_sign-block', ['petition' => $petition]); ?>
                <?php
                    $itemsPerPage = 20;
                    $count = $petition->getNewsCount();
                    $pages = ceil($count / $itemsPerPage);
                ?>
                <?php if ($count): ?>

                    <?php if ($pages > 1): ?>
                        <?php $CI->load->view('petitions/_petition_pager', array(
                                                                      'pages' => $pages,
                                                                      'current_page' => $current_page,
                                                                      'petition' => $petition,
                                                                      'tabName' => 'news'
                                                                 )); ?>
                    <?php endif; ?>

                    <?php foreach($petition->getNewsUpdates($current_page, $itemsPerPage) as $update): ?>
                        <div class="block petition-news-block">
                            <header>
                                <?php if (!$update->getContent() && is_numeric($update->getTitle())): ?>
                                    Reached <?php echo $update->getTitle(); ?> signatures.
                                <?php else: ?>
                                    <?php echo htmlspecialchars(strip_tags($update->getTitle())); ?>
                                <?php endif; ?>
                            </header>
                            <p class="content">
                                <?php if(filter_var($update->getContent(), FILTER_VALIDATE_URL)): ?>
                                    <a href="<?php echo $update->getContent(); ?>" target="_blank"><?php echo $update->getContent(); ?></a>
                                <?php elseif (!$update->getContent() && is_numeric($update->getTitle())): ?>
                                    Reached <?php echo $update->getTitle(); ?> signatures.
                                <?php else: ?>
                                    <?php echo $update->getContent() ? htmlspecialchars(strip_tags($update->getContent())) : '-'; ?>
                                <?php endif; ?>
                            </p>
                            <p class="by">- <?php echo htmlspecialchars($update->getAuthorName()); ?> - <?php echo $update->getCreatedOnDt()->format('m/d/Y'); ?></p>
                            <a class="link" target="_blank" href="<?php echo $update->getAuthorUrl(); ?>">Visit <?php echo htmlspecialchars($update->getAuthorName()); ?> on change.org</a>                        </div>
                    <?php endforeach; ?>
                    <div class="block petition_block">

                        <div class="table-responsive">
                            <table class="table table-hover petition-news-table">
                                <thead>
                                    <tr class="bold">
                                        <th class="title col-md-2">Title</th>
                                        <th class="news col-md-4">News</th>
                                        <th class="dated col-md-1">Date</th>
                                        <th class="author col-md-1">Author</th>
                                        <th class="author-url col-md-3">Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($petition->getNewsUpdates($current_page, $itemsPerPage) as $update): ?>
                                    <tr>
                                        <td class="title"><?php echo htmlspecialchars(strip_tags($update->getTitle())); ?></td>
                                        <td class="news change-org-petition-news-url">
                                            <?php if(filter_var($update->getContent(), FILTER_VALIDATE_URL)): ?>
                                                <a href="<?php echo $update->getContent(); ?>" target="_blank"><?php echo $update->getContent(); ?></a>
                                            <?php elseif (!$update->getContent() && is_numeric($update->getTitle())): ?>
                                                Reached <?php echo $update->getTitle(); ?> signatures.
                                            <?php else: ?>
                                                <?php echo $update->getContent() ? htmlspecialchars(strip_tags($update->getContent())) : '-'; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="dated"><?php echo $update->getCreatedOnDt()->format('m/d/Y'); ?></td>
                                        <td class="author"><?php echo htmlspecialchars($update->getAuthorName()); ?></td>
                                        <td class="author-url"><a target="_blank" href="<?php echo $update->getAuthorUrl(); ?>">Visit <?php echo htmlspecialchars($update->getAuthorName()); ?> on change.org</a></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div><!-- table-responsive end -->

                    </div><!-- block end -->

                    <?php if ($pages > 1): ?>
                        <?php $CI->load->view('petitions/_petition_pager', array(
                                                                      'pages' => $pages,
                                                                      'current_page' => $current_page,
                                                                      'petition' => $petition,
                                                                      'tabName' => 'news'
                                                                 )); ?>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="block user_info">
                        Sorry, No News Yet!
                    </div><!-- block end -->
                <?php endif; ?>
            </div>
            <div class="col-md-4 col-sm-4">
                <?php $this->load->view('/partials/trending-petitions'); ?>
            </div>
        </div>
        <!-- row end -->
    </section>
    <!-- container end -->
</main>


<?php if ($CI->user) { $CI->load->view('partials/_address-modal'); }