<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

use Carlos\Organize\Model\Laravel\Lei\Lei;

class EntidadeJuridicaLei
{
    private $uuid;
    private $nome;
    private $entidadeJuridicaId;
    private $lei;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, ?string $nome, int $entidadeJuridicaId, Lei $lei)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->entidadeJuridicaId = $entidadeJuridicaId;
        $this->lei = $lei;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(\PDO $pdo, array $data): self
    {
        $lei = Lei::findById($pdo, $data['lei_id']);
        return new self($data['uuid'], $data['nome'] ?? null, $data['entidade_juridica_id'], $lei);
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridica_leis WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($pdo, $data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM entidade_juridica_leis");
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = self::create($pdo, $data);
        }

        return $leis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO entidade_juridica_leis (uuid, nome, entidade_juridica_id, lei_id, created_at, updated_at) VALUES ('%s', %s, %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->entidadeJuridicaId,
            $this->lei->getId(),
            $this->createdAt,
            $this->updatedAt
        );
    }
}