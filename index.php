<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>QR Code Scanner</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="icon" type="image/x-icon" href="images/psa-philsys-logo.ico">
    </head>
    <body class="bg-light">
        <div class="modal fade bd-example-modal-lg" id="modal-camera" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Capture National ID QR Code</h5>
                        <i class="fa-solid fa-xmark" style="width: 3%; height: 3%;" class="close" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <center>
                            <div id="qr-reader"></div>
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade bd-example-modal-lg" id="modal-result" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image Result</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <img id="image-result" width="528" height="680" style="border:1px solid"/>
                        </center>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="reg_wrapper">  
            <div id="layoutAuthentication">
                <div id="layoutAuthentication_content">
                    <main>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-7">
                                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-md-8"> 
                                                </div> 
                                                <div class="col-md-4">  
                                                    <div style="text-align: right"><img src="assets/img/philsys-logo.png" width="150px" /></div>
                                                </div>
                                            </div> 
                                            <!-- <h3 class="text-center font-weight-light my-4">PRO Check Registration</h3> -->
                                        </div>
                                        <div class="card-body"> 
                                            <div class="small mb-3 text-muted">Select a camera to scan PhilSys QR Code</div>
                                            <div class="row">   
                                                <div class="col-12">   
                                                    <label for="inputCamera">Select Camera</label>
                                                    <select class="form-control" id="camera-select"></select>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div> 
            </div> 
        </div> 
    </body>
    <script>
        scanning = false;

        const qrCodeSuccessCallback = (decodedText) => {
            html5QrCode.stop();
            scanning = !scanning;

            $("#modal-camera").modal("hide");

            result = JSON.parse(decodedText);

            individual_id = result.pcn;
            individual_id_type = "VID";
            
            input_bio = "off";
            input_otp = "off";
            input_demo = "on";
            input_ekyc = "on";

            input_otp_value = "";
            input_demo_value = "{\"age\": 0}";

            $.ajax({
                method: "POST",
                url: "http://10.10.20.121:8000/authenticate/",
                data: {
                    individual_id : individual_id,
                    individual_id_type : individual_id_type,
                    input_bio : input_bio,
                    input_otp : input_otp,
                    input_demo : input_demo,
                    input_ekyc : input_ekyc,
                    input_otp_value : input_otp_value,
                    input_demo_value : input_demo_value,
                },
                success: function(result){
                    /* 
                    
                    Available Demographic Details:

                        - age
                        - bloodType_eng
                        - dob
                        - firstName_eng
                        - fullAddress_eng
                        - gender_eng
                        - lastName_eng
                        - maritalStatus_eng
                        - middleName_eng
                        - name_eng
                        - permanentAddressLine1_eng
                        - permanentAddress_eng
                        - permanentBarangay_eng
                        - permanentCity_eng
                        - permanentCountry_eng
                        - permanentFullAddress_eng
                        - permanentProvince_eng
                        - permanentZipcode_eng
                        - photo
                        - pobCity_eng
                        - pobCountry_eng
                        - pobProvince_eng
                        - postalCode
                        - presentAddressLine1_eng
                        - presentAddress_eng
                        - presentBarangay_eng
                        - presentCity_eng
                        - presentCountry_eng
                        - presentProvince_eng
                        - suffix_eng

                    */

                    const imgElement = document.getElementById('image-result');
                    imgElement.src = "data:image/jpeg;base64," + result.identity.photo;

                    $("#modal-result").modal("show");
                }
            });
            // Optionally, stop scanning after a successful read
        };

        const qrCodeErrorCallback = (errorMessage) => {
            console.log(`QR Code scan error: ${errorMessage}`);
        };

        const html5QrCode = new Html5Qrcode("qr-reader");

        // Get available cameras
        async function getCameras() {
            try {
                const cameras = await Html5Qrcode.getCameras();
                const cameraSelect = document.getElementById('camera-select');
                
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "--- SELECT CAMERA ---";
                cameraSelect.appendChild(option);

                cameras.forEach(camera => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.textContent = camera.label || `Camera ${camera.id}`;
                    cameraSelect.appendChild(option);
                });

                // Automatically select the first camera
                if (cameras.length > 0) {
                    cameraSelect.value = "";
                }
            } catch (err) {
                console.error("Error getting cameras:", err);
            }
        }

        const start_qr_scanner = () => {
            const selectedCameraId = document.getElementById('camera-select').value;
            html5QrCode.start(
                { deviceId: { exact: selectedCameraId } },
                { fps: 10, qrbox: { width: 450, height: 450 } },
                qrCodeSuccessCallback,
                qrCodeErrorCallback
            ).then(() => {
                scanning = !scanning;
            }).catch(err => {
                console.error("Unable to start scanning:", err);
            });
        }

        const start_camera = () => {
            if(scanning) {
                html5QrCode.stop().then(() => start_qr_scanner());
                scanning = !scanning
            }
            else {
                start_qr_scanner();
            }
            
            const cameraSelect = document.getElementById('camera-select');
            cameraSelect.value = "";

            $('#modal-camera').modal('show'); 
        }

        document.getElementById('camera-select').addEventListener('change', () => {
            start_camera();
            
            const cameraSelect = document.getElementById('camera-select');
            cameraSelect.value = "";
        });

        // Load available cameras on page load
        window.onload = getCameras;
    </script>
</html>
