<?php

class Author
{
    private static int $count = 0;
    private int $id;
    private string $firstName;
    private string $lastName;
    private $birthDate;

    public function __construct(string $firstName, string $lastName, $birthDate)
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
    public function getName()
    {
        $fName = $this->firstName;
        $lName = $this->lastName;
        return "$fName $lName";
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