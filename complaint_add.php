<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];
    $complaint_type = trim($_POST['complaint_type']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    /* ===== STRICT BACKEND VALIDATION ===== */

    // No spaces allowed
    if(preg_match("/\s/", $first_name) || preg_match("/\s/", $last_name)){
        $error = "Names cannot contain spaces.";
    }
    // Letters only
    elseif(!preg_match("/^[A-Za-z]+$/", $first_name)){
        $error = "First name must contain letters only.";
    }
    elseif(!preg_match("/^[A-Za-z]+$/", $last_name)){
        $error = "Last name must contain letters only.";
    }
    // Email validation + no spaces
    elseif(preg_match("/\s/", $email)){
        $error = "Email cannot contain spaces.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format.";
    }
    // Phone validation
    elseif(preg_match("/\s/", $phone)){
        $error = "Phone number cannot contain spaces.";
    }
    elseif(!preg_match("/^[0-9]{10}$/", $phone)){
        $error = "Phone number must be exactly 10 digits.";
    }
    else{

        $query = "INSERT INTO complaints 
              (user_id, first_name, last_name, email, phone, department, complaint_type, description, status, created_at)
              VALUES 
              ('$user_id','$first_name','$last_name','$email','$phone','$department','$complaint_type','$description','Pending',NOW())";

        if(mysqli_query($conn,$query)){
            $success = "Complaint submitted successfully.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | Submit Complaint</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    padding:20px;
}

.card{
    background:white;
    width:850px;
    padding:40px;
    border-radius:18px;
    box-shadow:0 25px 60px rgba(0,0,0,0.25);
}

.card h2{
    margin:0;
    color:#1e3a8a;
}

.subtitle{
    font-size:14px;
    color:#6b7280;
    margin-bottom:25px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

.full{
    grid-column:1 / 3;
}

label{
    font-size:13px;
    font-weight:600;
    color:#374151;
    display:block;
    margin-bottom:6px;
}

input, select, textarea{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:14px;
    transition:0.2s;
}

input:focus, select:focus, textarea:focus{
    border-color:#2563eb;
    outline:none;
    box-shadow:0 0 8px rgba(37,99,235,0.3);
}

button{
    padding:14px;
    width:100%;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

button:hover{
    background:#1e40af;
}

.message{
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
    font-size:14px;
}

.success{
    background:#dcfce7;
    color:#166534;
}

.error{
    background:#fee2e2;
    color:#991b1b;
}

.footer{
    margin-top:15px;
    text-align:right;
}

.footer a{
    text-decoration:none;
    font-size:13px;
    color:#2563eb;
}
</style>
</head>

<body>

<div class="card">

<h2>Submit Complaint</h2>
<p class="subtitle">Fill out the form below to raise your issue.</p>

<?php if($success != ""){ ?>
<div class="message success"><?php echo $success; ?></div>
<?php } ?>

<?php if($error != ""){ ?>
<div class="message error"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="form-grid">

    <div>
        <label>First Name</label>
        <input type="text" name="first_name"
               pattern="[A-Za-z]+"
               title="Only letters allowed, no spaces"
               required>
    </div>

    <div>
        <label>Last Name</label>
        <input type="text" name="last_name"
               pattern="[A-Za-z]+"
               title="Only letters allowed, no spaces"
               required>
    </div>

    <div>
        <label>Email Address</label>
        <input type="email" name="email"
               pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
               title="Enter a valid email without spaces"
               required>
    </div>

    <div>
        <label>Phone Number</label>
        <input type="text" name="phone"
               pattern="[0-9]{10}"
               maxlength="10"
               title="Enter exactly 10 digits, no spaces"
               required>
    </div>

    <div>
        <label>Department</label>
        <select name="department" required>
            <option value="">Select Department</option>
            <option>Accounts</option>
            <option>IT Support</option>
            <option>HR</option>
            <option>Customer Service</option>
        </select>
    </div>

    <div>
        <label>Complaint Title</label>
        <input type="text" name="complaint_type" required>
    </div>

    <div class="full">
        <label>Complaint Description</label>
        <textarea name="description" rows="4" required></textarea>
    </div>

    <div class="full">
        <button type="submit">Submit Complaint</button>
    </div>

</div>

</form>

<div class="footer">
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</div>

</body>
</html>