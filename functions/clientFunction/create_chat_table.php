<?php
include '../../dbConnect.php';

// Create chat_messages table
$sql = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (sender_id) REFERENCES clients(account_id),
    FOREIGN KEY (receiver_id) REFERENCES doctors(account_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Chat messages table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?> 