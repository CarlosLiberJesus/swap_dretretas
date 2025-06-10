<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Cidadao;

class Cidadao
{
    private $id;
    private $uuid;
    private $nome;
    private $dataNascimento;
    private $dataFalecimento;
    private $genero;
    private $freguesiaId;
    private $nacional;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->nacional = true;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $instance = new self($data['id'] ?? 0);
        $instance->uuid = $data['uuid'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->dataNascimento = $data['data_nascimento'] ?? null;
        $instance->dataFalecimento = $data['data_falecimento'] ?? null;
        $instance->genero = $data['genero'] ?? null;
        $instance->freguesiaId = $data['freguesia_id'] ?? null;
        $instance->nacional = $data['nacional'] ?? true;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadaos WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function findByUuid(\PDO $pdo, string $uuid): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadaos WHERE uuid = ?");
        $stmt->execute([$uuid]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM cidadaos");
        $cidadaos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cidadaos[] = self::create($data);
        }

        return $cidadaos;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO cidadaos (id, uuid, nome, data_nascimento, data_falecimento, genero, freguesia_id, nacional, created_at, updated_at) VALUES (%d, '%s', '%s', %s, %s, %s, %s, %d, '%s', '%s')",
            $this->id,
            $this->uuid,
            $this->nome,
            $this->dataNascimento !== null ? "'" . $this->dataNascimento . "'" : "NULL",
            $this->dataFalecimento !== null ? "'" . $this->dataFalecimento . "'" : "NULL",
            $this->genero !== null ? "'" . $this->genero . "'" : "NULL",
            $this->freguesiaId !== null ? $this->freguesiaId : "NULL",
            $this->nacional ? 1 : 0,
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

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getDataNascimento(): ?string
    {
        return $this->dataNascimento;
    }

    public function getDataFalecimento(): ?string
    {
        return $this->dataFalecimento;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function getFreguesiaId(): ?int
    {
        return $this->freguesiaId;
    }

    public function isNacional(): ?bool
    {
        return $this->nacional;
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

    public function setDataNascimento(?string $dataNascimento): void
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function setDataFalecimento(?string $dataFalecimento): void
    {
        $this->dataFalecimento = $dataFalecimento;
    }

    public function setGenero(?string $genero): void
    {
        $this->genero = $genero;
    }

    public function setFreguesiaId(?int $freguesiaId): void
    {
        $this->freguesiaId = $freguesiaId;
    }

    public function setNacional(bool $nacional): void
    {
        $this->nacional = $nacional;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // Método para buscar a nacionalidade relacionada
    public function nacionalidade(\PDO $pdo): ?CidadaoNacionalidade
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_nacionalidades WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return CidadaoNacionalidade::create($data);
        }

        return null;
    }

    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_anexos WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = CidadaoAnexo::create($data);
        }

        return $anexos;
    }

    public function cargos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_cargos WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = CidadaoAnexo::create($data);
        }

        return $anexos;
    }

    // Método para buscar o contacto relacionado
    public function contacto(\PDO $pdo): ?CidadaoContacto
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_contactos WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return CidadaoContacto::create($data);
        }

        return null;
    }

    // Método para buscar o contacto relacionado
    public function dados(\PDO $pdo): ?CidadaoDados
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_dados WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return CidadaoDados::create($data);
        }

        return null;
    }

    // Método para buscar o contacto relacionado
    public function morada(\PDO $pdo): ?CidadaoMorada
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_moradas WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return CidadaoMorada::create($data);
        }

        return null;
    }

    // Método para buscar as relações relacionadas
    public function relacoes(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM cidadao_relacoes WHERE cidadao_id = ?");
        $stmt->execute([$this->id]);
        $relacoes = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $relacoes[] = CidadaoRelacao::create($data);
        }

        return $relacoes;
    }
}