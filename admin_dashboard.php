<?php
session_start();
include "config/db.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* Admin Access Check */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

/* Query */
$query = "SELECT complaints.id,
                 complaints.complaint_type,
                 complaints.status,
                 complaints.created_at,
                 users.full_name
          FROM complaints
          LEFT JOIN users ON complaints.user_id = users.id
          ORDER BY complaints.created_at DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

$total = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | Admin Dashboard</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#f3f4f6;
}

/* Header */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 40px;
    background:#111827;
    color:white;
}

.brand{
    font-size:22px;
    font-weight:600;
}

.datetime{
    font-size:14px;
}

/* Container */
.container{
    padding:40px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

th,td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #eee;
}

th{
    background:#3b82f6;
    color:white;
}

tr:hover{
    background:#f9fafb;
}

.badge{
    padding:6px 14px;
    border-radius:30px;
    color:white;
    font-size:12px;
    font-weight:600;
    letter-spacing:0.5px;
    display:inline-block;
}

/* STATUS COLORS */
.pending { background:#f59e0b; }
.underreview { background:#0ea5e9; }
.inprogress { background:#3b82f6; }
.onhold { background:#ef4444; }
.escalated { background:#9333ea; }
.resolved { background:#10b981; }
.closed { background:#6b7280; }
.suspended { background:#111827; }

a{
    text-decoration:none;
    color:#3b82f6;
    font-weight:600;
}

.logout{
    margin-top:20px;
    display:inline-block;
    background:#ef4444;
    color:white;
    padding:8px 16px;
    border-radius:6px;
}
</style>
</head>

<body>

<div class="header">
    <div class="brand">ResolveX Admin</div>
    <div class="datetime" id="datetime"></div>
</div>

<div class="container">

<div class="card">
    <h3>Total Complaints: <?php echo $total; ?></h3>
</div>

<table>
<tr>
    <th>User</th>
    <th>Complaint Type</th>
    <th>Status</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php
if($total > 0){
    while($row = mysqli_fetch_assoc($result)){

        $status = strtolower(trim($row['status']));

        switch($status){
            case "pending": $statusClass="pending"; break;
            case "under review": $statusClass="underreview"; break;
            case "in progress": $statusClass="inprogress"; break;
            case "on hold": $statusClass="onhold"; break;
            case "escalated": $statusClass="escalated"; break;
            case "resolved": $statusClass="resolved"; break;
            case "closed": $statusClass="closed"; break;
            case "suspended": $statusClass="suspended"; break;
            default: $statusClass="pending";
        }
?>
<tr>
    <td><?php echo $row['full_name'] ?? 'Unknown'; ?></td>
    <td><?php echo $row['complaint_type']; ?></td>
    <td>
        <span class="badge <?php echo $statusClass; ?>">
            <?php echo $row['status']; ?>
        </span>
    </td>
    <td><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
    <td>
        <a href="update_status.php?id=<?php echo $row['id']; ?>">Update</a>
    </td>
</tr>
<?php
    }
}else{
    echo "<tr><td colspan='5'>No complaints found.</td></tr>";
}
?>

</table>

<a class="logout" href="logout.php">Logout</a>

</div>

<script>
function updateTime(){
    const now = new Date();
    document.getElementById("datetime").innerHTML =
        now.toLocaleString('en-IN');
}
setInterval(updateTime,1000);
updateTime();
</script>

</body>
</html>