<?php

class MigrationHandler {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function importInstituicao(Instituicao $instituicao): bool {
        if ($instituicao->checkIfExists($this->pdo)) {
            echo "Instituição já existe: " . $instituicao->getNome() . "\n";
            return false;
        }

        $sql = $instituicao->toSqlInsert();
        try {
            $this->pdo->exec($sql);
            echo "Instituição importada com sucesso: " . $instituicao->getNome() . "\n";
            return true;
        } catch (\Exception $e) {
            echo "Erro ao importar: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Uso
$pdo = new \PDO("mysql:host=localhost;dbname=laravel_db", "user", "password");
$migrationHandler = new MigrationHandler($pdo);

$instituicao = new Instituicao("Presidência da República", 1, 1);
$migrationHandler->importInstituicao($instituicao);