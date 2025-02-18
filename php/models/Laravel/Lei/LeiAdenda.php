<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class LeiAdenda
{
    private $id;
    private $leiOriginalId;
    private $leiAdendaId;
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
        $leiAdenda = new self($data['id'] ?? 0);
        $leiAdenda->leiOriginalId = $data['lei_original_id'] ?? null;
        $leiAdenda->leiAdendaId = $data['lei_adenda_id'] ?? null;
        $leiAdenda->createdAt = $data['created_at'] ?? $leiAdenda->createdAt;
        $leiAdenda->updatedAt = $data['updated_at'] ?? $leiAdenda->updatedAt;

        return $leiAdenda;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_adendas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM lei_adendas");
        $adendas = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $adendas[] = self::create($data);
        }

        return $adendas;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO lei_adendas (id, lei_original_id, lei_adenda_id, created_at, updated_at) VALUES (%d, %d, %d, '%s', '%s')",
            $this->id,
            $this->leiOriginalId,
            $this->leiAdendaId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeiOriginalId(): ?int
    {
        return $this->leiOriginalId;
    }

    public function getLeiAdendaId(): ?int
    {
        return $this->leiAdendaId;
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

    public function setLeiOriginalId(int $leiOriginalId): void
    {
        $this->leiOriginalId = $leiOriginalId;
    }

    public function setLeiAdendaId(int $leiAdendaId): void
    {
        $this->leiAdendaId = $leiAdendaId;
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