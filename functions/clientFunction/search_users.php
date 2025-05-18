<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include '../../dbConnect.php';

try {
    $searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
    
    if (empty($searchTerm)) {
        echo json_encode(['success' => true, 'users' => []]);
        exit;
    }

    $searchTerm = "%{$searchTerm}%";
    
    // Search for doctors
    $query = "
        SELECT 
            a.id,
            d.name,
            d.image,
            d.specialty,
            d.subspecialty
        FROM accounts a
        JOIN doctors d ON a.id = d.account_id
        WHERE a.role = 'doctor'
        AND (
            d.name LIKE ? OR 
            d.specialty LIKE ? OR 
            d.subspecialty LIKE ?
        )
        ORDER BY d.name ASC
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $users = [];
        
        while ($row = $result->fetch_assoc()) {
            // Format image path
            if ($row['image']) {
                $row['image'] = '/FinalsWeb/' . $row['image'];
            } else {
                $row['image'] = '/FinalsWeb/img/default-doctor.png';
            }
            $users[] = $row;
        }
        
        echo json_encode(['success' => true, 'users' => $users]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?> 