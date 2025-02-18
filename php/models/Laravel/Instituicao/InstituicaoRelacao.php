<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\RelacaoTipo;
use Carlos\Organize\Model\Laravel\Cidadao\Cidadao;


class InstituicaoRelacao
{
    private $id;
    private $instituicaoId;
    private $comInstituicaoId;
    private $comCidadaoId;
    private $relacaoTipoId;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $relacao = new self();
        $relacao->id = $data['id'] ?? 0;
        $relacao->instituicaoId = $data['instituicao_id'] ?? null;
        $relacao->relacaoTipoId = $data['relacao_tipo_id'] ?? null;
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

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getInstituicaoId(): int
    {
        return $this->instituicaoId;
    }

    public function getInstituicao(\PDO $pdo): ?Instituicao
    {
        return Instituicao::findById($pdo, $this->instituicaoId);
    }

    public function getComInstituicaoId(): ?int
    {
        return $this->comInstituicaoId;
    }

    public function getComInstituicao(\PDO $pdo): ?Instituicao
    {
        return Instituicao::findById($pdo, $this->comInstituicaoId);
    }

    public function getComCidadaoId(): ?int
    {
        return $this->comCidadaoId;
    }

    public function getComCidadao(\PDO $pdo): ?Cidadao
    {
        return Cidadao::findById($pdo, $this->comCidadaoId);
    }

    public function getRelacaoTipoId(): int
    {
        return $this->relacaoTipoId;
    }

    public function getRelacaoTipo(): ?RelacaoTipo
    {
        return RelacaoTipo::getById($this->relacaoTipoId);
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setInstituicaoId(int $id): void
    {
        $this->instituicaoId = $id;
    }

    public function setInstituicao(Instituicao $instituicao): void
    {
        $this->instituicaoId = $instituicao->getId();
    }

    public function setComInstituicaoId(?int $id): void
    {
        $this->comInstituicaoId = $id;
    }

    public function setRelacaoTipo(RelacaoTipo $relacaoTipo): void
    {
        $this->relacaoTipoId = $relacaoTipo->id;
    }

    public function setRelacaoTipoId(int $id): void
    {
        $this->relacaoTipoId = $id;
    }

    public function setComInstituicao(?Instituicao $instituicao): void
    {
        $this->comInstituicaoId = $instituicao->getId();
    }

    public function setComCidadaoId(?int $id): void
    {
        $this->comCidadaoId = $id;
    }

    public function setComCidadao(?Cidadao $cidadao): void
    {
        $this->comCidadaoId = $cidadao->getId();
    }
}