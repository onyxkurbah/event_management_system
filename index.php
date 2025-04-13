<?php
include 'includes/header.php';
include 'db.php';

// Fetch all events
$events = $conn->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-8 flex justify-between items-center">
    <h2 class="text-xl text-dark border-b-4 border-primary pb-2">UPCOMING EVENTS</h2>
    <a href="create_event.php" class="pixel-button bg-accent text-white px-4 py-2 border-2 border-black">
        NEW EVENT
    </a>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="overflow-x-auto">
        <table class="w-full pixel-table">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left">EVENT NAME</th>
                    <th class="px-4 py-3 text-left">DATE</th>
                    <th class="px-4 py-3 text-left">LOCATION</th>
                    <th class="px-4 py-3 text-left">PRICE</th>
                    <th class="px-4 py-3 text-left">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
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
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>