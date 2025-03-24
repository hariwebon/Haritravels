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

// Fetch trip counts by month
$trip_counts = [];
$result = $conn->query("SELECT DATE_FORMAT(trip_date, '%Y-%m') AS month, COUNT(*) AS total 
                        FROM trip_details 
                        GROUP BY DATE_FORMAT(trip_date, '%Y-%m') 
                        ORDER BY month ASC");
while ($row = $result->fetch_assoc()) {
    $trip_counts[$row['month']] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report - HARIHARAN TRAVELS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-sizing: border-box;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 8px 0;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            display: flex;
            gap: 20px;
        }
        .message-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
        }
        .report-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1;
            max-width: 400px;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include "sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="message-section">
            <h3>Hai</h3>
        </div>

        <div class="report-section">
            <h3>Month-wise Trip Report</h3>
            <?php if (!empty($trip_counts)): ?>
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
            <?php else: ?>
                <p>No trip data available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>