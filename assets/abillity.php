<?php
require_once "./interfaces/abillityinterface.php";
class Abillity implements AbillityInterface{
    private $name;
    private $lossEnergy;
    private $dmgMultiplier;

    public function __construct($n, $le, $dmg){
        $this->name = $n;
        $this->lossEnergy = $le;
        $this->dmgMultiplier = $dmg;
    }

    public function Execute(Character $user, Character $target){
        $user->LoseEnergy($this->lossEnergy);
        $target->RecieveDamage($user->getAttack() * $this->dmgMultiplier);
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
        $this->name = $name;
        return $this;
    }
}