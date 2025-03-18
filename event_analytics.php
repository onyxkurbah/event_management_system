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

<h2>Analytics for: <?= $event['event_name'] ?></h2>

<div class="grid">
    <div class="analytics-card pixel-borders">
        <div class="analytics-label">Total Attendees</div>
        <div class="analytics-value"><?= $total_attendees ?></div>
    </div>
    
    <div class="analytics-card pixel-borders">
        <div class="analytics-label">Total Revenue</div>
        <div class="analytics-value"><span class="rupee-symbol">₹</span><?= number_format($total_revenue, 2) ?></div>
    </div>
</div>

<div class="card pixel-borders">
    <h3>Event Details</h3>
    <p><strong><span class="pixel-icon icon-calendar"></span>Date:</strong> <?= date('F j, Y, g:i a', strtotime($event['event_date'])) ?></p>
    <p><strong><span class="pixel-icon icon-location"></span>Location:</strong> <?= $event['event_location'] ?></p>
    <p><strong><span class="pixel-icon icon-ticket"></span>Ticket Price:</strong> <span class="rupee-symbol">₹</span><?= number_format($event['ticket_price'], 2) ?></p>
</div>

<a href="index.php" class="button">Back to Events</a>

<?php include 'includes/footer.php'; ?>