<?php

use App\App;

require_once __DIR__ . '/vendor/autoload.php';

$app = new App();

// Set header for JSON response format
header('Content-Type: application/json');

// Main API Router
$response = handleRequest($app);
echo json_encode($response);

/**
 * Handle incoming API requests and determine the appropriate response.
 *
 * @param App $app The application instance for fetching articles.
 * @return array The response content to be encoded as JSON.
 */
function handleRequest(App $app): array {
    // If no specific query parameters, return the list of articles.
    if (!isset($_GET['title']) && !isset($_GET['prefixsearch'])) {
        return getAllArticles($app);
    }

    // Handle prefix search requests.
    if (isset($_GET['prefixsearch'])) {
        return searchArticlesByPrefix($app, $_GET['prefixsearch']);
    }

    // Fetch individual article by title or other parameters.
    return getArticleDetails($app, $_GET);
}

/**
 * Retrieve a list of all available articles.
 *
 * @param App $app The application instance.
 * @return array List of all articles.
 */
function getAllArticles(App $app): array {
    return ['content' => $app->getListOfArticles()];
}

/**
 * Search articles by a prefix string (case-insensitive).
 *
 * @param App $app The application instance.
 * @param string $prefix The prefix string to search for.
 * @return array List of articles matching the prefix.
 */
function searchArticlesByPrefix(App $app, string $prefix): array {
    $allArticles = $app->getListOfArticles();
    $matchedArticles = [];

    // Filter articles that start with the given prefix (case-insensitive).
    foreach ($allArticles as $article) {
        if (stripos($article, $prefix) === 0) {
            $matchedArticles[] = $article;
        }
    }

    return ['content' => $matchedArticles];
}

/**
 * Fetch article details based on query parameters (e.g., title).
 *
 * @param App $app The application instance.
 * @param array $queryParams The query parameters from the request.
 * @return array Article details or an empty response.
 */
function getArticleDetails(App $app, array $queryParams): array {
    return ['content' => $app->fetch($queryParams)];
}