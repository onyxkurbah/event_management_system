<?php
include 'includes/header.php';
include 'includes/db.php';

$attendee_id = $_GET['attendee_id'];
$attendee = $conn->query("SELECT a.*, e.event_name, e.event_date, e.event_location 
                         FROM attendees a 
                         JOIN events e ON a.event_id = e.event_id 
                         WHERE a.attendee_id = $attendee_id")->fetch(PDO::FETCH_ASSOC);
?>

<style type="text/css" media="print">
    /* Hide everything except the ticket when printing */
    body * {
        visibility: hidden;
    }
    
    #printable-ticket, #printable-ticket * {
        visibility: visible;
    }
    
    #printable-ticket {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background-color: white !important;
        border: none !important;
        box-shadow: none !important;
        padding: 20px;
    }
    
    /* Remove unnecessary elements when printing */
    #printable-ticket .no-print {
        display: none !important;
    }
    
    /* Ensure clean print with black text */
    #printable-ticket .print-black {
        color: black !important;
    }
    
    /* Make sure borders print properly */
    #printable-ticket .print-border {
        border: 2px solid black !important;
    }
    
    /* Ensure QR code prints clearly */
    #printable-ticket img {
        max-width: 200px !important;
        height: auto !important;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }
    
    /* Improve layout for printing */
    #printable-ticket .grid {
        display: block !important;
        margin-bottom: 10px !important;
    }
    
    #printable-ticket .flex {
        display: flex !important;
        margin-bottom: 8px !important;
    }
    
    /* Add page break control */
    #printable-ticket {
        page-break-inside: avoid;
    }
    
    /* Ensure proper font rendering */
    #printable-ticket * {
        font-family: 'Courier New', monospace !important;
        font-size: 12pt !important;
    }
    
    #printable-ticket h3 {
        font-size: 16pt !important;
        font-weight: bold !important;
        margin-bottom: 15px !important;
    }
</style>

<!-- Add regular styling for the ticket -->
<style>
    /* Ticket animation */
    @keyframes ticketAppear {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    
    #printable-ticket {
        animation: ticketAppear 0.4s ease-out forwards;
    }
    
    /* Improved QR code styling */
    .qr-container {
        padding: 8px;
        background: repeating-linear-gradient(
            45deg,
            rgba(0, 0, 0, 0.05),
            rgba(0, 0, 0, 0.05) 10px,
            rgba(255, 255, 255, 0.5) 10px,
            rgba(255, 255, 255, 0.5) 20px
        );
    }
    
    /* Print button pulse animation */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .print-button {
        animation: pulse 2s infinite;
    }
    
    .print-button:hover {
        animation: none;
    }
</style>

<div class="mb-6 flex items-center">
    <a href="view_attendees.php?event_id=<?= $attendee['event_id'] ?>" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-accent pb-2">EVENT TICKET</h2>
</div>

<div class="pixel-card animate-pixel max-w-md mx-auto mb-8" id="printable-ticket">
    <!-- Ticket Header with Retro Design -->
    <div class="bg-accent border-b-2 border-black px-4 py-3 print-border relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="grid grid-cols-8 h-full">
                <?php for($i = 0; $i < 8; $i++): ?>
                    <div class="border-r-2 border-black opacity-10"></div>
                <?php endfor; ?>
            </div>
        </div>
        <h3 class="text-white text-center print-black relative z-10">EVENT TICKET</h3>
    </div>
    
    <!-- Ticket Body -->
    <div class="p-6 bg-white">
        <div class="text-center mb-4">
            <h2 class="text-lg border-b-2 border-black pb-2 inline-block"><?= $attendee['event_name'] ?></h2>
        </div>
        
        <!-- QR Code with Pixel Border -->
        <div class="flex justify-center mb-6">
            <div class="p-3 bg-white border-2 border-black shadow-pixel qr-container">
                <?php if ($attendee['qr_code_url']): ?>
                    <img src="<?= $attendee['qr_code_url'] ?>" alt="QR Code" class="border-2 border-black print-border w-48 h-48">
                <?php else: ?>
                    <?php 
                    // If QR code is missing, generate one on the fly
                    include 'includes/functions.php';
                    $qr_code_url = generateQRCode($attendee['ticket_number']);
                    // Update the database with the new QR code URL
                    $update_sql = "UPDATE attendees SET qr_code_url = :qr_code_url WHERE attendee_id = :attendee_id";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->execute([
                        ':qr_code_url' => $qr_code_url,
                        ':attendee_id' => $attendee_id
                    ]);
                    ?>
                    <img src="<?= $qr_code_url ?>" alt="QR Code" class="border-2 border-black print-border w-48 h-48">
                <?php endif; ?>
                <div class="text-center mt-2 text-xs no-print">SCAN TO VERIFY</div>
            </div>
        </div>
        
        <!-- Ticket Details with Pixel Art Style -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black w-24 text-center">
                    EVENT
                </div>
                <span class="font-bold"><?= $attendee['event_name'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black w-24 text-center">
                    DATE
                </div>
                <span><?= date('M j, Y g:i a', strtotime($attendee['event_date'])) ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black w-24 text-center text-sm">
                    VENUE
                </div>
                <span><?= $attendee['event_location'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black w-24 text-center">
                    NAME
                </div>
                <span class="font-bold"><?= $attendee['full_name'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-accent text-white px-2 py-1 border-2 border-black mr-2 print-border print-black w-24 text-center">
                    TICKET #
                </div>
                <span class="font-bold"><?= $attendee['ticket_number'] ?></span>
            </div>
        </div>
        
        <!-- Decorative Pixel Art Ticket Edge -->
        <div class="border-t-2 border-dashed border-black pt-4 mt-4 no-print">
            <div class="flex justify-between">
                <?php for($i = 0; $i < 10; $i++): ?>
                    <div class="w-4 h-4 bg-accent border border-black"></div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="text-center mt-6 no-print">
            <button onclick="printTicket()" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black print-button">
                <span class="flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    PRINT TICKET
                </span>
            </button>
            
            <a href="view_attendees.php?event_id=<?= $attendee['event_id'] ?>" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black ml-2">
                BACK
            </a>
        </div>
    </div>
</div>

<script>
function printTicket() {
    // Add a small delay to ensure styles are applied
    setTimeout(() => {
        window.print();
    }, 100);
}

// Add a message when printing is done or canceled
window.addEventListener('afterprint', function() {
    // Create a temporary message
    const message = document.createElement('div');
    message.className = 'pixel-card animate-pixel mt-4 mb-4 bg-success border-2 border-black p-4 no-print';
    message.innerHTML = `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-white border-2 border-black flex items-center justify-center mr-3">
                <span class="text-success text-lg">✓</span>
            </div>
            <span class="text-white">Ticket sent to printer!</span>
        </div>
    `;
    
    // Insert after the ticket
    const ticket = document.getElementById('printable-ticket');
    ticket.parentNode.insertBefore(message, ticket.nextSibling);
    
    // Remove after 3 seconds
    setTimeout(() => {
        message.style.animation = 'pixelFadeOut 0.3s ease-out forwards';
        setTimeout(() => {
            message.remove();
        }, 300);
    }, 3000);
});

// Add the fade out animation
document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('style[data-animation="pixelFadeOut"]')) {
        const style = document.createElement('style');
        style.setAttribute('data-animation', 'pixelFadeOut');
        style.textContent = `
            @keyframes pixelFadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-10px); }
            }
        `;
        document.head.appendChild(style);
    }
});
</script>

<?php include 'includes/footer.php'; ?>
