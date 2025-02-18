<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoNacionalidade
{
    private $id;
    private $instituicaoId;
    private $nacionalidadeId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, int $instituicaoId, int $nacionalidadeId)
    {
        $this->id = $id;
        $this->instituicaoId = $instituicaoId;
        $this->nacionalidadeId = $nacionalidadeId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['id'], $data['instituicao_id'], $data['nacionalidade_id']);
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

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getInstituicaoId(): int
    {
        return $this->instituicaoId;
    }

    public function getNacionalidadeId(): int
    {
        return $this->nacionalidadeId;
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

    public function setInstituicaoId(int $instituicaoId): void
    {
        $this->instituicaoId = $instituicaoId;
    }

    public function setNacionalidadeId(int $nacionalidadeId): void
    {
        $this->nacionalidadeId = $nacionalidadeId;
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