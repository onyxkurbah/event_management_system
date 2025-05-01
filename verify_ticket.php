<?php
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_number = $_POST['ticket_number'];
    $attendee = $conn->query("SELECT a.*, e.event_name, e.event_date, e.event_location 
                             FROM attendees a 
                             JOIN events e ON a.event_id = e.event_id 
                             WHERE a.ticket_number = '$ticket_number'")
                     ->fetch(PDO::FETCH_ASSOC);

    if ($attendee) {
        echo '<div class="pixel-card animate-pixel mb-6 border-2 border-black p-4" style="background-color: #00b894;">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-white border-2 border-black flex items-center justify-center mr-3" style="box-shadow: 2px 2px 0 0 #000;">
                        <span style="color: #00b894; font-size: 1.25rem;">✓</span>
                    </div>
                    <h3 style="color: #ffffff; font-size: 1.25rem;">TICKET VERIFIED!</h3>
                </div>
                <div class="mt-4">
                    <div class="flex items-center mb-3">
                        <div style="min-width: 100px; background-color: white; border: 2px solid black; padding: 4px 8px; text-align: center; box-shadow: 2px 2px 0 0 #000; margin-right: 10px; font-size: 12px;">
                            EVENT
                        </div>
                        <span style="color: #ffffff; font-weight: bold; flex: 1;">'.$attendee['event_name'].'</span>
                    </div>
                    <div class="flex items-center mb-3">
                        <div style="min-width: 100px; background-color: white; border: 2px solid black; padding: 4px 8px; text-align: center; box-shadow: 2px 2px 0 0 #000; margin-right: 10px; font-size: 12px;">
                            ATTENDEE
                        </div>
                        <span style="color: #ffffff; font-weight: bold; flex: 1;">'.$attendee['full_name'].'</span>
                    </div>
                    <div class="flex items-center mb-3">
                        <div style="min-width: 100px; background-color: white; border: 2px solid black; padding: 4px 8px; text-align: center; box-shadow: 2px 2px 0 0 #000; margin-right: 10px; font-size: 12px;">
                            DATE
                        </div>
                        <span style="color: #ffffff; flex: 1;">'.date('M j, Y g:i a', strtotime($attendee['event_date'])).'</span>
                    </div>
                    <div class="flex items-center mb-3">
                        <div style="min-width: 100px; background-color: white; border: 2px solid black; padding: 4px 8px; text-align: center; box-shadow: 2px 2px 0 0 #000; margin-right: 10px; font-size: 12px;">
                            LOCATION
                        </div>
                        <span style="color: #ffffff; flex: 1;">'.$attendee['event_location'].'</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <button onclick="closeVerification()" class="pixel-button bg-white border-2 border-black px-4 py-2" style="color: #00b894;">
                        OK
                    </button>
                </div>
            </div>';
    } else {
        echo '<div class="pixel-card animate-pixel mb-6 border-2 border-black p-4" style="background-color: #ff7675;">
                <div class="flex items-center mb-2">
                    <div class="w-10 h-10 bg-white border-2 border-black flex items-center justify-center mr-3" style="box-shadow: 2px 2px 0 0 #000;">
                        <span style="color: #ff7675; font-size: 1.25rem;">✗</span>
                    </div>
                    <h3 style="color: #ffffff; font-size: 1.25rem;">TICKET NOT FOUND!</h3>
                </div>
                <p style="color: #ffffff; margin-top: 0.5rem;">The ticket number you entered could not be verified. Please check and try again.</p>
                <div class="mt-4 text-center">
                    <button onclick="closeVerification()" class="pixel-button bg-white border-2 border-black px-4 py-2" style="color: #ff7675;">
                        OK
                    </button>
                </div>
            </div>';
    }
}
?>
<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-primary pb-2">VERIFY TICKET</h2>
</div>

<div class="pixel-card animate-pixel max-w-md mx-auto mb-8">
    <div class="bg-primary border-b-2 border-black px-4 py-3 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="grid grid-cols-10 h-full">
                <?php for($i = 0; $i < 10; $i++): ?>
                    <div class="border-r-2 border-black opacity-10"></div>
                <?php endfor; ?>
            </div>
        </div>
        <h3 class="text-white text-center relative z-10">VERIFY TICKET</h3>
    </div>
    
    <div class="p-6 bg-white">
        <!-- Manual Verification Form -->
        <form method="POST" id="verify-form">
            <div class="mb-6">
                <label for="ticket_number" class="block mb-2 flex items-center">
                    <div class="w-6 h-6 bg-primary border-2 border-black flex items-center justify-center mr-2">
                        <span class="text-white">#</span>
                    </div>
                    TICKET NUMBER
                </label>
                <input type="text" id="ticket_number" name="ticket_number" required
                       class="w-full px-4 py-2 pixel-input" placeholder="Enter ticket number">
            </div>
            
            <button type="submit" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black w-full">
                VERIFY
            </button>
        </form>
        
        <!-- Divider with Pixel Art Style -->
        <div class="my-6 relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t-2 border-black border-dashed"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white px-4 text-dark">OR</span>
            </div>
        </div>
        
        <!-- QR Code Reader Button -->
        <div class="text-center">
            <button id="scanButton" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black w-full flex items-center justify-center">
                <div class="w-6 h-6 bg-white border-2 border-black flex items-center justify-center mr-2">
                    <span class="text-accent">◎</span>
                </div>
                <span id="scanButtonText">SCAN QR CODE</span>
            </button>
        </div>
        
        <!-- QR Scanner Container (Hidden by default) -->
        <div id="qr-reader-container" class="mt-6 hidden">
            <div class="pixel-card animate-pixel mb-4 bg-warning border-2 border-black p-4">
                <p class="text-dark text-center">POINT CAMERA AT QR CODE</p>
            </div>
            
            <div id="qr-reader" class="border-4 border-black shadow-pixel"></div>
            
            <div class="mt-4">
                <button id="cancelScan" class="pixel-button bg-danger text-white px-4 py-2 border-2 border-black w-full">
                    CANCEL SCAN
                </button>
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Scanner Script -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scanButton = document.getElementById('scanButton');
    const scanButtonText = document.getElementById('scanButtonText');
    const qrReaderContainer = document.getElementById('qr-reader-container');
    const qrReader = document.getElementById('qr-reader');
    const cancelScanButton = document.getElementById('cancelScan');
    const ticketNumberInput = document.getElementById('ticket_number');
    const verifyForm = document.getElementById('verify-form');
    
    let html5QrCode;
    
    // Style the QR reader to match the pixel art theme
    function applyQrReaderStyles() {
        // Find the video element inside qr-reader and style it
        const videoElement = qrReader.querySelector('video');
        if (videoElement) {
            videoElement.style.width = '100%';
            videoElement.style.border = '4px solid #000';
            videoElement.style.imageRendering = 'pixelated';
        }
        
        // Style the scan region
        const scanRegion = qrReader.querySelector('.scan-region-highlight');
        if (scanRegion) {
            scanRegion.style.border = '4px solid #5a65f1';
            scanRegion.style.borderRadius = '0';
        }
        
        // Style any buttons inside the QR reader
        const buttons = qrReader.querySelectorAll('button');
        buttons.forEach(button => {
            button.style.border = '2px solid #000';
            button.style.backgroundColor = '#5a65f1';
            button.style.color = 'white';
            button.style.padding = '4px 8px';
            button.style.margin = '4px';
            button.style.fontFamily = '"Press Start 2P", cursive';
            button.style.fontSize = '10px';
        });
    }
    
    scanButton.addEventListener('click', function() {
        // Show QR reader
        qrReaderContainer.classList.remove('hidden');
        scanButton.classList.add('hidden');
        
        // Initialize QR scanner
        html5QrCode = new Html5Qrcode("qr-reader");
        
        const qrCodeSuccessCallback = (decodedText) => {
            // Extract the ticket number from the QR code text
            // The QR code might contain the full ticket number or a URL with the ticket number
            let ticketNumber = decodedText;
            
            // If it's a URL, try to extract the ticket number
            if (decodedText.includes('TICKET-')) {
                const match = decodedText.match(/TICKET-[a-f0-9]+/);
                if (match) {
                    ticketNumber = match[0];
                }
            }
            
            // Stop scanning
            html5QrCode.stop().then(() => {
                // Fill the ticket number input with the extracted ticket number
                ticketNumberInput.value = ticketNumber;
                
                // Reset UI
                qrReaderContainer.classList.add('hidden');
                scanButton.classList.remove('hidden');
                
                // Auto-submit the form
                verifyForm.submit();
            }).catch((err) => {
                console.error("Failed to stop QR Code scanner", err);
            });
        };
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        // Start scanning
        html5QrCode.start(
            { facingMode: "environment" }, // Use back camera if available
            config, 
            qrCodeSuccessCallback,
            (errorMessage) => {
                // Handle scan errors silently
                console.log(errorMessage);
            }
        ).then(() => {
            // Apply styles after the scanner has initialized
            setTimeout(applyQrReaderStyles, 500);
        });
    });
    
    // Cancel scan button handler
    cancelScanButton.addEventListener('click', function() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                qrReaderContainer.classList.add('hidden');
                scanButton.classList.remove('hidden');
            }).catch((err) => {
                console.error("Failed to stop QR Code scanner", err);
            });
        }
    });
});
</script>

<script>
function closeVerification() {
    // Find the verification result div and hide it with animation
    const verificationResult = document.querySelector('.pixel-card.animate-pixel.mb-6');
    if (verificationResult) {
        verificationResult.style.animation = 'pixelFadeOut 0.3s ease-out forwards';
        setTimeout(() => {
            verificationResult.style.display = 'none';
        }, 300);
    }
}

// Add the fade out animation to the stylesheet
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pixelFadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }
    `;
    document.head.appendChild(style);
});
</script>
<?php include 'includes/footer.php'; ?>
