<?php
include 'includes/header.php';
include 'includes/db.php';

$event_id = $_GET['event_id'];
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search)) {
    $attendees = $conn->query("SELECT * FROM attendees WHERE event_id = $event_id AND 
                              (full_name LIKE '%$search%' OR 
                               email LIKE '%$search%' OR 
                               phone LIKE '%$search%' OR 
                               ticket_number LIKE '%$search%')")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $attendees = $conn->query("SELECT * FROM attendees WHERE event_id = $event_id")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-warning pb-2">ATTENDEES: <?= $event['event_name'] ?></h2>
</div>

<!-- Search Bar -->
<div class="pixel-card animate-pixel mb-4">
    <div class="bg-primary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">SEARCH ATTENDEES</h3>
    </div>
    <div class="p-4">
        <form method="GET" action="">
            <input type="hidden" name="event_id" value="<?= $event_id ?>">
            <div class="flex">
                <input type="text" name="search" placeholder="Search by name, email, phone or ticket#" 
                       class="w-full px-4 py-2 pixel-input" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="ml-2 pixel-button bg-primary text-white px-4 py-2 border-2 border-black">
                    SEARCH
                </button>
                <?php if (!empty($search)): ?>
                <a href="view_attendees.php?event_id=<?= $event_id ?>" class="ml-2 pixel-button bg-dark text-white px-4 py-2 border-2 border-black">
                    CLEAR
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
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
                    <th class="px-4 py-3 text-left">AGE</th>
                    <th class="px-4 py-3 text-left">GENDER</th>
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
                            <td class="px-4 py-3"><?= isset($attendee['age']) ? $attendee['age'] : 'N/A' ?></td>
                            <td class="px-4 py-3"><?= isset($attendee['gender']) ? $attendee['gender'] : 'Not Specified' ?></td>
                            <td class="px-4 py-3">
                                <span class="bg-primary text-white px-2 py-1 border-2 border-black"><?= $attendee['ticket_number'] ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="view_ticket.php?attendee_id=<?= $attendee['attendee_id'] ?>" 
                                       class="pixel-button bg-secondary text-white px-2 py-1 border-2 border-black text-[10px]">
                                        TICKET
                                    </a>
                                    <a href="edit_attendee.php?attendee_id=<?= $attendee['attendee_id'] ?>" 
                                       class="pixel-button bg-primary text-white px-2 py-1 border-2 border-black text-[10px]">
                                        EDIT
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
                        <td colspan="7" class="px-4 py-3 text-center">NO ATTENDEES REGISTERED YET.</td>
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