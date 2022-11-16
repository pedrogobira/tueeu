@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Write your post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <x-success-message/>
                    <form method="POST" action="{{ route('post.store', $cause->id) }}">
                        @csrf
                        <input type="number" name="author_id" id=""
                               value="{{ Auth::id() }}" required hidden>
                        <input type="number" name="cause_id" id=""
                               value="{{ $cause->id }}" required hidden>
                        <div>
                            <x-input-label for="title" :value="__('Your post title')"/>

                            <x-text-input class="block mt-1 w-full" type="text" name="title"
                                          required autofocus/>

                            <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="body" :value="__('Your post body')"/>

                            <x-textarea-input class="block mt-1 w-full" type="text" name="body" cols="60" rows="10"
                                              required autofocus/>

                            <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Store') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
