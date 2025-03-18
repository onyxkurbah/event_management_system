<?php
include 'includes/header.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $ticket_price = $_POST['ticket_price'];

    // Update the event
    $sql = "UPDATE events 
            SET event_name = :event_name, event_description = :event_description, 
                event_date = :event_date, event_location = :event_location, 
                ticket_price = :ticket_price 
            WHERE event_id = :event_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':event_name' => $event_name,
        ':event_description' => $event_description,
        ':event_date' => $event_date,
        ':event_location' => $event_location,
        ':ticket_price' => $ticket_price,
        ':event_id' => $event_id
    ]);

    echo "<p>Event updated successfully!</p>";
}

// Fetch event details
$event_id = $_GET['event_id'];
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);
?>

<h2>Edit Event</h2>
<form method="POST">
    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
    <label>Event Name:</label>
    <input type="text" name="event_name" value="<?= $event['event_name'] ?>" required><br>
    <label>Description:</label>
    <textarea name="event_description" required><?= $event['event_description'] ?></textarea><br>
    <label>Date:</label>
    <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required><br>
    <label>Location:</label>
    <input type="text" name="event_location" value="<?= $event['event_location'] ?>" required><br>
    <label>Ticket Price:</label>
    <input type="number" step="0.01" name="ticket_price" value="<?= $event['ticket_price'] ?>" required><br>
    <button type="submit">Update Event</button>
</form>

<?php include 'includes/footer.php'; ?>