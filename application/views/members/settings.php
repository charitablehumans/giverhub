<?php
    /** @var \Entity\User $user */
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
?>
<?php $this->load->view('/members/_header', array('user' => $user)); ?>

<main class="members_main" role="main" id="main_content_area">

    <section class="container">		
		<div class="row">

            <!-- COL #1 -->

            <?php //$this->load->view('/members/_member_new_nav', array('user' => $user)); ?>

			
			<div class="col-md-12">
                <div class="block">
                    <h3 class="gh_block_title">Personal Info <i class="icon-gh-user pull-right"></i></h3>

                    <div class="gh_block_section overflow_auto">
                        <form action="#" class="gh_personal_info">
                            <div class="col-md-6">
                                <div class="gh_form_block">
                                    <small class="color_light">NAME</small>

                                    <div class="gh_input">
                                        <input type="text" />
                                        <strong><?php echo htmlspecialchars($user->getName()); ?></strong>
                                        <a href="#" class="gh_edit" data-key="name" data-type="info">Edit <i class="icon-edit"></i></a>
                                    </div>
                                </div>

                                <div class="gh_form_block">
                                    <small class="color_light">USERNAME</small>

                                    <div class="gh_input">
                                        <input type="text" />
                                        <strong><?php echo htmlspecialchars($user->getUsername()); ?></strong>
                                        <a href="#" class="gh_edit" data-key="username" data-type="info">Edit <i class="icon-edit"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="gh_form_block">
                                    <small class="color_light">EMAIL</small>

                                    <div class="gh_input">
                                        <input type="text" />
                                        <strong><?php echo htmlspecialchars($user->getEmail()) ?></strong>
                                    </div>
                                </div>

                                <div class="gh_form_block">
                                    <small class="color_light">PASSWORD</small>

                                    <div class="gh_input gh_password">
                                        <input id="new-password" type="password" title="New Password" placeholder="New Password"/>
                                        <input id="confirm-new-password" type="password" title="Confirm New Password" placeholder="Confirm New Password"/>
                                        <strong>
                                            <?php if($user->hasPasswordChanged()): ?>
                                                Modified on <?php echo htmlspecialchars($user->getPasswordChangedDateTime()->format('m/d/Y')); ?>
                                            <?php else: ?>
                                                Never Modified
                                            <?php endif; ?>
                                        </strong>
                                        <a href="#" class="gh_edit_password" data-key="password" data-type="info">Edit <i class="icon-edit"></i></a>
                                    </div>
                                </div>
                            </div>

                        </form><!-- gh_personal_info end -->
                    </div><!-- gh_block_section end -->

                    <div class="gh_block_section overflow_auto">
                        <p class="col-md-6">
                            <span class="color_light">PAYMENT METHOD: </span>
                            <span id="settings-page-payment-method"></span>
                            <a class="btn-change-add-payments-from-settings" href="#">Change/Add</a>
                        </p>
                        <p class="col-md-6">
                            <span class="color_light">FORGOT PASSWORD: </span>
                            <a data-email="<?php echo htmlspecialchars($user->getEmail()); ?>" class="btn-forgot-password-from-settings" href="#">Reset Password</a>
                        </p>
                    </div>
                    <br/>
                    <span class="addresses-header">Address(es)</span>
                    <hr/>
                    <div class="addresses-container settings-addresses" data-address-container-from="settings" id="settings-addresses">
                        <?php $CI->load->view('partials/_addresses'); ?>
                    </div>
                    <hr/>
                    <a class="btn btn-primary btn-add-address-from-settings btn-sm" href="#">Add Address</a>
                </div><!-- block end -->

                <div class="block" id="nonprofit-data">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nonprofit Data</th>
                                <th>Select which fields you would like displayed</th>
                                <th>Display</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach([ ['label' => 'Revenue', 'name' => 'show_revenue'],
                                            ['label' => 'Program Services', 'name' => 'show_program_services'],
                                            ['label' => 'Fundraising Expenses', 'name' => 'show_fundraising_expenses'],
                                            ['label' => 'Executive Compensation', 'name' => 'show_executive_compensation'],
                                            ['label' => 'Advertising and Promotion', 'name' => 'show_advertising_and_promotion'],
                                            ['label' => 'Office Expenses', 'name' => 'show_office_expenses'],
                                            ['label' => 'Information Technology', 'name' => 'show_information_technology'],
                                            ['label' => 'Travel', 'name' => 'show_travel'],
                                            ['label' => 'Conferences, conventions, meetings', 'name' => 'show_conferences_conventions_meetings']] as $row): ?>
                                <tr>
                                    <td colspan="2"><?php echo $row['label']; ?></td>
                                    <td>
                                        <input
                                            name="<?php echo $row['name']; ?>"
                                            <?php echo $CI->user->getSetting($row['name']) ? 'checked=checked' : ''; ?>
                                            type="checkbox">
                                        <span class="hide">saved..</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="block site-settings-block">
                    <h3 class="gh_block_title">Recurring donations</h3>

                    <div class="recurring-donations-container">
                        <center><img alt="Loading" src="/images/ajax-loaders/ajax-loader.gif"></center>
                    </div><!-- gh_block_section end -->
                </div><!-- block end -->


                <div class="block site-settings-block">
                    <h3 class="gh_block_title">Defaults <i class="icon-cog pull-right"></i></h3>

                    <div class="gh_block_section">
                        <div class="clearfix gh_spacer_28 gh_switch_save">
                            <strong class="color_title pull-left">Sign all petitions anonymously</strong>
                            <div class="gh_btn_switch pull-right"
                                 data-type="sign-petitions-anonymously"
                                 data-label-icon="glyphicon glyphicon-align-justify"
                                 data-on-label="icon-ok"
                                 data-checked="<?php if ($user->getSignPetitionsAnonymously()): ?>1<?php else: ?>0<?php endif; ?>"
                                 data-off-label="icon-remove">
                                <input type="checkbox" value="" <?php echo $user->getSignPetitionsAnonymously() ? 'checked="checked"' : ''; ?>/>
                            </div>
                        </div><!-- clearfix end -->
                    </div><!-- gh_block_section end -->
                </div><!-- block end -->

                <div class="block site-settings-block">
                    <h3 class="gh_block_title">Delete Account</h3>

                    <div class="gh_block_section">
                        <div class="clearfix">
                            <strong class="color_title pull-left">Delete this account</strong>
                            <button
                                type="button"
                                data-loading-text="DELETE"
                                class="btn btn-danger pull-right btn-delete-account">DELETE</button>
                        </div><!-- clearfix end -->
                    </div><!-- gh_block_section end -->
                </div><!-- block end -->
			</div>
		</div><!-- row end -->
	</section><!-- container end -->
</main>

<?php if ($CI->user) { $CI->load->view('partials/_donation_modals'); }
