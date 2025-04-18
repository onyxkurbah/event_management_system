<?php
include 'db.php';

if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Delete the event
    $sql = "DELETE FROM events WHERE event_id = :event_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':event_id' => $event_id]);

    // Redirect back to the homepage
    header("Location: index.php");
    exit();
} else {
    die("Invalid request.");
}
?>