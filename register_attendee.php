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
    $ticket_number = generateTicketNumber();
    $qr_code_url = generateQRCode($ticket_number);

    $sql = "INSERT INTO attendees (event_id, full_name, email, phone, ticket_number, qr_code_url) 
            VALUES (:event_id, :full_name, :email, :phone, :ticket_number, :qr_code_url)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':event_id' => $event_id,
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':ticket_number' => $ticket_number,
        ':qr_code_url' => $qr_code_url
    ]);

    echo '<div class="mb-6 bg-success border-2 border-black p-4 animate-pixel">
            <p class="text-white">ATTENDEE REGISTERED SUCCESSFULLY!</p>
            <p class="text-white mt-2">TICKET NUMBER: ' . $ticket_number . '</p>
            <div class="mt-4 flex justify-center">
                <a href="view_ticket.php?attendee_id=' . $conn->lastInsertId() . '" 
                   class="pixel-button bg-secondary text-white px-4 py-2 border-2 border-black">
                    VIEW TICKET
                </a>
            </div>
          </div>';
}

// Fetch events for dropdown
$events = $conn->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-success pb-2">REGISTER ATTENDEE</h2>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="p-6">
        <form method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="event_id" class="block mb-2">SELECT EVENT</label>
                    <select id="event_id" name="event_id" required
                            class="w-full px-4 py-2 pixel-input">
                        <?php foreach ($events as $event): ?>
                            <option value="<?= $event['event_id'] ?>" <?= ($event_id == $event['event_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($event['event_name']) ?> - <?= date('M j, Y', strtotime($event['event_date'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label for="full_name" class="block mb-2">FULL NAME</label>
                    <input type="text" id="full_name" name="full_name" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="email" class="block mb-2">EMAIL</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="phone" class="block mb-2">PHONE</label>
                    <input type="text" id="phone" name="phone"
                           class="w-full px-4 py-2 pixel-input">
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-end">
                <a href="index.php" class="mr-4 text-dark hover:text-primary">CANCEL</a>
                <button type="submit" class="pixel-button bg-success text-white px-4 py-2 border-2 border-black">
                    REGISTER ATTENDEE
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>