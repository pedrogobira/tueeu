@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update your cause') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <x-success-message/>
                    <form method="POST" action="{{ route('cause.custom-update', $cause->id) }}">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Your cause name')"/>

                            <x-text-input class="block mt-1 w-full" type="text" name="name"
                                          required autofocus/>

                            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Your cause description')"/>

                            <x-text-input class="block mt-1 w-full" type="text" name="description"
                                              required autofocus/>

                            <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
