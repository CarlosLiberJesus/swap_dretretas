<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

use Carlos\Organize\Model\Laravel\ContactoTipo;

class CidadaoContacto
{
    private $id;
    private $cidadaoId;
    private $contactoTipo;
    private $contacto;
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
        $contacto = new self($data['id'] ?? 0);
        $contacto->id = $data['id'] ?? null;
        $contacto->cidadaoId = $data['cidadao_id'] ?? null;
        $contacto->contactoTipo = $data['contacto_tipo_id'] ?? null;
        $contacto->contacto = $data['contacto'] ?? null;
        $contacto->createdAt = $data['created_at'] ?? $contacto->createdAt;
        $contacto->updatedAt = $data['updated_at'] ?? $contacto->updatedAt;
        return $contacto;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_contactos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_contactos");
        $contactos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $contactos[] = self::create($data);
        }

        return $contactos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_contactos (id, cidadao_id, contacto_tipo_id, contacto, created_at, updated_at) VALUES (%d, %d, %d, '%s', '%s', '%s')",
            $this->id,
            $this->cidadaoId,
            $this->contactoTipo->id,
            $this->contacto,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCidadaoId(): ?int
    {
        return $this->cidadaoId;
    }

    public function getContactoTipo(): ?ContactoTipo
    {
        return $this->contactoTipo;
    }

    public function getContacto(): ?string
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

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setContactoTipo(ContactoTipo $contactoTipo): void
    {
        $this->contactoTipo = $contactoTipo;
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