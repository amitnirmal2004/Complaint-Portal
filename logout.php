<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="refresh" content="1;url=login.php">
<title>Logging out...</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 50px;
    text-align: center;
}
p {
    font-weight: bold;
    color: #333;
    font-size: 18px;
}
</style>
</head>
<body>
<p>Logging out, please wait...</p>
</body>
</html>