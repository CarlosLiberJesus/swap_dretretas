<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoNacionalidade
{
    private $id;
    private $cidadaoId;
    private $nacionalidadeId;
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
        $nacionalidade = new self($data['id'] ?? 0);
        $nacionalidade->cidadaoId = $data['cidadao_id'] ?? null;
        $nacionalidade->nacionalidadeId = $data['nacionalidade_id'] ?? null;
        $nacionalidade->createdAt = $data['created_at'] ?? $nacionalidade->createdAt;
        $nacionalidade->updatedAt = $data['updated_at'] ?? $nacionalidade->updatedAt;
        return $nacionalidade;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_nacionalidades WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_nacionalidades");
        $nacionalidades = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nacionalidades[] = self::create($data);
        }

        return $nacionalidades;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_nacionalidades (id, cidadao_id, nacionalidade_id, created_at, updated_at) VALUES (%d, %d, %d, '%s', '%s')",
            $this->id,
            $this->cidadaoId,
            $this->nacionalidadeId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCidadaoId(): ?int
    {
        return $this->cidadaoId;
    }

    public function getNacionalidadeId(): ?int
    {
        return $this->nacionalidadeId;
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

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setNacionalidadeId(int $nacionalidadeId): void
    {
        $this->nacionalidadeId = $nacionalidadeId;
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