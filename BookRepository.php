<?php

/**
 * BookRepository
 * Contains the book array and deals with it.
 * Adds, gets all, filters by id, returns by id, removes by id, checks for id 
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

    //Returns a book with a certain id
    public function returnById(int $id)
    {
        foreach ($this->books as $book) {
            if ($book->getid() == $id) {
                return $book;
            }
        }
    }

    //Removes a book with a certain index
    public function removeById(int $id)
    {
        foreach ($this->books as $index => $book) {
            if ($book->getId() === $id) {
                // Remove the object at this index
                unset($this->books[$index]);
                break; // Exit loop since we found what we needed
            }
        }
    }

    //Checks if a book exists at a certain index and returns bool
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