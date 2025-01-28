<?php
require_once '../includes/functions.php';

// Add error handling
try {
    header('Content-Type: application/json');

    $seed = isset($_GET['seed']) ? intval($_GET['seed']) : 42;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $batchSize = isset($_GET['batchSize']) ? intval($_GET['batchSize']) : 10; // Default batch size
    $language = isset($_GET['language']) ? $_GET['language'] : 'en_US';
    $likes = isset($_GET['likes']) ? floatval($_GET['likes']) : 5.0;
    $reviews = isset($_GET['reviews']) ? floatval($_GET['reviews']) : 4.7;

    // Generate book data for the current page/batch
    $books = generateBookData($seed, $page, $language, $likes, $reviews, $batchSize);

    // Slice the books array to return only the current batch
    $batchBooks = array_slice($books, 0, $batchSize);

    echo json_encode([
        'success' => true,
        'data' => $batchBooks
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}