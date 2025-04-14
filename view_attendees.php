<?php
include 'includes/header.php';
include 'db.php';

$event_id = $_GET['event_id'];
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);
$attendees = $conn->query("SELECT * FROM attendees WHERE event_id = $event_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-warning pb-2">ATTENDEES: <?= $event['event_name'] ?></h2>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="bg-warning border-b-2 border-black px-4 py-3">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                <span class="text-dark"><?= date('M j, Y', strtotime($event['event_date'])) ?></span>
                <span class="mx-2">•</span>
                <span class="text-dark"><?= $event['event_location'] ?></span>
            </div>
            <div class="mt-2 sm:mt-0">
                <span class="bg-dark text-white px-3 py-1 border-2 border-black">
                    <?= count($attendees) ?> ATTENDEES
                </span>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full pixel-table">
            <thead>
                <tr>
                    <th class="px-4 py-3 text-left">NAME</th>
                    <th class="px-4 py-3 text-left">EMAIL</th>
                    <th class="px-4 py-3 text-left">PHONE</th>
                    <th class="px-4 py-3 text-left">TICKET #</th>
                    <th class="px-4 py-3 text-left">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($attendees) > 0): ?>
                    <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <td class="px-4 py-3"><?= $attendee['full_name'] ?></td>
                            <td class="px-4 py-3"><?= $attendee['email'] ?></td>
                            <td class="px-4 py-3"><?= $attendee['phone'] ?></td>
                            <td class="px-4 py-3">
                                <span class="bg-primary text-white px-2 py-1 border-2 border-black"><?= $attendee['ticket_number'] ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="view_ticket.php?attendee_id=<?= $attendee['attendee_id'] ?>" 
                                       class="pixel-button bg-secondary text-white px-2 py-1 border-2 border-black text-[10px]">
                                        TICKET
                                    </a>
                                    <a href="delete_attendee.php?attendee_id=<?= $attendee['attendee_id'] ?>" 
                                       class="pixel-button bg-danger text-white px-2 py-1 border-2 border-black text-[10px]" 
                                       onclick="return confirm('Are you sure you want to delete this attendee?')">
                                        DELETE
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center">NO ATTENDEES REGISTERED YET.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="flex space-x-4">
    <a href="index.php" class="pixel-button bg-dark text-white px-4 py-2 border-2 border-black">
        BACK TO EVENTS
    </a>
    
    <a href="register_attendee.php?event_id=<?= $event_id ?>" class="pixel-button bg-success text-white px-4 py-2 border-2 border-black">
        REGISTER NEW ATTENDEE
    </a>
    
    <a href="verify_ticket.php" class="pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
        VERIFY TICKET
    </a>
</div>

<?php include 'includes/footer.php'; ?>