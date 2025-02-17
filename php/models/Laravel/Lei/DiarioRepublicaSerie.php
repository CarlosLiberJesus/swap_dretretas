<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaSerie
{
    private $id;
    private $nome;
    private $sinopse;
    private $serieId;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $nome, string $sinopse)
    {
        $this->nome = $nome;
        $this->sinopse = $sinopse;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $serie = new self($data['nome'], $data['sinopse']);
        $serie->serieId = $data['serie_id'] ?? null;
        return $serie;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_series WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republica_series");
        $series = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $series[] = self::create($data);
        }

        return $series;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_series (nome, sinopse, serie_id, created_at, updated_at) VALUES ('%s', '%s', %s, '%s', '%s')",
            $this->nome,
            $this->sinopse,
            $this->serieId !== null ? $this->serieId : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
