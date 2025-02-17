<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoNacionalidade
{
    private $id;
    private $instituicaoId;
    private $nacionalidadeId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $instituicaoId, int $nacionalidadeId)
    {
        $this->instituicaoId = $instituicaoId;
        $this->nacionalidadeId = $nacionalidadeId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['instituicao_id'], $data['nacionalidade_id']);
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_nacionalidades WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_nacionalidades");
        $nacionalidades = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nacionalidades[] = self::create($data);
        }
        return $nacionalidades;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_nacionalidades (instituicao_id, nacionalidade_id, created_at, updated_at) VALUES (%d, %d, '%s', '%s')",
            $this->instituicaoId,
            $this->nacionalidadeId,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
