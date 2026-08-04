<?php
class Weapon{
    private $name;
    private $aditionalDamage;

    public function __construct($n, $dmg){
        $this->name = $n;
        $this->aditionalDamage = $dmg;
    }

    public function setUpWeapon(){
        return $this->aditionalDamage;
    }

    /**
     * Get the value of name
     */
    public function getName() {
        return $this->name;
    }
}