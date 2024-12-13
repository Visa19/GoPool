<?php
session_start();
error_reporting(0);
require_once './classes/DbConnector.php';

use classes\DbConnector;

try {
    $dbConnector = new DbConnector();
    $dbh = $dbConnector->getConnection();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoPool | ChatBox</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .message {
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .message.sender {
            background-color: #d1e7dd;
            text-align: left;
        }

        .message.receiver {
            background-color: #f8d7da;
            text-align: right;
        }

        #chatPartnerName {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!--Header-->
    <?php include('includes/header.php'); ?>

    <section class="page-header listing_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>ChatBox</h1>
                </div>
            </div>
        </div>
        <!-- Dark Overlay-->
        <div class="dark-overlay"></div>
    </section>
    <br>
    <br>

    <!-- Chatbox Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4>Select User to Chat</h4>
                <input type="text" id="searchUser" class="form-control mb-3" placeholder="Search for users...">
                <ul id="userList" class="list-group">
                    <?php 
                    $userFullName = $_SESSION['fname'];
                    $sql = "SELECT FullName FROM tblusers WHERE FullName != :userFullName";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':userFullName', $userFullName, PDO::PARAM_STR);
                    $query->execute();
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li class="list-group-item user" data-fullname="' . $row['FullName'] . '">' . $row['FullName'] . '</li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-8">
<!--                <h4>Your Chat</h4>-->
                <div id="chatPartnerName"></div>
                <div id="chatBox" class="border p-3 mb-3" style="height: 300px; overflow-y: scroll;"></div>
                <textarea id="message" class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                <button id="sendMessage" class="btn btn-primary mt-2">Send</button>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        let selectedUserFullName = null;

        $('.user').click(function() {
            selectedUserFullName = $(this).data('fullname');
            $('#chatPartnerName').text(selectedUserFullName);
            loadMessages();
        });

        $('#sendMessage').click(function() {
            const message = $('#message').val();
            if (message.trim() !== '' && selectedUserFullName !== null) {
                $.post('sendMessage.php', {
                    message: message,
                    receiver_fullname: selectedUserFullName
                }, function(response) {
                    $('#message').val('');
                    loadMessages();
                });
            }
        });

        function loadMessages() {
            if (selectedUserFullName !== null) {
                $.get('getMessages.php', { receiver_fullname: selectedUserFullName }, function(data) {
                    $('#chatBox').html(data);
                    $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
                });
            }
        }

        $('#searchUser').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('#userList .user').each(function() {
                const userName = $(this).text().toLowerCase();
                $(this).toggle(userName.includes(searchText));
            });
        });

        setInterval(function() {
            if (selectedUserFullName !== null) {
                loadMessages();
            }
        }, 5000);
    });
    </script>
</body>
</html>
