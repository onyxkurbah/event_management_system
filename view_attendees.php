<?php
include 'includes/header.php';
include 'db.php';

$event_id = $_GET['event_id'];
$attendees = $conn->query("SELECT * FROM attendees WHERE event_id = $event_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Attendees List</h2>
<div class="card pixel-borders">
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Ticket Number</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($attendees as $attendee): ?>
            <tr>
                <td><?= $attendee['full_name'] ?></td>
                <td><?= $attendee['email'] ?></td>
                <td><?= $attendee['phone'] ?></td>
                <td><?= $attendee['ticket_number'] ?></td>
                <td>
                    <a href="delete_attendee.php?attendee_id=<?= $attendee['attendee_id'] ?>" onclick="return confirm('Are you sure you want to delete this attendee?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<a href="index.php" class="button">Back to Events</a>

<?php include 'includes/footer.php'; ?>

