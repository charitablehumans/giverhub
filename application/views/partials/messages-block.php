<div class="block messages-block" ng-if="user_open">
    <div class="header">Your conversation with <a href="{{ user_open.entity.url }}">{{ user_open.entity.name }}</a></div>
    <perfect-scrollbar refresh-on-change="refresh_messages_scroll" class="messages" scroll-down="true" wheel-propagation="true" wheel-speed="10" min-scrollbar-length="20">
        <div class="message" ng-repeat="message in user_open.messages">
            <img ng-src="{{ message.from.imageUrl }}" alt="{{ message.from.name }}">
            <!--<span class="msg" ng-bind-html="message.message | nl2br"></span>-->
            <span class="msg">{{ message.message }}</span>
            <div class="date" am-time-ago="message.sent"></div>
            <div class="volunteer" ng-if="message.volunteer">
                <header>
                    <span class="dated">{{message.volunteer.event.date_string}}</span>
                    <span class="time">{{message.volunteer.event.time_string}}</span>
                </header>
                <p class="desc">{{message.volunteer.event.description}}</p>
                <p class="location"><span class="bold">Location:</span> {{message.volunteer.event.location}}</p>
            </div>
            <button
                ng-if="message.volunteer && charity_page_id && message.volunteer.status == 'requested'"
                type="button"
                ng-click="accept_request(message)"
                class="btn btn-primary btn-accept-request">Accept request</button>
            <span ng-if="message.volunteer && message.volunteer.status == 'accepted'">Request has been accepted!</span>
        </div>
    </perfect-scrollbar>
    <div class="respond-wrapper">
                        <textarea msd-elastic
                                  class="form-control"
                                  ng-model="msg.msg"
                                  ng-keypress="keypress($event)"
                                  placeholder="Respond to {{ user_open.entity.name }}"></textarea>
        <button ng-disabled="!msg" type="button" class="btn btn-primary btn-sm" ng-click="send(user_open, msg.msg)">Reply</button>
        <label><input type="checkbox" ng-change="change_enter_to_send()" ng-model="enter_to_send.enter_to_send"> Press enter to send message (shift+enter adds a new line)</label>
    </div>
</div>
<div class="block message-block" ng-if="!user_open">No messages yet...</div>