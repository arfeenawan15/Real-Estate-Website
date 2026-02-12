<?php
require_once 'config.php';

// Simple password protection (change this password!)
$admin_password = 'admin123';

session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = "Invalid password!";
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login - Awan Associates</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background: linear-gradient(135deg, #144975, #B87333);
                    margin: 0;
                }
                .login-box {
                    background: white;
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                    text-align: center;
                }
                input[type="password"] {
                    padding: 12px;
                    width: 250px;
                    border: 2px solid #ddd;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                button {
                    background: #B87333;
                    color: white;
                    padding: 12px 30px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                }
                button:hover {
                    background: #144975;
                }
                .error {
                    color: red;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h2>Admin Login</h2>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter Password" required>
                    <br>
                    <button type="submit">Login</button>
                    <?php if(isset($error)): ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Fetch data
$conn = getDBConnection();

// Get contact submissions
$contact_query = "SELECT * FROM contact_submissions ORDER BY submitted_at DESC";
$contact_result = $conn->query($contact_query);

// Get property interests
$property_query = "SELECT * FROM property_interests ORDER BY submitted_at DESC";
$property_result = $conn->query($property_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Awan Associates</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #144975, #B87333);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #144975;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #B87333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #144975;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .logout-btn {
            background: white;
            color: #144975;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
        }
        .logout-btn:hover {
            background: #B87333;
            color: white;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background: linear-gradient(135deg, #144975, #1a5a8a);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-box h3 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .stat-box p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-new {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Awan Associates - Admin Dashboard</h1>
        <a href="?logout=1" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="stats">
            <div class="stat-box">
                <h3><?php echo $contact_result->num_rows; ?></h3>
                <p>Contact Submissions</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $property_result->num_rows; ?></h3>
                <p>Property Interests</p>
            </div>
        </div>

        <div class="section">
            <h2>📧 Contact Form Submissions</h2>
            <?php if ($contact_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $contact_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['submitted_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No contact submissions yet.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>🏠 Property Interest Submissions</h2>
            <?php if ($property_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Property</th>
                            <th>Buyer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $property_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($row['property_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['buyer_email']); ?></td>
                                <td><?php echo htmlspecialchars($row['buyer_phone']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['buyer_message'], 0, 50)) . '...'; ?></td>
                                <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['submitted_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No property interests yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>