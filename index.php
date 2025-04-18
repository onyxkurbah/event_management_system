<?php
include 'includes/header.php';
include 'db.php';

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$event_id_search = isset($_GET['event_id_search']) ? $_GET['event_id_search'] : '';

// Fetch events based on search criteria
if (!empty($search)) {
    $events = $conn->query("SELECT * FROM events WHERE 
                          event_name LIKE '%$search%' OR 
                          event_description LIKE '%$search%' OR 
                          event_location LIKE '%$search%'")->fetchAll(PDO::FETCH_ASSOC);
} elseif (!empty($event_id_search)) {
    $events = $conn->query("SELECT * FROM events WHERE event_id = '$event_id_search'")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $events = $conn->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="mb-8 flex justify-between items-center">
    <h2 class="text-xl text-dark border-b-4 border-primary pb-2">UPCOMING EVENTS</h2>
    <a href="create_event.php" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black">
        NEW EVENT
    </a>
</div>

<!-- Search Bar -->
<div class="pixel-card animate-pixel mb-4">
    <div class="bg-primary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">SEARCH EVENTS</h3>
    </div>
    <div class="p-4">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex">
                <input type="text" name="search" placeholder="Search by name, description or location" 
                       class="w-full px-4 py-2 pixel-input" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="ml-2 pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
                    SEARCH
                </button>
            </div>
            
            <div class="flex">
                <input type="number" name="event_id_search" placeholder="Search by event ID" 
                       class="w-full px-4 py-2 pixel-input" value="<?= htmlspecialchars($event_id_search) ?>">
                <button type="submit" class="ml-2 pixel-button bg-secondary text-white px-4 py-2 border-2 border-black">
                    FIND ID
                </button>
            </div>
        </form>
        
        <?php if (!empty($search) || !empty($event_id_search)): ?>
        <div class="mt-2 text-right">
            <a href="index.php" class="pixel-button bg-dark text-white px-4 py-2 border-2 border-black">
                CLEAR SEARCH
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="overflow-x-auto">
        <table class="w-full pixel-table">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">EVENT NAME</th>
                    <th class="px-4 py-3 text-left">DATE</th>
                    <th class="px-4 py-3 text-left">LOCATION</th>
                    <th class="px-4 py-3 text-left">PRICE</th>
                    <th class="px-4 py-3 text-left">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($events) > 0): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td class="px-4 py-3">#<?= $event['event_id'] ?></td>
                            <td class="px-4 py-3"><?= $event['event_name'] ?></td>
                            <td class="px-4 py-3"><?= date('M j, Y', strtotime($event['event_date'])) ?></td>
                            <td class="px-4 py-3"><?= $event['event_location'] ?></td>
                            <td class="px-4 py-3">₹<?= number_format($event['ticket_price'], 2) ?></td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="edit_event.php?event_id=<?= $event['event_id'] ?>" class="pixel-button bg-primary text-white px-2 py-1 border-2 border-black text-[10px]">
                                        EDIT
                                    </a>
                                    <a href="delete_event.php?event_id=<?= $event['event_id'] ?>" class="pixel-button bg-danger text-white px-2 py-1 border-2 border-black text-[10px]" onclick="return confirm('Are you sure you want to delete this event?')">
                                        DELETE
                                    </a>
                                    <a href="register_attendee.php?event_id=<?= $event['event_id'] ?>" class="pixel-button bg-success text-white px-2 py-1 border-2 border-black text-[10px]">
                                        REGISTER
                                    </a>
                                    <a href="view_attendees.php?event_id=<?= $event['event_id'] ?>" class="pixel-button bg-warning text-dark px-2 py-1 border-2 border-black text-[10px]">
                                        ATTENDEES
                                    </a>
                                    <a href="event_analytics.php?event_id=<?= $event['event_id'] ?>" class="pixel-button bg-secondary text-white px-2 py-1 border-2 border-black text-[10px]">
                                        STATS
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center">NO EVENTS FOUND.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>