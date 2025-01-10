<?php
$authors = [];

//Author object
include "Author.php";

//Book object
require_once "Book.php";

//include "BookRepository.php";
class BookRepository
{

    private $books = [];

    //Add the given book object to the array
    public function add($newBook)
    {
        $this->books[] = $newBook;
    }

    //show all books and their indexes
    public function showAll()
    {
        //add a check if the books array is empty
        if (empty($this->books)) {
            echo "There are no books in the array.";
        } else {
            foreach ($this->books as $key => $book) {
                echo $key . ': ' . $book->getTitle() . "\n";
            }
        }


    }

    //Filters the books array by author id and returns filtered array
    public function filterById($chosenAuthorId)
    {
        $filteredBooks = array_filter($this->books, function ($book) use ($chosenAuthorId) {
            return $book->getAuthor()->getId() === $chosenAuthorId;
        });
        return $filteredBooks;
    }

    //Returns a book with a certain index
    public function returnByIndex($index)
    {
        return $this->books[$index];
    }

    //Removes a book with a certain index
    public function removeByIndex($index)
    {
        unset($this->books[$index]);
    }

    //Checks if a book exists at a certain index and returns bool
    public function checkForIndex($index)
    {
        return array_key_exists($index, $this->books);
    }

}


class Main
{

    //Repository object
    public $repository;

    //Sets the repository object
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    //Directly adds a book, onl used by TestData
    public function addForTest($nBook)
    {
        $this->repository->add($nBook);
    }

    /**
     * Displays the id and full name of all authors
     * Chooses an author based on id number and returns that author
     * @param mixed $authors
     * @return mixed
     */
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

    //To be addapted and reused for a 'show details' function
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

    //Gets all the details for a new book and adds it through the repository
    public function addBook()
    {
        global $authors;

        $chosenAuthor = $this->pickAuthor($authors);

        $bookTitle = readline("Enter the title: ");
        $bookNumber = readline("Enter the ISBN: ");
        $publisher = readline("Enter the publisher: ");
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
        $this->repository->add($newBook);

        echo "$bookTitle has been added. \n";
    }

    public function removeBook()
    {
        do {
            // Display the list of books with their titles and indexes
            $this->repository->showAll();

            $removeBookIndex = readline("Enter the index of the title you want to remove: ");

            // Check if the entered index is valid
            $bool = $this->repository->checkForIndex($removeBookIndex);
            if (!$bool) {
                echo "That index does not exist.\n";
                continue;
            }

            //Returns the book of the chosen index
            $removeBook = $this->repository->returnByIndex($removeBookIndex);
            $confirmation = readline('Are you sure you want to remove "' . $removeBook->getTitle() . '"? Yes or No: ');
            $confirmation = strtolower($confirmation);

            if ($confirmation === 'yes') {
                $this->repository->removeByIndex($removeBookIndex);
                echo '"' . $removeBook->getTitle() . '" removed.\n';
                break;
            } elseif ($confirmation === 'no') {
                return;
            }
        } while (true);
    }

    public function showAllBooks()
    {
        //
        $this->repository->showAll();
        //     echo "There are no books in the array.";
        // } else {
        //     $this->bookLoop($books); So this was my only use of bookLoop?
        // }
    }
    public function showAuthorBooks()
    {
        global $authors;

        // Get the chosen author object
        $chosenAuthor = $this->pickAuthor($authors);
        $chosenAuthorId = $chosenAuthor->getId();

        // Filter books by author ID
        //Uses the books array so put into repo
        // $filteredBooks = array_filter($books, function ($book) use ($chosenAuthorId) {
        //     return $book->getAuthor()->getId() === $chosenAuthorId;
        // });
        $filteredBooks = $this->repository->filterById($chosenAuthorId);

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


//Make BookRepository object
$repo = new BookRepository();

$game = new Main($repo);
//Adds makes and adds Author/Book objects to the books/authors array
require_once "TestData.php";

$game->mainMenu();

#$game->addBook();
#var_dump($books);
#$game->removeBook();
#$game->showAllBooks();
#$dingus = $game->pickAuthor($authors);
#echo $dingus->getFirstName();
#$game->showAuthorBooks();