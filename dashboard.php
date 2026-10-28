<?php
session_start();
include "config/db.php";

/* ===== Error Reporting (REMOVE AFTER TESTING) ===== */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ===== Session Check ===== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ===== Safe Session Values ===== */
$user_id = $_SESSION['user_id'];
$name    = $_SESSION['name'] ?? "User";
$role    = $_SESSION['role'] ?? "";

/* ===== If Admin → Redirect to Admin Panel ===== */
if ($role === "admin") {
    header("Location: admin_dashboard.php");
    exit;
}

/* ===== Stats Queries (User Only) ===== */
$total = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as count FROM complaints WHERE user_id='$user_id'"))['count'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as count FROM complaints WHERE user_id='$user_id' AND status='Pending'"))['count'];

$resolved = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as count FROM complaints WHERE user_id='$user_id' AND status='Resolved'"))['count'];

$today = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as count FROM complaints WHERE user_id='$user_id' AND DATE(created_at)=CURDATE()"))['count'];
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:white;
}

.header{
    background:#111827;
    padding:18px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header h2{
    margin:0;
}

.logout{
    background:#dc2626;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:14px;
}

.container{
    padding:40px;
}

.welcome{
    font-size:22px;
    font-weight:600;
}

.datetime{
    margin-top:5px;
    font-size:14px;
    opacity:0.8;
}

.stats{
    margin-top:30px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:20px;
}

.stat-card{
    background:rgba(255,255,255,0.15);
    padding:25px;
    border-radius:16px;
    backdrop-filter:blur(8px);
    text-align:center;
}

.stat-card h3{
    margin:0;
    font-size:26px;
}

.stat-card p{
    margin:6px 0 0;
    font-size:14px;
}

.actions{
    margin-top:40px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.action-card{
    background:white;
    padding:30px;
    border-radius:18px;
    text-decoration:none;
    color:#111827;
    transition:0.3s;
    display:block;
}

.action-card:hover{
    transform:translateY(-6px);
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
}

.action-card h3{
    margin:0 0 10px;
    color:#2563eb;
}

.action-card p{
    margin:0;
    font-size:14px;
    color:#6b7280;
}
</style>
</head>

<body>

<div class="header">
    <h2>ResolveX Portal</h2>
    <a class="logout" href="logout.php">Logout</a>
</div>

<div class="container">

<div class="welcome">
Welcome, <?php echo htmlspecialchars($name); ?> 👋
</div>

<div class="datetime" id="datetime"></div>

<div class="stats">
    <div class="stat-card">
        <h3><?php echo $total; ?></h3>
        <p>Total Complaints</p>
    </div>

    <div class="stat-card">
        <h3><?php echo $pending; ?></h3>
        <p>Pending</p>
    </div>

    <div class="stat-card">
        <h3><?php echo $resolved; ?></h3>
        <p>Resolved</p>
    </div>

    <div class="stat-card">
        <h3><?php echo $today; ?></h3>
        <p>Today</p>
    </div>
</div>

<div class="actions">

    <a href="complaint_add.php" class="action-card">
        <h3>Submit Complaint</h3>
        <p>Create and register a new complaint</p>
    </a>

    <a href="my_complaints.php" class="action-card">
        <h3>My Complaints</h3>
        <p>Track your complaint status</p>
    </a>

</div>

</div>

<script>
function updateTime(){
    const now = new Date();
    document.getElementById("datetime").innerHTML =
        now.toLocaleDateString() + " | " + now.toLocaleTimeString();
}
setInterval(updateTime,1000);
updateTime();
</script>

</body>
</html>