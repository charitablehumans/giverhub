<?php
/** @var \Entity\Petition $petition */
/** @var \Giverhub_Petition $CI */
$CI =& get_instance();
?>

<?php $this->load->view('/giverhub-petitions/_header', array('petition' => $petition)); ?>

<main class="petition_main" role="main" id="main_content_area">

    <section class="container">
        <div class="row">
			<div class="hide-on-sm-md-resolution">
            <?php $this->load->view('/giverhub-petitions/_nav', array('petition' => $petition)); ?>
			</div>

            <div class="col-lg-10 col-md-12 col-sm-12">
				<div class="g-petition-information-edit col-md-6">
					<div class="block">
                        <div id="" class="blk gh_spacer_7">
                            <div id="petition_show_normal">

                                <div class="row gh_spacer_14">
							    	<div>Who is the target of your petition?</div>
									<div><input class="form-control target" id="target" type="text" placeholder="e.g. a politician, a corporation, a group, etc" value="<?php echo $petition->getTargetText();?>"></div>
									<div class="alert alert-danger" role="alert">
						                <span class="message"><strong>Oops!</strong> Something unexpected went wrong!</span>
						            </div>
  								</div>

								<div class="row gh_spacer_14">
							   		<div>What should they do?</div>
							   		<div><input class="form-control what" type="text" placeholder="How should they fix the problem?" value="<?php echo $petition->getWhatText();?>"></div>
									<div class="alert alert-danger" role="alert">
						                <span class="message"><strong>Oops!</strong> Something unexpected went wrong!</span>
						            </div>
							  	</div>

								<div class="row gh_spacer_21">
							   		<div>Why does this matter?</div>
							   		<div>
										<textarea class="form-control why-text-edit why" placeholder="Tell the world why they should sign your petition. Why does this matter? Why is it important? What moved you to create this petition?"><?php echo $petition->getWhyText();?></textarea>
									</div>
									<div class="alert alert-danger" role="alert">
						                <span class="message"><strong>Oops!</strong> Something unexpected went wrong!</span>
						            </div>
							  	</div>
								<input type="hidden" id="store-petition-id" name="" value="<?php echo $petition->getId();?>">

								<button type="button" class="btn btn-success btn-update-g-petition" data-loading-text="Update">Update</button>

                            </div>
                            <div id="petition_show_more" style="display: none"></div>
                        </div>
                    </div>
				</div>

                <div class="col-md-6 g-petition-information">
                    <div class="block">
                        <?php if ($this->user == $petition->getUser()) : ?>
                            <div class="g-petition-content-edit-icon"><img alt="Edit Petition" src="/images/icon-edit-petition2.gif"></div>
                        <?php endif;?>

                        <div id="petition-overview-container" class="blk gh_spacer_7">
                            <div id="petition_show_normal">
                                <?php
                                $petitionOverview = Common::truncate($petition->getWhyText(),"350"," (...)");
                                echo $petitionOverview;
                                ?>
                            </div>
                            <div id="petition_show_more" style="display: none"><?php echo htmlspecialchars($petition->getWhyText()); ?></div>
                        </div>

                        <?php if( strlen($petitionOverview) < strlen($petition->getWhyText()) ): ?>
                            <p><a id="expand-petition-overview" href="#">View More</a></p>
                        <?php endif; ?>
                    </div>
                    <!-- block end -->

					<!-- Code to display GiverHub petition news -->
					<div class="col-md-12 clearfix">
                        <h3 class="news-h3">News</h3>
                        <?php if ($this->user == $petition->getUser()) : ?>
                            <img alt="Add petition news" class="add_petition_news" src="/images/icon-edit-petition2.gif">
                        <?php endif; ?>
					</div>

					<?php $news = $petition->getNews(); ?>
                    <?php if (!$news): ?>
                        <div class="block">
                            <div id="petition-overview-container" class="blk gh_spacer_7">
                                <div id="petition_show_normal">
                                    News will be displayed here.
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($news as $petitionNews): ?>
                            <div class="block">
                                <div id="petition-overview-container" class="blk gh_spacer_7">
                                    <div id="petition_show_normal">
                                        <?php echo htmlspecialchars($petitionNews->getContent()); ?>
                                    </div>
                                    <div class="pull-right"><?php echo date('m-d-Y',strtotime($petitionNews->getCreatedOn()));?></div>
                                </div>
                            </div>
                            <?php if ($petitionNews != end($news)): ?>
                                <div class="vertical-separator"><hr></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
				<input type="hidden" name="g-petition-input-id" id="g-petition-input-id" value="<?php echo $petition->getId(); ?>">

                <div class="col-md-6">
                    <div class="block g-petition-sign-block">
                        <?php $this->load->view('/giverhub-petitions/_sign-block', ['petition' => $petition]); ?>
                    </div>
                    <!-- block end -->

                    <div class="block">
                        <h3 class="gh_block_title gh_spacer_21">Recent Signatures</h3>
						<div class="table-responsive">
                            <?php $this->load->view('/giverhub-petitions/_giverhub_petition_recent_signatures_table', array('giverhubPetition' => $petition)); ?>
                        </div>
                    </div>
                    <!-- block end -->
                </div>

            </div>

        </div>
    <!-- row end -->
    </section>
<!-- container end -->
</main>
<?php if ($CI->user) { $CI->load->view('partials/_address-modal'); }
