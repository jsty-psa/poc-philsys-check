const html5QrCode = new Html5Qrcode("qr-reader");
var scanning = false;

document.getElementById('camera-select').addEventListener('change', () => {
    start_camera();
    
    const cameraSelect = document.getElementById('camera-select');
    cameraSelect.value = "";
});

window.onload = getCameras;