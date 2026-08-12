<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <a href="?reset=1">Reset de emergência</a>
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

        $itens =[
            new Item("Poção de Cura", "Heal", 30),
            new Item("Encantamento Força", "damageBuff", 20)
            ];
        if(isset($_GET['reset'])){
            if($_SESSION['battle']->getWinner() === $_SESSION['player']){
                $_SESSION['player']->LevelUp();
            }
        
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
                    break;
                
                case "Mage":
                case 2:
                    $player = new Mage($charName);
                    break;

                case "Archer":
                case 3:
                    $player = new Archer($charName);
                    break;
            }

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
                    break;

                case 2:
                    $cpu = new Mage("(CPU)");
                    break;

                case 3: 
                    $cpu = new Archer("(CPU)");
                    break;
            }

            $battle = new Battle();
            $_SESSION['player']->resetStats();
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
        }


        while(!$battle->isOver() && !$battle->isPlayerTurn()){
            $battle->playTurn();
        }
        $_SESSION['battle'] = $battle;

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
            Player === Order[1]: <?= var_export($fighters['player'] === $battle->getOrder()[1], true) ?>
        </pre>
        Player LVL : <?= $player->getLevel(); ?><br>
        Player EP: <?= $player->getEnergyPoints()?><br>
        Player max EP: <?= $player->getEnergyPoints(); ?>
        CPU LVL: <?= $fighters['cpu']->getLevel();?><br>

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
                <button type="submit" name="pAction" value="attack">Atacar</button>

                <?php 
                if(!$battle->getControllers()['player']->getDisableAbillity()){
                echo '<button type="submit" name="pAction" value="abillity">Usar Habilidade</button><br>';
                }

                else{
                    echo '<button type="submit" name="pAction" value"abillity" disabled>Usar Habilidade</button><br>';
                }

                if(!empty($player->getInventory())){
                    echo "<p>Pode usar um item!!!!</p>";
                    echo "Itens: ";
                    foreach($player->getInventory() as $item){
                        print_r($item); 
                        echo "<select name='itemChoosed'>";
                        echo "<option value='{$item->getName()}'>{$item->getName()}</option>";
                        echo "</select>";
                    }
                    echo "<button type='submit' name='pAction' value='item'>Usar item</button>";
                }

                else{
                    echo  "<p>Não possui itens</p>";
                }
                ?>

            </form>
        
        <?php else: ?>
            <p>Processando turno CPU</p>
            <?= sleep(5); ?>
        <?php endif;?>
        <hr>
        <h3>Log de Batalha</h3>
        <?= $_SESSION['battle']->getLog()->render() ?>
        <a href="?newchar=1">Voltar</a>
    </div>
</body>
</html>