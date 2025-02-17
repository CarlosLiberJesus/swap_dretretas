<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Lei;

class EntidadeJuridica
{
    private $id;
    private $nome;
    private $descricao;
    private $params;
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
        $instance->nome = $data['nome'] ?? null;
        $instance->descricao = $data['descricao'] ?? null;
        $instance->params = $data['params'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridicas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM entidade_juridicas");
        $entidades = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $entidades[] = self::create($data);
        }

        return $entidades;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO entidade_juridicas (nome, descricao, params, created_at, updated_at) VALUES ('%s', %s, %s, '%s', '%s')",
            $this->nome,
            $this->descricao !== null ? "'" . $this->descricao . "'" : "NULL",
            $this->params !== null ? "'" . $this->params . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function getParams(): ?string
    {
        return $this->params;
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

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setParams(?string $params): void
    {
        $this->params = $params;
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
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridica_anexos WHERE entidades_juridica_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = EntidadeJuridicaAnexo::create($data);
        }

        return $anexos;
    }

    // Método para buscar os anexos relacionados
    public function leis(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM entidade_juridica_leis WHERE entidades_juridica_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = EntidadeJuridicaLei::create($data);
        }

        return $anexos;
    }
}