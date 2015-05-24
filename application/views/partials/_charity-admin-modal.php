<?php
/** @var \Entity\Charity $charity */
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('charity-admin-modal', [
    'header' => 'Nonprofit Admin',
    'modal_size' => 'col-md-5',
    'body' => '<p class="lead txtCntr address-lead">Edit Nonprofit Data</p>
                <form id="charity-admin-data-form" action="#">
                    <input type="hidden" name="charity_id" value="'.$charity->getId().'">
                    <div class="form-group">
                        <label>Tagline: <input type="text" class="form-control" id="charity-admin-data-tagline-input" name="tagline" placeholder="Tagline" value="'.htmlspecialchars($charity->getAdminDataValue('tagline')).'" tabindex="1"></label>
                    </div>
                    <div class="form-group">
                        <label>Mission: <textarea class="form-control" id="charity-admin-data-mission-textarea" name="mission" placeholder="Mission" tabindex="2">'.htmlspecialchars($charity->getAdminDataValue('mission')).'</textarea></label>
                    </div>
                </form>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn-save-charity-admin-data btn btn-primary" data-loading-text="SAVE" tabindex="3">SAVE</button>',
    'footer_string' => true,
]);
$CI->modal('charity-admin-modal-facebook-page', [
    'header' => 'Facebook Page',
    'modal_size' => 'col-md-5',
    'body' => '<p class="lead txtCntr address-lead">Edit Facebook Page</p>
                <form class="charity-admin-data-form" action="#">
                    <input type="hidden" name="charity_id" value="'.$charity->getId().'">

                    <div class="form-group">
                        <label
                            data-content="Connect your GiverHub page with your Facebook Page. In doing so a feed from your facebook page will be displayed here on GiverHub. The name of your facebook page is part of the url address to your facebook page. For example, if your facebook page is located at https://www.facebook.com/aspca it means that the name of your facebook page would simply be aspca"
                            title="Facebook Page"
                            data-trigger="hover"
                            data-placement="top"
                            class="gh_popover">
                            Facebook Page: <input type="text" class="form-control" id="charity-admin-data-facebook-page" name="facebook_page" placeholder="Facebook Page" value="'.htmlspecialchars($charity->getAdminDataValue('facebook_page')).'" tabindex="3"></label>
                    </div>
                </form>',
    'body_string' => true,
    'footer' => '<button type="button" class="btn-save-charity-admin-data btn btn-primary" data-loading-text="SAVE" tabindex="3">SAVE</button>',
]);