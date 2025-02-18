const biometricCapture = async (type, count, port, pcn) => {
    const date = new Date().toISOString();

    await fetch("http://127.0.0.1:" + port + "/capture", {
        method: "CAPTURE",
        headers: {
            "content-type": "application/json",
            "accept": "*/*",
        },
        body: JSON.stringify({
            "env": "Staging",
            "purpose": "Auth",
            "specVersion": "0.9.5.1.5",
            "timeout": "300000",
            "captureTime": date,
            "domainUri": "https://api.apps-external.uat2.phylsys.gov.ph",
            "transactionId": "1234567890",
            "bio": [
                {
                    "type": type,
                    "count": count,
                    "bioSubType": [
                        "UNKNOWN"
                    ],
                    "requestedScore": "60",
                    "deviceId": "2147000102",
                    "deviceSubId": "1",
                    "previousHash": ""
                }
            ],
            "customOpts": [
                {
                    "name": "name1",
                    "value": "value1"
                }
            ]
        })
    })
    .then(response => response.json())
    .then(data => {
        data = data.biometrics;
        var result = JSON.stringify(data);

        authenticate(pcn, result);
    })
    .catch(error => {
        console.error("Error:", error);
    });
} 

const authenticate = (pcn, biometric_input) => {  
    fetch("http://10.10.20.121:8000/authenticate/", {
        method: "POST",
        headers: {
            "content-type": "application/json",
            "accept": "*/*",
        },
        body: JSON.stringify({
            "individual_id": pcn,
            "individual_id_type": "VID",
            
            "input_bio": "on",
            "input_otp": "off",
            "input_demo": "off",
            "input_ekyc": "on",
    
            "input_otp_value": null,
            "input_demo_value": null,
            "input_bio_value": biometric_input,
        })
    })
    .then(response => response.json())
    .then(data => {
        
        console.log(data.identity);

        const imgElement = document.getElementById('image-result');
        imgElement.src = "data:image/jpeg;base64," + data.identity.photo;

        $("#modal-result").modal("show");
        
        $("#qr-scanner").show();
        $("#module-loading").hide();
    })
    .catch(error => {
        console.log(error);
        $("#error-message").html(`Error connecting to the server. Please try again.`);
        $("#modal-error").modal("show");
        
        $("#qr-scanner").show();
        $("#module-loading").hide();
    });
}

const eGovLivenessCheck = (first_name, middle_name, last_name, suffix, birth_date) => {
    window.eKYC().start({
        pubKey: "eyJpdiI6IlNGVTVMMjdFNG4rbEpzOG9YTTRsUlE9PSIsInZhbHVlIjoieWJHRXFaOUJhSVZTYkVtdkNxcndPZz09IiwibWFjIjoiNmY3OTNhNmE3YzBjMGUxY2IyYjQ2YjNjZTllZDkxNzIwNDU2NGFmOWMyNzExZmI4ODc4NDM1YjNjYTUxMDIwYSIsInRhZyI6IiJ9"
    }).then((data) => {
        face_liveness_session_id = data.result.session_id;
        
        fetch("https://ws.everify.gov.ph/api/query", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer 1012|HRwFGOx4wlK48DKPCuFwaaFOlL2SSzYvueoS36EN8016a948'
            },
            body: JSON.stringify({
                "first_name": first_name,
                "middle_name": middle_name,
                "last_name": last_name,
                "suffix": suffix,
                "birth_date": birth_date,
                "face_liveness_session_id": face_liveness_session_id,

            })
        })
        .then(response => response.json())
        .then(data => {    
            if(data.meta.result_grade != 1) {
                alert("Authentication Failed");
            }
            else {
                alert("Authentication Success");
            }

            console.log(data);
            
            $("#qr-scanner").show();
            $("#module-loading").hide();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }).catch((err) => {
        return err;
    });
}