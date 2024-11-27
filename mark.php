<?php
include('db_connect.php');
$sql = "
    SELECT 
        students.id AS student_id, 
        students.name, 
        marks.physics, 
        marks.chemistry, 
        marks.maths, 
        marks.total 
    FROM students 
    LEFT JOIN marks ON students.id = marks.student_id
    ORDER BY students.id";
$pdo = $GLOBALS["pdoconnection"];
$query = $pdo->prepare($sql);
$query->execute();
$data = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Entry</title>
    <style>
        h1 {
            text-align: center;
            font-family: Arial, sans-serif;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form div {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        form input {
            width: 85%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #8fb38f;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        form button:hover {
            background-color: #647d64;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color:#8fb38f;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
   
    <h1>Mark Entry</h1>

    <form action="marks_action.php" method="POST">
        <div>
            <label for="student_id">Student ID:</label>
            <input type="number" id="student_id" name="student_id" required>
        </div>
        <div>
            <label for="physics">Physics:</label>
            <input type="number" id="physics" name="physics" required>
        </div>
        <div>
            <label for="chemistry">Chemistry:</label>
            <input type="number" id="chemistry" name="chemistry" required>
        </div>
        <div>
            <label for="maths">Maths:</label>
            <input type="number" id="maths" name="maths" required>
        </div>
        <div>
            <button type="submit">Submit Marks</button>
        </div>
    </form>

    <h1>Student Entries</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Physics</th>
                <th>Chemistry</th>
                <th>Maths</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)): ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars(isset($row['physics']) ? $row['physics'] : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(isset($row['chemistry']) ? $row['chemistry'] : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(isset($row['maths']) ? $row['maths'] : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars(isset($row['total']) ? $row['total'] : 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>
