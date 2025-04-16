<?php
include 'includes/header.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendee_id = $_POST['attendee_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];

    $sql = "UPDATE attendees 
            SET full_name = :full_name, email = :email, phone = :phone, age = :age
            WHERE attendee_id = :attendee_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':age' => $age,
        ':attendee_id' => $attendee_id
    ]);

    echo "<div class='mb-6 bg-success border-2 border-black p-4 animate-pixel'>
            <p class='text-white'>ATTENDEE UPDATED SUCCESSFULLY!</p>
          </div>";
    
    // Get event_id to redirect back to attendees list
    $event_id = $conn->query("SELECT event_id FROM attendees WHERE attendee_id = $attendee_id")->fetch(PDO::FETCH_ASSOC)['event_id'];
}

// Fetch attendee details
$attendee_id = $_GET['attendee_id'];
$attendee = $conn->query("SELECT * FROM attendees WHERE attendee_id = $attendee_id")->fetch(PDO::FETCH_ASSOC);
$event = $conn->query("SELECT * FROM events WHERE event_id = {$attendee['event_id']}")->fetch(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex items-center">
    <a href="view_attendees.php?event_id=<?= $attendee['event_id'] ?>" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-primary pb-2">EDIT ATTENDEE</h2>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="bg-primary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">EVENT: <?= $event['event_name'] ?></h3>
    </div>
    <div class="p-6">
        <form method="POST">
            <input type="hidden" name="attendee_id" value="<?= $attendee['attendee_id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="full_name" class="block mb-2">FULL NAME</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($attendee['full_name']) ?>" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="email" class="block mb-2">EMAIL</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($attendee['email']) ?>" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="phone" class="block mb-2">PHONE</label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($attendee['phone']) ?>"
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="age" class="block mb-2">AGE</label>
                    <input type="number" id="age" name="age" min="1" max="120" 
                           value="<?= isset($attendee['age']) ? htmlspecialchars($attendee['age']) : '' ?>"
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="ticket_number" class="block mb-2">TICKET NUMBER</label>
                    <input type="text" id="ticket_number" value="<?= htmlspecialchars($attendee['ticket_number']) ?>" disabled
                           class="w-full px-4 py-2 pixel-input bg-gray-100">
                    <p class="text-[10px] mt-1 text-dark">Ticket number cannot be edited</p>
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-end">
                <a href="view_attendees.php?event_id=<?= $attendee['event_id'] ?>" class="mr-4 text-dark hover:text-primary">CANCEL</a>
                <button type="submit" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
                    UPDATE ATTENDEE
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>