<?php
session_start(); // Start the session at the very top

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("../LoginRegisterAuthentication/connection.php");
include('../crud/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Server</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .upload-form {
            width: 40%;
        }
        .file-list {
            width: 55%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            color: white;
            background-color: #007bff;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <h1>File Server</h1>

    <div class="container">
        <!-- File Upload Form -->
        <div class="upload-form">
            <h2>Upload File</h2>
            <form action="fileserver.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload" required>
                <br><br>
                <input type="submit" value="Upload File" name="submit" class="btn">
            </form>
        </div>

        <!-- File List -->
        <div class="file-list">
            <h2>Available Files</h2>
            <table>
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Download</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $directory = 'uploads/'; // Directory where files are stored
                    $user_directory = 'uploads/' . $_SESSION['username'] . '/'; // User-specific directory

                    // Create user-specific directory if it doesn't exist
                    if (!file_exists($user_directory)) {
                        mkdir($user_directory, 0777, true);
                    }

                    // Handle file upload
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                        $target_file = $user_directory . basename($_FILES["fileToUpload"]["name"]);
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            echo "<div class='alert alert-success'>The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
                        }
                    }

                    // Handle file deletion
                    if (isset($_GET['delete'])) {
                        $fileToDelete = basename($_GET['delete']);
                        $filePath = $user_directory . $fileToDelete;
                        if (file_exists($filePath)) {
                            unlink($filePath);
                            echo "<div class='alert alert-success'>The file $fileToDelete has been deleted.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>File not found.</div>";
                        }
                    }

                    // Display files in the user-specific directory
                    $files = scandir($user_directory);
                    foreach ($files as $file) {
                        if ($file !== '.' && $file !== '..') {
                            echo "<tr>
                                    <td>" . htmlspecialchars($file) . "</td>
                                    <td><a href=\"{$user_directory}" . urlencode($file) . "\" download>Download</a></td>
                                    <td><a href=\"fileserver.php?delete=" . urlencode($file) . "\" class=\"btn btn-danger\" onclick=\"return confirm('Are you sure you want to delete this file?');\">Delete</a></td>
                                  </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
