<?php
include 'includes/header.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $ticket_price = $_POST['ticket_price'];

    $sql = "INSERT INTO events (event_name, event_description, event_date, event_location, ticket_price) 
            VALUES (:event_name, :event_description, :event_date, :event_location, :ticket_price)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':event_name' => $event_name,
        ':event_description' => $event_description,
        ':event_date' => $event_date,
        ':event_location' => $event_location,
        ':ticket_price' => $ticket_price
    ]);

    echo "<div class='message success fade-in pixel-borders'><p>Event created successfully!</p></div>";
}
?>

<h2>Create Event</h2>

<form method="POST" class="pixel-borders">
    <label>Event Name:</label>
    <input type="text" name="event_name" required>
    
    <label>Description:</label>
    <textarea name="event_description" required></textarea>
    
    <label>Date:</label>
    <input type="datetime-local" name="event_date" required>
    
    <label>Location:</label>
    <input type="text" name="event_location" required>
    
    <label>Ticket Price:</label>
    <input type="number" step="0.01" name="ticket_price" required>
    
    <button type="submit">Create Event</button>
</form>

<?php include 'includes/footer.php'; ?>

