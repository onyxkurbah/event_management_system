<?php
include 'includes/header.php';
include 'db.php';

$event_id = $_GET['event_id'];

// Fetch event details
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);

// Fetch total attendees
$total_attendees = $conn->query("SELECT COUNT(*) as total FROM attendees WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC)['total'];

// Calculate total revenue correctly (ticket price × number of attendees)
$total_revenue = $event['ticket_price'] * $total_attendees;
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-secondary pb-2">STATS: <?= $event['event_name'] ?></h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="pixel-card animate-pixel">
        <div class="bg-primary border-b-2 border-black px-4 py-3">
            <h3 class="text-white">TOTAL ATTENDEES</h3>
        </div>
        <div class="p-6 flex items-center">
            <div class="text-4xl font-bold text-dark"><?= $total_attendees ?></div>
        </div>
    </div>
    
    <div class="pixel-card animate-pixel">
        <div class="bg-success border-b-2 border-black px-4 py-3">
            <h3 class="text-white">TOTAL REVENUE</h3>
        </div>
        <div class="p-6 flex items-center">
            <div class="text-4xl font-bold text-dark">₹<?= number_format($total_revenue, 2) ?></div>
        </div>
    </div>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="bg-secondary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">EVENT DETAILS</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <div class="font-bold mb-1">DATE & TIME</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            DATE
                        </div>
                        <span><?= date('M j, Y, g:i a', strtotime($event['event_date'])) ?></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="font-bold mb-1">LOCATION</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            PLACE
                        </div>
                        <span><?= $event['event_location'] ?></span>
                    </div>
                </div>
                
                <div>
                    <div class="font-bold mb-1">TICKET PRICE</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            PRICE
                        </div>
                        <span>₹<?= number_format($event['ticket_price'], 2) ?></span>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="font-bold mb-1">DESCRIPTION</div>
                <div class="bg-light border-2 border-black p-4 shadow-pixel-sm">
                    <p class="whitespace-pre-line"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex space-x-4">
    <a href="index.php" class="pixel-button bg-dark text-white px-4 py-2 border-2 border-black">
        BACK TO EVENTS
    </a>
    
    <a href="view_attendees.php?event_id=<?= $event_id ?>" class="pixel-button bg-warning text-dark px-4 py-2 border-2 border-black">
        VIEW ATTENDEES
    </a>
</div>

<?php include 'includes/footer.php'; ?>