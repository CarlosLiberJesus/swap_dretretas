<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

use Carlos\Organize\Model\Laravel\AnexoTipo;

class EntidadeJuridicaAnexo
{
    private $uuid;
    private $nome;
    private $entidadeJuridicaId;
    private $anexoTipo;
    private $path;
    private $src;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, int $entidadeJuridicaId, AnexoTipo $anexoTipo)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->entidadeJuridicaId = $entidadeJuridicaId;
        $this->anexoTipo = $anexoTipo;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $anexoTipo = AnexoTipo::findById($data['anexo_tipo_id']);
        $anexo = new self($data['uuid'], $data['nome'], $data['entidades_juridica_id'], $anexoTipo);
        $anexo->path = $data['path'] ?? null;
        $anexo->src = $data['src'] ?? null;
        return $anexo;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridica_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM entidade_juridica_anexos");
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }

        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO entidade_juridica_anexos (uuid, nome, entidades_juridica_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES ('%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->entidadeJuridicaId,
            $this->anexoTipo->id,
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
