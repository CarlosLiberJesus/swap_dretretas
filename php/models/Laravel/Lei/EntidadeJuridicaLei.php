<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

use Carlos\Organize\Model\Laravel\Lei\Lei;

class EntidadeJuridicaLei
{
    private $uuid;
    private $nome;
    private $entidadeJuridicaId;
    private $lei;
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
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->entidadeJuridicaId = $data['entidade_juridica_id'] ?? null;
        $instance->lei = Lei::findById($data['lei_id']);
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridica_leis WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM entidade_juridica_leis");
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = self::create($data);
        }

        return $leis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO entidade_juridica_leis (uuid, nome, entidade_juridica_id, lei_id, created_at, updated_at) VALUES ('%s', %s, %d, %d, '%s', '%s')",
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->entidadeJuridicaId,
            $this->lei->getId(),
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getEntidadeJuridicaId(): ?int
    {
        return $this->entidadeJuridicaId;
    }

    public function getLei(): ?Lei
    {
        return $this->lei;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEntidadeJuridicaId(int $entidadeJuridicaId): void
    {
        $this->entidadeJuridicaId = $entidadeJuridicaId;
    }

    public function setLei(Lei $lei): void
    {
        $this->lei = $lei;
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