<div class="block volunteering-opportunities-block"
     ng-controller="VolunteeringOpportunitiesListController">
    <header>Volunteering Events</header>
    <div ng-hide="opportunities.length" class="no-opportunities">No opportunities available at this moment.</div>
    <?php if (isset($two_col) && $two_col): ?>
        <div ng-show="opportunities.length" ng-repeat="opportunity in opportunities | orderBy:order_by">
            <div class="row vol-list-row"
                 ng-class="$index >= opportunities.length-2 ? 'last-row' : ''"
                 ng-if="$index % 2 === 0">
                <div
                    class="col-md-6 volunteering-block"
                    ng-class="$first ? 'first' : ''"
                    id="vol-event-block-{{opportunities[$index].id}}"
                    ng-init="opportunity = opportunities[$index]">
                    <?php $this->load->view('/volunteering/_volunteering_list_item'); ?>
                </div>
                <div
                    class="col-md-6 volunteering-block"
                    id="vol-event-block-{{ opportunities[$index + 1].id}}"
                    ng-if="opportunities.length > $index + 1"
                    ng-init="opportunity = opportunities[$index + 1]">
                    <?php $this->load->view('/volunteering/_volunteering_list_item'); ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div ng-show="opportunities.length"
             class="volunteering-block"
             id="vol-event-block-{{opportunity.id}}"
             ng-class="$first ? 'first' : ''"
             ng-repeat="opportunity in opportunities | orderBy:order_by">
            <?php $this->load->view('/volunteering/_volunteering_list_item'); ?>
        </div>
    <?php endif; ?>
</div>