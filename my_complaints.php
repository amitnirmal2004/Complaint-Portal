<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

/* Search & Filter */
$search = "";
$statusFilter = "";

if(isset($_GET['search'])){
    $search = $_GET['search'];
}

if(isset($_GET['status'])){
    $statusFilter = $_GET['status'];
}

$query = "SELECT * FROM complaints WHERE user_id='$uid'";

if($search != ""){
    $query .= " AND complaint_type LIKE '%$search%'";
}

if($statusFilter != ""){
    $query .= " AND status='$statusFilter'";
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn,$query);

/* Stats */
$total = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM complaints WHERE user_id='$uid'"));
$pending = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM complaints WHERE user_id='$uid' AND status='Pending'"));
$resolved = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM complaints WHERE user_id='$uid' AND status='Resolved'"));
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | My Complaints</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
}

/* Header */
.header{
    background:#111827;
    padding:18px 40px;
    color:white;
    display:flex;
    justify-content:space-between;
}

/* Container */
.container{
    padding:40px;
}

/* Cards */
.stats{
    display:flex;
    gap:20px;
    margin-bottom:30px;
}

.stat-box{
    flex:1;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
    text-align:center;
}

.stat-box h3{
    margin:0;
    font-size:28px;
    color:#2563eb;
}

/* Main Card */
.card{
    background:white;
    padding:30px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);
}

/* Search */
.search-bar{
    display:flex;
    gap:15px;
    margin-bottom:20px;
}

input,select{
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    padding:10px 20px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    text-align:left;
    padding:12px;
    background:#f1f5f9;
}

td{
    padding:12px;
    border-bottom:1px solid #e5e7eb;
}

tr:hover{
    background:#f9fafb;
}

/* Status */
.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    color:white;
}

.pending{background:#f59e0b;}
.inprogress{background:#3b82f6;}
.resolved{background:#16a34a;}
.underreview{background:#6366f1;}
.onhold{background:#f97316;}
.escalated{background:#dc2626;}
.closed{background:#6b7280;}
</style>
</head>

<body>

<div class="header">
    <h2>ResolveX</h2>
    <a href="dashboard.php" style="color:white;text-decoration:none;">Back</a>
</div>

<div class="container">

<!-- Stats Section -->
<div class="stats">
    <div class="stat-box">
        <h3><?php echo $total; ?></h3>
        <p>Total Complaints</p>
    </div>
    <div class="stat-box">
        <h3><?php echo $pending; ?></h3>
        <p>Pending</p>
    </div>
    <div class="stat-box">
        <h3><?php echo $resolved; ?></h3>
        <p>Resolved</p>
    </div>
</div>

<div class="card">

<h2>My Complaints</h2>

<form method="GET" class="search-bar">
    <input type="text" name="search" placeholder="Search complaint..." value="<?php echo $search; ?>">
    <select name="status">
        <option value="">All Status</option>
        <option <?php if($statusFilter=="Pending") echo "selected"; ?>>Pending</option>
        <option <?php if($statusFilter=="In Progress") echo "selected"; ?>>In Progress</option>
        <option <?php if($statusFilter=="Resolved") echo "selected"; ?>>Resolved</option>
        <option <?php if($statusFilter=="Under Review") echo "selected"; ?>>Under Review</option>
        <option <?php if($statusFilter=="On Hold") echo "selected"; ?>>On Hold</option>
        <option <?php if($statusFilter=="Escalated") echo "selected"; ?>>Escalated</option>
        <option <?php if($statusFilter=="Closed") echo "selected"; ?>>Closed</option>
    </select>
    <button type="submit">Filter</button>
</form>

<table>
<tr>
    <th>Complaint</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { 
$statusClass = strtolower(str_replace(" ","",$row['status']));
?>

<tr>
    <td><?php echo $row['complaint_type']; ?></td>
    <td>
        <span class="badge <?php echo $statusClass; ?>">
            <?php echo $row['status']; ?>
        </span>
    </td>
    <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>