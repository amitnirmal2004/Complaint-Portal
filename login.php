<?php
session_start();
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['full_name'];

            header("Location: dashboard.php");
            exit;

        } else {
            $message = "Invalid Password!";
        }

    } else {
        $message = "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>ResolveX | Secure Login</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.wrapper{
    display:flex;
    gap:60px;
    align-items:center;
}

/* Left Panel */
.left-panel{
    color:white;
    max-width:350px;
}

.left-panel h1{
    margin:0;
    font-size:30px;
    font-weight:600;
}

.datetime{
    margin-top:15px;
    font-size:14px;
    opacity:0.9;
}

/* Login Card */
.card{
    background:white;
    width:380px;
    padding:40px;
    border-radius:18px;
    box-shadow:0 20px 45px rgba(0,0,0,0.35);
    animation:fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

.logo{
    text-align:center;
    font-size:26px;
    font-weight:700;
    color:#2563eb;
    margin-bottom:5px;
}

.subtitle{
    text-align:center;
    font-size:13px;
    color:#6b7280;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #d1d5db;
    font-size:14px;
}

input:focus{
    outline:none;
    border:1px solid #2563eb;
}

button{
    width:100%;
    padding:12px;
    background:#2563eb;
    border:none;
    color:white;
    font-weight:600;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#1d4ed8;
}

.error{
    color:red;
    font-size:13px;
    margin-bottom:10px;
    text-align:center;
}

.footer{
    margin-top:15px;
    text-align:center;
    font-size:12px;
    color:#9ca3af;
}
</style>
</head>

<body>

<div class="wrapper">

<div class="left-panel">
    <h1 id="greeting"></h1>
    <div class="datetime" id="datetime"></div>
</div>

<div class="card">

<div class="logo">ResolveX</div>
<div class="subtitle">Complaint Management Portal</div>

<?php if($message != ""){ ?>
<div class="error"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <button type="submit">Secure Login</button>
</form>

<div class="footer">
© 2026 ResolveX. All rights reserved.
</div>

</div>

</div>

<script>
function updateDateTime(){
    const now = new Date();
    const hours = now.getHours();

    let greetingText = "";

    if(hours < 12){
        greetingText = "Welcome 👋 Good Morning";
    } 
    else if(hours < 18){
        greetingText = "Welcome 👋 Good Afternoon";
    } 
    else {
        greetingText = "Welcome 👋 Good Evening";
    }

    document.getElementById("greeting").innerText = greetingText;

    document.getElementById("datetime").innerText =
        now.toLocaleDateString() + " | " + now.toLocaleTimeString();
}

setInterval(updateDateTime,1000);
updateDateTime();
</script>

</body>
</html>