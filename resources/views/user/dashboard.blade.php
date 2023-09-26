<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Portal') }}
        </h2>
    </x-slot>
    <style>
        #divvideo {
            box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="mb-3">Today's Attendance</h1>
                    <hr />
                    <div class="mt-2 p-3">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne" id="mark-attendance">
                                        Mark Attendance
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample"
                                    style="visibility: inherit !important;">
                                    <div class="accordion-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-4" style="background:#fff;border-radius: 5px;"
                                                    id="divvideo">
                                                    <center>
                                                        <p class="login-box-msg"> <i
                                                                class="glyphicon glyphicon-qrcode"></i> Scan QR Code</p>
                                                        {{-- <p id="status-message">the is status message</p> --}}
                                                    </center>
                                                    <video id="preview" width="100%" height="50%"
                                                        style="border-radius:10px;"></video>
                                                    {{-- display message here on success of fail --}}
                                                    <div class="flex p-2 my-2" style="font-size: 10px">
                                                        <x-secondary-button id="open-scanner" class="mr-2">
                                                            {{ __('Open Scanner') }}
                                                        </x-secondary-button>
                                                        <x-secondary-button id="close-scanner">
                                                            {{ __('Close Scanner') }}
                                                        </x-secondary-button>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="CheckInOut.php" method="post" class="form-horizontal"
                                                        style="border-radius: 5px;padding:10px;background:#fff;"
                                                        id="divvideo">

                                                        <i class="glyphicon glyphicon-pencil"></i> <label>Enter Gate
                                                            ID</label>
                                                        <p id="time"></p>
                                                        <input type="text" name="studentID" id="text"
                                                            placeholder="Enter GateID" class="form-control" autofocus>
                                                    </form>
                                                    <div style="border-radius: 5px;padding:10px;background:#fff;"
                                                        id="divvideo">
                                                        <table id="example1" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <td>NAME</td>
                                                                    <td>Qr ID</td>
                                                                    <td>Working Hours</td>
                                                                    <td>LOGDATE</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {{-- {{ dd($Atnd_data) }} --}}
                                                                @forelse ($Atnd_data as $AtndData)
                                                                    <tr>
                                                                        <td>{{ ucfirst($AtndData['name']) ?? '' }}</td>
                                                                        <td>{{ $AtndData['attendance_ID'] ?? '' }}</td>
                                                                        <td>{{ $AtndData['totalWorkingTime'] ?? '' }}
                                                                        </td>
                                                                        <td>{{ $AtndData['create_at'] ?? '' }}</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="4" style="text-align: center;">
                                                                            No Record for Today</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // EventListner for opening the camera
        document.getElementById('open-scanner').addEventListener('click', markAttendance);
        // EventListner for closing the camera
        document.getElementById('close-scanner').addEventListener('click', closeCamera);

        // creating an instance of the class scanner for opening and closing the camera
        function createInstance() {
            // Select the video element for displaying the camera feed
            const videoElement = document.getElementById('preview');
            const scanner = new Instascan.Scanner({
                video: videoElement
            });
            return scanner;
        }
        // defining a variable to store the current active instance of the Scanner class. 
        var camScanner;

        // below function is for marking the attendance
        function markAttendance() {
            camScanner = createInstance();
            // Get available cameras and start the scanner
            Instascan.Camera.getCameras()
                .then(function(cameras) {
                    if (cameras.length > 0) {
                        // Start the scanner with the first available camera
                        camScanner.start(cameras[0]);
                        // get qrcode value from scan event
                        camScanner.addListener('scan', function(content) {
                            console.log(content);
                            alert("scanned " + content);
                            $.ajax({
                                type: "POST",
                                url: "/user/attendance",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    qrCodeNumber: content
                                },
                                success: function(response) {
                                    console.log(response);
                                    swal(response.head, response.message)
                                    // Swal.fire({
                                    //     icon: response.head,
                                    //     title: response.message,
                                    //     showConfirmButton: false,
                                    //     timer: 1500
                                    // })
                                }
                            });
                        });
                    } else {
                        alert('No cameras found');
                    }
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
        // Function to close the camera
        function closeCamera() {
            // Stop the scanner
            camScanner.stop();
        }
    </script>

</x-app-layout>
