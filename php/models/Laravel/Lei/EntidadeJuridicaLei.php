<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

use Carlos\Organize\Model\Laravel\Lei\Lei;

class EntidadeJuridicaLei
{
    private $id;
    private $uuid;
    private $nome;
    private $entidadeJuridicaId;
    private $leiId;
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
        $instance = new self($data['id'] ?? 0);
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->entidadeJuridicaId = $data['entidade_juridica_id'] ?? null;
        $instance->leiId = $data['lei_id'];
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
            "INSERT INTO entidade_juridica_leis (id, uuid, nome, entidade_juridica_id, lei_id, created_at, updated_at) VALUES (%d, '%s', %s, %d, %d, '%s', '%s')",
            $this->id,
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->entidadeJuridicaId,
            $this->leiId,
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

    public function getEntidadeJuridicaId(): ?int
    {
        return $this->entidadeJuridicaId;
    }

    public function getLeiId():  int
    {
        return $this->leiId;
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

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function setEntidadeJuridicaId(int $entidadeJuridicaId): void
    {
        $this->entidadeJuridicaId = $entidadeJuridicaId;
    }

    public function setLei(int $leiId): void
    {
        $this->leiId = $leiId;
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