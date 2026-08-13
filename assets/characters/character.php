<?php
require_once __dir__."/../interfaces/hittable.php";
abstract class Character implements Hittable{
    protected $hp;
    protected $maxHp;
    protected $maxep;
    protected $ep;
    protected $attack;
    protected $lastAttack;
    protected $defense;
    protected $lastDefense;
    protected $initiative;
    protected array $inventory;
    protected $deafaultWeapon;
    protected $level;

    public function __construct($mHp, $maxEP, $att, $def, $ini){
        $this->setMaxHp($mHp);
        $this->setHp($mHp);
        $this->setMaxep($maxEP);
        $this->setep($maxEP);
        $this->setAttack($att);
        $this->setDefense($def);
        $this->setInitiative($ini);
        $this->setLevel(1);
        $this->inventory = [];
    }

    public abstract function LevelUp();

    public function isAlive(){
        return $this->hp > 0;
    }

    public abstract function useAbillity(Character $target);

    public function canAct(){
        if(!$this->isAlive()){
            #throw new Exception("O Personagem Foi derrotado, não poderá agir!!!");
            echo "O Personagem Foi derrotado, não poderá agir!!!";
            return false;
        }

        return true;
    }

    public function resetStats(){
        $this->hp = $this->maxHp;
        $this->ep = $this->maxep;
    }

    public function Heal($v){
        $this->hp += $v;
    }

    public function buffAttack($v){
        $this->attack += $v;
    }

    public function buffDefense($v){
        $this->defense += $v;
    }

    public function LoseEnergy($energy){
        $this->ep -= $energy;
    }

    public function RecieveDamage(float $dmg){
        $attackerDmg = max(0, $dmg - $this->defense);
        $this->hp = max(0, $this->hp - $attackerDmg);
    }

    public function Attack(Character $target){
        if($this->canAct()){
            $target->RecieveDamage($this->attack);
        }
    }

    public function AttackWithWeapon(Character $target){
        if($this->canAct()){
            $bonusDmg = $this->deafaultWeapon->getAdditionalDamage();
            $target->RecieveDamage($this->getAttack()+$bonusDmg);
        }
    }

    public function addItem(Item $item){
        $this->inventory[$item->getName()] = $item;        
    }

    public function removeItem($item){
        unset($this->inventory[$item]);
    }

    // Access Functions
    public function getHp(){
        return $this->hp;
    }

    public function setHp(int $hp){
        $this->hp = $hp;
        
    }

    public function getMaxHp(){
        return $this->maxHp;
    }

    public function setMaxHp(int $maxHp){
        $this->maxHp = $maxHp;
        
    }

    public function getMaxep(){
        return $this->maxep;
    }

    public function setMaxep(int $maxep){
        $this->maxep = $maxep;
        
    }

    
    public function getEp(){
        return $this->ep;
    }

    public function setEp($ep){
        $this->ep = $ep;
        
    }

    public function getAttack(){
        return $this->attack;
    }

    public function setAttack($attack){
        $this->attack = $attack;
        
    }

    public function getDefense(){
        return $this->defense;
    }

    public function setDefense($defense){
        $this->defense = $defense;
        
    }

    public function getInventory(){
        return $this->inventory;
    }

    public function getLevel(){
        return $this->level;
    }

    public function getInitiative() {
        return $this->initiative;
    }

    public function setInitiative($initiative){
        $this->initiative = $initiative;
    }

    /**
     * Set the value of level
     */
    public function setLevel($level): self {
        $this->level = $level;
        return $this;
    }

    public function getDefaultWeapon(){
        return $this->deafaultWeapon;
    }
    
    public function setDefaultWeapon($obj){
        $this->deafaultWeapon = $obj;
    }
}