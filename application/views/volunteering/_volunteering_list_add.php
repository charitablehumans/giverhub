<?php
    /** @var \Entity\Charity $charity */
    /** @var \Base_Controller $CI */
    $CI =& get_instance();
    $opportunities = $charity->getVolunteeringOpportunities();
?>
<div
    data-charity-id="<?php echo $charity->getId(); ?>"
    data-is-charity-admin="<?php echo $CI->user && $CI->user->isCharityAdmin($charity) ? '1' : '0'; ?>"
    data-opportunities="<?php echo htmlspecialchars(json_encode($opportunities)); ?>"
    data-time-zones="<?php echo htmlspecialchars(json_encode(\Entity\CharityVolunteeringOpportunity::$time_zones)); ?>"
    ng-controller="VolunteeringOpportunitiesController"
    class="block add-volunteering-block VolunteeringOpportunitiesController volunteering-info">
    <script type="text/ng-template" id="time_zones_modal.html">
        <header class="modal-header clearfix">
            <span class="header">Time Zones</span>
            <button type="button" class="close" ng-click="cancel()" data-dismiss="modal" aria-hidden="true">x</button>
        </header>
        <div class="modal-body">
            <form name="time_zones_form" novalidate>
                <select
                    class="form-control"
                    ng-model="selected.time_zone"
                    ng-options="tz.name for tz in time_zones"><option value="">-- Select Time Zone --</option></select>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-click="ok()">OK</button>
            <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
        </div>
    </script>
    <script type="text/ng-template" id="create_volunteering_opportunity_modal.html">
        <header class="modal-header clearfix">
            <span class="header">Volunteering Information</span>
            <button type="button" class="close" ng-click="cancel()" data-dismiss="modal" aria-hidden="true">x</button>
        </header>
        <div class="modal-body">
            <form name="create_volunteering_opportunity_form" novalidate>
                <input type="hidden" ng-model="opportunity.charity_id">
                <input required maxlength="40" ng-model="opportunity.title" class="form-control" type="text" placeholder="Title: E.g. Care for homeless kittens (limit 40 characters)">
                <textarea required ng-model="opportunity.description" class="form-control" placeholder="Description: E.g. Helping care for kittens, change litter, give food, clean cages, etc."></textarea>
                <input required ng-model="opportunity.location" class="form-control" type="text" placeholder="Location/Address">
                <hr/>
                Date of Event
                <div class="times-wrapper" ng-class="{hastimezone : !!opportunity.time_zone}">
                    <div class="time-zone">{{ opportunity.time_zone.name }}</div>
                    <input
                        type="text"
                        class="form-control"
                        min-date="minDate"
                        datepicker-options="dateOptions"
                        datepicker-popup="{{date_picker_format}}"
                        ng-model="opportunity.start_date"
                        is-open="$parent.date_picker_opened_start"
                        ng-required="true"
                        ng-click="open_date_picker($event,true)"
                        close-text="Close"/>
                    <select
                        class="form-control"
                        ng-show="!opportunity.all_day"
                        ng-required="!opportunity.all_day"
                        ng-model="opportunity.start_time"
                        ng-options="time.name as time.name for time in times"></select>
                    <span class="to">to</span>
                    <select
                        class="form-control"
                        ng-show="!opportunity.all_day"
                        ng-required="!opportunity.all_day"
                        ng-model="opportunity.end_time"
                        ng-options="time.name as time.name for time in times"></select>
                    <input
                        type="text"
                        class="form-control"
                        min-date="minDate"
                        datepicker-options="dateOptions"
                        datepicker-popup="{{date_picker_format}}"
                        ng-model="opportunity.end_date"
                        is-open="$parent.date_picker_opened_end"
                        ng-click="open_date_picker($event,false)"
                        close-text="Close"/>
                    <a href="#" ng-click="time_zones_modal()">Time Zones</a>
                </div>

                <label class="all-day-label"><input type="checkbox" ng-model="opportunity.all_day"/>All Day</label>

                <!--<select required class="form-control" ng-model="opportunity.occurs">
                    <option value="once">Does not repeat</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>-->
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-disabled="create_volunteering_opportunity_form.$invalid || saving" ng-click="add()">SAVE</button>
        </div>
    </script>
    <h3 class="gh_block_title">Volunteering Information</h3>
    <div ng-hide="opportunities.length" class="no-opportunities">No opportunities available at this moment.</div>
    <ul ng-show="opportunities.length" class="opportunity-list" ng-class="{isNonprofitAdmin : isNonprofitAdmin}">
        <?php foreach($opportunities as $opportunity): ?>
            <li ng-show="false">
                <div class="title"><a href="/volunteering-opportunities/<?php echo $charity->getUrlSlug(); ?>"><?php echo htmlspecialchars($opportunity->getTitle()); ?></a></div>
                <div class="date"><?php echo $opportunity->getDateString(); ?></div>
                <div class="time"><?php echo $opportunity->getTimeString(); ?></div>
            </li>
        <?php endforeach; ?>
        <li ng-repeat="opportunity in opportunities | orderBy:order_by" popover-trigger="mouseenter" popover-placement="bottom" popover-title="{{opportunity.title}}" popover="<p>{{opportunity.description}}</p><p>{{opportunity.location}}</p>">
            <div class="title"><a href="/volunteering-opportunities/<?php echo $charity->getUrlSlug(); ?>">{{opportunity.title}}</a></div>
            <div class="date">{{opportunity.date_string}}</div>
            <div class="time">{{opportunity.time_string}}</div>
            <button ng-show="isNonprofitAdmin" type="button" class="btn btn-danger btn-delete-opportunity" ng-click="delete(opportunity)" ng-disabled="opportunity.deleting">X</button>
            <button ng-show="isNonprofitAdmin" type="button" class="btn btn-primary btn-edit-opportunity" ng-click="edit(opportunity)" ng-disabled="opportunity.deleting">EDIT</button>
            <div class="time-zone"></div>
            <div class="time-zone" ng-class="{hide : !opportunity.time_zone}">{{opportunity.time_zone.name}}</div>
        </li>
    </ul>
    <div ng-show="isNonprofitAdmin && !opportunities.length" class="add-wrapper">
        <span>Tell people about Volunteering Events</span>
        <button type="button" class="btn btn-primary" ng-click="open_create_modal()">ADD</button>
    </div>
    <div ng-show="isNonprofitAdmin && opportunities.length" class="add-wrapper2">
        <a href="#" ng-click="open_create_modal()">Add Volunteering Opportunities</a>
    </div>
</div>