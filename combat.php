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
    <link rel="stylesheet" href="css/battle_style.css">
    <title>Batalha</title>
</head>
<body>
    <div class="container">
        <?php
        $itens = [
            new Item("Poção de Cura", "Heal", 30),
            new Item("Encantamento Força", "DamageBuff", 15),
            new Item("Escudo Mágico", "DefenseBuff", 10)
        ];

        $defaultWeapons = [
            new Weapon("Espada do Crepusculo", 20, 5),
            new Weapon("Cajado dos Eternos", 15, 10),
            new Weapon("Arco de Flechas tripla", 10, 7)
        ];

        if (isset($_GET['reset'])) {
            if ($_SESSION['battle']->getWinner() == $_SESSION['player']) {
                $_SESSION['player']->LevelUp();
                $_SESSION['player']->addItem($itens[random_int(0, 2)]);
                echo "Você ganhou um item!!";
            }

            $_SESSION['player']->resetStats();

            unset($_SESSION['battle']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        if (isset($_GET['newchar'])) {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
        }

        if (!isset($_SESSION['player'])) {
            $charName = isset($_POST['charName']) ? $_POST['charName'] : "Player";
            $pClass = isset($_POST['charClass']) ? $_POST['charClass'] : random_int(1, 3);
            $player = null;

            switch ($pClass) {
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
        } else {
            $player = $_SESSION['player'];
        }

        if (!isset($_SESSION['battle']) && isset($_SESSION['player'])) {
            $cpu = null;
            $cpuChoose = random_int(1, 3);

            switch ($cpuChoose) {
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
            $battle->addFighters($player, $cpu);

            if ($player->getLevel() > $cpu->getLevel()) {
                $battle->GrindCpu();
                echo "A CPU upou de nível";
                for ($i = 0; $i < 2; $i++) {
                    $cpu->addItem($itens[random_int(0, 2)]);
                }
            }

            $_SESSION['battle'] = $battle;
        } else {
            $battle = $_SESSION['battle'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pAction']) && !$battle->isOver()) {
            if ($battle->isPlayerTurn()) {
                $battle->playTurn();
            }

            if (!$battle->isOver() && !$battle->isPlayerTurn()) {
                $_SESSION['cpuStep'] = 'waiting';
            }
        }

        if (isset($_GET['cpu']) && $_GET['cpu'] === '1' && ($_SESSION['cpuStep'] ?? null) === 'waiting') {
            $_SESSION['cpuStep'] = 'result';

            if (!$battle->isOver() && !$battle->isPlayerTurn()) {
                $battle->playTurn();
                $_SESSION['battle'] = $battle;
            }
        }

        if (isset($_GET['continue']) && $_GET['continue'] == 1 && ($_SESSION['cpuStep'] ?? null) == 'result') {
            unset($_SESSION['cpuStep']);
        }

        $_SESSION['battle'] = $battle;

        if (!$battle->isOver() && !$battle->isPlayerTurn() && !isset($_SESSION['cpuStep'])) {
            $_SESSION['cpuStep'] = 'waiting';
        }

        $cpuStep = $_SESSION['cpuStep'] ?? null;
        $fighters = $battle->getFighters();
        $player = $fighters['player'];
        $cpu = $fighters['cpu'];
        ?><br>

        <!-- Combat Session -->
        <h1>Combate</h1>

        <div class="status-grid">
            <div class="status-card">
                <h4><?= htmlspecialchars($player->getName()) ?></h4>
                <?php $playerPct = max(0, min(100, ($player->getHp() / $player->getMaxHp()) * 100)); ?>
                <div class="hp-bar">
                    <div class="hp-bar-fill<?= $playerPct <= 30 ? ' low' : '' ?>" style="width: <?= $playerPct ?>%"></div>
                </div>
                <p class="hp-text"><?= $player->getHp() ?> / <?= $player->getMaxHp() ?> HP</p>
                <?php $playerEpPct = max(0, min(100, ($player->getEp() / $player->getMaxEp()) * 100)); ?>
                <div class="ep-bar">
                    <div class="ep-bar-fill" style="width: <?= $playerEpPct ?>%"></div>
                </div>
                <p class="ep-text"><?= $player->getEp() ?> / <?= $player->getMaxEp() ?> EP</p>
            </div>

            <div class="status-card cpu">
                <h4><?= htmlspecialchars($cpu->getName()) ?></h4>
                <?php $cpuPct = max(0, min(100, ($cpu->getHp() / $cpu->getMaxHp()) * 100)); ?>
                <div class="hp-bar">
                    <div class="hp-bar-fill<?= $cpuPct <= 30 ? ' low' : '' ?>" style="width: <?= $cpuPct ?>%"></div>
                </div>
                <p class="hp-text"><?= $cpu->getHp() ?> / <?= $cpu->getMaxHp() ?> HP</p>
            </div>
        </div>

        <?php if ($battle->isOver()): ?>

            <div class="victory-banner">
                <?php $winner = $battle->getWinner(); ?>
                <h2><?= $winner ? "Vencedor: " . htmlspecialchars($winner->getName()) : "Empate (limite de turnos atingido)" ?></h2>
            </div>

            <div class="action-links">
                <a href="?reset=1">Jogar Novamente (mesmo personagem)</a>
                <a href="?newchar=1">Criar um personagem novo</a>
                <a href="generate_report.php" target="_blank">Baixar Relatório em PDF</a>
            </div>

        <?php elseif ($battle->isPlayerTurn()): ?>

            <div class="card">
                <p class="action-title">Sua vez de agir</p>

                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                    <div class="action-buttons">
                        <button type="submit" name="pAction" value="attack">Atacar</button>

                        <?php
                        $weapon = $player->getDefaultWeapon();
                        $canUseWeapon = $weapon->getWeaponDuration() > 0;
                        $isLowDurability = $weapon->getWeaponDuration() <= 2;
                        ?>
                        <button type="submit" name="pAction" value="weapon" class="weapon-btn" <?= $canUseWeapon ? '' : 'disabled' ?>>
                            <span class="weapon-name">⚔️ <?= htmlspecialchars($weapon->getName()) ?></span>
                            <span class="weapon-durability<?= $isLowDurability ? ' low' : '' ?>"><?= $weapon->getWeaponDuration() ?> usos</span>
                        </button>

                        <?php if (!$player->getDisableAbillity()): ?>
                            <button type="submit" name="pAction" value="abillity">Usar Habilidade</button>
                        <?php else: ?>
                            <button type="submit" disabled>Usar Habilidade (sem energia)</button>
                        <?php endif; ?>
                    </div>

                    <div class="item-section">
                        <?php if (!empty($player->getInventory())): ?>
                            <p class="action-title">Usar Item</p>
                            <div class="item-row">
                                <select name="itemChoosed">
                                    <?php foreach ($player->getInventory() as $item): ?>
                                        <option value="<?= htmlspecialchars($item->getName()) ?>"><?= htmlspecialchars($item->getName()) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="pAction" value="item">Usar</button>
                            </div>
                        <?php else: ?>
                            <p class="no-items">Você não possui itens</p>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

        <?php elseif ($cpuStep == 'waiting'): ?>

            <p class="cpu-waiting">Processando turno da CPU...</p>
            <script>
                setTimeout(() => {
                    window.location.href = "?cpu=1";
                }, 3000);
            </script>

        <?php elseif ($cpuStep == 'result'): ?>

            <?php $lastEntry = $battle->getLog()->getLastEntry(); ?>
            <p class="cpu-waiting"><?= htmlspecialchars($lastEntry['message'] ?? '') ?></p>
            <script>
                setTimeout(() => window.location.href = '?continue=1', 1500);
            </script>

        <?php endif; ?>

        <div class="card">
            <h3>Log de Batalha</h3>
            <div class="battle-log-wrapper" id="battleLogWrapper">
                <?= $battle->getLog()->render() ?>
            </div>
        </div>

        <a href="?newchar=1">Voltar</a>
    </div>

    <script>
        const logWrapper = document.getElementById('battleLogWrapper');
        if (logWrapper) {
            logWrapper.scrollTop = logWrapper.scrollHeight;
        }
    </script>
</body>
</html>