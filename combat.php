<?php
require_once "./assets/characters/mage.php";
require_once "./assets/characters/warrior.php";
require_once "./assets/characters/archer.php";
require_once "./assets/manage/CPU.php";
require_once "./assets/manage/player.php";
require_once "./assets/manage/item.php";
require_once "./assets/manage/weapon.php";
require_once "./assets/manage/battle.php";

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <a href="?reset=1">Reset de emergência</a>
        <?php
        $itens =[
            new Item("Poção de Cura", "Heal", 30),
            new Item("Encantamento Força", "damageBuff", 15),
            new Item("Escudo Mágico", "defenseBuff", 10)
        ];

        $defaultWeapons = [
            new Weapon("Espada do Crepusculo", 20, 5),
            new Weapon("Cajado dos Eternos", 15, 10),
            new Weapon("Arco de Flechas tripla", 10, 7)
        ];

        if(isset($_GET['reset'])){
            if($_SESSION['battle']->getWinner() == $_SESSION['player']){
                $_SESSION['player']->LevelUp();
                $_SESSION['player']->addItem($itens[random_int(0, 2)]);
                echo "Você ganhou um item!!";
            }

            $_SESSION['player']->resetStats();

            unset($_SESSION['battle']);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }

        if(isset($_GET['newchar'])){
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
        }

        if(!isset($_SESSION['player'])){
            /*
            if(!isset($_POST['charName']) && !isset($_POST['charClass'])){

            }*/

            $charName = isset($_POST['charName']) ? $_POST['charName'] : "Player";
            $pClass = isset($_POST['charClass']) ? $_POST['charClass'] : random_int(1, 3);
            $player = null;

            switch($pClass){
                case "Warrior":
                case 1:
                    $player = new Warrior($charName);
                    $player->setDefaultWeapon($defaultWeapons[0]);
                    break;
                
                case "Mage":
                case 2:
                    $player = new Mage($charName);
                    $player->setDefaultWeapon($defaultWeapons[1]);
                    break;

                case "Archer":
                case 3:
                    $player = new Archer($charName);
                    $player->setDefaultWeapon($defaultWeapons[2]);
                    break;
            }

            $player->addItem($itens[0]);
            $_SESSION['player'] = $player;

        }

        else{
            $player = $_SESSION['player'];
        }

        if(!isset($_SESSION['battle']) && isset($_SESSION['player'])){
            $cpu = null;
            $cpuChoose = random_int(1, 3);

            switch($cpuChoose){
                case 1:
                    $cpu = new Warrior("(CPU)");
                    $cpu->setDefaultWeapon($defaultWeapons[0]);
                    break;

                case 2:
                    $cpu = new Mage("(CPU)");
                    $cpu->setDefaultWeapon($defaultWeapons[1]);
                    break;

                case 3: 
                    $cpu = new Archer("(CPU)");
                    $cpu->setDefaultWeapon($defaultWeapons[2]);
                    break;
            }

            $battle = new Battle();
            #$_SESSION['player']->resetStats();
            for($i = 0; $i < 3; $i++){
                $cpu->addItem($itens[random_int(0, 2)]);
            }
            
            $battle->addFighters($player, $cpu);

            if($player->getLevel() > $cpu->getLevel()){
                $battle->GrindCpu();
                echo "A CPU upou de nível";
            }

            $_SESSION['battle'] = $battle;
        }

        else{
            $battle = $_SESSION['battle'];
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pAction']) && !$battle->isOver()){
            if($battle->isPlayerTurn()){
                $battle->playTurn();
            }

            if (!$battle->isOver() && !$battle->isPlayerTurn()) {
                $_SESSION['cpuStep'] = 'waiting';
            }
        }

        if (isset($_GET['cpu']) && $_GET['cpu'] === '1' && ($_SESSION['cpuStep'] ?? null) === 'waiting'){
            unset($_SESSION['cpuStep']);

            if (!$battle->isOver() && !$battle->isPlayerTurn()) {
                $battle->playTurn();
                $_SESSION['battle'] = $battle;
            }   
        }
 
    


        /*
        if($_SERVER['REQUEST_METHOD'] === 'POST' && !$battle->isPlayerTurn() && !$battle->isOver()){
            $battle->playturn();
        }
        */

        $_SESSION['battle'] = $battle;

        $cpuWaiting = ($_SESSION['cpuStep'] ?? null) === 'waiting';
        $fighters = $battle->getFighters();
        $player = $fighters['player'];
        $cpu = $fighters['cpu'];
        ?><br>

        <!--Debug Session-->
        <pre>
            turnCount: <?= $battle->getTurnCount() ?>
            isPlayerTurn: <?= var_export($battle->isPlayerTurn(), true) ?>
            isOver: <?= var_export($battle->isOver(), true) ?>
            POST recebido: <?= var_export($_POST, true) ?>
            Order[0]: <?= $battle->getOrder()[0]->getName() ?>
            Order[1]: <?= $battle->getOrder()[1]->getName() ?>
            Player === Order[0]: <?= var_export($fighters['player'] === $battle->getOrder()[0], true) ?>
            Player === Order[1]: <?= var_export($fighters['player'] === $battle->getOrder()[1], true) ?><br>
            Player Items: <?= print_r($player) ?><br>
            CPU Items: <?= print_r($cpu) ?>
        </pre><br>
        Player LVL : <?= $player->getLevel(); ?><br>
        Player EP: <?= $player->getEp() ?><br>
        CPU LVL: <?= $fighters['cpu']->getLevel();?><br>
        CPU EP: <?= $cpu->getEp(); ?>

        <!--Combat Session-->
        <h1>Combate</h1>

        <div class="status">
            <p><strong><?= htmlspecialchars($player->getName()) ?> HP: <?= $player->getHp();?></strong></p>
            <p><strong><?= htmlspecialchars($cpu->getName())?> HP: <?= $cpu->getHp();?></strong></p>
        </div>

        <?php if($battle->isOver()): ?>
            <h2>
                <?php $winner = $battle->getWinner(); ?>
                <?=  $winner ? "Vencedor: ". htmlspecialchars($winner->getName()) : "Empate (limite de turnos atingido)"?>
            </h2>
            <a href="?reset=1">Jogar Novamente (mesmo personagem)</a><br>
            <a href="?newchar=1">Criar um personagem novo</a>

        <?php elseif($battle->isPlayerTurn()): ?>
            <form method="POST">
                <button type="submit" name="pAction" value="attack">Atacar</button><br>
                <?php
                if($player->getDefaultWeapon()->getWeaponDuration() > 0){
                    echo "<button type='submit' name='pAction' value='weapon'>{$player->getDefaultWeapon()->getName()}</button> ";
                    
                }

                else{
                    printf('<button type="submit" disabled>Atacar com %s</button> ', $player->getDefaultWeapon()->getName());
                }
                
                echo "Estado {$player->getDefaultWeapon()->getWeaponDuration()}<br>";

                if(!$battle->getControllers()['player']->getDisableAbillity()){
                echo '<button type="submit" name="pAction" value="abillity">Usar Habilidade</button><br>';
                }

                else{
                    echo '<button type="submit" disabled>Usar Habilidade</button><br>';
                }

                if(!empty($player->getInventory())){
                    echo "<p>Pode usar um item!!!!</p>";
                    echo "Itens: ";
                    echo "<select name='itemChoosed'>";
                    foreach($player->getInventory() as $item){
                        echo "<option value='{$item->getName()}'>{$item->getName()}</option>";
                    }
                    echo "</select>";
                    echo "<button type='submit' name='pAction' value='item'>Usar item</button>";
                }

                else{
                    echo  "<p>Não possui itens</p>";
                }
                ?>

            </form>

        <?php elseif ($cpuWaiting): ?>

            <p>Processando turno CPU</p>
            <script>
                setTimeout(() => {
                    window.location.href = "?cpu=1";
                }, 3000);
            </script>
        <?php endif;?>
        <hr>
        <h3>Log de Batalha</h3>
        <?= $_SESSION['battle']->getLog()->render() ?>
        <a href="?newchar=1">Voltar</a>
    </div>
</body>
</html>