<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Received story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    @if($received == null)
                        <h3 class="font-semibold">
                            {{ __('Nothing to receive') }}
                        </h3>
                    @else
                        <div>
                            <x-input-label for="title" :value="__('Title')"/>
                            <x-text-input class="block mt-1 w-full" type="text" name="title"
                                          value="{{ $received->title ?? '' }}"
                                          disabled/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="body" :value="__('Body')"/>
                            <x-textarea-input name="body" cols="50" rows="20"
                                              disabled>{{ $received->body }}</x-textarea-input>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <form id="identify" method="POST" action="{{ route('chat-request.store') }}">
                                @csrf
                                <input type="number" name="from_user_id" value="{{ auth()->user()->id }}" hidden
                                       required>
                                <input type="number" name="to_user_id" value="{{ $received->author_id }}" hidden required>
                                <input type="number" name="story_id" value="{{ $received->id }}" hidden required>
                                <x-primary-button class="align-right" form="identify">
                                    I identify with this
                                </x-primary-button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

