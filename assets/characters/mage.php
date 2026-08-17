<?php
require_once "character.php";

class Mage extends Character{
    private $name;
    
    public function __construct($n)
    {
        parent::__construct(80, 60, 10, 6, 16);
        $this->setName($n);
        $this->setEnergyRequired(30);
    }

    public function useAbillity(Character $target){
        $this->canAct();
        $this->ep -= $this->getEnergyRequired();
        if($this->ep < $this->getEnergyRequired()){
            $this->disableAbillity = true;
        }
        $dmg = $this->attack * 2.8;
        $target->RecieveDamage($dmg);
        return $dmg;
    }

    public function LevelUP(){
        $this->maxHp += 6;
        $this->defense += 1;
        $this->attack += 4; 
        $this->maxep += 8;
        $this->initiative += 1;
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
        $this->name = "O Mago ".$name;
        return $this;
    }
}