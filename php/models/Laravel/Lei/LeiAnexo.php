<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class LeiAnexo
{
    private $id;
    private $uuid;
    private $nome;
    private $leiId;
    private $anexoTipoId;
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
        $instance = new self($data['id'] ?? 0);
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->leiId = $data['lei_id'] ?? null;
        $instance->anexoTipoId = $data['anexo_tipo_id'] ?? null;
        $instance->path = $data['path'] ?? null;
        $instance->src = $data['src'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_anexos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByNome(\PDO $pdo, string $nome): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_anexos WHERE nome LIKE ?");
        $stmt->execute(['%' . $nome . '%']);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM lei_anexos");
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }

        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO lei_anexos (uuid, nome, lei_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES (%d, '%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->id,
            $this->uuid,
            $this->nome,
            $this->leiId,
            $this->anexoTipoId,
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getLeiId(): ?int
    {
        return $this->leiId;
    }

    public function getAnexoTipoId(): ?int
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

    public function setLeiId(int $leiId): void
    {
        $this->leiId = $leiId;
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