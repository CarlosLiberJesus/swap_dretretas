<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\AnexoTipo;

class InstituicaoAnexo
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $anexoTipoId;
    private $path;
    private $src;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $uuid, string $nome, int $instituicaoId, int $anexoTipoId)
    {
        $this->id = $id;
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
            $data['id'],
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

    public static function all(\PDO $pdo, $instituicaoId): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_anexos WHERE instituicao_id = $instituicaoId");
        $stmt->execute([$instituicaoId]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }

        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_anexos (id, uuid, nome, instituicao_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES (%d, '%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->id,
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

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getInstituicaoId(): int
    {
        return $this->instituicaoId;
    }

    public function getAnexoTipoId(): int
    {
        return $this->anexoTipoId;
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setInstituicaoId(int $instituicaoId): void
    {
        $this->instituicaoId = $instituicaoId;
    }

    public function setAnexoTipoId(int $anexoTipoId): void
    {
        $this->anexoTipoId = $anexoTipoId;
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