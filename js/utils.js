const convertDateFormat = (dateString) => {
    const dateParts = dateString.split('/'); 
    const month = parseInt(dateParts[0], 10);
    const day = parseInt(dateParts[1], 10);
    const year = parseInt(dateParts[2], 10);

    const date = new Date(year, month - 1, day);

    const formattedDate = date.getFullYear() + '-' + 
                            String(date.getMonth() + 1).padStart(2, '0') + '-' +
                            String(date.getDate()).padStart(2, '0');

    return formattedDate;
}

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

        if (cameras.length > 0) {
            cameraSelect.value = "";
        }
    } catch (err) {
        console.error("Error getting cameras:", err);
    }
}