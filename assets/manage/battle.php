<?php
require_once "battelog.php";
require_once "player.php";
require_once "CPU.php";
class Battle{
    private array $fighters;
    private array $controllers;

    private Battlelog $log;
    private array $order;
    private int $turnIndex;
    private int $turnCount;
    private int $maxTurn = 50;


    public function __construct(){
        $this->log = new Battlelog();
        $this->setTurnCount(0);
        $this->turnIndex = 0;
        $this->controllers = [];
    }

    public function addFighters(Character $player, Character $cpu){
        $this->fighters = ['player' => $player, 'cpu' => $cpu];
        $this->controllers = [
            'player' => new Player(),
            'cpu' => new CPU()
        ];
        $this->setOrder();
    }

    public function getCurrentFighter(): Character{
        return $this->order[$this->turnIndex % 2];
    }

    public function getOponnent(Character $current): Character{
        return $current === $this->fighters['player'] ? $this->fighters['cpu'] : $this->fighters['player'];
    }

    public function isPlayerTurn(){
        return $this->getCurrentFighter() === $this->fighters['player'];
    }

    public function advanceTurn(){
        $this->turnIndex++;
        $this->turnCount++;
    }

    public function isOver(){
        return $this->getWinner() != null || $this->turnCount >= $this->maxTurn;
    }

    public function getWinner(){
        foreach($this->fighters as $fighter){
            if(!$fighter->isAlive()){
                return $this->getOponnent($fighter);
            }
        }
        return null;
    }


    public function playTurn(){
        $current = $this->getCurrentFighter();

        if(!$current->canAct() ){
            $this->log->register("{$current->getName()} não pode agir");
            $this->advanceTurn();
            return;
        }

        if($current === $this->fighters['player']){
            $controller = $this->controllers['player'];
        }

        else{
            $controller = $this->controllers['cpu'];
        }

        if($this->turnCount % 10 == 0){
            $this->rechargeEP();
        }

        $target = $this->getOponnent($current);
        #$controller->decideAction($current, $target);
        $message = $controller->decideAction($current, $target);
        $this->log->register($message);
        $this->advanceTurn();
    }

    public function rechargeEP(){
        $p = $this->fighters['player'];
        $p->setEp(max($p->getMaxEp(), $p->getEp() + 10));
        $c = $this->fighters['cpu'];
        $c->setEp(max($c->getMaxEp(), $c->getEp()+10));
    }

    public function GrindCPU(){
        $choose = random_int(-1, 1);
        echo "$choose";
        for($i = 0; $i < $this->fighters['player']->getLevel()+$choose; $i++){
            $this->fighters['cpu']->LevelUp();
        }
        $this->fighters['cpu']->resetStats();
    }

    /**
     * Get the value of fighters
     *
     * @return array
     */
    public function getFighters(): array {
        return $this->fighters;
    }

    /**
     * Get the value of log
     *
     * @return Battlelog
     */
    public function getLog(): Battlelog {
        return $this->log;
    }

    /**
     * Set the value of log
     *
     * @param Battlelog $log
     *
     * @return self
     */
    public function setLog(Battlelog $log): self {
        $this->log = $log;
        return $this;
    }

    /**
     * Get the value of order
     *
     * @return array
     */
    public function getOrder(): array {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @param array $order
     *
     * @return self
     */
    public function setOrder(){
        if($this->fighters['player']->getInitiative() >= $this->fighters['cpu']->getInitiative()){
            $this->order = [$this->fighters['player'], $this->fighters['cpu']];
        }

        else{
            $this->order = [$this->fighters['cpu'], $this->fighters['player']];
        }
    }

    /**
     * Get the value of turnCount
     *
     * @return int
     */
    public function getTurnCount(): int {
        return $this->turnCount;
    }

    /**
     * Set the value of turnCount
     *
     * @param int $turnCount
     *
     * @return self
     */
    public function setTurnCount(int $turnCount): self {
        $this->turnCount = $turnCount;
        return $this;
    }

    public function getControllers(): array {
        return $this->controllers;
    }
}