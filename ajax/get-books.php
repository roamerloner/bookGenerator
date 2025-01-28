<?php
require_once '../includes/functions.php';

try {
    header('Content-Type: application/json');

    $seed = isset($_GET['seed']) ? intval($_GET['seed']) : 42;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $batchSize = isset($_GET['batchSize']) ? intval($_GET['batchSize']) : 20;
    $language = isset($_GET['language']) ? $_GET['language'] : 'en_US';
    $likes = isset($_GET['likes']) ? floatval($_GET['likes']) : 5.0;
    $reviews = isset($_GET['reviews']) ? floatval($_GET['reviews']) : 4.7;

    
    $books = generateBookData($seed, $offset, $language, $likes, $reviews, $batchSize);

    echo json_encode([
        'success' => true,
        'data' => $books
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

