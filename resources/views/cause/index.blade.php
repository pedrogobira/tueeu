<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Causes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                @if($causes == null || count($causes) == 0)
                                    <h3 class="font-semibold">
                                        {{ __('No records found') }}
                                    </h3>
                                @else
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Access
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Created at
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                                Updated at
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($causes as $cause)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 hover:underline mr-auto">
                                                    <a href="{{ route('cause.show', $cause->id) }}">{{ __('Enter') }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 mr-auto">
                                                    {{ $cause->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $cause->created_at }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $cause->updated_at }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="mt-4">
                                    {{ $causes->links() }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
