<?php
foreach ($authors as $author) {
    // Create two new Book instances for each author
    for ($i = 1; $i <= 2; $i++) {
        $bookTitle = "Book Title $i by $author";
        $isbn = "ISBN-$i-" . rand(1000, 9999);
        $publisher = "Publisher $i";
        $publicationDate = new DateTimeImmutable('2023-01-01');
        $pageCount = rand(100, 500);

        // Create a new Book instance
        $newBook = new Book($bookTitle, $author, $isbn, $publisher, $publicationDate, $pageCount);

        // Add the Book instance to the books array
        $books[] = $newBook;
    }
}