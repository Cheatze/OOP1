<?php

class BookRepository
{

    private array $books = [];

    //Add the given book object to the array
    public function add(object $newBook)
    {
        $this->books[] = $newBook;
    }

    //show all books and their indexes
    //Put this in main and make a getAll
    public function showAll()
    {
        //$books = $this->repository->getAll();
        //add a check if the books array is empty
        if (empty($this->books)) {
            echo "There are no books in the array.";
        } else {
            foreach ($this->books as $key => $book) {
                echo $key . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
            }
        }


    }

    public function getAll()
    {
        return $this->books;
    }

    //Filters the books array by author id and returns filtered array
    public function filterById(int $chosenAuthorId)
    {
        $filteredBooks = array_filter($this->books, function ($book) use ($chosenAuthorId) {
            return $book->getAuthor()->getId() === $chosenAuthorId;
        });
        return $filteredBooks;
    }

    //Returns a book with a certain index
    public function returnByIndex(int $index)
    {
        return $this->books[$index];
    }

    //Removes a book with a certain index
    public function removeByIndex(int $index)
    {
        unset($this->books[$index]);
    }

    //Checks if a book exists at a certain index and returns bool
    public function checkForIndex(int $index)
    {
        return array_key_exists($index, $this->books);
    }

}