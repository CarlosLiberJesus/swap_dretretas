<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class LeiEmissor
{
    private $leiId;
    private $emissorTipo;
    private $emissorId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $leiId, string $emissorTipo, int $emissorId)
    {
        $this->leiId = $leiId;
        $this->emissorTipo = $emissorTipo;
        $this->emissorId = $emissorId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['lei_id'],
            $data['emissor_tipo'],
            $data['emissor_id']
        );
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_emissores WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM lei_emissores");
        $emissores = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $emissores[] = self::create($data);
        }

        return $emissores;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO lei_emissores (lei_id, emissor_tipo, emissor_id, created_at, updated_at) VALUES (%d, '%s', %d, '%s', '%s')",
            $this->leiId,
            $this->emissorTipo,
            $this->emissorId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getLeiId(): int
    {
        return $this->leiId;
    }

    public function getEmissorTipo(): string
    {
        return $this->emissorTipo;
    }

    public function getEmissorId(): int
    {
        return $this->emissorId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setLeiId(int $leiId): void
    {
        $this->leiId = $leiId;
    }

    public function setEmissorTipo(string $emissorTipo): void
    {
        $this->emissorTipo = $emissorTipo;
    }

    public function setEmissorId(int $emissorId): void
    {
        $this->emissorId = $emissorId;
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