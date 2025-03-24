<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "picture_password_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total counts
$driver_count = $conn->query("SELECT COUNT(*) as total FROM drivers")->fetch_assoc()['total'];
$vehicle_count = $conn->query("SELECT COUNT(*) as total FROM vehicles")->fetch_assoc()['total'];

// Get trip counts by month
$trip_counts = [];
$result = $conn->query("SELECT DATE_FORMAT(trip_date, '%Y-%m') AS month, COUNT(*) AS total 
                        FROM trip_details 
                        GROUP BY DATE_FORMAT(trip_date, '%Y-%m') 
                        ORDER BY month ASC");
while ($row = $result->fetch_assoc()) {
    $trip_counts[$row['month']] = $row['total'];
}
$total_trips = array_sum($trip_counts); // Total trips across all months
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home - HARIHARAN TRAVELS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
        .welcome-message {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: scale(1.05);
        }
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            animation: pulse 2s infinite;
        }
        .stat-card h3 {
            margin: 10px 0;
            font-size: 1.25rem;
        }
        .stat-card p {
            font-size: 2rem;
            font-weight: bold;
        }
        .trip-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <?php include "sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="text-muted">You have successfully logged in.</p>
        </div>

        <!-- Stats Section with Animated Icons -->
        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-user text-primary"></i>
                <h3>Total Drivers</h3>
                <p><?php echo $driver_count; ?></p>
            </div>
            <div class="stat-card">
                <i class="fas fa-car text-success"></i>
                <h3>Total Vehicles</h3>
                <p><?php echo $vehicle_count; ?></p>
            </div>
            <div class="stat-card">
                <i class="fas fa-road text-warning"></i>
                <h3>Total Trips</h3>
                <p><?php echo $total_trips; ?></p>
            </div>
        </div>

        <!-- Monthly Trip Details -->
        <?php if (!empty($trip_counts)): ?>
            <div class="trip-details">
                <h3>Trips by Month</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Trip Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trip_counts as $month => $count): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($month); ?></td>
                                    <td><?php echo $count; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>