<?php
include 'includes/db.php';

if (isset($_GET['attendee_id'])) {
    $attendee_id = $_GET['attendee_id'];

    // Fetch event_id before deleting the attendee (to redirect back to the correct event)
    $event_id = $conn->query("SELECT event_id FROM attendees WHERE attendee_id = $attendee_id")->fetch(PDO::FETCH_ASSOC)['event_id'];

    // Delete the attendee
    $sql = "DELETE FROM attendees WHERE attendee_id = :attendee_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':attendee_id' => $attendee_id]);

    // Redirect back to the attendees list for the event
    header("Location: view_attendees.php?event_id=$event_id");
    exit();
} else {
    die("Invalid request.");
}
?>