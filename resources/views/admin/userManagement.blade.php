<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex-custom items-center-custom justify-between-custom mb-2-custom">
                        <h1 class="mb-3">User List</h1>
                        <x-primary-button class="ml-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            {{ __('Add User') }}
                        </x-primary-button>
                    </div>
                    <hr />
                    <div class="mt-2 p-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First</th>
                                    <th scope="col">email</th>
                                    <th scope="col">Qr Code</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($userDatas as $userData)
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>{{ $userData->name }}</td>
                                        <td>{{ $userData->email }}</td>
                                        <td>{{ $userData->qrcode }}</td>
                                        <td>{{ $userData->role }}</td>
                                        <td>
                                            <i class="fas fa-user-edit mr-3 c-p" title="Update User Profile"></i>
                                            <i class="fas fa-trash c-p mr-3" title="Delete User"></i>
                                            <i class="fas fa-qrcode c-p qrnumber generateQRCode"
                                                data-qrnumber="{{ $userData->qrcode ?? 'No Code Found' }}"
                                                title="Download QR Code"></i>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Content to display when the data set is empty --}}
                                    <p>No items found.</p>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="qrcode" style="display: none"></div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="registration-form" action="{{ route('admin.register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Create') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        $(document).ready(function() {
            $('.qrnumber').click(function() {
                // Send an AJAX request to get the QR code SVG
                $.ajax({
                    url: '/generate-qr-code', // Make sure the URL matches your Laravel route
                    type: 'GET',
                    success: function(response) {
                        // Create a temporary hidden <div> to render the SVG
                        var svgDiv = document.createElement('div');
                        svgDiv.innerHTML = response;

                        // Create an <img> element
                        var img = new Image();

                        // Convert the SVG to a data URI (PNG)
                        img.src = 'data:image/svg+xml;base64,' + btoa(svgDiv.innerHTML);

                        // Create a temporary <a> element to initiate the download
                        var a = document.createElement('a');
                        a.href = img.src;
                        a.download = 'qrcode.png'; // You can specify the download filename

                        // Trigger a click event to start the download
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    },
                    error: function(error) {
                        console.error('Error generating QR code:', error);
                    }
                });
            });
        });
    </script> --}}
    @if (session('success'))
        <script>
            swal({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error",
                button: "OK",
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const urlInput = $('#urlInput');
            const generateButton = $('.generateQRCode');
            const qrcodeDiv = $('#qrcode');
            const refreshQRcode = location;

            generateButton.on('click', function() {
                // alert($(this).data('qrnumber'));
                const url = $(this).data('qrnumber').toString()

                if (url) {
                    // Create a QR code instance
                    const qrcode = new QRCode(qrcodeDiv[0], {
                        text: url,
                        width: 350,
                        height: 350,
                    });

                    // Trigger download when the button is clicked
                    const downloadButton = $('<a>').attr({
                        href: qrcodeDiv.children('canvas').get(0).toDataURL('image/png'),
                        download: 'qrcode.png',
                    }).css('display', 'none');

                    $('body').append(downloadButton);
                    downloadButton[0].click();
                    downloadButton.remove();
                    refreshQRcode.reload();
                } else {
                    alert('Please enter a URL before generating the QR code.');
                }
            });

        });
    </script>


</x-app-layout>
