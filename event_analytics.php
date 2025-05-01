<?php
include 'includes/header.php';
include 'includes/db.php';

$event_id = $_GET['event_id'];

// Fetch event details
$event = $conn->query("SELECT * FROM events WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC);

// Fetch total attendees
$total_attendees = $conn->query("SELECT COUNT(*) as total FROM attendees WHERE event_id = $event_id")->fetch(PDO::FETCH_ASSOC)['total'];

// Calculate total revenue correctly (ticket price × number of attendees)
$total_revenue = $event['ticket_price'] * $total_attendees;

// Get gender distribution
$gender_query = "SELECT gender, COUNT(*) as count 
                FROM attendees 
                WHERE event_id = $event_id 
                GROUP BY gender";
$gender_data = $conn->query($gender_query)->fetchAll(PDO::FETCH_ASSOC);

// Format gender data for chart
$gender_labels = [];
$gender_counts = [];
$gender_colors = [
    'Male' => '#4361ee',
    'Female' => '#f72585',
    'Non-Binary' => '#7209b7',
    'Prefer not to say' => '#6c757d',
    'null' => '#6c757d' // For any nulls in database
];

foreach ($gender_data as $data) {
    $gender_labels[] = $data['gender'] ? $data['gender'] : 'Not Specified';
    $gender_counts[] = $data['count'];
}

// Get age distribution in ranges
$age_ranges = [
    '0-17' => 0,
    '18-24' => 0,
    '25-34' => 0,
    '35-44' => 0,
    '45-54' => 0,
    '55+' => 0,
    'Not Specified' => 0
];

$age_query = "SELECT age FROM attendees WHERE event_id = $event_id";
$ages = $conn->query($age_query)->fetchAll(PDO::FETCH_ASSOC);

foreach ($ages as $age_data) {
    $age = isset($age_data['age']) ? intval($age_data['age']) : null;
    
    if ($age === null) {
        $age_ranges['Not Specified']++;
    } elseif ($age < 18) {
        $age_ranges['0-17']++;
    } elseif ($age >= 18 && $age <= 24) {
        $age_ranges['18-24']++;
    } elseif ($age >= 25 && $age <= 34) {
        $age_ranges['25-34']++;
    } elseif ($age >= 35 && $age <= 44) {
        $age_ranges['35-44']++;
    } elseif ($age >= 45 && $age <= 54) {
        $age_ranges['45-54']++;
    } else {
        $age_ranges['55+']++;
    }
}

// Remove empty age ranges
foreach ($age_ranges as $range => $count) {
    if ($count === 0) {
        unset($age_ranges[$range]);
    }
}
?>

<div class="mb-6 flex items-center">
    <a href="index.php" class="mr-3 pixel-button bg-primary text-white px-3 py-1 border-2 border-black">
        BACK
    </a>
    <h2 class="text-xl text-dark border-b-4 border-secondary pb-2">STATS: <?= $event['event_name'] ?></h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="pixel-card animate-pixel">
        <div class="bg-primary border-b-2 border-black px-4 py-3">
            <h3 class="text-white">TOTAL ATTENDEES</h3>
        </div>
        <div class="p-6 flex items-center">
            <div class="text-4xl font-bold text-dark"><?= $total_attendees ?></div>
        </div>
    </div>
    
    <div class="pixel-card animate-pixel">
        <div class="bg-success border-b-2 border-black px-4 py-3">
            <h3 class="text-white">TOTAL REVENUE</h3>
        </div>
        <div class="p-6 flex items-center">
            <div class="text-4xl font-bold text-dark">₹<?= number_format($total_revenue, 2) ?></div>
        </div>
    </div>
</div>

<!-- New Demographics Section -->
<div class="pixel-card animate-pixel mb-8">
    <div class="bg-secondary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">ATTENDEE DEMOGRAPHICS</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Gender Distribution -->
            <div>
                <div class="font-bold mb-4">GENDER DISTRIBUTION</div>
                <div class="bg-light border-2 border-black p-4 shadow-pixel-sm">
                    <?php if (count($gender_data) > 0): ?>
                        <div style="height: 180px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    <?php else: ?>
                        <p class="text-center py-8">No gender data available</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Age Distribution -->
            <div>
                <div class="font-bold mb-4">AGE DISTRIBUTION</div>
                <div class="bg-light border-2 border-black p-4 shadow-pixel-sm">
                    <?php if (count($age_ranges) > 1): ?>
                        <div style="height: 150px;">
                            <canvas id="ageChart"></canvas>
                        </div>
                    <?php else: ?>
                        <p class="text-center py-8">No age data available</p>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="pixel-card animate-pixel mb-8">
    <div class="bg-secondary border-b-2 border-black px-4 py-3">
        <h3 class="text-white">EVENT DETAILS</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <div class="font-bold mb-1">DATE & TIME</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            DATE
                        </div>
                        <span><?= date('M j, Y, g:i a', strtotime($event['event_date'])) ?></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="font-bold mb-1">LOCATION</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            PLACE
                        </div>
                        <span><?= $event['event_location'] ?></span>
                    </div>
                </div>
                
                <div>
                    <div class="font-bold mb-1">TICKET PRICE</div>
                    <div class="flex items-center">
                        <div class="bg-primary text-white px-2 py-1 border-2 border-black mr-2">
                            PRICE
                        </div>
                        <span>₹<?= number_format($event['ticket_price'], 2) ?></span>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="font-bold mb-1">DESCRIPTION</div>
                <div class="bg-light border-2 border-black p-4 shadow-pixel-sm">
                    <p class="whitespace-pre-line"><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex space-x-4">
    <a href="index.php" class="pixel-button bg-dark text-white px-4 py-2 border-2 border-black">
        BACK TO EVENTS
    </a>
    
    <a href="view_attendees.php?event_id=<?= $event_id ?>" class="pixel-button bg-warning text-dark px-4 py-2 border-2 border-black">
        VIEW ATTENDEES
    </a>
</div>

<!-- Chart.js for graphs -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gender Chart
<?php if (count($gender_data) > 0): ?>
document.addEventListener('DOMContentLoaded', function() {
    const genderLabels = <?= json_encode($gender_labels) ?>;
    const genderCounts = <?= json_encode($gender_counts) ?>;
    
    // Manually define colors for pixel-art consistency
    const genderColors = [];
    genderLabels.forEach(label => {
        if (label === 'Male') genderColors.push('#4361ee');
        else if (label === 'Female') genderColors.push('#f72585');
        else if (label === 'Non-Binary') genderColors.push('#7209b7');
        else genderColors.push('#6c757d'); // For "Prefer not to say" or "Not Specified"
    });
    
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: genderCounts,
                backgroundColor: genderColors,
                borderColor: '#000000',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,
                        padding: 8,
                        font: {
                            family: 'Press Start 2P, cursive',
                            size: 7
                        }
                    }
                }
            }
        }
    });
});
<?php endif; ?>

// Age Chart
<?php if (count($age_ranges) > 1): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ageLabels = <?= json_encode(array_keys($age_ranges)) ?>;
    const ageCounts = <?= json_encode(array_values($age_ranges)) ?>;
    
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: 'Number of Attendees',
                data: ageCounts,
                backgroundColor: '#4361ee',
                borderColor: '#000000',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            family: 'Press Start 2P, cursive',
                            size: 6
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Press Start 2P, cursive',
                            size: 6
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>