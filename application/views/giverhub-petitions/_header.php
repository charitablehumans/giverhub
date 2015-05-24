<?php
/** @var \Entity\Petition $petition */
/** @var \Petitions $CI */
$CI =& get_instance();
?>
<section class="gh_secondary_header clearfix petition_page_gh_secondary_header">

    <span class="trama"></span>
    <span class="lftgrad"></span>
    <span class="rgtgrad"></span>

    <section class="container org_header petition-header">
        <div class="row">

            <div class="col-md-8">
                <div class="gh_spacer_21 petition-header-sub-container spacing-xs-resolutions">
                    <span class="org_slogan petition-targets" title="<?php echo htmlspecialchars($petition->getTargetText()); ?>" data-placement="bottom">Petitioning <?php echo htmlspecialchars($petition->getTargetText()); ?></span>
                    <h2 class="org_name petition-title"><?php echo htmlspecialchars($petition->getTitle()); ?></h2>
                    <?php if ($petition->hasVideo()): ?>
                        <?php echo $petition->getVideoHtml(); ?>
                    <?php elseif ($petition->hasImage()): ?>
                        <?php echo $petition->getImageHtml(); ?>
                    <?php endif; ?>
                    <?php if ($this->user == $petition->getUser()) : ?>
                        <div class="g-petition-image-edit-icon"><button type="button" class="btn btn-success btn-xs">Edit Media</button></div>
                    <?php endif;?>

                    <span class="org_slogan petition-by">
                        Petition by <?php echo $petition->getUser()->getLink(); ?>
                    </span>
                </div>
            </div>


            <div class="col-md-4">

                <address>
					<table class="g-petition-header-info-table">
						<tbody>
							<tr>
								<td class="g-petition-header-info-td">Created</td><td><?php echo $petition->getCreatedDate()->format('m/d/y');?></td>
							</tr>
							<tr>
								<td class="g-petition-header-info-td">Goal</td>
								<td>
                                    <span class="g-petition-goal-wrapper">
                                        <?php $this->load->view('/giverhub-petitions/_goal', ['petition' => $petition]); ?>
                                    </span>
                                <?php if ($this->user == $petition->getUser()): ?>
                                    <button type="button" class="btn btn-danger btn-sm add_goal_header_link">Edit Goal</button>
                                <?php endif; ?>
								</td>
							</tr>
							<tr>
								<td class="g-petition-header-info-td">Deadline</td>
								<td>
                                    <span class="g-petition-deadline-wrapper">
                                        <?php $this->load->view('/giverhub-petitions/_deadline', ['petition' => $petition]); ?>
                                    </span>
                                    <?php if ($this->user == $petition->getUser()): ?>
                                        <button type="button" class="btn btn-danger btn-sm add_deadline_header_link">Edit Deadline</button>
                                    <?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>
                </address>
				<input type="hidden" name="g-petition-input-id" id="g-petition-input-id" value="<?php echo $petition->getId(); ?>">
                <div class="col-md-6">

                </div>

                <div class="col-md-6">

                </div>
            </div>

        </div>
        <!-- row end -->
    </section>
    <!-- org_header end -->
</section><!-- gh_secondary_header end -->
<?php $CI->load->view('giverhub-petitions/_modals', ['user' => $this->user,'petition' =>$petition]); ?>

