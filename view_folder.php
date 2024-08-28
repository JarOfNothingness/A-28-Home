<?php
include("../LoginRegisterAuthentication/connection.php");

$folder_id = $_GET['folder_id'];

$query = "SELECT file_name, file_path FROM fileserver_files WHERE folder_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $folder_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Files in this folder</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<a href='" . htmlspecialchars($row['file_path']) . "' download>" . htmlspecialchars($row['file_name']) . "</a><br>";
}
?>
