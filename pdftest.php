<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Antes do erro<br>";
naoExiste(); // função que não existe, só pra forçar um erro fatal
echo "Depois do erro"; // isso não deveria aparecer