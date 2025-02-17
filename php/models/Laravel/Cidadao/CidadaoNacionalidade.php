<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoNacionalidade
{
    private $cidadaoId;
    private $nacionalidadeId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, int $nacionalidadeId)
    {
        $this->cidadaoId = $cidadaoId;
        $this->nacionalidadeId = $nacionalidadeId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['cidadao_id'], $data['nacionalidade_id']);
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
            "INSERT INTO cidadao_nacionalidades (cidadao_id, nacionalidade_id, created_at, updated_at) VALUES (%d, %d, '%s', '%s')",
            $this->cidadaoId,
            $this->nacionalidadeId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
