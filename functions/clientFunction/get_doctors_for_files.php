<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

include '../../dbConnect.php';

try {
    // First, let's verify the database connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Let's check the accounts table first
    $checkAccounts = $conn->query("SELECT COUNT(*) as count FROM accounts WHERE role = 'doctor'");
    $doctorAccounts = $checkAccounts->fetch_assoc()['count'];

    // Then check the doctors table
    $checkDoctors = $conn->query("SELECT COUNT(*) as count FROM doctors");
    $totalDoctors = $checkDoctors->fetch_assoc()['count'];

    // Get a sample doctor record to verify the data
    $sampleDoctor = $conn->query("
        SELECT a.id, a.role, d.name, d.account_id 
        FROM accounts a 
        LEFT JOIN doctors d ON a.id = d.account_id 
        WHERE a.role = 'doctor' 
        LIMIT 1
    ");
    $sampleData = $sampleDoctor->fetch_assoc();

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    
    // Base query
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
    ";
    
    // Add search conditions if search term exists
    if (!empty($searchTerm)) {
        $searchTerm = "%{$searchTerm}%";
        $query .= " AND (
            d.name LIKE ? OR 
            d.specialty LIKE ? OR 
            d.subspecialty LIKE ?
        )";
    }
    
    $query .= " ORDER BY d.name ASC";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameters if search term exists
    if (!empty($searchTerm)) {
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    }
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $doctors = [];
        
        // Debug: Check if we got any results
        $num_rows = $result->num_rows;
        
        while ($row = $result->fetch_assoc()) {
            // Ensure image path is properly formatted
            if ($row['image']) {
                $row['image'] = '/FinalsWeb/' . $row['image'];
            } else {
                $row['image'] = '/FinalsWeb/img/default-doctor.png';
            }
            $doctors[] = $row;
        }
        
        // Debug: Add detailed information to response
        echo json_encode([
            'success' => true, 
            'doctors' => $doctors,
            'debug' => [
                'num_rows' => $num_rows,
                'query' => $query,
                'search_term' => $searchTerm,
                'doctor_accounts_count' => $doctorAccounts,
                'total_doctors_count' => $totalDoctors,
                'sample_doctor' => $sampleData
            ]
        ]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error retrieving doctors: ' . $e->getMessage(),
        'debug' => [
            'error' => $e->getMessage(),
            'query' => $query ?? 'Query not set'
        ]
    ]);
}

$stmt->close();
$conn->close();
?> 