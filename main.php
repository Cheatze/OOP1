<?php
$authors = [];

//Author object
require_once "Author.php";

//Book object
require_once "Book.php";

require_once "BookRepository.php";

class Main
{

    //Repository object
    public object $repository;

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
            $authorId = (int) $authorId;

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

    //change variable to $removeBookId
    public function bookDetails(object $book, int $removeBookIndex)
    {
        //I think all of this can go in a method with the argument $book

        echo "Title: " . $book->getTitle() . "\n";

        // Convert the Author object to a string representation
        $author = $book->getAuthor();
        echo "Author: " . $author->getFirstName() . " " . $author->getLastName() . "\n";

        echo "ISBN: " . $book->getIsbn() . "\n";
        echo "Publisher: " . $book->getPublisher() . "\n";
        echo "Publication Date: " . $book->getPublicationDateAsString() . "\n";
        echo "Page Count: " . $book->pageCount . "\n\n";

        $confirmation = readline("Do you want to delete this book? Yes/No/Menu ");
        $confirmation = strtolower($confirmation);

        if ($confirmation === 'yes') {
            //addapt and change to removeById;
            $this->repository->removeByIndex($removeBookIndex);
            echo '"' . $book->getTitle() . '" removed.\n';
        } elseif ($confirmation === 'no') {

            $this->bookDetails($book, $removeBookIndex);
        } else {
            $this->mainMenu();
        }
    }

    //To be addapted and reused for a 'show details' function
    //Still used by show by author method
    public function bookLoop(array $books)
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

    //Gets all the details for a new book and adds it to the array through the repository
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
            $this->showAll();

            //Change to work with id
            $removeBookIndex = readline("Enter the index of the title you want to remove: ");

            // Check if the entered index is valid
            //Addapt and change to checkForId
            $bool = $this->repository->checkForIndex($removeBookIndex);
            if (!$bool) {
                echo "That index does not exist.\n";
                continue;
            }

            //Returns the book of the chosen index
            //Addapt and change to returnById
            $removeBook = $this->repository->returnByIndex($removeBookIndex);
            $confirmation = readline('Are you sure you want to remove "' . $removeBook->getTitle() . '"? Yes or No: ');
            $confirmation = strtolower($confirmation);

            if ($confirmation === 'yes') {
                //Addapt and change to removeById
                $this->repository->removeByIndex($removeBookIndex);
                echo '"' . $removeBook->getTitle() . '" removed.\n';
                break;
            } elseif ($confirmation === 'no') {
                return;
            }
        } while (true);
    }

    public function showAll()
    {
        $books = $this->repository->getAll();
        //add a check if the books array is empty
        if (empty($books)) {
            echo "There are no books in the array.";
        } else {
            foreach ($books as $key => $book) {
                //echo $key . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
                echo 'Id: ' . $book->getId() . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
            }
        }


    }

    public function showAllBooks()
    {
        //Shows a index/title list of all books
        $this->showAll();

        //Ask if you want to remove a book and return to main menu if no
        $question = readline("Do you want to see al the details of a certain book? yes/no ");
        $question = strtolower($question);
        if ($question == "no") {
            $this->mainMenu();
        }

        //Change to detailsBookId
        $detailsBookId = readline("Enter the id of a title if you want to see its details: ");
        $detailsBookId = (int) $detailsBookId;

        // Check if the entered index is valid
        $bool = $this->repository->checkForId($detailsBookId);
        if (!$bool) {
            echo "That index does not exist.\n ";
            $this->showAllBooks();
        }

        $detailsBook = $this->repository->returnById($detailsBookId);

        $this->bookDetails($detailsBook, $detailsBookId);



    }

    //Shows books by a chosen author
    public function showAuthorBooks()
    {
        global $authors;

        // Get the chosen author object
        $chosenAuthor = $this->pickAuthor($authors);
        $chosenAuthorId = $chosenAuthor->getId();

        // Filter books by author ID
        $filteredBooks = $this->repository->filterById($chosenAuthorId);

        //Shows all books by chosen author and their details
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
            $choice = (int) readline("Choose by number: ");
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
                default:
                    $this->mainMenu();
            }
        }
    }

}


//Make BookRepository object
$repo = new BookRepository();

//Make main and pass the repository
$game = new Main($repo);
//Makes and adds test Author/Book objects to the books/authors array
require_once "TestData.php";

$game->mainMenu();