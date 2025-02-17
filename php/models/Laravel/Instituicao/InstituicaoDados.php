<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoDados
{
    private $id;
    private $nif;
    private $certidaoPermanente;
    private $instituicaoId;
    private $descricao;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, ?float $nif, ?string $certidaoPermanente, int $instituicaoId, string $descricao)
    {
        $this->id = $id;
        $this->nif = $nif;
        $this->certidaoPermanente = $certidaoPermanente;
        $this->instituicaoId = $instituicaoId;
        $this->descricao = $descricao;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['nif'] ?? null,
            $data['certidao_permanente'] ?? null,
            $data['instituicao_id'],
            $data['descricao']
        );
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_dados WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_dados");
        $dados = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $dados[] = self::create($data);
        }

        return $dados;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_dados (nif, certidao_permanente, instituicao_id, descricao, created_at, updated_at) VALUES (%s, %s, %d, '%s', '%s', '%s')",
            $this->nif !== null ? "'" . $this->nif . "'" : "NULL",
            $this->certidaoPermanente !== null ? "'" . $this->certidaoPermanente . "'" : "NULL",
            $this->instituicaoId,
            $this->descricao,
            $this->createdAt,
            $this->updatedAt
        );
    }
}