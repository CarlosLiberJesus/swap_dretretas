<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoCargo
{
    private $id;
    private $uuid;
    private $cargo;
    private $instituicaoId;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $uuid, string $cargo, int $instituicaoId)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->cargo = $cargo;
        $this->instituicaoId = $instituicaoId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self($data['id'], $data['uuid'], $data['cargo'], $data['instituicao_id']);
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_cargos");
        $cargos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cargos[] = self::create($data);
        }

        return $cargos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_cargos (uuid, cargo, instituicao_id, created_at, updated_at) VALUES ('%s', '%s', %d, '%s', '%s')",
            $this->uuid,
            $this->cargo,
            $this->instituicaoId,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Método para buscar os anexos relacionados
    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargo_anexos WHERE instituicao_cargo_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = InstituicaoAnexo::create($data);
        }

        return $anexos;
    }

    // Método para buscar as leis relacionadas
    public function leis(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_cargo_leis WHERE instituicao_cargo_id = ?");
        $stmt->execute([$this->id]);
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = InstituicaoCargoLei::create($data);
        }

        return $leis;
    }
}

