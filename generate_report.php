<?php
require_once 'vendor/autoload.php';
require_once "./assets/characters/mage.php";
require_once "./assets/characters/warrior.php";
require_once "./assets/characters/archer.php";
require_once "./assets/manage/CPU.php";
require_once "./assets/manage/player.php";
require_once "./assets/manage/item.php";
require_once "./assets/manage/weapon.php";
require_once "./assets/manage/battle.php";

use Dompdf\Dompdf;

session_start();

if (!isset($_SESSION['battle']) || !$_SESSION['battle']->isOver()) {
    die("Nenhuma batalha finalizada para gerar relatório.");
}

$battle = $_SESSION['battle'];
$fighters = $battle->getFighters();
$winner = $battle->getWinner();
$logEntries = $battle->getLog()->getEntries();
$stats = $battle->getLog()->getStats();

$html = "
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 24px; }
        .entry { margin-bottom: 4px; border-bottom: 1px solid #eee; padding: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Relatório de Combate</h1>
    <p><strong>Participantes:</strong> " . htmlspecialchars($fighters['player']->getName()) . " vs " . htmlspecialchars($fighters['cpu']->getName()) . "</p>
    <p><strong>Vencedor:</strong> " . ($winner ? htmlspecialchars($winner->getName()) : "Empate (limite de turnos atingido)") . "</p>

    <h2>Estatísticas Finais</h2>
    <table>
        <tr>
            <th>Personagem</th>
            <th>Dano Total Causado</th>
            <th>Itens Usados</th>
        </tr>
";

foreach ($stats as $characterName => $data) {
    $totalDamage = round($data['totalDamage'] ?? 0, 1);
    $itemsUsed = !empty($data['itemsUsed']) ? implode(', ', $data['itemsUsed']) : "Nenhum";

    $html .= "
        <tr>
            <td>" . htmlspecialchars($characterName) . "</td>
            <td>{$totalDamage}</td>
            <td>" . htmlspecialchars($itemsUsed) . "</td>
        </tr>
    ";
}

$html .= "
    </table>

    <h2>Log da Batalha</h2>
";

foreach ($logEntries as $entry) {
    $html .= "<div class='entry'>Turno {$entry['turn']}: " . htmlspecialchars($entry['message']) . "</div>";
}

$html .= "</body></html>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_combate.pdf", ["Attachment" => true]);