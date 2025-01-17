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
        <style>
            .circle {
            width: 200px; /* Set the desired width */
            height: 200px; /* Set the same height for a perfect circle */
            border-radius: 50%; /* Makes the image circular */
            overflow: hidden; /* Ensures overflow is hidden */
            display: flex; /* Center content if needed */
            justify-content: center;
            align-items: center;
            }

            .circle img {
                width: 100%; /* Makes the image responsive */
                height: auto; /* Maintains aspect ratio */
                object-fit: cover; /* Ensures the image covers the circle */
            }

            .rotating {
                animation: rotate 4s linear infinite; /* Rotate continuously */
            }

            @keyframes rotate {
                from {
                    transform: rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>
    <body id="verify_wrapper">
        <div class="modal fade bd-example-modal-lg" id="modal-result" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Image Result</h5>
                        <i class="fa-solid fa-xmark" style="width: 3%; height: 3%;" class="close" data-bs-dismiss="modal" aria-label="Close"></i>
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
        <div class="modal fade bd-example-modal-lg" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Error Result</h5>
                        <i class="fa-solid fa-xmark" style="width: 3%; height: 3%;" class="close" data-bs-dismiss="modal" aria-label="Close"></i>
                    </div>
                    <div class="modal-body">
                        <div id="error-message">Test</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="layoutVerification">
            <div id="layoutVerification_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"> 
                                        <div class="row">
                                            <div class="col-12"> 
                                                <img src="assets/img/philsys-logo.png" width="150px" />   
                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="card-body">
                                        <div class="small mb-3 text-muted">Scan your National ID for verification.</div>
                                        <form>
                                            <div class="form-floating mb-3"> 
                                                <div class="container"> 
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div id="qr-scanner">
                                                                <center>
                                                                    <div id="qr-reader"></div><br />
                                                                    <select id="camera-select"></select><br /><br />
                                                                    <select id="auth-type" name="auth-type">
                                                                        <option value="">--- Select Auth Type ---</option>
                                                                        <option value="egov">eGov App</option>
                                                                        <option value="nid">National ID</option>
                                                                    </select>
                                                                </center>
                                                            </div>
                                                            <div id="module-loading" style="display: none;">
                                                                <center>
                                                                    <!-- <img src="assets/gif/tiger-loading.gif"/> <br/> -->
                                                                    <span style="font-size: 48px">Verifying</span><br/>
                                                                    <!-- <img src="assets/gif/bar-loading.gif" width="250px" height="15px"/> -->
                                                                    <img class="circle rotating" src="assets/img/rene.png">
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div> 
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">Copyright DCRPID &copy; 2024</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div> 
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://liveness.everify.gov.ph/js/everify-liveness-sdk.min.js"></script>
        <script type="text/javascript" src="./js/utils.js"></script>
        <script type="text/javascript" src="./js/qr_scanner.js"></script>
        <script type="text/javascript" src="./js/apis.js"></script>
        <script type="text/javascript" src="./js/index.js"></script>
    </body>
</html>
