<?php
function generateTicketNumber() {
    return uniqid('TICKET-');
}

function generateQRCode($data) {
    // Use QRCode Monkey API to generate QR codes
    $apiUrl = "https://api.qrserver.com/v1/create-qr-code/";
    
    // Build the URL with parameters
    $params = [
        'data' => $data,
        'size' => '200x200',
        'format' => 'png'
    ];
    
    $qrCodeUrl = $apiUrl . '?' . http_build_query($params);
    
    // Return the direct URL to the QR code image
    return $qrCodeUrl;
}
?>