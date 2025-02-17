<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class Lei
{
    private $id;
    private $uuid;
    private $codigo;
    private $nomeCompleto;
    private $proponente;
    private $sumario;
    private $texto;
    private $path;
    private $emVigor;
    private $dataToggle;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        int $id,
        string $uuid,
        string $codigo,
        string $nomeCompleto,
        ?string $proponente,
        ?string $sumario,
        ?string $texto,
        ?string $path,
        bool $emVigor,
        ?string $dataToggle
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->codigo = $codigo;
        $this->nomeCompleto = $nomeCompleto;
        $this->proponente = $proponente;
        $this->sumario = $sumario;
        $this->texto = $texto;
        $this->path = $path;
        $this->emVigor = $emVigor;
        $this->dataToggle = $dataToggle;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['uuid'],
            $data['codigo'],
            $data['nome_completo'],
            $data['proponente'] ?? null,
            $data['sumario'] ?? null,
            $data['texto'] ?? null,
            $data['path'] ?? null,
            (bool) $data['em_vigor'],
            $data['data_toggle'] ?? null
        );
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM leis WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM leis");
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = self::create($data);
        }

        return $leis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO leis (uuid, codigo, nome_completo, proponente, sumario, texto, path, em_vigor, data_toggle, created_at, updated_at) VALUES ('%s', '%s', '%s', %s, %s, %s, %s, %d, %s, '%s', '%s')",
            $this->uuid,
            $this->codigo,
            $this->nomeCompleto,
            $this->proponente !== null ? "'" . $this->proponente . "'" : "NULL",
            $this->sumario !== null ? "'" . $this->sumario . "'" : "NULL",
            $this->texto !== null ? "'" . $this->texto . "'" : "NULL",
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->emVigor ? 1 : 0,
            $this->dataToggle !== null ? "'" . $this->dataToggle . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }
}



