<!DOCTYPE html>
<html>

<head>
    <title>Budget Builders - Client Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;

            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            background: linear-gradient(45deg, #3BB9FF, #C5908E, lightgreen, pink);
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

        h1 {
            color: #2a4d69;
            margin-bottom: 40px;
            text-align: center;
        }

        .dashboard-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 50px;
        }

        .dashboard-links a {
            display: inline-block;
            background: #2a4d69;
            color: #fff;
            text-decoration: none;
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
        }

        .dashboard-links a:hover {
            background: red;
        }

        p {
            color: #666;
            margin-top: 50px;
            text-align: center;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h1>Budget Builders - Client Management</h1>

    <div class="dashboard-links">
        <a href="insertClient.php">Add New Client & Request</a>
        <a href="searchClient.php">Search Clients</a>
        <a href="updateClient.php">Update Client & Request Info</a>
    </div>

    <p>Select an action above to manage client records.</p>
</body>

</html>