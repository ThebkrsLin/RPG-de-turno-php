<?php
class Weapon{
    private $name;
    private $additionalDamage;

    public function __construct($n, $dmg){
        $this->name = $n;
        $this->additionalDamage = $dmg;
    }

    public function setUpWeapon(){
        return $this->additionalDamage;
    }

    /**
     * Get the value of name
     */
    public function getName() {
        return $this->name;
    }

    public function getAdditionalDamage(){
        return $this->additionalDamage;
    }
}