<!DOCTYPE html>
<html>
<head>
    <title>PHP Calculator</title>
</head>
<body>

<h2>Simple PHP Calculator</h2>

<form method="post">
    Enter First Number:
    <input type="number" name="num1" required><br><br>

    Enter Second Number:
    <input type="number" name="num2" required><br><br>

    Select Operation:
    <select name="operation">
        <option value="add">Addition</option>
        <option value="sub">Subtraction</option>
        <option value="mul">Multiplication</option>
        <option value="div">Division</option>
    </select><br><br>

    <input type="submit" name="calculate" value="Calculate">
</form>

<?php
if(isset($_POST['calculate'])){

    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operation = $_POST['operation'];
    $result = 0;

    switch($operation){
        case "add":
            $result = $num1 + $num2;
            echo "<h3>Result: $num1 + $num2 = $result</h3>";
            break;

        case "sub":
            $result = $num1 - $num2;
            echo "<h3>Result: $num1 - $num2 = $result</h3>";
            break;

        case "mul":
            $result = $num1 * $num2;
            echo "<h3>Result: $num1 × $num2 = $result</h3>";
            break;

        case "div":
            if($num2 != 0){
                $result = $num1 / $num2;
                echo "<h3>Result: $num1 ÷ $num2 = $result</h3>";
            } else {
                echo "<h3>Cannot divide by zero!</h3>";
            }
            break;
    }
}
?>

</body>
</html>