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

        if(isset($_GET['reset'])){
            unset($_SESSION['battle']);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }

        $cpuChoose = random_int(1, 3);

        if(!isset($_SESSION['battle'])){
            $charName = $_POST['charName'] ?? $_SESSION['charName'] ?? 'Player';
            $pchoosen = $_POST['charClass'] ?? $_SESSION['charClass'] ?? random_int(1, 3);
            
            $player = null;
            $cpu = null;


            switch($pchoosen){
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
            $battle->addFighters($player, $cpu);

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
        <pre>
            turnCount: <?= $battle->getTurnCount() ?>
            isPlayerTurn: <?= var_export($battle->isPlayerTurn(), true) ?>
            isOver: <?= var_export($battle->isOver(), true) ?>
            POST recebido: <?= var_export($_POST, true) ?>
            Order[0]: <?= $battle->getOrder()[0]->getName() ?>
            Order[1]: <?= $battle->getOrder()[1]->getName() ?>
            Player === Order[0]: <?= var_export($fighters['player'] === $battle->getOrder()[0], true) ?>
            Player === Order[1]: <?= var_export($fighters['player'] === $battle->getOrder()[1], true) ?>
            Player HP: <?= $battle->getFighters()['player']->getHp() ?>
            CPU HP: <?= $battle->getFighters()['cpu']->getHp()?>
        </pre>
        Player HP : <?= $player->getHp(); ?><br>
        CPU HP: <?= $fighters['cpu']->getHp();?><br>
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
            <a href="?reset=1">ogar Novamente</a>

        <?php elseif($battle->isPlayerTurn()): ?>
            <form method="POST">
                <button type="submit" name="pAction" value="attack">Atacar</button>
                <button type="submit" name="pAction" value="abillity">Usar Habilidade</button>
            </form>
        
        <?php else: ?>
            <p>Processando turno CPU</p>
        <?php endif;?>
        <hr>
        <h3>Log de Batalha</h3>
        <?= $battle->getLog()->render() ?>
        <a href="index.php?reset=1">Voltar</a>
    </div>
</body>
</html>