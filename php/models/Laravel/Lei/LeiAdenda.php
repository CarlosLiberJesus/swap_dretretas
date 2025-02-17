<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class LeiAdenda
{
    private $leiOriginalId;
    private $leiAdendaId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $leiOriginalId, int $leiAdendaId)
    {
        $this->leiOriginalId = $leiOriginalId;
        $this->leiAdendaId = $leiAdendaId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['lei_original_id'], $data['lei_adenda_id']);
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
            "INSERT INTO lei_adendas (lei_original_id, lei_adenda_id, created_at, updated_at) VALUES (%d, %d, '%s', '%s')",
            $this->leiOriginalId,
            $this->leiAdendaId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
