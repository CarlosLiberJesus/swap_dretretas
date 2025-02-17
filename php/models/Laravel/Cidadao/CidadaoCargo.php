<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class CidadaoCargo
{
    private $cidadaoId;
    private $cargoId;
    private $legislaturaId;
    private $inicio;
    private $fim;
    private $src;
    private $sinopse;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $cidadaoId, int $cargoId, int $legislaturaId, string $inicio)
    {
        $this->cidadaoId = $cidadaoId;
        $this->cargoId = $cargoId;
        $this->legislaturaId = $legislaturaId;
        $this->inicio = $inicio;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $cargo = new self($data['cidadao_id'], $data['cargo_id'], $data['legislatura_id'], $data['inicio']);
        $cargo->fim = $data['fim'] ?? null;
        $cargo->src = $data['src'] ?? null;
        $cargo->sinopse = $data['sinopse'] ?? null;
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
            "INSERT INTO cidadao_cargos (cidadao_id, cargo_id, legislatura_id, inicio, fim, src, sinopse, created_at, updated_at) VALUES (%d, %d, %d, '%s', %s, %s, %s, '%s', '%s')",
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
}
