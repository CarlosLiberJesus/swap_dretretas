<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoLegislatura
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $legislaturaId;
    private $dataInicio;
    private $dataFim;
    private $sinopse;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, int $instituicaoId, int $legislaturaId)
    {
        $this->uuid = $uuid;
        $this->instituicaoId = $instituicaoId;
        $this->legislaturaId = $legislaturaId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $legislatura = new self($data['uuid'], $data['instituicao_id'], $data['legislatura_id']);
        $legislatura->nome = $data['nome'] ?? null;
        $legislatura->dataInicio = $data['data_inicio'] ?? null;
        $legislatura->dataFim = $data['data_fim'] ?? null;
        $legislatura->sinopse = $data['sinopse'] ?? null;
        return $legislatura;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_legislaturas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_legislaturas");
        $legislaturas = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $legislaturas[] = self::create($data);
        }
        return $legislaturas;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_legislaturas (uuid, nome, instituicao_id, legislatura_id, data_inicio, data_fim, sinopse, created_at, updated_at) VALUES ('%s', %s, %d, %d, %s, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->instituicaoId,
            $this->legislaturaId,
            $this->dataInicio !== null ? "'" . $this->dataInicio . "'" : "NULL",
            $this->dataFim !== null ? "'" . $this->dataFim . "'" : "NULL",
            $this->sinopse !== null ? "'" . $this->sinopse . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}
