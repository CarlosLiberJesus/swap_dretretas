<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\Lei;

class InstituicaoCargoLei
{
    private $uuid;
    private $nome;
    private $instituicaoCargoId;
    private $leiId;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, int $instituicaoCargoId, int $leiId)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->instituicaoCargoId = $instituicaoCargoId;
        $this->leiId = $leiId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['uuid'],
            $data['nome'],
            $data['instituicao_cargo_id'],
            $data['lei_id']
        );
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargo_leis WHERE uuid = ?");
        $stmt->execute([$uuid]);
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
            "INSERT INTO instituicao_cargo_leis (uuid, nome, instituicao_cargo_id, lei_id, created_at, updated_at) VALUES ('%s', '%s', %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->instituicaoCargoId,
            $this->leiId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
