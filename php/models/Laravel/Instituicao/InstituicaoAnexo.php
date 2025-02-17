<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\AnexoTipo;

class InstituicaoAnexo
{
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $anexoTipoId;
    private $path;
    private $src;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, int $instituicaoId, int $anexoTipoId)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->instituicaoId = $instituicaoId;
        $this->anexoTipoId = $anexoTipoId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['uuid'],
            $data['nome'],
            $data['instituicao_id'],
            $data['anexo_tipo_id']
        );
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_anexos");
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }

        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_anexos (uuid, nome, instituicao_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES ('%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->instituicaoId,
            $this->anexoTipoId,
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
