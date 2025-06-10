<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublica
{
    private $id;
    private $uuid;
    private $nome;
    private $publicacao; //date
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

    public static function findByNome(\PDO $pdo, string $nome): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republicas WHERE nome LIKE ?");
        $stmt->execute(['%' . $nome . '%']);
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
            "INSERT INTO diario_republicas (id, uuid, nome, publicacao, created_at, updated_at) VALUES (%d, '%s', '%s', '%s', '%s', '%s')",
            $this->id,
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

    public function publicacoes(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacoes WHERE diario_republica_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = DiarioRepublicaPublicacao::create($data);
        }

        return $anexos;
    }
}
