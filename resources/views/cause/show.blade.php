<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $cause->name ?? __('Cause') }}
        </h2>
        @if($cause->name == null || $cause->name == '')
            <a href="{{ route('cause.updateView', $cause->id) }}" class="mt-2 text-indigo-600 underline">{{ __('Give this cause a name') }}</a>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-xl mx-auto">
                <div class="p-6">
                    <form action="{{ route('post.create', $cause->id) }}" method="get">
                        <x-primary-button>New post</x-primary-button>
                    </form>
                </div>
            </div>
            @foreach($cause->posts()->get() as $post)
                <div class="mt-3 bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-xl mx-auto">
                    <div class="p-6 bg-white border-b border-gray-200 ">
                        <h3 class="text-xl">{{ $post->title }}</h3>
                        <span class="text-xs">{{ $post->author->user->name }}</span>
                        <span class="text-xs">{{ __('created this post at') }} {{ $post->created_at }}</span>
                        <p class="mt-4 text-clip overflow-hidden">{{ $post->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
