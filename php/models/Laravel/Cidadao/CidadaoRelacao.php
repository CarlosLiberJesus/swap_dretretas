<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoRelacao
{
    private $cidadaoId;
    private $comCidadaoId;
    private $relacaoTipoId;
    private $onde;
    private $ondeId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, int $comCidadaoId, int $relacaoTipoId)
    {
        $this->cidadaoId = $cidadaoId;
        $this->comCidadaoId = $comCidadaoId;
        $this->relacaoTipoId = $relacaoTipoId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $relacao = new self($data['cidadao_id'], $data['com_cidadao_id'], $data['relacao_tipo_id']);
        $relacao->onde = $data['onde'] ?? null;
        $relacao->ondeId = $data['onde_id'] ?? null;
        return $relacao;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_relacoes WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_relacoes");
        $relacoes = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $relacoes[] = self::create($data);
        }

        return $relacoes;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_relacoes (cidadao_id, com_cidadao_id, relacao_tipo_id, onde, onde_id, created_at, updated_at) VALUES (%d, %d, %d, %s, %s, '%s', '%s')",
            $this->cidadaoId,
            $this->comCidadaoId,
            $this->relacaoTipoId,
            $this->onde !== null ? "'" . $this->onde . "'" : "NULL",
            $this->ondeId !== null ? "'" . $this->ondeId . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
