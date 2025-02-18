<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

use Carlos\Organize\Model\Laravel\AnexoTipo;

class InstituicaoLegislaturaAnexo
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoLegislaturaId;
    private $anexoTipo;
    private $path;
    private $src;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $uuid, string $nome, int $instituicaoLegislaturaId, AnexoTipo $anexoTipo)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->nome = $nome;
        $this->instituicaoLegislaturaId = $instituicaoLegislaturaId;
        $this->anexoTipo = $anexoTipo;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $anexoTipo = AnexoTipo::findById($data['anexo_tipo_id']);
        $anexo = new self($data['id'], $data['uuid'], $data['nome'], $data['instituicao_legislatura_id'], $anexoTipo);
        $anexo->path = $data['path'] ?? null;
        $anexo->src = $data['src'] ?? null;
        return $anexo;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_legislatura_anexos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_legislatura_anexos");
        $anexos = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = self::create($data);
        }
        return $anexos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_legislatura_anexos (id, uuid, nome, instituicao_legislatura_id, anexo_tipo_id, path, src, created_at, updated_at) VALUES (%d, '%s', '%s', %d, %d, %s, %s, '%s', '%s')",
            $this->id,
            $this->uuid,
            $this->nome,
            $this->instituicaoLegislaturaId,
            $this->anexoTipo->id,
            $this->path !== null ? "'" . $this->path . "'" : "NULL",
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getInstituicaoLegislaturaId(): int
    {
        return $this->instituicaoLegislaturaId;
    }

    public function getAnexoTipo(): AnexoTipo
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setInstituicaoLegislaturaId(int $instituicaoLegislaturaId): void
    {
        $this->instituicaoLegislaturaId = $instituicaoLegislaturaId;
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