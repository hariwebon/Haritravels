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

// Create trip_details table if not exists with new location columns
$conn->query("CREATE TABLE IF NOT EXISTS trip_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_date DATE NOT NULL,
    location_from VARCHAR(100) NOT NULL,
    location_to VARCHAR(100) NOT NULL,
    vehicle_no VARCHAR(50) NOT NULL,
    driver_name VARCHAR(100) NOT NULL,
    trip_type ENUM('Drop', 'Pickup') NOT NULL
)");

// Fetch vehicles and drivers for dropdowns
$vehicles = $conn->query("SELECT vehicle_no FROM vehicles")->fetch_all(MYSQLI_ASSOC);
$drivers = $conn->query("SELECT driver_name FROM drivers")->fetch_all(MYSQLI_ASSOC);

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trip_date = $_POST['trip_date'];
    $location_from = $_POST['location_from'];
    $location_to = $_POST['location_to'];
    $vehicle_no = $_POST['vehicle_no'];
    $driver_name = $_POST['driver_name'];
    $trip_type = $_POST['trip_type'];

    $sql = "INSERT INTO trip_details (trip_date, location_from, location_to, vehicle_no, driver_name, trip_type) 
            VALUES ('$trip_date', '$location_from', '$location_to', '$vehicle_no', '$driver_name', '$trip_type')";
    if ($conn->query($sql) === TRUE) {
        $message = "Trip data added successfully!";
    } else {
        $message = "Error adding trip data: " . $conn->error;
    }
}

// Fetch existing trip data
$trip_data = $conn->query("SELECT * FROM trip_details ORDER BY id ASC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trip Push - HARIHARAN TRAVELS</title>
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
        .message-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .trip-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .message {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    
<?php include "sidebar.php" ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="message-section">
            <h3>Hello</h3>
        </div>

        <div class="trip-section">
            <h3>Add New Trip</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" name="trip_date" value="2025-01-01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Place or Location (From)</label>
                    <input type="text" class="form-control" name="location_from" placeholder="Enter starting location" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Place or Location (To)</label>
                    <input type="text" class="form-control" name="location_to" placeholder="Enter destination" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Vehicle No</label>
                    <select class="form-select" name="vehicle_no" required>
                        <option value="">Select Vehicle</option>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?php echo htmlspecialchars($vehicle['vehicle_no']); ?>">
                                <?php echo htmlspecialchars($vehicle['vehicle_no']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Driver Name</label>
                    <select class="form-select" name="driver_name" required>
                        <option value="">Select Driver</option>
                        <?php foreach ($drivers as $driver): ?>
                            <option value="<?php echo htmlspecialchars($driver['driver_name']); ?>">
                                <?php echo htmlspecialchars($driver['driver_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Drop or Pickup</label>
                    <select class="form-select" name="trip_type" required>
                        <option value="">Select Type</option>
                        <option value="Drop">Drop</option>
                        <option value="Pickup">Pickup</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Trip</button>
            </form>

            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'successfully') !== false ? 'text-success' : 'text-danger'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($trip_data)): ?>
            <div class="trip-section">
                <h3>Existing Trip Data</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Location (From)</th>
                                <th>Location (To)</th>
                                <th>Vehicle No</th>
                                <th>Driver Name</th>
                                <th>Drop or Pickup</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trip_data as $index => $trip): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($trip['trip_date']); ?></td>
                                    <td><?php echo htmlspecialchars($trip['location_from']); ?></td>
                                    <td><?php echo htmlspecialchars($trip['location_to']); ?></td>
                                    <td><?php echo htmlspecialchars($trip['vehicle_no']); ?></td>
                                    <td><?php echo htmlspecialchars($trip['driver_name']); ?></td>
                                    <td><?php echo htmlspecialchars($trip['trip_type']); ?></td>
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