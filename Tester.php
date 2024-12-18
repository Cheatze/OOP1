<?php

$naamen = [];

class Boekje
{
    public string $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
}

class Tester
{
    protected string $naam;
    public function __construct($naam)
    {
        $this->naam = $naam;
    }
    public function get_naam()
    {
        echo $this->naam;
    }

    public function getoo()
    {
        $this->get_naam();
    }

    public function boekjes(string $name)
    {
        $namel = new Boekje($name);
        global $naamen;
        return $naamen[] = $namel;
    }
}

$naam = new Tester("Terry");
#$naamen[] = $naam;
#$naam = new Tester("Barry");
#$naamen[] = $naam;
#echo 'Ik ben ' . $naamen[0]->get_naam() . ' ha';
#echo "Ik ben ";
#$naamen[0]->get_naam();
echo "\n";
#echo 'Ik ben ' . $naam->get_naam();
#echo 'ik ben ' . $naam->getoo() . 'ho';
$naam->boekjes("Bloek");
echo "Ik ben " . $naamen[0]->getName();

#get object from array where name = 'x'