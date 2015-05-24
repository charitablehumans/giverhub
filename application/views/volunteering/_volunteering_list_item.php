<header>
    <span class="dated">{{opportunity.date_string}}</span>
    <span class="time">{{opportunity.time_string}}</span>
</header>
<p class="desc">{{opportunity.description}}</p>
<p class="location"><span class="bold">Location:</span> {{opportunity.location}}</p>

<button type="button"
        class="btn btn-volunteer btn-primary"
        data-placement="bottom"
        tooltip-placement="bottom"
        tooltip-append-to-body="true"
        data-container="body"
        tooltip="Clicking “volunteer” will bring up a form message that will be sent to the nonprofit. If you have any questions, concerns, or requests you will be able to modify the text before sending"
        title="Clicking “volunteer” will bring up a form message that will be sent to the nonprofit. If you have any questions, concerns, or requests you will be able to modify the text before sending"
        data-event-id="{{ opportunity.id }}">volunteer</button>

<div
    ng-hide="true"
    ng-controller="VolunteeringOpportunityCommentsController"
    class="activity-like-share-comment-wrapper">
    <div class="activity-share-comment">
        <a ng-show="opportunity.hasSignedInUserLiked" href="" ng-click="like(opportunity,false)">Unlike</a>
        <a ng-hide="opportunity.hasSignedInUserLiked" href="" ng-click="like(opportunity,true)">Like</a> ·
        <a href="" ng-click="showComments = true">Comment</a> ·
        <a href="" ng-click="share()">Share</a> ·
                                    <span class="comment-share-like-indicators">
                                        <span class="glyphicon glyphicon-thumbs-up">{{ opportunity.likes }}</span> · <span class="glyphicon glyphicon-comment">{{ opportunity.comments.length }}</span>
                                    </span>
    </div>

    <div ng-show="showComments || opportunity.comments.length" class="activity-comments-wrapper">
        <div class="activity-feed-comments-container">
            <a class="show-more-comments-link" href="" ng-show="view_more && opportunity.comments.length > 4" ng-click="view_more = false; limitComments = max_safe_integer">View {{ opportunity.comments.length - 4 }} more comments.</a>

            <div class="comment-container" ng-repeat="comment in opportunity.comments | limitTo: limitComments">
                <a class="profile-pic-a" title="{{ comment.user.name }}" href="{{ comment.user.url }}"><img alt="{{ comment.user.name }}" class="profile-pic" ng-src="{{ comment.user.imageUrl }}"></a>
                <div class="mess">
                    <a href="{{ comment.user.url }}" title="{{ comment.user.name }}">{{ comment.user.name }}</a>
                    <span ng-bind-html="comment.text | nl2br"></span>
                    <div class="date">{{ comment.humanizedDateDifference }}</div>
                </div>

                <a ng-show="comment.belongsToSignedInUser" class="delete-comment" href="">x</a>
                <img alt="Loading" ng-show="comment.deleting" class="deleting-comment-indicator" src="/images/ajax-loaders/indicator2.gif">
            </div>
        </div>

        <div ng-show="user" class="make-post-comment-container">
            <img ng-src="{{ user.imageUrl }}" alt="{{ user.name }}"/>
            <textarea ng-keydown="$event.keyCode == 13 && submitComment($event, opportunity)" ng-disabled="submittingComment" ng-model="commentText" class="make-post-comment-textarea angular" placeholder="Write a comment..."></textarea>
            <div class="youtube-preview-container"></div>
            <div class="external-url-preview-container hide"></div>
        </div>
    </div>
</div>