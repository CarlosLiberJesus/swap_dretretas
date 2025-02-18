<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoDados
{
    private $id;
    private $nif;
    private $cc;
    private $ccAux;
    private $segSocial;
    private $nSaude;
    private $cidadaoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $dados = new self($data['id'] ?? 0);
        $dados->nif = $data['nif'] ?? null;
        $dados->cc = $data['cc'] ?? null;
        $dados->ccAux = $data['cc_aux'] ?? null;
        $dados->segSocial = $data['seg_social'] ?? null;
        $dados->nSaude = $data['n_saude'] ?? null;
        $dados->cidadaoId = $data['cidadao_id'] ?? null;
        $dados->createdAt = $data['created_at'] ?? $dados->createdAt;
        $dados->updatedAt = $data['updated_at'] ?? $dados->updatedAt;
        return $dados;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_dados WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_dados");
        $dados = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $dados[] = self::create($data);
        }

        return $dados;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_dados (id, nif, cc, cc_aux, seg_social, n_saude, cidadao_id, created_at, updated_at) VALUES (%d, %s, %s, %s, %s, %s, %d, '%s', '%s')",
            $this->id,
            $this->nif !== null ? $this->nif : "NULL",
            $this->cc !== null ? $this->cc : "NULL",
            $this->ccAux !== null ? "'" . $this->ccAux . "'" : "NULL",
            $this->segSocial !== null ? $this->segSocial : "NULL",
            $this->nSaude !== null ? $this->nSaude : "NULL",
            $this->cidadaoId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNif(): ?float
    {
        return $this->nif;
    }

    public function getCc(): ?float
    {
        return $this->cc;
    }

    public function getCcAux(): ?string
    {
        return $this->ccAux;
    }

    public function getSegSocial(): ?float
    {
        return $this->segSocial;
    }

    public function getNSaude(): ?float
    {
        return $this->nSaude;
    }

    public function getCidadaoId(): ?int
    {
        return $this->cidadaoId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNif(?float $nif): void
    {
        $this->nif = $nif;
    }

    public function setCc(?float $cc): void
    {
        $this->cc = $cc;
    }

    public function setCcAux(?string $ccAux): void
    {
        $this->ccAux = $ccAux;
    }

    public function setSegSocial(?float $segSocial): void
    {
        $this->segSocial = $segSocial;
    }

    public function setNSaude(?float $nSaude): void
    {
        $this->nSaude = $nSaude;
    }

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}