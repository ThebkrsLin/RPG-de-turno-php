<?php
require_once "character.php";

class Archer extends Character{
    private $name;

    public function __construct($n)
    {
        parent::__construct(95, 45, 16, 9, 20);
        $this->setName($n);
    }

    public function useAbillity(Character $target){
        $this->canAct();
        $r = 10;
        if($this->energyPoints < $r){
            throw new Exception("Você não possui energia, não poderá usar a habilidade!!");
        }
        $this->energyPoints -= $r;
        $target->RecieveDamage($this->getAttack() * 1.4);
    }

    public function LevelUp(){
        $this->maxHp += 5;
        $this->defense += 2;
        $this->attack += 5;
        $this->maxEnergyPoints += 4;
        $this->initiative += 2;
        $this->level += 1;
    }
    /**
     * Get the value of name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self {
        $this->name = "O Arqueiro ".$name;
        return $this;
    }
}