<?php
include("../LoginRegisterAuthentication/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folder_id = $_POST['folder_id'];
    $input_password = $_POST['password'];

    $query = "SELECT folder_password FROM fileserver_folders WHERE folder_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $folder_id);
    $stmt->execute();
    $stmt->bind_result($folder_password);
    $stmt->fetch();

    if (password_verify($input_password, $folder_password)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
