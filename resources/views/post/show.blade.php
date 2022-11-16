<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your story') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <div>
                        <x-input-label for="title" :value="__('Your story title')"/>
                        <x-text-input class="block mt-1 w-full" type="text" name="title"
                                      value="{{ $story?->title ?? '' }}"
                                      disabled/>
                    </div>

                    <div class="mt-4">
                        <x-input-label for="body" :value="__('Your story body')"/>
                        <x-textarea-input name="body" cols="50" rows="20" disabled>{{ $story?->body }}</x-textarea-input>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
