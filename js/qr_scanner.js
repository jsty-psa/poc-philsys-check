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

const qrCodeSuccessCallback = (decodedText) => {
    html5QrCode.stop();
    scanning = !scanning;

    result = JSON.parse(decodedText);

    console.log(decodedText);

    console.log(result.subject);

    // console.log(result.subject.Suffix);
    // console.log(result.subject.lName);
    // console.log(result.subject.fName);
    // console.log(result.subject.mName);
    // console.log(result.subject.sex);
    // console.log(result.subject.BF);
    // console.log(result.subject.DOB);
    // console.log(result.subject.POB);
    // console.log(result.subject.PCN);

    const pcn = result.subject.PCN;
    const first_name = result.subject.fName;
    const middle_name = result.subject.mName;
    const last_name = result.subject.lName;
    const suffix = result.subject.Suffix == null ? "N/A" : result.subject.Suffix;
    const birth_date = convertDateFormat2(result.subject.DOB);

    var auth_type = $("#auth-type").val();

    if(auth_type == "egov") {
        eGovLivenessCheck(first_name, middle_name, last_name, suffix, birth_date);
        $("#qr-scanner").hide();
        $("#module-loading").show();
    }
    else if(auth_type == "nid") {
        biometricCapture("Finger", 1, 4501, pcn);
        $("#qr-scanner").hide();
        $("#module-loading").show();
    }
    else {
        alert("Select Authentication Type");
    }

};

const qrCodeErrorCallback = (errorMessage) => {
    console.log(`QR Code scan error: ${errorMessage}`);
};