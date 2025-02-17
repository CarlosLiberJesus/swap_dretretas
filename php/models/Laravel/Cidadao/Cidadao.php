<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class Cidadao
{
    private $id;
    private $uuid;
    private $nome;
    private $dataNascimento;
    private $dataFalecimento;
    private $freguesiaId;
    private $nacional;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, bool $nacional)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->nacional = $nacional;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $cidadao = new self($data['uuid'], $data['nome'], $data['nacional']);
        $cidadao->dataNascimento = $data['data_nascimento'] ?? null;
        $cidadao->dataFalecimento = $data['data_falecimento'] ?? null;
        $cidadao->freguesiaId = $data['freguesia_id'] ?? null;
        return $cidadao;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadaos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadaos");
        $cidadaos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cidadaos[] = self::create($data);
        }

        return $cidadaos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadaos (uuid, nome, data_nascimento, data_falecimento, freguesia_id, nacional, created_at, updated_at) VALUES ('%s', '%s', %s, %s, %s, %d, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->dataNascimento !== null ? "'" . $this->dataNascimento . "'" : "NULL",
            $this->dataFalecimento !== null ? "'" . $this->dataFalecimento . "'" : "NULL",
            $this->freguesiaId !== null ? $this->freguesiaId : "NULL",
            $this->nacional ? 1 : 0,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
