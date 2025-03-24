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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vehicle'])) {
    $vehicle_no = $conn->real_escape_string($_POST['vehicle_no']);
    $vehicle_age = $conn->real_escape_string($_POST['vehicle_age']);
    $insurance = $conn->real_escape_string($_POST['insurance']);
    $pucc = $conn->real_escape_string($_POST['pucc']);
    $tax = $conn->real_escape_string($_POST['tax']);
    $permit = $conn->real_escape_string($_POST['permit']);
    
    $sql = "INSERT INTO vehicles (vehicle_no, vehicle_age, insurance, pucc, tax, permit) 
            VALUES ('$vehicle_no', '$vehicle_age', '$insurance', '$pucc', '$tax', '$permit')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Vehicle added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vehicles Details - HARIHARAN TRAVELS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
        .tab-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <?php include "sidebar.php" ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p class="text-muted">Vehicles Details Management</p>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="vehicleTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-vehicle" type="button" role="tab" aria-controls="add-vehicle" aria-selected="true">Add New Vehicle</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#vehicle-list" type="button" role="tab" aria-controls="vehicle-list" aria-selected="false">Vehicle List</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="vehicleTabContent">
            <!-- Add Vehicle Tab -->
            <div class="tab-pane fade show active" id="add-vehicle" role="tabpanel" aria-labelledby="add-tab">
                <h3>Add New Vehicle</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Vehicle No</label>
                        <input type="text" class="form-control" name="vehicle_no" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle Age (in years)</label>
                        <input type="number" class="form-control" name="vehicle_age" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Insurance (Yes/No)</label>
                        <select class="form-select" name="insurance" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">PUCC (Yes/No)</label>
                        <select class="form-select" name="pucc" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tax (Yes/No)</label>
                        <select class="form-select" name="tax" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permit (Yes/No)</label>
                        <select class="form-select" name="permit" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <button type="submit" name="add_vehicle" class="btn btn-primary">Add Vehicle</button>
                </form>
            </div>

            <!-- Vehicle List Tab -->
            <div class="tab-pane fade" id="vehicle-list" role="tabpanel" aria-labelledby="list-tab">
                <h3>Vehicle List</h3>
                <div class="table-responsive">
                    <table id="vehicleTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Vehicle No</th>
                                <th>Vehicle Age</th>
                                <th>Insurance</th>
                                <th>PUCC</th>
                                <th>Tax</th>
                                <th>Permit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM vehicles ORDER BY id ASC");
                            $sno = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $sno++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['vehicle_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['vehicle_age']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['insurance']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['pucc']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tax']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['permit']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $total_vehicles = $result->num_rows;
                echo "<p>Total Vehicles in Office: " . $total_vehicles . "</p>";
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#vehicleTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>