<?php

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
        $this->publisher = $publsiher;
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

    public function getAuthorName(){
        $name = $this->author->getName();
        return $name;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function getPublisher()
    {
        return $this->publisher;
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