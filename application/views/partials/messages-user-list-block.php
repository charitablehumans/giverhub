<div class="block messages-user-list-block" id="messages-user-list-block">
    <input type="text" class="form-control msg-search-users" placeholder="Search users..." ng-model="search.user.name">
    <perfect-scrollbar refresh-on-change="users" class="user-list" wheel-propagation="true" wheel-speed="10">
        <div class="user"
             ng-class="{
             open: user==user_open,
             unseen :
                user.last_message &&
                !user.last_message.seen &&
                ((user.last_message.to.type == 'user' && user.last_message.to.id == signed_in_user.id) || (user.last_message.to.type == 'charity' && user.last_message.to.id == charity_page_id)) &&
                user.last_message.to.id != user.last_message.from.id
             }"
             ng-repeat="user in users | orderBy : ['last_message.unread_by_current_user ? 0 : 1', 'last_message.id === false ? 0 : 1', 'last_message ? -last_message.id : 0', 'entity.name'] | filter : search"
             ng-click="open(user)">
            <img ng-src="{{ user.entity.imageUrl }}" alt="{{ user.entity.name }}">
            <div class="right-wrapper">
                <span class="name">{{ user.entity.name }}</span>
                <div class="date">{{ user.last_message.sent | amDateFormat:'M/D' }}</div>
                <div class="msg">{{ user.last_message ? user.last_message.message : ''}}</div>
            </div>
        </div>
    </perfect-scrollbar>
</div>