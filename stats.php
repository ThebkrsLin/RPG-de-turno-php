<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <?php
        require_once "./assets/characters/mage.php";
        require_once "./assets/characters/warrior.php";
        require_once "./assets/characters/archer.php";
        require_once "./assets/manage/CPU.php";
        require_once "./assets/manage/player.php";
        require_once "./assets/item.php";
        require_once "./assets/manage/battle.php";

        session_start();

        session_write_close();

        if(isset($_GET['reset'])){
            unset($_SESSION['battle']);
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }

        if(!isset($_SESSION['battle'])){
            $charName = $_POST['charName'];
            $pchoosen = $_POST['charClass'];
            $cpuChoose = random_int(1, 3);
            $player = null;
            $cpu = null;


            switch($pchoosen){
                case "Warrior":
                    $player = new Warrior($charName);
                    break;
                
                case "Mage":
                    $player = new Mage($charName);
                    break;

                case "Archer":
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

        if($_SERVER['REQUEST_METHOD'] === 'POST' && !$battle->isOver()){
            if($battle->isPlayerTurn()){
                $battle->playerTurn();
            }

            while(!$battle->isOver() && !$battle->isPlayerTurn()){
                $battle->playerTurn();
            }
        }

        $_SESSION['battle'] = $battle;

        $fighters = $battle->getFighters();
        $player = $fighters['player'];
        $cpu = $fighters['cpu'];
        ?><br>]
        <h1>Combate</h1>

        <div class="status">
            <p><strong><?= htmlspecialchars($player->getName()) ?></strong></p>
            <p><strong><?= htmlspecialchars($cpu->getName())?></strong></p>
        </div>

        <?php if($battle->isOver()): ?>
            <h2>
                <?php $winner = $battle->getWinner(); ?>
                <?=  $winner ? "Vencedor: ". htmlspecialchars($winner->getName()) : "Empate (limite de turnos atingido)"?>
            </h2>
            <a href="?reset=1">Jogar Novamente</a>

        <?php elseif($battle->isPlayerTurn()): ?>
            <form method="POST">
                <button type="submit" name="pAction" value="attack">Atacar</button>
                <button type="subtmit" name="pAction" value="abillity">Usar Habilidade</button>
            </form>
        
        <?php else: ?>
            <p>Processando turno CPU</p>
        <?php endif;?>
        <hr>
        <h3>Log de Batalha</h3>
        <?= $battle->getLog()->render() ?>
        <a href="javascript:history.go(-1)">Voltar</a>
    </div>
</body>
</html>