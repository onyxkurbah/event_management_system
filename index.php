<?php
include 'includes/header.php';
include 'includes/db.php';

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$event_id_search = isset($_GET['event_id_search']) ? $_GET['event_id_search'] : '';

// Fetch events based on search criteria
if (!empty($search)) {
    $events = $conn->query("SELECT * FROM events WHERE 
                          event_name LIKE '%$search%' OR 
                          event_description LIKE '%$search%' OR 
                          event_location LIKE '%$search%'
                          ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);
} elseif (!empty($event_id_search)) {
    $events = $conn->query("SELECT * FROM events WHERE event_id = '$event_id_search'")->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Order by upcoming events first
    $events = $conn->query("SELECT * FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);
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

<!-- Events Table -->
<div class="pixel-card animate-pixel mb-8">
    <div class="bg-primary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">
            <?= count($events) ?> EVENT<?= count($events) != 1 ? 'S' : '' ?> FOUND
            <?php if (!empty($search)): ?>
                FOR "<?= htmlspecialchars($search) ?>"
            <?php elseif (!empty($event_id_search)): ?>
                WITH ID #<?= htmlspecialchars($event_id_search) ?>
            <?php endif; ?>
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full pixel-table">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="px-4 py-3 text-left border-r-2 border-b-2 border-black">ID</th>
                    <th class="px-4 py-3 text-left border-r-2 border-b-2 border-black">EVENT NAME</th>
                    <th class="px-4 py-3 text-left border-r-2 border-b-2 border-black">DATE</th>
                    <th class="px-4 py-3 text-left border-r-2 border-b-2 border-black">LOCATION</th>
                    <th class="px-4 py-3 text-left border-r-2 border-b-2 border-black">PRICE</th>
                    <th class="px-4 py-3 text-left border-b-2 border-black">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($events) > 0): ?>
                    <?php 
                    $today = date('Y-m-d');
                    foreach ($events as $index => $event): 
                        $is_past = $event['event_date'] < $today;
                        $row_class = $index % 2 === 0 ? 'bg-gray-100' : 'bg-white';
                        if ($is_past) {
                            $row_class .= ' opacity-70';
                        }
                    ?>
                        <tr class="<?= $row_class ?> hover:bg-gray-200 transition-colors">
                            <td class="px-4 py-3 border-r-2 border-b-2 border-gray-300">#<?= $event['event_id'] ?></td>
                            <td class="px-4 py-3 border-r-2 border-b-2 border-gray-300 font-medium">
                                <?= $event['event_name'] ?>
                                <?php if ($is_past): ?>
                                    <span class="ml-2 text-xs bg-gray-500 text-white px-2 py-1 rounded">PAST</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 border-r-2 border-b-2 border-gray-300">
                                <div class="flex items-center">
                                    <span class="bg-primary text-white px-2 py-1 text-xs mr-2 border-2 border-black">
                                        <?= date('M j', strtotime($event['event_date'])) ?>
                                    </span>
                                    <?= date('Y', strtotime($event['event_date'])) ?>
                                </div>
                            </td>
                            <td class="px-4 py-3 border-r-2 border-b-2 border-gray-300"><?= $event['event_location'] ?></td>
                            <td class="px-4 py-3 border-r-2 border-b-2 border-gray-300 font-bold">₹<?= number_format($event['ticket_price'], 2) ?></td>
                            <td class="px-4 py-3 border-b-2 border-gray-300">
                                <div class="flex flex-wrap gap-1">
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
                        <td colspan="6" class="px-4 py-6 text-center border-b-2 border-gray-300 bg-gray-100">
                            <div class="flex flex-col items-center justify-center">
                                <div class="text-lg font-bold mb-2">NO EVENTS FOUND</div>
                                <div class="text-sm text-gray-500">Try adjusting your search criteria</div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>