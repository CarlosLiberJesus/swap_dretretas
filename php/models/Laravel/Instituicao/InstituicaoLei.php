<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoLei
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $leiId;
    private $createdAt;
    private $updatedAt;

    public function __construct(string $uuid, string $nome, int $instituicaoId, int $leiId)
    {
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->instituicaoId = $instituicaoId;
        $this->leiId = $leiId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $lei = new self($data['uuid'], $data['nome'], $data['instituicao_id'], $data['lei_id']);
        return $lei;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_leis WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_leis");
        $leis = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = self::create($data);
        }
        return $leis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_leis (uuid, nome, instituicao_id, lei_id, created_at, updated_at) VALUES ('%s', '%s', %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->instituicaoId,
            $this->leiId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
