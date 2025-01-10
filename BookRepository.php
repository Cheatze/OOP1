<?php

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
                echo $key . ': ' . $book->getTitle() . " by: " . $book->getAuthorName() . "\n";
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