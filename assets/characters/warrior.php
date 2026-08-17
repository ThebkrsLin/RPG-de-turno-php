<?php
require_once "character.php";
#require_once "weapon.php";
class Warrior extends Character{
    private $name;

    public function __construct($n)
    {
        parent::__construct(120, 30, 18, 13, 7);
        $this->setName($n);
    }

    #[Override]
    public function useAbillity(Character $target)
    {
        $this->canAct();
        $r = 15;
        if($this->ep < $r){
            echo $this->name." está sem energia, não poderá usar a habilidade<br>";
            return false;
        }
        
        else{   
            $this->ep -= $r;
            $dmg = $this->attack * 1.8;
            $target->RecieveDamage($dmg);
            return $dmg;
        }
    }

    public function LevelUp(){
        $this->maxHp += 10;
        $this->defense += 3;
        $this->attack += 2;
        $this->maxep += 2;
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
        $this->name = "O Guerreiro ".$name;
        return $this;
    }
}