<?php

use App\App;

require_once __DIR__ . '/vendor/autoload.php';

$app = new App();

// Function to render the HTML head with styles and scripts
function renderHead(): void {
    echo "<head>
    <link rel='stylesheet' href='http://design.wikimedia.org/style-guide/css/build/wmui-style-guide.min.css'>
    <link rel='stylesheet' href='styles.css'>
    <script src='main.js'></script>
    </head>";
}

// Function to fetch the article body if a title is provided
function fetchArticle(App $app, string $title): string {
    $filePath = sprintf('articles/%s', $title);
    return file_exists($filePath) ? file_get_contents($filePath) : '';
}

// Function to render the header with word count information
function renderHeader(string $wordCount): void {
    echo "<div id='header' class='header'>
    <a href='/'>Article editor</a>
    <div>$wordCount</div>
    </div>";
}

// Function to render the article creation/editing form
function renderForm(string $title, string $body): void {
    echo "<div class='main'>
    <h2>Create/Edit Article</h2>
    <p>Create a new article by filling out the fields below. Edit an article by typing the beginning of the title in the title field, selecting the title from the auto-complete list, and changing the text in the text field.</p>
    <form action='index.php' method='post'>
        <input name='title' type='text' placeholder='Article title...' value='" . htmlentities($title) . "'>
        <br />
        <textarea name='body' placeholder='Article body...'>" . htmlentities($body) . "</textarea>
        <br />
        <button class='submit-button' type='submit'>Submit</button>
    </form>
    <h2>Preview</h2>
    <div>
        <h3>" . htmlentities($title) . "</h3>
        <p>" . nl2br(htmlentities($body)) . "</p>
    </div>
    </div>";
}

// Function to render the list of articles
function renderArticlesList(App $app): void {
    $articles = $app->getListOfArticles();
    echo "<h2>Articles</h2><ul>";
    foreach ($articles as $article) {
        echo "<li><a href='index.php?title=" . urlencode($article) . "'>" . htmlentities($article) . "</a></li>";
    }
    echo "</ul>";
}

// Function to save the article content to a file
function saveArticle(App $app, string $title, string $body): void {
    $filePath = sprintf('articles/%s', htmlentities($title));
    $app->save($filePath, $body);
}

// Function to count total words across all articles
function getWordCount(): string {
    $directory = 'articles/';
    $totalWordCount = 0;

    if (is_dir($directory)) {
        $files = new DirectoryIterator($directory);

        // Use a loop to process files, avoid loading entire file into memory
        foreach ($files as $fileInfo) {
            // Skip directories and non-files
            if ($fileInfo->isDot() || !$fileInfo->isFile()) {
                continue;
            }

            // Open the file as a stream instead of loading it entirely into memory
            $filePath = $directory . $fileInfo->getFilename();
            $fileHandle = fopen($filePath, 'r');

            if ($fileHandle) {
                // Process the file in chunks (e.g., 1024 bytes at a time)
                while (!feof($fileHandle)) {
                    $chunk = fread($fileHandle, 1024);  // Read in chunks to avoid large memory consumption
                    // Count words in the current chunk and add to the total
                    $totalWordCount += str_word_count($chunk);
                }

                // Close the file to free resources
                fclose($fileHandle);
            }
        }
    }

    return "$totalWordCount words written";
}

// Render HTML head
renderHead();

// Fetch article details if 'title' is set in the URL
$title = '';
$body = '';
if (isset($_GET['title'])) {
    $title = htmlentities($_GET['title']);
    $body = fetchArticle($app, $title);
}

// Display the word count of all articles
$wordCount = getWordCount();

// Render the header and main content (form and articles list)
echo "<body>";
renderHeader($wordCount);
echo "<div class='page'>";
renderForm($title, $body);
renderArticlesList($app);
echo "</div>";
echo "</body>";

// Handle form submission for saving an article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['body'])) {
    saveArticle($app, $_POST['title'], $_POST['body']);
}