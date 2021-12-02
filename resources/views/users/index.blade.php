<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="xl:flex">

            <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight pb-2">Search</h2>

                        <form method="GET">
                            <div>
                                <x-label for="name" :value="__('Name')" />

                                <x-input id="name" name="name" type="text" :value="request()->input('name')" class="block mt-1 w-full" />
                            </div>

                            <div class="mt-4">
                                <x-label for="about" :value="__('About')" />

                                <x-input id="about" name="about" type="text" :value="request()->input('about')" class="block mt-1 w-full" />
                            </div>

                            <div class="mt-4">
                                <x-label for="country" :value="__('Country')" />

                                <select id="country" name="country" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                    <option value="">--- Any ---</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->short_code }}" {{ request()->input('country') === $country->short_code ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="date-rangepicker" class="mt-4" >

                                <x-label :value="__('Registration date')"></x-label>

                                <div class="flex flex-row mt-2 gap-2">
                                    <div class="flex-grow">
                                        <x-label for="registered_from" :value="__('From')"></x-label>

                                        <x-input id="registered_from" name="registered_from" type="text" :value="request()->input('registered_from')" class="block mt-1 w-full"></x-input>
                                    </div>
                                    <div class="flex-grow">
                                        <x-label for="registered_to" :value="__('To')"></x-label>

                                        <x-input id="registered_to" name="registered_to" type="text" :value="request()->input('registered_to')" class="block mt-1 w-full"></x-input>
                                    </div>

                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.1.4/dist/js/datepicker-full.min.js"></script>
                            <script>
                                const el = document.getElementById('date-rangepicker');
                                const rangepicker = new DateRangePicker(el, {
                                  format: 'yyyy-mm-dd',
                                  allowOneSidedRange: true,
                                  clearBtn: true
                                });
                            </script>

                            <div class="flex items-center justify-end mt-4">
                                <x-button type="button" class="ml-3" onclick="location.assign(location.pathname)">
                                    {{ __('Reset') }}
                                </x-button>
                                <x-button class="ml-3">
                                    {{ __('Filter') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="sm:px-6 lg:px-8 xl:flex-grow">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="min-w-full align-middle overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">About</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Country</span>
                                    </th>
                                </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($users as $user)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $user->about }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            {{ $user->country->name }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

