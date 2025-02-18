<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoMorada
{
    private $id;
    private $cidadaoId;
    private $morada;
    private $codigoPostal;
    private $localidade;
    private $concelhoId;
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
        $morada = new self($data['id'] ?? 0);
        $morada->cidadaoId = $data['cidadao_id'] ?? null;
        $morada->morada = $data['morada'] ?? null;
        $morada->codigoPostal = $data['codigo_postal'] ?? null;
        $morada->localidade = $data['localidade'] ?? null;
        $morada->concelhoId = $data['concelho_id'] ?? null;
        $morada->createdAt = $data['created_at'] ?? $morada->createdAt;
        $morada->updatedAt = $data['updated_at'] ?? $morada->updatedAt;
        return $morada;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_moradas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_moradas");
        $moradas = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $moradas[] = self::create($data);
        }

        return $moradas;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_moradas (id, cidadao_id, morada, codigo_postal, localidade, concelho_id, created_at, updated_at) VALUES (%d, %d, '%s', %s, %s, %s, '%s', '%s')",
            $this->id,
            $this->cidadaoId,
            $this->morada,
            $this->codigoPostal !== null ? "'" . $this->codigoPostal . "'" : "NULL",
            $this->localidade !== null ? "'" . $this->localidade . "'" : "NULL",
            $this->concelhoId !== null ? $this->concelhoId : "NULL",
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

    public function getMorada(): ?string
    {
        return $this->morada;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function getLocalidade(): ?string
    {
        return $this->localidade;
    }

    public function getConcelhoId(): ?int
    {
        return $this->concelhoId;
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

    public function setMorada(string $morada): void
    {
        $this->morada = $morada;
    }

    public function setCodigoPostal(?string $codigoPostal): void
    {
        $this->codigoPostal = $codigoPostal;
    }

    public function setLocalidade(?string $localidade): void
    {
        $this->localidade = $localidade;
    }

    public function setConcelhoId(?int $concelhoId): void
    {
        $this->concelhoId = $concelhoId;
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