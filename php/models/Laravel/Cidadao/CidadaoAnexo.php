<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

use Carlos\Organize\Model\Laravel\AnexoTipo;

class CidadaoAnexo
{
    private $id;
    private $uuid;
    private $nome;
    private $cidadaoId;
    private $anexoTipo;
    private $path;
    private $src;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $anexo = new self($data['id'] ?? 0);
        $anexo->uuid = $data['uuid'] ?? null;
        $anexo->nome = $data['nome'] ?? null;
        $anexo->cidadaoId = $data['cidadao_id'] ?? null;
        $anexo->anexoTipo = AnexoTipo::findById($data['anexo_tipo_id']);
        $anexo->path = $data['path'] ?? null;
        $anexo->src = $data['src'] ?? null;
        $anexo->createdAt = $data['created_at'] ?? $anexo->createdAt;
        $anexo->updatedAt = $data['updated_at'] ?? $anexo->updatedAt;
        return $anexo;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_anexos");
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }

        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_anexos (uuid, nome, cidadao_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES (%d, '%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->id,
            $this->uuid,
            $this->nome,
            $this->cidadaoId,
            $this->anexoTipo->id,
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getCidadaoId(): ?int
    {
        return $this->cidadaoId;
    }

    public function getAnexoTipo(): ?AnexoTipo
    {
        return $this->anexoTipo;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setAnexoTipo(AnexoTipo $anexoTipo): void
    {
        $this->anexoTipo = $anexoTipo;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}