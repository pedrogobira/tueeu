@if(session('message'))
<div class="my-4 text-center">
    <div class="font-semibold text-lg text-green-800">
        {{ __('Success') }}
    </div>
    <div class="text-sm text-green-600">
        {{ session('message') }}
    </div>
</div>
@endif
