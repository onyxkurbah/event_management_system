<?php
include 'includes/header.php';
include 'db.php';

// Fetch all events
$events = $conn->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Upcoming Events</h2>
<div class="table-wrapper">
<table>
    <tr>
        <th>Event Name</th>
        <th>Date</th>
        <th>Location</th>
        <th>Ticket Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $event): ?>
        <tr>
            <td><?= $event['event_name'] ?></td>
            <td><?= date('F j, Y, g:i a', strtotime($event['event_date'])) ?></td>
            <td><?= $event['event_location'] ?></td>
            <td><span class="rupee-symbol">₹</span><?=number_format($event['ticket_price'], 2) ?></td>
            <td class="action-links">
                <a href="edit_event.php?event_id=<?= $event['event_id'] ?>" class="action-btn">Edit</a>
                <a href="delete_event.php?event_id=<?= $event['event_id'] ?>" class="action-btn danger" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                <a href="register_attendee.php?event_id=<?= $event['event_id'] ?>" class="action-btn success">Register</a>
                <a href="view_attendees.php?event_id=<?= $event['event_id'] ?>" class="action-btn info">View</a>
                <a href="event_analytics.php?event_id=<?= $event['event_id'] ?>" class="action-btn warning">Analytics</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const main = document.querySelector('main');
        for (let i = 0; i < 30; i++) {
            const star = document.createElement('div');
            star.classList.add('star');
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.animationDelay = Math.random() * 4 + 's';
            main.appendChild(star);
        }
    });
</script>
<?php include 'includes/footer.php'; ?>