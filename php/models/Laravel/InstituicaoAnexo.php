<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class InstituicaoAnexo {
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $anexoTipoId;
    private $path;
    private $src;

    public function __construct(int $instituicaoId, int $anexoTipoId) {
        $this->uuid = $this->generateUuid();
        $this->instituicaoId = $instituicaoId;
        $this->anexoTipoId = $anexoTipoId;
    }

    private function generateUuid(): string {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function setNome(?string $nome): self {
        $this->nome = $nome;
        return $this;
    }

    public function setPath(?string $path): self {
        $this->path = $path;
        return $this;
    }

    public function setSrc(?string $src): self {
        $this->src = $src;
        return $this;
    }

    public function toSqlInsert(): string {
        return sprintf(
            "INSERT INTO instituicao_anexos (uuid, nome, instituicao_id, anexo_tipo_id, path, src) VALUES ('%s', '%s', %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome ?? 'NULL',
            $this->instituicaoId,
            $this->anexoTipoId,
            $this->path ?? 'NULL',
            $this->src ?? 'NULL'
        );
    }

    public function checkIfExists(\PDO $pdo): bool {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM instituicao_anexos WHERE instituicao_id = ? AND path = ?");
        $stmt->execute([$this->instituicaoId, $this->path]);
        return $stmt->fetchColumn() > 0;
    }
}