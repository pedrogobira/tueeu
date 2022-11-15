<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-2xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 flex">
                    <div class="p-2 border-r border-grey-800 flex-2">
                        <ul id="notifications">
                        </ul>
                        <ul id="contacts">
                        </ul>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="lg:w-2/4 mx-4 lg:mx-auto p-4 bg-white rounded-xl">
                            <div class="space-y-3" id="chat-messages">
                                <div class="p-4 bg-gray-200 rounded-xl">
                                    <p class="font-semibold">eu</p>
                                    <p>mensagem</p>
                                </div>
                            </div>
                        </div>

                        <div class="lg:w-2/4 mt-6 mx-4 lg:mx-auto p-4 bg-white rounded-xl">
                            <form method="post" action="." class="flex">
                                <x-text-input type="text" name="content" class="flex-1" placeholder="Your message..."
                                              id="chat-message-input"/>
                                <x-primary-button class="ml-2" id="chat-message-submit">
                                    Submit
                                </x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const conn = new WebSocket('ws://172.20.128.5:8090/?token={{ auth()->user()->token }}');
    conn.onopen = function (e) {
        console.log('connection established');
        requestUnreadNotification({{ auth()->user()->id }});
        requestConnectedChatUser({{ auth()->user()->id }});
    }
    conn.onmessage = function (e) {
        let data = JSON.parse(e.data);

        console.log(data);
        if (data.response_load_unread_notification) {
            loadUnreadNotifications(data);
        }

        if (data.response_chat_processing) {
            loadUnreadNotifications(data);
            requestConnectedChatUser({{ auth()->user()->id }});
        }

        if (data.response_connected_chat_user) {
            loadConnectedUsers(data);
        }
    }

    function requestUnreadNotification(userId) {
        let data = {
            user_id: userId,
            type: "request_load_unread_notification"
        }

        conn.send(JSON.stringify(data));
    }

    function requestChatProcessing(chatRequestId, fromUserId, toUserId, action) {
        let data = {
            chat_request_id: chatRequestId,
            from_user_id: fromUserId,
            to_user_id: toUserId,
            action: action,
            type: 'request_chat_processing'
        }

        conn.send(JSON.stringify(data));
    }

    function requestConnectedChatUser(fromUserId) {
        let data = {
            from_user_id: fromUserId,
            type: 'request_connected_chat_user'
        }

        conn.send(JSON.stringify(data));
    }

    function loadConnectedUsers(data) {
        let html = '';
        if (data.data.length > 0) {
            for (let count = 0; count < data.data.length; count++) {
                html += `
                    <li class="">
                        <a href="">${data.data[count].name}</a>
                    </li>
                    `;
            }
        } else {
            html = 'No contacts';
        }
        document.getElementById('contacts').innerHTML = html;
    }

    function loadUnreadNotifications(data) {
        console.log(data);
        let html = '';
        if (data.data.length > 0) {
            for (let count = 0; count < data.data.length; count++) {
                html += `
                    <li class="">
                        <div>
                            <span>${data.data[count].name}</span>
                        </div>
                        <div>
                            <button onclick="requestChatProcessing(${data.data[count].id}, ${data.data[count].from_user_id}, ${data.data[count].to_user_id}, 'approve')">
                                Approve
                            </button>
                               or
                            <button onclick="requestChatProcessing(${data.data[count].id}, ${data.data[count].from_user_id}, ${data.data[count].to_user_id}, 'reject')">
                                Refuse
                            </button>
                        </div>
                    </li>
                    `;
            }
        } else {
            html = 'No notifications for now';
        }
        document.getElementById('notifications').innerHTML = html;
    }
</script>
