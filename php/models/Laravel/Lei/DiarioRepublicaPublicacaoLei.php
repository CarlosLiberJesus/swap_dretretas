<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class DiarioRepublicaPublicacaoLei
{
    private $id;
    private $drPublicacaoId;
    private $leiId;
    private $src;
    private $paginas;
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
        $instance->drPublicacaoId = $data['dr_publicacao_id'] ?? null;
        $instance->leiId = $data['lei_id'] ?? null;
        $instance->src = $data['src'] ?? null;
        $instance->paginas = $data['paginas'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM diario_republica_publicacao_leis WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM diario_republica_publicacao_leis");
        $publicacaoLeis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $publicacaoLeis[] = self::create($data);
        }

        return $publicacaoLeis;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO diario_republica_publicacao_leis (dr_publicacao_id, lei_id, src, paginas, created_at, updated_at) VALUES (%d, %d, %s, '%s', '%s', '%s')",
            $this->drPublicacaoId,
            $this->leiId,
            $this->src !== null ? "'" . $this->src . "'" : "NULL",
            $this->paginas,
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrPublicacaoId(): ?int
    {
        return $this->drPublicacaoId;
    }

    public function getLeiId(): ?int
    {
        return $this->leiId;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getPaginas(): ?string
    {
        return $this->paginas;
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

    public function setDrPublicacaoId(int $drPublicacaoId): void
    {
        $this->drPublicacaoId = $drPublicacaoId;
    }

    public function setLeiId(int $leiId): void
    {
        $this->leiId = $leiId;
    }

    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function setPaginas(string $paginas): void
    {
        $this->paginas = $paginas;
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