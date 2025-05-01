<?php
include 'includes/header.php';
include 'includes/db.php';

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

    echo "<div class='mb-6 bg-success border-2 border-black p-4 animate-pixel'>
            <p class='text-white'>EVENT UPDATED SUCCESSFULLY!</p>
          </div>";
}

// Fetch event details
$event_id = $_GET['event_id'];
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-primary pb-2">EDIT EVENT</h2>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="p-6">
        <form method="POST">
            <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label for="event_name" class="block mb-2">EVENT NAME</label>
                    <input type="text" id="event_name" name="event_name" value="<?= $event['event_name'] ?>" required 
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div class="col-span-2">
                    <label for="event_description" class="block mb-2">DESCRIPTION</label>
                    <textarea id="event_description" name="event_description" rows="4" required
                              class="w-full px-4 py-2 pixel-input"><?= $event['event_description'] ?></textarea>
                </div>
                
                <div>
                    <label for="event_date" class="block mb-2">DATE & TIME</label>
                    <input type="datetime-local" id="event_date" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="event_location" class="block mb-2">LOCATION</label>
                    <input type="text" id="event_location" name="event_location" value="<?= $event['event_location'] ?>" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
                
                <div>
                    <label for="ticket_price" class="block mb-2">TICKET PRICE (₹)</label>
                    <input type="number" id="ticket_price" name="ticket_price" step="0.01" value="<?= $event['ticket_price'] ?>" required
                           class="w-full px-4 py-2 pixel-input">
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-end">
                <a href="index.php" class="mr-4 text-dark hover:text-primary">CANCEL</a>
                <button type="submit" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
                    UPDATE EVENT
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>