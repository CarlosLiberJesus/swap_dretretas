<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoCargo
{
    private $id;
    private $cidadaoId;
    private $cargoId;
    private $legislaturaId;
    private $inicio;
    private $fim;
    private $src;
    private $sinopse;
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
        $cargo = new self($data['id'] ?? 0);
        $cargo->cidadaoId = $data['cidadao_id'] ?? null;
        $cargo->cargoId = $data['cargo_id'] ?? null;
        $cargo->legislaturaId = $data['legislatura_id'] ?? null;
        $cargo->inicio = $data['inicio'] ?? null;
        $cargo->fim = $data['fim'] ?? null;
        $cargo->src = $data['src'] ?? null;
        $cargo->sinopse = $data['sinopse'] ?? null;
        $cargo->createdAt = $data['created_at'] ?? $cargo->createdAt;
        $cargo->updatedAt = $data['updated_at'] ?? $cargo->updatedAt;
        return $cargo;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_cargos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadao_cargos");
        $cargos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cargos[] = self::create($data);
        }

        return $cargos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadao_cargos (cidadao_id, cargo_id, legislatura_id, inicio, fim, src, sinopse, created_at, updated_at) VALUES (%d, %d, %d, %d, '%s', %s, %s, %s, '%s', '%s')",
            $this->id,
            $this->cidadaoId,
            $this->cargoId,
            $this->legislaturaId,
            $this->inicio,
            $this->fim !== null ? "'" . $this->fim . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->sinopse !== null ? "'" . $this->sinopse . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_cargo_anexos WHERE cidadao_id = ? AND cargo_id = ?");
        $stmt->execute([$this->cidadaoId, $this->cargoId]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = CidadaoCargoAnexo::create($data);
        }

        return $anexos;
    }

    // Getters and Setters
    public function getCidadaoId(): ?int
    {
        return $this->cidadaoId;
    }

    public function getCargoId(): ?int
    {
        return $this->cargoId;
    }

    public function getLegislaturaId(): ?int
    {
        return $this->legislaturaId;
    }

    public function getInicio(): ?string
    {
        return $this->inicio;
    }

    public function getFim(): ?string
    {
        return $this->fim;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getSinopse(): ?string
    {
        return $this->sinopse;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setCidadaoId(int $cidadaoId): void
    {
        $this->cidadaoId = $cidadaoId;
    }

    public function setCargoId(int $cargoId): void
    {
        $this->cargoId = $cargoId;
    }

    public function setLegislaturaId(int $legislaturaId): void
    {
        $this->legislaturaId = $legislaturaId;
    }

    public function setInicio(string $inicio): void
    {
        $this->inicio = $inicio;
    }

    public function setFim(?string $fim): void
    {
        $this->fim = $fim;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function setSinopse(?string $sinopse): void
    {
        $this->sinopse = $sinopse;
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