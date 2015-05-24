<?php
/** @var \Entity\Petition $petition */
/** @var \Base_Controller $CI */
$CI =& get_instance();
$CI->modal('add-g-petition-news-modal', [
    'extra_classes' => 'add-g-petition-news-modal',
    'header' => 'News',
    'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
    'body' => '<p class="lead txtCntr">Add News</p>
				<form class="add-g-petition-news-form" action="#">
					<textarea
                    	class="form-control"
						id="giverhub-petition-news"
                        name="giverhub-petition-news"
                        data-placeholder=""
                        title=""
                        tabindex="2"
						rows="5"></textarea>
				</form>',
    'body_string' => true,
    'footer' => '<a class="btn btn-primary noise btn-add-g-petition-news">SAVE</a>
                <a data-dismiss="modal" class="btn btn-cancel-g-petition-news">CANCEL</a>',
    'footer_string' => true,
]);
$CI->modal('add-g-petition-goal-modal', [
    'extra_classes' => 'add-g-petition-goal-modal',
    'header' => 'Goal',
    'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
    'body' => '<p class="lead txtCntr">Add Goal</p>
				<form class="add-g-petition-goal-form" action="#">
					<input type="text"
					class="form-control g-petition-goal-input"
					name="g-petition-goal-input"
					placeholder="How many signatures are you aiming for? (numbers only)"
					value="'.$petition->getGoal().'"
					id="g-petition-goal-input" >
				</form>',
    'body_string' => true,
    'footer' => '<a class="btn btn-primary noise btn-add-g-petition-goal">SAVE</a>
                <a data-dismiss="modal" class="btn btn-cancel-g-petition-goal">CANCEL</a>',
    'footer_string' => true,
]);
$CI->modal('add-g-petition-deadline-modal', [
    'extra_classes' => 'add-g-petition-deadline-modal',
    'header' => 'Deadline',
    'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
    'body' => '<p class="lead txtCntr">Add Deadline</p>
				<form class="add-g-petition-deadline-form" action="#">
					<div class="input-group date" id="petitiondeadlinepicker" data-date-format="MM/DD/YY">
                    	<input
                            type="text"
							name="g-petition-deadline-date"
							id="g-petition-deadline-date"
                            class="form-control g-petition-deadline-date"
                            placeholder="mm/dd/yy"
                            value="'.($petition->getEndAt() ? $petition->getEndAt()->format('m/d/y') : '').'"
                            tabindex="5"/>
                        <span class="input-group-addon cursor-pointer"><img src="/assets/images/calendar-glyph.png" class="calendar-glyph" alt="Pick a Date"/></span>
                    </div>
				</form>',
    'body_string' => true,
    'footer' => '<a class="btn btn-primary noise btn-add-g-petition-deadline">SAVE</a>
                <a data-dismiss="modal" class="btn btn-cancel-g-petition-deadline">CANCEL</a>',
    'footer_string' => true,
]);
$CI->modal('edit-g-petition-media-modal', [
    'extra_classes' => 'edit-g-petition-media-modal',
    'header' => 'Media',
    'modal_size' => 'col-md-5 col-lg-4 col-md-offset-4',
    'body' => '/modals/edit-g-petition-media-modal-body',
    'body_string' => false,
    'body_data' => ['petition' => $petition],
]);