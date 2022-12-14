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
                        <div>
                            <ul id="notifications">
                            </ul>
                        </div>
                        <div class="mt-2 p-2 border-t border-grey-800">
                            <ul id="contacts">
                            </ul>
                        </div>
                    </div>
                    <div class="ml-4 flex-1 flex-col flex justify-center items-center">
                        <div id="chat-header">
                            <div>
                                <b>{{ __('Select contact to talk') }}</b>
                            </div>
                        </div>
                        <div id="chat-area">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const conn = new WebSocket('ws://172.20.128.5:8090/?token={{ auth()->user()->token }}');
    const from_user_id = {{ auth()->user()->id }};
    let to_user_id = '';
    let to_user_name = '';

    conn.onopen = function (e) {
        console.log('connection established');
        requestUnreadNotification(from_user_id);
        requestConnectedChatUser(from_user_id);
    }

    conn.onmessage = function (e) {
        let data = JSON.parse(e.data);

        if (data.response_load_unread_notification) {
            loadUnreadNotifications(data);
        }

        if (data.response_chat_processing) {
            loadUnreadNotifications(data);
            requestConnectedChatUser(from_user_id);
        }

        if (data.response_connected_chat_user) {
            loadConnectedUsers(data);
        }

        if (data.message) {
            getMessages(data);
        }

        if (data.chat_history) {
            loadChatHistory(data);
        }

        if (data.response_create_cause_request) {
            getCauseRequest(data);
        }

        if(data.response_cause_processing) {
            requestChatHistory(from_user_id, to_user_id);
            showResponseOfCauseRequest();
        }
    }

    /* FOR REQUESTS */

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

    function sendMessage() {
        document.getElementById('send-button').disabled = true;
        const message = document.getElementById('message').value.trim();
        const data = {
            'message': message,
            'from_user_id': from_user_id,
            'to_user_id': to_user_id,
            'type': 'request_send_message'
        };

        conn.send(JSON.stringify(data));
        document.getElementById('message').value = '';
        document.getElementById('send-button').disabled = false;
    }

    function requestChatHistory(fromUserId, toUserId) {
        const data = {
            from_user_id: fromUserId,
            to_user_id: toUserId,
            type: 'request_chat_history'
        }

        conn.send(JSON.stringify(data));
    }

    function requestCreateCause() {
        document.getElementById('create-cause-button').disabled = true;
        const message = document.getElementById('message').value.trim();

        const data = {
            'message': message,
            'from_user_id': from_user_id,
            'to_user_id': to_user_id,
            'type': 'request_create_cause'
        };

        conn.send(JSON.stringify(data));
        document.getElementById('message').value = '';
        document.getElementById('send-button').disabled = false;
    }

    function requestCauseProcessing(causeRequestId, fromUserId, toUserId, action) {
        let data = {
            cause_request_id: causeRequestId,
            from_user_id: fromUserId,
            to_user_id: toUserId,
            action: action,
            type: 'request_cause_processing'
        }

        conn.send(JSON.stringify(data));
    }

    /* FOR RESPONSES */

    function loadConnectedUsers(data) {
        let html = '';
        if (data.data.length > 0) {
            for (let count in data.data) {
                html += `
                    <li class="">
                        <button class="hover:underline" onclick="makeChatArea(${data.data[count].id},'${data.data[count].name}')">${data.data[count].name}</button>
                    </li>
                    `;
            }
        } else {
            html = 'No contacts';
        }
        document.getElementById('contacts').innerHTML = html;
    }

    function loadUnreadNotifications(data) {
        let html = '';
        if (data.data.length > 0) {
            for (let count in data.data) {
                html += `
                    <li class="">
                        <div>
                            <span>${data.data[count].name}</span>
                        </div>
                        <div>
                            <button class="hover:underline" onclick="requestChatProcessing(${data.data[count].id}, ${data.data[count].from_user_id}, ${data.data[count].to_user_id}, 'approve')">
                                <b>Approve</b>
                            </button>
                               or
                            <button class="hover:underline" onclick="requestChatProcessing(${data.data[count].id}, ${data.data[count].from_user_id}, ${data.data[count].to_user_id}, 'reject')">
                                <b>Refuse</b>
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

    function makeChatArea(toUserId, toUserName) {
        let html = `
        <div class="mx-4 lg:mx-auto w-full p-4 bg-white rounded-xl" style="overflow-y: scroll; height:600px;" id="chat">
            <div class="space-y-3" id="chat-history">
            </div>
            <div class="mt-3 space-y-3" id="chat-messages">
            </div>
        </div>

        <div class="mt-6 mx-4 lg:mx-auto p-4 bg-white rounded-xl">
                <x-text-input type="text" name="content" class="flex-1 m-2" placeholder="{{ __('Your message') }}"
                              id="message"/>
                <x-primary-button class="m-2" id="send-button" onclick="sendMessage()">
                    {{ __('Submit') }}
        </x-primary-button>
        <a class="m-2 hover:underline" style="cursor:pointer" id="create-cause-button" onclick="requestCreateCause()">
{{ __('Create cause') }}
        </a>
</div>
`

        document.getElementById('chat-area').innerHTML = html;
        document.getElementById('chat-header').innerHTML = `<div><b>{{ __('Chat with') }} ${toUserName}</b></div>`;
        to_user_id = toUserId;
        to_user_name = toUserName;
        requestChatHistory(from_user_id, to_user_id);
    }

    function getMessages(data) {
        let html = '';
        if (data.from_user_id == from_user_id) {
            html += `
                <div class="p-4 bg-gray-200 rounded-xl">
                    <p class="font-semibold">{{ __('you') }}</p>
                    <p>${data.message}</p>
                </div>
                `
        } else {
            html += `
                <div class="p-4 bg-gray-200 rounded-xl">
                    <p class="font-semibold">${data.from_user_name}</p>
                    <p>${data.message}</p>
                </div>
                `
        }

        if (html != '') {
            let previousChat = document.getElementById('chat-messages');
            previousChat.innerHTML += html;
        }
    }

    function scrollChat() {
        document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
    }

    function loadChatHistory(data) {
        let html = '';
        console.log(data);
        for (let count in data.chat_history) {
            if (data.chat_history[count].from_user_id == from_user_id) {
                if(data.chat_history[count].cause_request_id) {
                    html += `
                        <div class="p-4 bg-gray-200 rounded-xl cause-request">
                            <p class="font-semibold">{{ __('you') }}</p>
                            <p>${data.chat_history[count].chat_message}</p>
                            <p class="font-bold">{{ __('You send a cause request') }}</p>
                        </div>
                    `
                } else {
                    html += `
                        <div class="p-4 bg-gray-200 rounded-xl">
                            <p class="font-semibold">{{ __('You') }}</p>
                            <p>${data.chat_history[count].chat_message}</p>
                        </div>
                    `
                }
            } else {
                if(data.chat_history[count].cause_request_id) {
                    html += `
                        <div class="p-4 bg-gray-200 rounded-xl cause-request">
                            <p class="font-semibold">${to_user_name}</p>
                            <p>${data.chat_history[count].chat_message}</p>
                            <button class="hover:underline" onclick="requestCauseProcessing(${data.chat_history[count].cause_request_id}, ${data.chat_history[count].from_user_id}, ${data.chat_history[count].to_user_id}, 'approve')">
                                <b>Approve?</b>
                            </button>
                        </div>
                    `
                } else {
                    html += `
                        <div class="p-4 bg-gray-200 rounded-xl">
                            <p class="font-semibold">${to_user_name}</p>
                            <p>${data.chat_history[count].chat_message}</p>
                        </div>
                    `
                }
            }

            if (html != '') {
                document.getElementById('chat-history').innerHTML = html;
                scrollChat();
            }
        }
    }

    function getCauseRequest(data) {
        let html = '';
        if (data.from_user_id == from_user_id) {
            html += `
                        <div class="p-4 bg-gray-200 rounded-xl cause-request">
                            <p class="font-semibold">{{ __('you') }}</p>
                            <p>${data.message}</p>
                            <p class="font-bold">{{ __('You send a cause request') }}</p>
                        </div>
                    `
        } else {
            html += `
                        <div class="p-4 bg-gray-200 rounded-xl cause-request">
                            <p class="font-semibold">${data.from_user_name}</p>
                            <p>${data.message}</p>
                            <button class="hover:underline" onclick="requestCauseProcessing(${data.id}, ${data.from_user_id}, ${data.to_user_id}, 'approve')">
                                <b>Approve</b>
                            </button>
                            cause request
        </div>
`
        }

        if (html != '') {
            let previousChat = document.getElementById('chat-messages');
            previousChat.innerHTML += html;
        }
    }

    function showResponseOfCauseRequest() {
        const messages = document.querySelectorAll('.cause-request');
        console.log(messages);
        for (const message of messages) {
            console.log(message);
            message.innerHTML = '{{ __('Request accepted') }}'
        }
    }
</script>
