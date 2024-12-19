<?php
$books = [];
// $authors = [];
// $books = [
//     "Harry Potter and the Philosopher's Stone" => [
//         "author" => "J.K. Rowling",
//         "isbn" => "978-0-7475-3269-9",
//         "publisher" => "Bloomsbury",
//         "publishing_date" => "26 June 1997",
//         "pages" => 223
//     ],
//     "Harry Potter and the Chamber of Secrets" => [
//         "author" => "J.K. Rowling",
//         "isbn" => "978-0-7475-3849-3",
//         "publisher" => "Bloomsbury",
//         "publishing_date" => "2 July 1998",
//         "pages" => 251
//     ],
//     "The Shining" => [
//         "author" => "Stephen King",
//         "isbn" => "978-0-385-12167-5",
//         "publisher" => "Doubleday",
//         "publishing_date" => "28 January 1977",
//         "pages" => 447
//     ],
//     "It" => [
//         "author" => "Stephen King",
//         "isbn" => "978-0-670-81302-5",
//         "publisher" => "Viking",
//         "publishing_date" => "15 September 1986",
//         "pages" => 1138
//     ],
//     "The Da Vinci Code" => [
//         "author" => "Dan Brown",
//         "isbn" => "978-0-385-50420-8",
//         "publisher" => "Doubleday",
//         "publishing_date" => "18 March 2003",
//         "pages" => 689
//     ],
//     "Angels & Demons" => [
//         "author" => "Dan Brown",
//         "isbn" => "978-0-671-02735-4",
//         "publisher" => "Pocket Books",
//         "publishing_date" => "1 May 2000",
//         "pages" => 616
//     ]
// ];
$authors = ["J.K. Rowling", "Stephen King", "Dan Brown", "Bobby"];
// require_once "Book.php";
class Book
{
    private static int $count = 0;
    private int $id;
    public string $title;
    public $author;
    public string $isbn;
    public string $publisher;
    #public DateTimeImmutable $publicationDate;
    public $publicationDate;
    public int $pageCount;

    public function __construct(string $title, $author, string $isbn, string $publsiher, $publicationDate, int $pageCount)
    {
        $this->id = ++static::$count;
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->publsiher = $publsiher;
        $this->publicationDate = $publicationDate;
        $this->pageCount = $pageCount;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    // public function getAuthorName(){

    // }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function getPublisher()
    {
        return $this->publsiher;
    }

    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    public function getPublicationDateAsString()
    {
        return $this->publicationDate->format(DATE_ATOM);
    }

}
// require_once "Author";
class Main
{


    public function pickAuthor($authors)
    {
        do {
            foreach ($authors as $key => $author) {
                echo $key . ' ' . $author . "\n";
            }
            $authorIndex = readline("Choose an author by index number: ");
            if (array_key_exists($authorIndex, $authors)) {
                $checker = true;
            } else {
                echo "That author index does not exist" . "\n";
            }
        } while ($checker == false);

        return $authors[$authorIndex];
    }

    public function bookLoop($books)
    {
        foreach ($books as $book) {
            echo "Title: " . $book->getTitle() . "\n";
            echo "Author: " . $book->getAuthor() . "\n";
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
        $books[$bookTitle] = $newBook;

        echo "$bookTitle has been added. \n";
    }

    public function removeBook()
    {
        global $books;
        do {
            $titles = array_keys($books);
            foreach ($titles as $key => $title) {
                echo $key . ' ' . $title . "\n";
            }
            $removeBookindex = readline("Enter the index of the title you want to remove: ");
            $removeBook = $titles[$removeBookindex];
            if (array_key_exists($removeBook, $books) == false) {
                echo "That book does not exist or you spelled it wrong. " . "\n";
                continue;
            }
            $afirmation = readline('Are you sure you want to remove ' . $removeBook . '? Yes or No: ');
            $afirmation = strtolower($afirmation);
            if ($afirmation == 'yes') {
                break;
            } elseif ($afirmation == 'no') {
                return;
            }
        } while (true);
        unset($books[$removeBook]);
        echo "$removeBook removed \n";
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

        $chosenAuthor = $this->pickAuthor($authors);

        $filteredBooks = array_filter($books, function ($details) use ($chosenAuthor) {
            return $details['author'] === $chosenAuthor;
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

include "TestData.php";

$game = new Main();
#$game->mainMenu();
#$game->addBook();
#var_dump($books);
#$game->removeBook();
$game->showAllBooks();