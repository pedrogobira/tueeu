<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
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
</x-app-layout>
