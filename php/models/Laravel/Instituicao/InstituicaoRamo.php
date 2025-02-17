<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoRamo
{
    private $id;
    private $uuid;
    private $tipo;
    private $descricao;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $uuid, string $tipo, string $descricao)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->tipo = $tipo;
        $this->descricao = $descricao;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['id'], $data['uuid'], $data['tipo'], $data['descricao']);
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_ramos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_ramos");
        $ramos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $ramos[] = self::create($data);
        }

        return $ramos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_ramos (uuid, tipo, descricao, created_at, updated_at) VALUES ('%s', '%s', '%s', '%s', '%s')",
            $this->uuid,
            $this->tipo,
            $this->descricao,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
