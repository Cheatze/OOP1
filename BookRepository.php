<?php

/**
 * BookRepository
 * Contains the book array and deals with it.
 * Adds, gets all, filters, 
 */
class BookRepository
{

    private array $books = [];

    //Add the given book object to the array
    public function add(object $newBook)
    {
        $this->books[] = $newBook;
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
    //change to returnById
    public function returnByIndex(int $index)
    {
        return $this->books[$index];
    }
    public function returnById(int $id)
    {
        foreach ($this->books as $book) {
            if ($book->getid() == $id) {
                return $book;
            }
        }
        //return $this->books[$index];
    }

    //Removes a book with a certain index
    //Replace with removeById
    public function removeByIndex(int $index)
    {
        unset($this->books[$index]);
    }

    //Checks if a book exists at a certain index and returns bool
    //Replace with checkForId
    public function checkForIndex(int $index)
    {
        return array_key_exists($index, $this->books);
    }

    public function checkForId(int $id)
    {
        foreach ($this->books as $book) {
            if ($book->getId() === $id) {
                return true;
            }
        }
        return false;
    }

}