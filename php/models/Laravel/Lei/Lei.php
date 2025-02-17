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

    public function __construct()
    {
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $instance = new self();
        $instance->id = $data['id'] ?? null;
        $instance->uuid = $data['uuid'] ?? null;
        $instance->codigo = $data['codigo'] ?? null;
        $instance->nomeCompleto = $data['nome_completo'] ?? null;
        $instance->proponente = $data['proponente'] ?? null;
        $instance->sumario = $data['sumario'] ?? null;
        $instance->texto = $data['texto'] ?? null;
        $instance->path = $data['path'] ?? null;
        $instance->emVigor = $data['em_vigor'] ?? null;
        $instance->dataToggle = $data['data_toggle'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
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

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function getNomeCompleto(): ?string
    {
        return $this->nomeCompleto;
    }

    public function getProponente(): ?string
    {
        return $this->proponente;
    }

    public function getSumario(): ?string
    {
        return $this->sumario;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function isEmVigor(): ?bool
    {
        return $this->emVigor;
    }

    public function getDataToggle(): ?string
    {
        return $this->dataToggle;
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

    public function setCodigo(string $codigo): void
    {
        $this->codigo = $codigo;
    }

    public function setNomeCompleto(string $nomeCompleto): void
    {
        $this->nomeCompleto = $nomeCompleto;
    }

    public function setProponente(?string $proponente): void
    {
        $this->proponente = $proponente;
    }

    public function setSumario(?string $sumario): void
    {
        $this->sumario = $sumario;
    }

    public function setTexto(?string $texto): void
    {
        $this->texto = $texto;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function setEmVigor(bool $emVigor): void
    {
        $this->emVigor = $emVigor;
    }

    public function setDataToggle(?string $dataToggle): void
    {
        $this->dataToggle = $dataToggle;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // Método para buscar os anexos relacionados
    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM lei_anexos WHERE lei_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = LeiAnexo::create($data);
        }

        return $anexos;
    }
}