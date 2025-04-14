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

<div class="pixel-card animate-pixel max-w-md mx-auto my-8" id="printable-ticket">
    <div class="bg-primary border-b-2 border-black px-4 py-3 print-border">
        <h3 class="text-white text-center print-black">EVENT TICKET</h3>
    </div>
    <div class="p-6">
        <div class="text-center mb-2">
            <h2 class="text-lg"><?= $attendee['event_name'] ?></h2>
        </div>
        
        <div class="flex justify-center mb-4">
            <?php if ($attendee['qr_code_url']): ?>
                <img src="<?= $attendee['qr_code_url'] ?>" alt="QR Code" class="border-2 border-black print-border">
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
                <img src="<?= $qr_code_url ?>" alt="QR Code" class="border-2 border-black print-border">
            <?php endif; ?>
        </div>
        
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black">
                    EVENT
                </div>
                <span><?= $attendee['event_name'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black">
                    DATE
                </div>
                <span><?= date('M j, Y g:i a', strtotime($attendee['event_date'])) ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black">
                    LOCATION
                </div>
                <span><?= $attendee['event_location'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black">
                    NAME
                </div>
                <span><?= $attendee['full_name'] ?></span>
            </div>
            
            <div class="flex items-center">
                <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2 print-border print-black">
                    TICKET #
                </div>
                <span><?= $attendee['ticket_number'] ?></span>
            </div>
        </div>
        
        <div class="text-center no-print">
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