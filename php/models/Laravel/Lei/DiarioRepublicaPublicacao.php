<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaPublicacao
{
    private $id;
    private $uuid;
    private $nome;
    private $src;
    private $diarioRepublicaId;
    private $serieId;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, int $diarioRepublicaId, int $serieId)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->diarioRepublicaId = $diarioRepublicaId;
        $this->serieId = $serieId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $publicacao = new self($data['uuid'], $data['nome'], $data['diario_republica_id'], $data['serie_id']);
        $publicacao->src = $data['src'] ?? null;
        return $publicacao;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacoes WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republica_publicacoes");
        $publicacoes = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $publicacoes[] = self::create($data);
        }

        return $publicacoes;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_publicacoes (uuid, nome, src, diario_republica_id, serie_id, created_at, updated_at) VALUES ('%s', '%s', %s, %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->diarioRepublicaId,
            $this->serieId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
