<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\Lei;

class InstituicaoCargoLei
{
    private $id;
    private $instituicaoCargoId;
    private $leiId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, int $instituicaoCargoId, int $leiId)
    {
        $this->id = $id;
        $this->instituicaoCargoId = $instituicaoCargoId;
        $this->leiId = $leiId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['instituicao_cargo_id'],
            $data['lei_id']
        );
    }

    public static function findByInstituicaoCargoId(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargo_leis WHERE instituicao_cargo_id = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByLeiId(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargo_leis WHERE lei_id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_cargo_leis");
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = self::create($data);
        }

        return $leis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_cargo_leis (instituicao_cargo_id, lei_id, created_at, updated_at) VALUES (%d, %d, '%s', '%s')",
            $this->instituicaoCargoId,
            $this->leiId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
