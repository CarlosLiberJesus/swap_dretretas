<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class EntidadeJuridica
{
    private $id;
    private $nome;
    private $descricao;
    private $params;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $nome, ?string $descricao = null, ?string $params = null)
    {
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->params = $params;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['nome'],
            $data['descricao'] ?? null,
            $data['params'] ?? null
        );
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridicas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM entidade_juridicas");
        $entidades = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $entidades[] = self::create($data);
        }

        return $entidades;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO entidade_juridicas (nome, descricao, params, created_at, updated_at) VALUES ('%s', %s, %s, '%s', '%s')",
            $this->nome,
            $this->descricao !== null ? "'" . $this->descricao . "'" : "NULL",
            $this->params !== null ? "'" . $this->params . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
