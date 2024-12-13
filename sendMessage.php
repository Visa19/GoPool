<?php
session_start();
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $receiver_fullname = isset($_POST['receiver_fullname']) ? trim($_POST['receiver_fullname']) : '';
    $sender_fullname = isset($_SESSION['fname']) ? $_SESSION['fname'] : '';

    if ($message && $receiver_fullname && $sender_fullname) {
        // Get receiver_id and sender_id based on full names
        $stmt = $dbh->prepare("SELECT id FROM tblusers WHERE FullName = :fullname");
        $stmt->bindParam(':fullname', $receiver_fullname, PDO::PARAM_STR);
        $stmt->execute();
        $receiver = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($receiver) {
            $receiver_id = $receiver['id'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Receiver not found.']);
            exit;
        }

        $stmt = $dbh->prepare("SELECT id FROM tblusers WHERE FullName = :fullname");
        $stmt->bindParam(':fullname', $sender_fullname, PDO::PARAM_STR);
        $stmt->execute();
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($sender) {
            $sender_id = $sender['id'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sender not found.']);
            exit;
        }

        // Insert the message into the database
        $stmt = $dbh->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (:sender_id, :receiver_id, :message, NOW())");
        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Message sent.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
