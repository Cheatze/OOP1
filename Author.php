<?php

class Author
{
    private static int $count = 0;
    private int $id;
    public string $firstName;
    public string $lastName;
    public DateTimeImmutable $birthDate;

    public function __construct(string $firstName, string $lastName, DateTimeImmutable $birthDate)
    {
        $this->id = ++static::$count;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }
    public function getDateOfBirth()
    {
        return $this->birthDate;
    }
    public function getDateOfBirthAsString()
    {
        return $this->birthDate->format("Y-m-d");
    }
}