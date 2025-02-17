<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublica
{
    private $id;
    private $uuid;
    private $nome;
    private $publicacao;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'] ?? null;
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->publicacao = $data['publicacao'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republicas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republicas");
        $diarios = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $diarios[] = self::create($data);
        }

        return $diarios;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republicas (uuid, nome, publicacao, created_at, updated_at) VALUES ('%s', '%s', '%s', '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->publicacao,
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

    public function getPublicacao(): ?string
    {
        return $this->publicacao;
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

    public function setPublicacao(string $publicacao): void
    {
        $this->publicacao = $publicacao;
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
