<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoRelacao
{
    private $id;
    private $instituicaoId;
    private $comInstituicaoId;
    private $comCidadaoId;
    private $relacaoTipoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $instituicaoId, int $relacaoTipoId)
    {
        $this->instituicaoId = $instituicaoId;
        $this->relacaoTipoId = $relacaoTipoId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $relacao = new self($data['instituicao_id'], $data['relacao_tipo_id']);
        $relacao->comInstituicaoId = $data['com_instituicao_id'] ?? null;
        $relacao->comCidadaoId = $data['com_cidadao_id'] ?? null;
        return $relacao;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_relacoes WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_relacoes");
        $relacoes = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $relacoes[] = self::create($data);
        }
        return $relacoes;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_relacoes (instituicao_id, com_instituicao_id, com_cidadao_id, relacao_tipo_id, created_at, updated_at) VALUES (%d, %s, %s, %d, '%s', '%s')",
            $this->instituicaoId,
            $this->comInstituicaoId !== null ? $this->comInstituicaoId : "NULL",
            $this->comCidadaoId !== null ? $this->comCidadaoId : "NULL",
            $this->relacaoTipoId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
