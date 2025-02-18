<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoContacto
{
    private $id;
    private $instituicaoId;
    private $contactoTipoId;
    private $contacto;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, int $instituicaoId, int $contactoTipoId, string $contacto)
    {
        $this->id = $id;
        $this->instituicaoId = $instituicaoId;
        $this->contactoTipoId = $contactoTipoId;
        $this->contacto = $contacto;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['id'], $data['instituicao_id'], $data['contacto_tipo_id'], $data['contacto']);
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_contactos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_contactos");
        $contactos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $contactos[] = self::create($data);
        }

        return $contactos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_contactos (instituicao_id, contacto_tipo_id, contacto, created_at, updated_at) VALUES (%d, %d, '%s', '%s', '%s')",
            $this->instituicaoId,
            $this->contactoTipoId,
            $this->contacto,
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

    public function getContactoTipoId(): int
    {
        return $this->contactoTipoId;
    }

    public function getContacto(): string
    {
        return $this->contacto;
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

    public function setContactoTipoId(int $contactoTipoId): void
    {
        $this->contactoTipoId = $contactoTipoId;
    }

    public function setContacto(string $contacto): void
    {
        $this->contacto = $contacto;
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