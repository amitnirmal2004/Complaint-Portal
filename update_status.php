<?php
session_start();
include "config/db.php";

/* ===== ADMIN PROTECTION ===== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = intval($_GET['id']);

/* ===== FETCH COMPLAINT ===== */
$query = "SELECT * FROM complaints WHERE id='$id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Complaint not found.");
}

$row = mysqli_fetch_assoc($result);

/* ===== UPDATE STATUS ===== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $allowed_status = ["Pending","In Progress","Resolved","Closed"];
    $status = $_POST['status'];

    if(in_array($status, $allowed_status)){
        mysqli_query($conn, "UPDATE complaints SET status='$status' WHERE id='$id'");
        header("Location: admin_dashboard.php");
        exit;
    } else {
        die("Invalid status selection.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | Update Complaint</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
}

.header{
    padding:18px 50px;
    background:#111827;
    color:white;
    font-weight:600;
}

.container{
    display:flex;
    justify-content:center;
    align-items:center;
    height:85vh;
}

.card{
    background:white;
    width:480px;
    padding:35px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
}

h2{
    margin-top:0;
    text-align:center;
}

.info{
    font-size:14px;
    margin-bottom:8px;
    color:#374151;
}

.badge{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    color:white;
    font-size:12px;
    font-weight:600;
    margin:10px 0;
}

.pending{background:#f59e0b;}
.inprogress{background:#3b82f6;}
.resolved{background:#16a34a;}
.closed{background:#6b7280;}

select{
    width:100%;
    padding:10px;
    margin:20px 0;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:12px;
    background:#2563eb;
    border:none;
    color:white;
    font-weight:600;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

.back{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
    color:#2563eb;
}
</style>
</head>

<body>

<div class="header">
ResolveX Admin Panel
</div>

<div class="container">
<div class="card">

<h2>Update Complaint Status</h2>

<div class="info"><strong>Complaint ID:</strong> #<?php echo $id; ?></div>
<div class="info"><strong>Complaint:</strong> <?php echo htmlspecialchars($row['complaint_type']); ?></div>
<div class="info"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></div>
<div class="info"><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></div>
<div class="info"><strong>Department:</strong> <?php echo htmlspecialchars($row['department']); ?></div>
<div class="info"><strong>Created At:</strong> <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></div>

<?php 
$statusClass = strtolower(str_replace(" ","",$row['status']));
?>

<span class="badge <?php echo $statusClass; ?>">
Current Status: <?php echo htmlspecialchars($row['status']); ?>
</span>

<form method="POST">

<select name="status" required>
    <option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>Pending</option>
    <option value="In Progress" <?php if($row['status']=="In Progress") echo "selected"; ?>>In Progress</option>
    <option value="Resolved" <?php if($row['status']=="Resolved") echo "selected"; ?>>Resolved</option>
    <option value="Closed" <?php if($row['status']=="Closed") echo "selected"; ?>>Closed</option>
</select>

<button type="submit">Update Status</button>

</form>

<a class="back" href="admin_dashboard.php">Back to Dashboard</a>

</div>
</div>

</body>
</html>