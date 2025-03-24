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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_driver'])) {
    $driver_name = $conn->real_escape_string($_POST['driver_name']);
    $shift = $conn->real_escape_string($_POST['shift']);
    $basic_salary = $conn->real_escape_string($_POST['basic_salary']);
    
    $sql = "INSERT INTO drivers (driver_name, shift, basic_salary) VALUES ('$driver_name', '$shift', '$basic_salary')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Driver added successfully');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Driver Details - HARIHARAN TRAVELS</title>
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
            <p class="text-muted">Driver Details Management</p>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="driverTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-driver" type="button" role="tab" aria-controls="add-driver" aria-selected="true">Add New Driver</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#driver-list" type="button" role="tab" aria-controls="driver-list" aria-selected="false">Driver List</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="driverTabContent">
            <!-- Add Driver Tab -->
            <div class="tab-pane fade show active" id="add-driver" role="tabpanel" aria-labelledby="add-tab">
                <h3>Add New Driver</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Driver Name</label>
                        <input type="text" class="form-control" name="driver_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shift</label>
                        <select class="form-select" name="shift" required>
                            <option value="Morning">Morning</option>
                            <option value="Night">Night</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Basic Salary</label>
                        <input type="number" class="form-control" name="basic_salary" required>
                    </div>
                    <button type="submit" name="add_driver" class="btn btn-primary">Add Driver</button>
                </form>
            </div>

            <!-- Driver List Tab -->
            <div class="tab-pane fade" id="driver-list" role="tabpanel" aria-labelledby="list-tab">
                <h3>Driver List</h3>
                <div class="table-responsive">
                    <table id="driverTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Driver Name</th>
                                <th>Shift</th>
                                <th>Basic Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM drivers ORDER BY id ASC");
                            $sno = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $sno++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['driver_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['shift']) . "</td>";
                                echo "<td>₹" . number_format($row['basic_salary'], 2) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $total_drivers = $result->num_rows;
                echo "<p>Total Drivers in Office: " . $total_drivers . "</p>";
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
            $('#driverTable').DataTable({
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