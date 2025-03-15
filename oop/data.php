<?php
session_start();

// Initialize students array if not already set
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

// Get the incoming request method (POST or GET)
$requestMethod = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false];

if ($requestMethod === 'POST') {
    // Get the raw POST data
    $input = json_decode(file_get_contents('php://input'), true);

    // Handle actions based on the "action" parameter
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'add':
                $student = $input['student'];
                $_SESSION['students'][] = $student;
                $response['success'] = true;
                break;
            
            case 'delete':
                $index = $input['index'];
                if (isset($_SESSION['students'][$index])) {
                    unset($_SESSION['students'][$index]);
                    $_SESSION['students'] = array_values($_SESSION['students']); // Reindex array
                    $response['success'] = true;
                }
                break;
            
            case 'edit':
                $index = $input['index'];
                $student = $input['student'];
                if (isset($_SESSION['students'][$index])) {
                    $_SESSION['students'][$index] = $student;
                    $response['success'] = true;
                }
                break;
        }
    }
} elseif ($requestMethod === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
        $response['students'] = $_SESSION['students'];
        $response['success'] = true;
    }
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);