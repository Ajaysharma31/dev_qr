<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-3">Today's Attendance</h1>
                    <hr />
                    <div class="mt-2 p-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Total Time</th>
                                    <th scope="col">Date</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($Atnd_data as $AtndData)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ ucfirst($AtndData['user']['name']) }}</td>
                                        <td>{{ $AtndData['user']['email'] }}</td>
                                        <td>{{ $AtndData['totalWorkingTime'] }}</td>
                                        <td>{{ $AtndData['created_at'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center;">No Record for Today</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
