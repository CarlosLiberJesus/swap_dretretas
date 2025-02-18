<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Search;

class Nacionalidade
{
    private $id;
    private $uuid;
    private $nacionalidade;
    private $pais;
    private $params;

    public function __construct(int $id, string $uuid, string $nacionalidade, string $pais, ?string $params = null)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->nacionalidade = $nacionalidade;
        $this->pais = $pais;
        $this->params = $params;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['uuid'],
            $data['nacionalidade'],
            $data['pais'],
            $data['params'] ?? null
        );
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM nacionalidades WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM nacionalidades WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByNome(\PDO $pdo, string $nome): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM nacionalidades WHERE nacionalidade LIKE ?");
        $stmt->execute(['%' . $nome . '%']);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM nacionalidades");
        $nacionalidades = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nacionalidades[] = self::create($data);
        }

        return $nacionalidades;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO nacionalidades (uuid, nacionalidade, pais, params) VALUES ('%s', '%s', '%s', %s)",
            $this->uuid,
            $this->nacionalidade,
            $this->pais,
            $this->params !== null ? "'" . $this->params . "'" : "NULL"
        );
    }
}
