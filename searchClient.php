<!DOCTYPE html>
<html>
<head>
    <title>Search Clients</title>
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
            0% { background-position: 0 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        input[type="text"], select {
            padding: 8px 12px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 200px;
        }

        input[type="submit"] {
            background: #2a4d69;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            margin-left: 10px;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background: red;
        }

        a[href="index.php"], a.update-link {
            display: inline-block;
            margin-top: 10px;
            background: #2a4d69;
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        a[href="index.php"]:hover, a.update-link:hover {
            background: red;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            margin-bottom: 40px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
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

        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>SEARCH CLIENTS</h2>

    <form action="searchClient.php" method="post">
        <input type="text" name="FirstName" placeholder="First Name">
        <input type="text" name="LastName" placeholder="Last Name">
        <br>
        <select name="Type">
            <option value="">--- choose type ---</option>
            <option value="Residential">Residential</option>
            <option value="Commercial">Commercial</option>
        </select>
        <br>
        <input type="submit" name="submit" value="Search">
        <br>
        <a href="index.php">Go to Home</a>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        require_once('mysql_connect.php');

        $criteria = [];
        if (!empty($_POST['FirstName'])) 
            $criteria[] = "c.FirstName LIKE '" . mysqli_real_escape_string($dbcon, $_POST['FirstName']) . "%'";
        if (!empty($_POST['LastName'])) 
            $criteria[] = "c.LastName LIKE '" . mysqli_real_escape_string($dbcon, $_POST['LastName']) . "%'";
        if (!empty($_POST['Type'])) 
            $criteria[] = "c.Type='" . mysqli_real_escape_string($dbcon, $_POST['Type']) . "'";

        // Join with latest request for each client
        $sql = "
            SELECT 
    c.ClientId, c.FirstName, c.LastName, c.ContactName, c.ContactNumber, c.Type,
    COUNT(r.RequestId) AS TotalRequests,
    r.Description AS LatestRequest,
    r.EstimatedCost,
    r.FinalCost,
    r.StartDate
FROM Client c
LEFT JOIN Request r 
    ON r.RequestId = (
        SELECT RequestId 
        FROM Request 
        WHERE ClientId = c.ClientId 
        ORDER BY RequestId DESC 
        LIMIT 1
    )";

        if (!empty($criteria)) $sql .= " WHERE " . implode(" AND ", $criteria);

        $sql .= " GROUP BY c.ClientId ORDER BY c.LastName ASC";

        $result = mysqli_query($dbcon, $sql);
        $num = mysqli_num_rows($result);

        if ($num > 0) {
            echo "<p>$num clients found.</p>";
            echo "<table>
                    <tr>
                        <th>Client ID</th>
                        <th>Name</th>
                        <th>Contact Name</th>
                        <th>Contact Number</th>
                        <th>Type</th>
                        <th>Total Requests</th>
                        <th>Latest Request</th>
                        <th>Estimated Cost</th>
                        <th>Final Cost</th>
                        <th>Start Date</th>
                        <th>Actions</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['ClientId']}</td>
                        <td>{$row['FirstName']} {$row['LastName']}</td>
                        <td>{$row['ContactName']}</td>
                        <td>{$row['ContactNumber']}</td>
                        <td>{$row['Type']}</td>
                        <td>{$row['TotalRequests']}</td>
                        <td>{$row['LatestRequest']}</td>
                        <td>{$row['EstimatedCost']}</td>
                        <td>{$row['FinalCost']}</td>
                        <td>{$row['StartDate']}</td>
                        <td><a class='update-link' href='updateClient.php?ClientId={$row['ClientId']}'>Update</a></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No clients found.</p>";
        }

        mysqli_close($dbcon);
    }
    ?>
</body>
</html>
