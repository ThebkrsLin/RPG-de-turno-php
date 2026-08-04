<?php
require_once "character.php";

class Mage extends Character{
    private $name;
    
    public function __construct($mHp, $maxEP, $att, $def, $ini, $n)
    {
        parent::__construct($mHp, $maxEP, $att, $def, $ini);
        $this->setName($n);
    }

    public function useAbillity(Character $target){
        $this->canAct();
        $r = 20;
        if($this->energyPoints < $r){
            throw new Exception("Você está sem energia, não poderá usar a habilidade!!!");
        }
        $this->energyPoints -= $r;
        $target->RecieveDamage($this->getAttack() * 2.5);
    }

    public function LevelUP(){
        $this->maxHp += 6;
        $this->defense += 1;
        $this->attack += 4; 
        $this->maxEnergyPoints += 8;
        $this->initiative += 1;
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
        $this->name = "O Mago ".$name;
        return $this;
    }
}