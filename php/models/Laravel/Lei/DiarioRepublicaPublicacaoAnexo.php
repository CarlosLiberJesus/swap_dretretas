<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaPublicacao
{

    private $uuid;
    private $nome;
    private $diarioRepublicaPublicacaoId;
    private $anexoTipo;
    private $path;
    private $src;
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
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->diarioRepublicaPublicacaoId = $data['diario_republica_publicao_id'] ?? null;
        $instance->anexoTipo = AnexoTipo::findById($data['anexo_tipo_id']);
        $instance->path = $data['path'] ?? null;
        $instance->src = $data['src'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacao_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_publicacao_anexos (uuid, nome, diario_republica_publicao_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES ('%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome,
            $this->diarioRepublicaPublicacaoId,
            $this->anexoTipo->getId(),
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getDiarioRepublicaPublicacaoId(): ?int
    {
        return $this->diarioRepublicaPublicacaoId;
    }

    public function getAnexoTipo(): ?AnexoTipo
    {
        return $this->anexoTipo;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setDiarioRepublicaPublicacaoId(int $diarioRepublicaPublicacaoId): void
    {
        $this->diarioRepublicaPublicacaoId = $diarioRepublicaPublicacaoId;
    }

    public function setAnexoTipo(AnexoTipo $anexoTipo): void
    {
        $this->anexoTipo = $anexoTipo;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
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