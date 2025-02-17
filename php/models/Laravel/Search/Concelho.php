<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;

class Concelho
{
    private $uuid;
    private $nome;
    private $codigo;
    private $distritoId;
    private $params;

    public function __construct(string $uuid, string $nome, string $codigo, int $distritoId, ?string $params = null)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->distritoId = $distritoId;
        $this->params = $params;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['uuid'],
            $data['nome'],
            $data['codigo'],
            $data['distrito_id'],
            $data['params'] ?? null
        );
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM concelhos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM concelhos");
        $concelhos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $concelhos[] = self::create($data);
        }

        return $concelhos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO concelhos (uuid, nome, codigo, distrito_id, params) VALUES ('%s', '%s', '%s', %d, %s)",
            $this->uuid,
            $this->nome,
            $this->codigo,
            $this->distritoId,
            $this->params !== null ? "'" . $this->params . "'" : "NULL"
        );
    }
}
