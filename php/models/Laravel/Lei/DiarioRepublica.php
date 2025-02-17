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

    public function __construct(string $uuid, string $nome, string $publicacao)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->publicacao = $publicacao;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['uuid'], $data['nome'], $data['publicacao']);
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
}
