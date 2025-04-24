<?php
include 'includes/header.php';
include 'db.php';

$attendee_id = $_GET['attendee_id'];
$attendee = $conn->query("SELECT a.*, e.event_name, e.event_date, e.event_location 
                         FROM attendees a 
                         JOIN events e ON a.event_id = e.event_id 
                         WHERE a.attendee_id = $attendee_id")->fetch(PDO::FETCH_ASSOC);
?>

<!-- Add print-specific stylesheet -->
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
            <div class="p-2 bg-white border-2 border-black shadow-pixel">
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
                    LOCATION
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
            <button onclick="printTicket()" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black">
                PRINT TICKET
            </button>
            
            <a href="view_attendees.php?event_id=<?= $attendee['event_id'] ?>" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black ml-2">
                BACK
            </a>
        </div>
    </div>
</div>

<script>
function printTicket() {
    window.print();
}
</script>

<?php include 'includes/footer.php'; ?>