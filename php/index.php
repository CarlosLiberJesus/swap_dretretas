<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Carlos\Organize\Lib\MigrationHandler;
use Carlos\Organize\Model\Tretas\DocumentDRe;

echo "Hello, World!\n";

$finalBDPdo = new \PDO("mysql:host=localhost;dbname=lumen_db", "local_user", "Qwerty.123");
$finalBDHandler = new MigrationHandler($finalBDPdo);

$importBDPdo = new \PDO("mysql:host=localhost;dbname=python_dre", "local_user", "Qwerty.123");
$importBDHandler = new MigrationHandler($importBDPdo);

if($finalBDHandler->testConnection('diario_republicas') && $importBDHandler->testConnection('dreapp_document')) {
    echo "Conexão com as bases de dados estabelecida\n";

    //$res = $importBDHandler->getDReDocuments("SELECT * FROM `dreapp_document` WHERE date < '1910-10-05' ORDER BY date ASC;");
    $res = $importBDHandler->getDReDocuments("SELECT * FROM `dreapp_document` WHERE date > '2025-02-01' ORDER BY date ASC;");

    foreach ($res as $dreDocument) {
        echo $dreDocument->generateContent();

        echo "Deseja continuar? (s/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) != 's') {
            echo "Processo interrompido pelo utilizador.\n";
            break;
        }
    }
} else {
    echo "Erro a conectar com as base de dados.\n";
    exit(1);
}