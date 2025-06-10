<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Search;

class Freguesia
{
    private $id;
    private $uuid;
    private $nome;
    private $codigo;
    private $concelhoId;
    private $params;

    public function __construct(int $id, string $uuid, string $nome, string $codigo, int $concelhoId, ?string $params = null)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->concelhoId = $concelhoId;
        $this->params = $params;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['uuid'],
            $data['nome'],
            $data['codigo'],
            $data['concelho_id'],
            $data['params'] ?? null
        );
    }

    public static function findId(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM freguesias WHERE uuid = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM freguesias WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByNome(\PDO $pdo, string $nome): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM freguesias WHERE nome LIKE ?");
        $stmt->execute(['%' . $nome . '%']);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM freguesias");
        $freguesias = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $freguesias[] = self::create($data);
        }

        return $freguesias;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO freguesias (id, uuid, nome, codigo, concelho_id, params) VALUES (%d, '%s', '%s', '%s', %d, %s)",
            $this->id,
            $this->uuid,
            $this->nome,
            $this->codigo,
            $this->concelhoId,
            $this->params !== null ? "'" . $this->params . "'" : "NULL"
        );
    }
}