<!DOCTYPE html>
<html>
<head>
    <title>Add New Client and Request</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            background: linear-gradient(45deg, #3BB9FF, #FFF0DB, lightgreen, pink);
            background-size: 300% 300%;
            animation: color 12s ease-in-out infinite;
        }

        @keyframes color {
            0% { background-position: 0 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            color: #2a4d69;
            margin-bottom: 30px;
            text-align: center;
        }

        form {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 500px;
            max-width: 90%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px 5px;
        }

        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea { resize: none; }

        .form-buttons {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        input[type="submit"], input[type="reset"] {
            background: #2a4d69;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        input[type="submit"]:hover, input[type="reset"]:hover {
            background: red;
        }

        input[type="reset"] { margin-left: 10px; }

        a[href="index.php"] {
            display: block;
            margin-top: 20px;
            text-align: center;
            background: #2a4d69;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: 0.3s;
            width: fit-content;
        }

        a[href="index.php"]:hover { background: red; }

        .success {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            transition: opacity 1s ease;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<h2>ADD NEW CLIENT & REQUEST</h2>

<form action="insertClient.php" method="post">

<?php

if (isset($_POST['submit'])) {
    require_once('mysql_connect.php');
    $errors = [];

    $fields = ['FirstName', 'LastName', 'Address', 'ContactName', 'ContactNumber', 'Type', 'Description', 'EstimatedCost'];
    foreach ($fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Please enter $field.";
        } else {
            $$field = mysqli_real_escape_string($dbcon, trim($_POST[$field]));
        }
    }

    if (empty($errors)) {

        // Insert client
        $query = "INSERT INTO Client (FirstName, LastName, Address, ContactName, ContactNumber, Type)
                  VALUES ('$FirstName','$LastName','$Address','$ContactName','$ContactNumber','$Type')";
        $result = mysqli_query($dbcon, $query);

        if ($result) {

            // Get the new ClientID so this can match back with the request..
            $selectQuery = "SELECT ClientId FROM Client 
                            WHERE FirstName='$FirstName' 
                            AND LastName='$LastName' 
                            AND Address='$Address' 
                            AND ContactNumber='$ContactNumber'
                            ORDER BY ClientId DESC LIMIT 1";
            $selectResult = mysqli_query($dbcon, $selectQuery);

            if ($selectResult && mysqli_num_rows($selectResult) > 0) {
                $row = mysqli_fetch_assoc($selectResult);
                $clientId = $row['ClientId'];

                // This is to auto increment the date
                $dateOfRequest = date('Y-m-d');
                $startDate = date('Y-m-d', strtotime('+5 days')); // % days from the request

                //  EstimatedCost + 50 - Auto Incremented as well, will all be displayed in the tables
                $FinalCost = $EstimatedCost + 50;

                // Insert request - get request information
                $reqQuery = "INSERT INTO Request (ClientId, DateOfRequest, StartDate, Description, EstimatedCost, FinalCost)
                             VALUES ('$clientId', '$dateOfRequest', '$startDate', '$Description', '$EstimatedCost', '$FinalCost')";
                $reqResult = mysqli_query($dbcon, $reqQuery);

                if ($reqResult) {
                    echo "<div class='success'>Client and Request added successfully!</div>";
                } else {
                    echo "<p class='error'>Error adding request.</p>";
                }

            } else {
                echo "<p class='error'>Could not retrieve Client ID.</p>";
            }

        } else {
            echo "<p class='error'>Error adding client.</p>";
        }
    } else {
        foreach ($errors as $msg) {
            echo "<p class='error'>$msg</p>";
        }
    }

    mysqli_close($dbcon);
}
?>

<table>
    <tr>
        <td>First Name:</td>
        <td><input type="text" name="FirstName" required placeholder="Deron"></td>
    </tr>
    <tr>
        <td>Last Name:</td>
        <td><input type="text" name="LastName" required placeholder="Barker"></td>
    </tr>
    <tr>
        <td>Address:</td>
        <td><input type="text" name="Address" required placeholder="493 HelpMePlease"></td>
    </tr>
    <tr>
        <td>Contact Name:</td>
        <td><input type="text" name="ContactName" placeholder="Parent/Guardian Name"></td>
    </tr>
    <tr>
        <td>Contact Number:</td>
        <td><input type="text" name="ContactNumber" required placeholder="123-456-7890"></td>
    </tr>
    <tr>
        <td>Type:</td>
        <td>
            <select name="Type" required>
                <option value="">--- choose type ---</option>
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
            </select>
        </td>
    </tr>

    <tr>
        <td>Request Description:</td>
        <td><textarea name="Description" rows="3" required placeholder="Enter request details..."></textarea></td>
    </tr>

    <tr>
        <td>Estimated Cost ($):</td>
        <td><input type="text" name="EstimatedCost" required placeholder="1500"></td>
    </tr>
</table>


<div class="form-buttons">
    <input type="submit" name="submit" value="Add Client and Request">
    <input type="reset" value="Reset">
</div>

<a href="index.php">Go to Home</a>

</form>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const successMsg = document.querySelector('.success');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 1000);
        }, 3000);
    }
});
</script>

</body>
</html>
