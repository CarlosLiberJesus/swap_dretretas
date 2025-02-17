<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaPublicacao
{
    private $id;
    private $uuid;
    private $nome;
    private $src;
    private $diarioRepublicaId;
    private $serieId;
    private $createdAt;
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'] ?? null;
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->src = $data['src'] ?? null;
        $instance->diarioRepublicaId = $data['diario_republica_id'] ?? null;
        $instance->serieId = $data['serie_id'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacoes WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republica_publicacoes");
        $publicacoes = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $publicacoes[] = self::create($data);
        }

        return $publicacoes;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_publicacoes (uuid, nome, src, diario_republica_id, serie_id, created_at, updated_at) VALUES ('%s', '%s', %s, %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->diarioRepublicaId,
            $this->serieId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getDiarioRepublicaId(): ?int
    {
        return $this->diarioRepublicaId;
    }

    public function getSerieId(): ?int
    {
        return $this->serieId;
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

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function setDiarioRepublicaId(int $diarioRepublicaId): void
    {
        $this->diarioRepublicaId = $diarioRepublicaId;
    }

    public function setSerieId(int $serieId): void
    {
        $this->serieId = $serieId;
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