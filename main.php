<?php
//Put books array into the repositiory and remove global statments
$books = [];
$authors = [];

//Author object
include "Author.php";

//Book object
require_once "Book.php";

//include "BookRepository.php";
class BookRepository
{
    //private $books = [] also change TestData

    //Add the book object to the array
    public function add($newBook)
    {
        global $books;
        $books[] = $newBook;
    }

    //show all books
    public function showAll()
    {
        global $books;
        foreach ($books as $key => $book) {
            echo $key . ': ' . $book->getTitle() . "\n";
        }

    }

    //global $repo;
    // = $repo->filterById($chosenAuthorId);
    public function filterById($chosenAuthorId)
    {
        global $books;
        $filteredBooks = array_filter($books, function ($book) use ($chosenAuthorId) {
            return $book->getAuthor()->getId() === $chosenAuthorId;
        });
        return $filteredBooks;
    }

    public function returnByIndex($index)
    {
        global $books;
        return $books[$index];
    }

    public function removeByIndex($index)
    {
        global $books;
        unset($books[$index]);
    }

}
//Make BookRepository object
$repo = new BookRepository();

// require_once "Author";
class Main
{

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
        global $books;//check if no more use

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
        //$books[] = $newBook;
        global $repo;
        $repo->add($newBook);

        echo "$bookTitle has been added. \n";
    }

    public function removeBook()
    {
        //How can I put this all in the repository?
        global $books;
        do {
            // Display the list of books with their titles and IDs
            //replace with repo showall
            // foreach ($books as $key => $book) {
            //     echo $key . ': ' . $book->getTitle() . "\n";
            // }
            global $repo;
            $repo->showAll();

            $removeBookIndex = readline("Enter the index of the title you want to remove: ");

            // Check if the entered index is valid
            //How can I put this into the repo?
            if (!array_key_exists($removeBookIndex, $books)) {
                echo "That index does not exist.\n";
                continue;
            }

            //
            //$removeBook = $books[$removeBookIndex];
            $removeBook = $repo->returnByIndex($removeBookIndex);
            $confirmation = readline('Are you sure you want to remove "' . $removeBook->getTitle() . '"? Yes or No: ');
            $confirmation = strtolower($confirmation);

            if ($confirmation === 'yes') {
                $repo->removeByIndex($removeBookIndex);
                //unset($books[$removeBookIndex]);
                echo '"' . $removeBook->getTitle() . '" removed.\n';
                break;
            } elseif ($confirmation === 'no') {
                return;
            }
        } while (true);
    }

    public function showAllBooks()
    {
        global $repo;
        $repo->showAll();
        //     echo "There are no books in the array.";
        // } else {
        //     $this->bookLoop($books);
        // }
    }
    public function showAuthorBooks()
    {
        global $authors;
        //Another thing for the repo
        //global $books;

        // Get the chosen author object
        $chosenAuthor = $this->pickAuthor($authors);
        $chosenAuthorId = $chosenAuthor->getId();

        // Filter books by author ID
        //Uses the books array so put into repo
        // $filteredBooks = array_filter($books, function ($book) use ($chosenAuthorId) {
        //     return $book->getAuthor()->getId() === $chosenAuthorId;
        // });
        global $repo;
        $filteredBooks = $repo->filterById($chosenAuthorId);

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