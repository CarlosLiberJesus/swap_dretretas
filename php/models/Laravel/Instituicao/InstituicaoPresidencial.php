<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoPresidencial
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $presidencialId;
    private $dataInicio;
    private $dataFim;
    private $sinopse;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, int $instituicaoId, int $presidencialId)
    {
        $this->uuid = $uuid;
        $this->instituicaoId = $instituicaoId;
        $this->presidencialId = $presidencialId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $presidencial = new self($data['uuid'], $data['instituicao_id'], $data['presidencial_id']);
        $presidencial->nome = $data['nome'] ?? null;
        $presidencial->dataInicio = $data['data_inicio'] ?? null;
        $presidencial->dataFim = $data['data_fim'] ?? null;
        $presidencial->sinopse = $data['sinopse'] ?? null;
        return $presidencial;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_presidenciais WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_presidenciais");
        $presidenciais = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $presidenciais[] = self::create($data);
        }
        return $presidenciais;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_presidenciais (uuid, nome, instituicao_id, presidencial_id, data_inicio, data_fim, sinopse, created_at, updated_at) VALUES ('%s', %s, %d, %d, %s, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->instituicaoId,
            $this->presidencialId,
            $this->dataInicio !== null ? "'" . $this->dataInicio . "'" : "NULL",
            $this->dataFim !== null ? "'" . $this->dataFim . "'" : "NULL",
            $this->sinopse !== null ? "'" . $this->sinopse . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_presidencial_anexos WHERE instituicao_presidencial_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = InstituicaoAnexo::create($data);
        }

        return $anexos;
    }
}
