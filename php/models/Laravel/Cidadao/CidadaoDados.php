<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoDados
{
    private $nif;
    private $cc;
    private $ccAux;
    private $segSocial;
    private $nSaude;
    private $cidadaoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, ?float $nif = null, ?float $cc = null)
    {
        $this->cidadaoId = $cidadaoId;
        $this->nif = $nif;
        $this->cc = $cc;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $dados = new self($data['cidadao_id'], $data['nif'] ?? null, $data['cc'] ?? null);
        $dados->ccAux = $data['cc_aux'] ?? null;
        $dados->segSocial = $data['seg_social'] ?? null;
        $dados->nSaude = $data['n_saude'] ?? null;
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
            "INSERT INTO cidadao_dados (nif, cc, cc_aux, seg_social, n_saude, cidadao_id, created_at, updated_at) VALUES (%s, %s, %s, %s, %s, %d, '%s', '%s')",
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
}
