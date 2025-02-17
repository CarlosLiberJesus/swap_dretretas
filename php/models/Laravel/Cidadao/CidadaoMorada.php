<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoMorada
{
    private $cidadaoId;
    private $morada;
    private $codigoPostal;
    private $localidade;
    private $concelhoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, string $morada)
    {
        $this->cidadaoId = $cidadaoId;
        $this->morada = $morada;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $morada = new self($data['cidadao_id'], $data['morada']);
        $morada->codigoPostal = $data['codigo_postal'] ?? null;
        $morada->localidade = $data['localidade'] ?? null;
        $morada->concelhoId = $data['concelho_id'] ?? null;
        return $morada;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_moradas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_moradas");
        $moradas = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $moradas[] = self::create($data);
        }

        return $moradas;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_moradas (cidadao_id, morada, codigo_postal, localidade, concelho_id, created_at, updated_at) VALUES (%d, '%s', %s, %s, %s, '%s', '%s')",
            $this->cidadaoId,
            $this->morada,
            $this->codigoPostal !== null ? "'" . $this->codigoPostal . "'" : "NULL",
            $this->localidade !== null ? "'" . $this->localidade . "'" : "NULL",
            $this->concelhoId !== null ? $this->concelhoId : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
