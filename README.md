# PHP Text Editor App - Assessment Submission
## Project Overview
This repository contains an application for viewing and editing text documents. The users can:

* Create new pages
* Edit existing pages
* View a list of available content

As part of this assessment, I have addressed specific TODO tasks in both `api.php` and `index.php`, following the given instructions to improve readability, performance, and security.

## Files Added
- `apiCopy.php`: Refactored, added documentation, improved performance and security.
- `indexCopy.php`: Refactored HTML structure, added documentation, optimized word count function.

## Approach to Task Completion

### 1. `api.php` Improvements
#### Task A: Improve readability through refactoring and documentation

- **Refactoring**: I structured the code to separate concerns and improve clarity. By creating well-defined functions for handling routes and responses, I eliminated redundant code.

- **Documentation**: I added comments to each function and block of code, explaining its purpose and how it works. This makes it easier for future developers to understand and modify the code.

#### Task E: Documentation for other developers

- Provided clear comments explaining the logic of the code, the purpose of each function, and potential pitfalls to watch out for. The refactored code and comments now make it easier for new developers to understand and maintain the API.

### 2. `index.php` Improvements

#### Task A: Improve readability through refactoring and documentation

- **HTML and PHP Separation:** I restructured the code to separate the HTML from PHP logic. The HTML structure is cleaner, and PHP logic is only executed when needed.

- **Comments**: Added inline comments to explain the purpose of each section, especially around form handling, article fetching, and rendering. This ensures that future developers can follow the flow of the application easily.

#### Task E: Optimizing the word count function

- In the original implementation, the word count function (wfGetWc) reads entire files into memory using `file_get_contents()` and processes the word count. This can be problematic when dealing with large files or large numbers of files, as it can consume significant memory and cause performance bottlenecks.

#### Optimization Approach:
To address this, I modified the word count function to use streaming instead of loading the entire file into memory. Here's how the optimization works:

- **Directory Traversal:** The function loops through the files in the articles/ directory.
- Streaming Files: Instead of loading entire files into memory, it processes each file in chunks (e.g., 1024 bytes at a time) using `fread()`. This reduces memory usage when working with large files.
- **Word Counting:** For each chunk read from the file, the function counts the words using `str_word_count()` and adds it to the total word count.
- **Resource Management:** After processing, the file handle is closed using `fclose()` to free up system resources.
This approach is more efficient for large data sets, reducing memory consumption while maintaining the same word count functionality.

#### Future Optimization: Caching Word Count for Large Datasets
While the current implementation improves performance by processing files in chunks and minimizing memory usage, further optimizations can be achieved for larger datasets or frequent word count requests through `caching`. By caching the word count and only recalculating when articles are modified, the application can return results faster and reduce resource usage, providing a scalable solution for handling large volumes of data efficiently.

## Conclusion
In this submission, I focused on improving the readability, performance, and security of both `api.php` and `index.php`. My approach involved refactoring the code for better structure, documenting key parts for future maintainability, and optimizing performance where applicable (especially in the word count function).