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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $receiver_fullname = isset($_GET['receiver_fullname']) ? trim($_GET['receiver_fullname']) : '';
    $sender_fullname = isset($_SESSION['fname']) ? trim($_SESSION['fname']) : '';

    if (empty($receiver_fullname) || empty($sender_fullname)) {
        echo 'Invalid input data.';
        exit;
    }

    // Get receiver_id and sender_id based on full names
    $stmt = $dbh->prepare("SELECT id FROM tblusers WHERE FullName = :fullname");
    $stmt->bindParam(':fullname', $receiver_fullname, PDO::PARAM_STR);
    $stmt->execute();
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($receiver) {
        $receiver_id = $receiver['id'];
    } else {
        echo 'Receiver not found.';
        exit;
    }

    $stmt = $dbh->prepare("SELECT id FROM tblusers WHERE FullName = :fullname");
    $stmt->bindParam(':fullname', $sender_fullname, PDO::PARAM_STR);
    $stmt->execute();
    $sender = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($sender) {
        $sender_id = $sender['id'];
    } else {
        echo 'Sender not found.';
        exit;
    }

    // Fetch messages between the two users
    $stmt = $dbh->prepare("
        SELECT m.*, u1.FullName as sender_name, u2.FullName as receiver_name
        FROM messages m
        JOIN tblusers u1 ON m.sender_id = u1.id
        JOIN tblusers u2 ON m.receiver_id = u2.id
        WHERE (m.sender_id = :sender_id AND m.receiver_id = :receiver_id) 
        OR (m.sender_id = :receiver_id AND m.receiver_id = :sender_id)
        ORDER BY m.timestamp ASC
    ");
    $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
    $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML for messages
    $output = '';
    foreach ($messages as $message) {
        if ($message['sender_id'] == $sender_id) {
            $output .= '<div class="message sender"><strong>' . htmlspecialchars($sender_fullname) . ':</strong> ' . htmlspecialchars($message['message']) . '</div>';
        } else {
            $output .= '<div class="message receiver"><strong>' . htmlspecialchars($receiver_fullname) . ':</strong> ' . htmlspecialchars($message['message']) . '</div>';
        }
    }

    echo $output;
} else {
    echo 'Invalid request method.';
}
?>
