<?php
include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Email already registered";
    } else {
        $sql = "INSERT INTO users (full_name, email, password)
                VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $message = "Registration successful";
        } else {
            $message = "Error occurred";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ResolveX | Register</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 30px;
    background-color: #f5f5f5;
}
h2 {
    color: #333;
}
input, select, textarea, button {
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 300px;
}
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background-color: #45a049;
}
table {
    border-collapse: collapse;
    width: 80%;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
th {
    background-color: #4CAF50;
    color: white;
}
a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}
a:hover {
    color: #4CAF50;
}
</style>
</head>
<body>
    <h2>User Registration</h2>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Register</button>
    </form>

    <p><?php echo $message; ?></p>
</body>
</html>