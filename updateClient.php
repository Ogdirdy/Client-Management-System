<!DOCTYPE html>
<html>

<head>
    <title>Update Client and Request</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 40px;
            font-family: Arial, sans-serif;
            background: linear-gradient(45deg, #3BB9FF, #FFF0DB, lightgreen, pink);
            background-size: 300% 300%;
            animation: color 12s ease-in-out infinite;
        }

        @keyframes color {
            0% {
                background-position: 0 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        h2,
        h3 {
            text-align: center;
            color: #2a4d69;
            margin-bottom: 10px;
        }

        a[href="index.php"],
        a.update-link {
            display: inline-block;
            margin-bottom: 25px;
            background: #2a4d69;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        a[href="index.php"]:hover,
        a.update-link:hover {
            background: red;
        }

        form {
            padding: 20px;
            margin-bottom: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            max-width: 90%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px 15px;
            text-align: center;
        }

        th {
            background: #2a4d69;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        p.success {
            color: green;
            font-weight: bold;
            text-align: center;
            transition: opacity 1s;
        }

        p.error {
            color: red;
            text-align: center;
        }

        textarea,
        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background: #2a4d69;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background: red;
        }
    </style>
</head>

<body>
    <h2>UPDATE CLIENT & REQUEST INFO</h2>
    <a href="index.php">Go to Home</a>

    <?php
    require_once('mysql_connect.php');

    $ClientId = 0;
    if (isset($_GET['ClientId'])) $ClientId = intval($_GET['ClientId']);
    elseif (isset($_POST['ClientId'])) $ClientId = intval($_POST['ClientId']);

    if (isset($_POST['submit']) && $ClientId > 0) {
        $fields = ['FirstName', 'LastName', 'Address', 'ContactName', 'ContactNumber', 'Type', 'Description', 'EstimatedCost', 'StartDate'];
        $errors = [];

        foreach ($fields as $field) {
            if (empty($_POST[$field]) && $field !== 'EstimatedCost') $errors[] = "Please enter $field.";
            else $$field = mysqli_real_escape_string($dbcon, trim($_POST[$field]));
        }

        if (empty($errors)) {
            // UPDATE CLIENT - Their information from the client table
            $sql = "UPDATE Client SET 
                        FirstName='$FirstName', 
                        LastName='$LastName', 
                        Address='$Address', 
                        ContactName='$ContactName', 
                        ContactNumber='$ContactNumber', 
                        Type='$Type' 
                    WHERE ClientId=$ClientId";
            $result = mysqli_query($dbcon, $sql);

            // UPDATE LATEST REQUEST - The rest and esimated cost pertaining to that client
            $reqSql = "UPDATE Request 
                       SET Description='$Description', 
                           EstimatedCost='$EstimatedCost', 
                           StartDate='$StartDate' 
                       WHERE ClientId=$ClientId 
                       ORDER BY RequestId DESC LIMIT 1";
            $reqResult = mysqli_query($dbcon, $reqSql);

            if ($result && $reqResult)
                echo "<p class='success'>Client or Request updated successfully!</p>";
            else
                echo "<p class='error'>Error updating client or request.</p>";
        } else {
            foreach ($errors as $msg) echo "<p class='error'>$msg</p>";
        }
    }

    // Load client and latest request for update form - this is grabbing everything
    if ($ClientId > 0) {
        $sql = "SELECT c.*, 
                       (SELECT Description FROM Request WHERE ClientId=c.ClientId ORDER BY RequestId DESC LIMIT 1) AS LatestRequest,
                       (SELECT EstimatedCost FROM Request WHERE ClientId=c.ClientId ORDER BY RequestId DESC LIMIT 1) AS EstimatedCost,
                       (SELECT StartDate FROM Request WHERE ClientId=c.ClientId ORDER BY RequestId DESC LIMIT 1) AS StartDate
                FROM Client c
                WHERE c.ClientId=$ClientId";

        $result = mysqli_query($dbcon, $sql);

        if (mysqli_num_rows($result) > 0) {
            $client = mysqli_fetch_assoc($result);
    ?>

            <form action="updateClient.php" method="post">
                <input type="hidden" name="ClientId" value="<?php echo $client['ClientId']; ?>">

                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td>First Name:</td>
                        <td><input type="text" name="FirstName" value="<?php echo $client['FirstName']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input type="text" name="LastName" value="<?php echo $client['LastName']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><input type="text" name="Address" value="<?php echo $client['Address']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Contact Name:</td>
                        <td><input type="text" name="ContactName" value="<?php echo $client['ContactName']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Contact Number:</td>
                        <td><input type="text" name="ContactNumber" value="<?php echo $client['ContactNumber']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td>
                            <select name="Type">
                                <option value="">--- choose type ---</option>
                                <option value="Residential" <?php if ($client['Type'] == 'Residential') echo "selected"; ?>>Residential</option>
                                <option value="Commercial" <?php if ($client['Type'] == 'Commercial') echo "selected"; ?>>Commercial</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Latest Request:</td>
                        <td><textarea name="Description" rows="3"><?php echo htmlspecialchars($client['LatestRequest']); ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Estimated Cost:</td>
                        <td><input type="number" name="EstimatedCost" value="<?php echo $client['EstimatedCost']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Start Date:</td>
                        <td><input type="date" name="StartDate" value="<?php echo $client['StartDate']; ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="submit" value="Update Client">
                        </td>
                    </tr>
                </table>
            </form>

    <?php
        }
    }

    //This is to show the table on the update page
    echo "<h3>All Clients</h3>";

    $allClients = mysqli_query($dbcon, "
        SELECT c.*, 
               (SELECT Description FROM Request WHERE ClientId = c.ClientId ORDER BY RequestId DESC LIMIT 1) AS LatestRequest,
               (SELECT EstimatedCost FROM Request WHERE ClientId = c.ClientId ORDER BY RequestId DESC LIMIT 1) AS EstimatedCost,
               (SELECT StartDate FROM Request WHERE ClientId = c.ClientId ORDER BY RequestId DESC LIMIT 1) AS StartDate
        FROM Client c
        ORDER BY c.ClientId ASC
    ");

    if (mysqli_num_rows($allClients) > 0) {
        echo "<table>
                <tr>
                    <th>Client ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Contact Name</th>
                    <th>Contact Number</th>
                    <th>Type</th>
                    <th>Latest Request</th>
                    <th>Estimated Cost</th>
                    <th>Final Cost</th>
                    <th>Start Date</th>
                    <th>Action</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($allClients)) {
            $finalCost = $row['EstimatedCost'] + 50;
            echo "<tr>
                    <td>{$row['ClientId']}</td>
                    <td>{$row['FirstName']}</td>
                    <td>{$row['LastName']}</td>
                    <td>{$row['Address']}</td>
                    <td>{$row['ContactName']}</td>
                    <td>{$row['ContactNumber']}</td>
                    <td>{$row['Type']}</td>
                    <td>" . htmlspecialchars($row['LatestRequest']) . "</td>
                    <td>{$row['EstimatedCost']}</td>
                    <td>{$finalCost}</td>
                    <td>{$row['StartDate']}</td>
                    <td><a class='update-link' href='updateClient.php?ClientId={$row['ClientId']}'>Update</a></td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No clients found.</p>";
    }

    mysqli_close($dbcon);
    ?>


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const successMsgs = document.querySelectorAll('.success');
            successMsgs.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 1000);
                }, 3000);
            });
        });
    </script>

</body>

</html>
