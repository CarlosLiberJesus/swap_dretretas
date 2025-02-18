<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoComRamo
{
    private $id;
    private $instituicaoId;
    private $instituicaoRamoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, int $instituicaoId, int $instituicaoRamoId)
    {
        $this->id = $id;
        $this->instituicaoId = $instituicaoId;
        $this->instituicaoRamoId = $instituicaoRamoId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['id'], $data['instituicao_id'], $data['instituicao_ramo_id']);
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_com_ramos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_com_ramos");
        $ramos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $ramos[] = self::create($data);
        }

        return $ramos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_com_ramos (instituicao_id, instituicao_ramo_id, created_at, updated_at) VALUES (%d, %d, '%s', '%s')",
            $this->instituicaoId,
            $this->instituicaoRamoId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function getInstituicaoRamo(\PDO $pdo): ?InstituicaoRamo
    {
        return InstituicaoRamo::findById($pdo, $this->instituicaoRamoId);
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

    public function getInstituicaoRamoId(): int
    {
        return $this->instituicaoRamoId;
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

    public function setInstituicaoRamoId(int $instituicaoRamoId): void
    {
        $this->instituicaoRamoId = $instituicaoRamoId;
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