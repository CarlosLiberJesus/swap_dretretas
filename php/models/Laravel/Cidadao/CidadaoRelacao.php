<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoRelacao
{
    private $id;
    private $cidadaoId;
    private $comCidadaoId;
    private $relacaoTipoId;
    private $onde;
    private $ondeId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCidadaoId(): int
    {
        return $this->cidadaoId;
    }

    public function getCidadao(\PDO $pdo): ?Cidadao
    {
        return Cidadao::findById($pdo, $this->cidadaoId);
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

    public function getRelacaoTipo(\PDO $pdo): ?RelacaoTipo
    {
        return RelacaoTipo::findById($pdo, $this->relacaoTipoId);
    }

    public function getOnde(): ?string
    {
        return $this->onde;
    }

    public function getOndeId(): ?int
    {
        return $this->ondeId;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setCidadao(Cidadao $cidadao): void
    {
        $this->cidadaoId = $cidadao->getId();
    }

    public function setComCidadaoId(?int $comCidadaoId): void
    {
        $this->comCidadaoId = $comCidadaoId;
    }

    public function setComCidadao(?Cidadao $cidadao): void
    {
        $this->comCidadaoId = $cidadao->getId();
    }

    public function setRelacaoTipoId(int $relacaoTipoId): void
    {
        $this->relacaoTipoId = $relacaoTipoId;
    }

    public function setRelacaoTipo(RelacaoTipo $relacaoTipo): void
    {
        $this->relacaoTipoId = $relacaoTipo->getId();
    }

    public function setOnde(?string $onde): void
    {
        $this->onde = $onde;
    }

    public function setOndeId(?int $ondeId): void
    {
        $this->ondeId = $ondeId;
    }

    public static function create(array $data): self
    {
        $relacao = new self($data['id'] ?? 0);
        $relacao->cidadaoId = $data['cidadao_id'] ?? null;
        $relacao->comCidadaoId = $data['com_cidadao_id'] ?? null;
        $relacao->relacaoTipoId = $data['relacao_tipo_id'] ?? null;
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
            "INSERT INTO cidadao_relacoes (cidadao_id, com_cidadao_id, relacao_tipo_id, onde, onde_id, created_at, updated_at) VALUES (%d, %s, %d, %s, %s, '%s', '%s')",
            $this->cidadaoId,
            $this->comCidadaoId !== null ? $this->comCidadaoId : "NULL",
            $this->relacaoTipoId,
            $this->onde !== null ? "'" . $this->onde . "'" : "NULL",
            $this->ondeId !== null ? $this->ondeId : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
