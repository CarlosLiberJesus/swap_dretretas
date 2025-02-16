<?php

declare(strict_types=1);

namespace Carlos\Organize\Lib;

use Carlos\Organize\Model\Tretas\DocumentDRe;
use Carlos\Organize\Model\Tretas\DocumentDReText;
use Carlos\Organize\Model\Tretas\DocumentDReConnectsTo;

class MigrationHandler {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function testConnection(string $table): bool {
        try {
            $sql = "SELECT 1 FROM $table LIMIT 1";
            $stmt = $this->pdo->query($sql);
            $stmt->fetch();
            return true;
        } catch (\Exception $e) {
            print("Exception Connection: ". $e->getMessage() . "\n");
            return false;
        }
    }

    public function getDReDocuments(string $query): array {
        try {
            $stmt = $this->pdo->query($query);
            $documents = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $documentText = $this->getDocumentDReText($row['id']);
                $documentConnectsTo = $this->getDocumentDReConnectsTo($row['id']);
                $documents[] = new DocumentDRe($row, $documentText, $documentConnectsTo);
            }
            return $documents;
        } catch (\Exception $e) {
            print("Exception Query: ". $e->getMessage() . "\n");
            return [];
        }
    }

    private function getDocumentDReText(int $documentId): ?DocumentDReText {
        $sql = "SELECT * FROM dreapp_documenttext WHERE document_id = :document_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['document_id' => $documentId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new DocumentDReText($row);
        }

        return null;
    }

    private function getDocumentDReConnectsTo(int $documentId): ?DocumentDReConnectsTo {
        $sql = "SELECT * FROM dreapp_document_connects_to WHERE from_document_id = :document_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['document_id' => $documentId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new DocumentDReConnectsTo($row);
        }

        return null;
    }

    /*
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
    */
}

// $instituicao = new Instituicao("Presidência da República", 1, 1);
// $migrationHandler->importInstituicao($instituicao);