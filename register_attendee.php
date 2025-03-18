<?php
include 'includes/header.php';
include 'db.php';
include 'includes/functions.php';

$event_id = isset($_GET['event_id']) ? $_GET['event_id'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ticket_number = generateTicketNumber(); // Generate a unique ticket number

    $sql = "INSERT INTO attendees (event_id, full_name, email, phone, ticket_number) 
            VALUES (:event_id, :full_name, :email, :phone, :ticket_number)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':event_id' => $event_id,
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':ticket_number' => $ticket_number
    ]);

    echo '<div class="message success fade-in pixel-borders">
            <p>Attendee registered successfully!</p>
            <p><strong>Ticket Number:</strong> ' . $ticket_number . '</p>
          </div>';
}

// Fetch events for dropdown
$events = $conn->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Register Attendee</h2>
<form method="POST" class="pixel-borders">
    <div>
        <label for="event_id">Event:</label>
        <select id="event_id" name="event_id" required>
            <?php foreach ($events as $event): ?>
                <option value="<?= $event['event_id'] ?>" <?= ($event_id == $event['event_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($event['event_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required>
    </div>
    
    <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
    </div>
    
    <button type="submit">Register</button>
</form>

<?php include 'includes/footer.php'; ?>

