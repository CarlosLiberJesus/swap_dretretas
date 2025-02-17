<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

use Carlos\Organize\Model\Laravel\ContactoTipo;

class CidadaoContacto
{
    private $cidadaoId;
    private $contactoTipo;
    private $contacto;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, ContactoTipo $contactoTipo, string $contacto)
    {
        $this->cidadaoId = $cidadaoId;
        $this->contactoTipo = $contactoTipo;
        $this->contacto = $contacto;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $contactoTipo = ContactoTipo::findById($data['contacto_tipo_id']);
        $contacto = new self($data['cidadao_id'], $contactoTipo, $data['contacto']);
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
            "INSERT INTO cidadao_contactos (cidadao_id, contacto_tipo_id, contacto, created_at, updated_at) VALUES (%d, %d, '%s', '%s', '%s')",
            $this->cidadaoId,
            $this->contactoTipo->id,
            $this->contacto,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
