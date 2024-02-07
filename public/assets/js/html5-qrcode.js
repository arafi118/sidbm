var startScan, scanningEnabled = true;
var html5QrcodeScanner;

$(document).on('click', '#btnScanKartu', function (e) {
    e.preventDefault()

    scanningEnabled = true
    $('#scanQrCode').modal('show')

    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        false);

    html5QrcodeScanner.render((result) => {
        if (scanningEnabled) {
            if (result.length >= 10) {
                Swal.fire('Error', 'Sepertinya kartu angsuran yang anda punya bukan yang terbaru. Silahkan cetak ulang kartu angsuran anda', 'error')
            } else {
                window.location.href = '/transaksi/jurnal_angsuran?pinkel=' + result
            }

            scanningEnabled = false
        }
    });

    $('#html5-qrcode-button-camera-start').hide()
    $('#html5-qrcode-button-camera-stop').hide()
    $('#html5-qrcode-anchor-scan-type-change').hide()

    $('#html5-qrcode-button-camera-start').trigger('click')
    
    startScan = true
    $('#stopScan').html('Stop')
})

$(document).on('click','#stopScan', function (e) {
    e.preventDefault()

    if (startScan) {
        $(this).html('Scan Ulang')
        $('#html5-qrcode-button-camera-stop').trigger('click')
    } else {
        scanningEnabled = true;
        $(this).html('Stop')
        $('#html5-qrcode-button-camera-start').trigger('click')
    }

    startScan = !startScan
})

$(document).on('click','#scanQrCodeClose', function (e) {
    $('#scanQrCode').modal('hide')
    $('#html5-qrcode-button-camera-stop').trigger('click')
    $('#stopScan').html('Stop')
})

function onScanSuccess(decodedText, decodedResult) {
    console.log(`Code matched = ${decodedText}`, decodedResult);
}

function onScanFailure(error) {
    console.warn(`Code scan error = ${error}`);
}