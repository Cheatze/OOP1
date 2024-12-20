<?php
$books = [];
$authors = [];

//Author object
include "Author.php";

//Book object
require_once "Book.php";


//Adds makes and adds Author/Book objects to the books/authors array
require_once "TestData.php";

//include "BookRepository.php";
class BookRepository
{

}

// require_once "Author";
class Main
{

    public function pickAuthor($authors)
    {
        do {
            foreach ($authors as $author) {
                echo $author->getId() . ' ' . $author->getFirstName() . ' ' . $author->getLastName() . "\n";
            }
            $authorId = readline("Choose an author by ID number: ");

            // Find the author with the given ID
            $chosenAuthor = null;
            foreach ($authors as $author) {
                if ($author->getId() == $authorId) {
                    $chosenAuthor = $author;
                    break;
                }
            }

            if ($chosenAuthor !== null) {
                break;
            } else {
                echo "That author ID does not exist.\n";
            }
        } while (true);

        return $chosenAuthor;
    }

    public function bookLoop($books)
    {
        foreach ($books as $book) {
            echo "Title: " . $book->getTitle() . "\n";

            // Convert the Author object to a string representation
            $author = $book->getAuthor();
            echo "Author: " . $author->getFirstName() . " " . $author->getLastName() . "\n";

            echo "ISBN: " . $book->getIsbn() . "\n";
            echo "Publisher: " . $book->getPublisher() . "\n";
            echo "Publication Date: " . $book->getPublicationDateAsString() . "\n";
            echo "Page Count: " . $book->pageCount . "\n\n";
        }
    }

    public function addBook()
    {
        global $authors;
        global $books;

        $chosenAuthor = $this->pickAuthor($authors);

        $bookTitle = readline("Enter the title: ");
        $bookNumber = readline("Enter the ISBN: ");
        $publisher = readline("Enter the publisher: ");
        #$publicationDate = readline("Enter the publication date (YYYY-MM-DD): ");
        $pageCount = readline("Enter the page count: ");


        // Loop until a valid publication date is entered
        while (true) {
            $publicationDate = readline("Enter the publication date (YYYY-MM-DD): ");
            $publicationDateObj = DateTimeImmutable::createFromFormat('Y-m-d', $publicationDate);

            if ($publicationDateObj && $publicationDateObj->format('Y-m-d') === $publicationDate) {
                break; // Exit the loop if the date is valid
            } else {
                echo "Invalid date format. Please use YYYY-MM-DD.\n";
            }
        }

        // Create a new Book instance
        $newBook = new Book($bookTitle, $chosenAuthor, $bookNumber, $publisher, $publicationDateObj, (int) $pageCount);

        // Store the Book instance in the books array
        $books[] = $newBook;

        echo "$bookTitle has been added. \n";
    }

    public function removeBook()
    {
        global $books;
        do {
            // Display the list of books with their titles and IDs
            foreach ($books as $key => $book) {
                echo $key . ': ' . $book->getTitle() . "\n";
            }

            $removeBookIndex = readline("Enter the index of the title you want to remove: ");

            // Check if the entered index is valid
            if (!array_key_exists($removeBookIndex, $books)) {
                echo "That index does not exist.\n";
                continue;
            }

            $removeBook = $books[$removeBookIndex];
            $confirmation = readline('Are you sure you want to remove "' . $removeBook->getTitle() . '"? Yes or No: ');
            $confirmation = strtolower($confirmation);

            if ($confirmation === 'yes') {
                unset($books[$removeBookIndex]);
                echo '"' . $removeBook->getTitle() . '" removed.\n';
                break;
            } elseif ($confirmation === 'no') {
                return;
            }
        } while (true);
    }

    public function showAllBooks()
    {
        global $books;
        if (empty($books)) {
            echo "There are no books in the array.";
        } else {
            $this->bookLoop($books);
        }
    }
    public function showAuthorBooks()
    {
        global $authors;
        global $books;

        // Get the chosen author object
        $chosenAuthor = $this->pickAuthor($authors);
        $chosenAuthorId = $chosenAuthor->getId();

        // Filter books by author ID
        $filteredBooks = array_filter($books, function ($book) use ($chosenAuthorId) {
            return $book->getAuthor()->getId() === $chosenAuthorId;
        });

        if (empty($filteredBooks)) {
            echo "There are no books by that author \n";
        } else {
            $this->bookLoop($filteredBooks);
        }
    }

    public function mainMenu()
    {
        while (true) {
            echo "What do you want to do? \n";
            echo "1: add a book \n";
            echo "2: Remove a book \n";
            echo "3: Show all books \n";
            echo "4: Show all books of a certain author \n";
            echo "5: exit \n";
            $choice = readline("Choose by number: ");
            switch ($choice) {
                case "1":
                    $this->addBook();
                    break;
                case "2":
                    $this->removeBook();
                    break;
                case "3":
                    $this->showAllBooks();
                    break;
                case "4":
                    $this->showAuthorBooks();
                    break;
                case "5":
                    exit();
            }
        }
    }

}



$game = new Main();
$game->mainMenu();
#$game->addBook();
#var_dump($books);
#$game->removeBook();
#$game->showAllBooks();
#$dingus = $game->pickAuthor($authors);
#echo $dingus->getFirstName();
#$game->showAuthorBooks();