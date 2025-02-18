<?php declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class InstituicaoLegislatura
{
    private $id;
    private $uuid;
    private $nome;
    private $instituicaoId;
    private $legislaturaId;
    private $dataInicio;
    private $dataFim;
    private $sinopse;
    private $createdAt;
    private $updatedAt;

    public function __construct(int $id, string $uuid, int $instituicaoId, int $legislaturaId)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->instituicaoId = $instituicaoId;
        $this->legislaturaId = $legislaturaId;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        $legislatura = new self($data['id'], $data['uuid'], $data['instituicao_id'], $data['legislatura_id']);
        $legislatura->nome = $data['nome'] ?? null;
        $legislatura->dataInicio = $data['data_inicio'] ?? null;
        $legislatura->dataFim = $data['data_fim'] ?? null;
        $legislatura->sinopse = $data['sinopse'] ?? null;
        return $legislatura;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_legislaturas WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return self::create($data);
        }
        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicao_legislaturas");
        $legislaturas = [];
        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $legislaturas[] = self::create($data);
        }
        return $legislaturas;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicao_legislaturas (uuid, nome, instituicao_id, legislatura_id, data_inicio, data_fim, sinopse, created_at, updated_at) VALUES ('%s', %s, %d, %d, %s, %s, %s, '%s', '%s')",
            $this->uuid,
            $this->nome !== null ? "'" . $this->nome . "'" : "NULL",
            $this->instituicaoId,
            $this->legislaturaId,
            $this->dataInicio !== null ? "'" . $this->dataInicio . "'" : "NULL",
            $this->dataFim !== null ? "'" . $this->dataFim . "'" : "NULL",
            $this->sinopse !== null ? "'" . $this->sinopse . "'" : "NULL",
            $this->createdAt,
            $this->updatedAt
        );
    }

    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_legislatura_anexos WHERE instituicao_legislatura_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = InstituicaoLegislaturaAnexo::create($data);
        }

        return $anexos;
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

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getInstituicaoId(): int
    {
        return $this->instituicaoId;
    }

    public function getLegislaturaId(): int
    {
        return $this->legislaturaId;
    }

    public function getDataInicio(): ?string
    {
        return $this->dataInicio;
    }

    public function getDataFim(): ?string
    {
        return $this->dataFim;
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function setInstituicaoId(int $instituicaoId): void
    {
        $this->instituicaoId = $instituicaoId;
    }

    public function setLegislaturaId(int $legislaturaId): void
    {
        $this->legislaturaId = $legislaturaId;
    }

    public function setDataInicio(?string $dataInicio): void
    {
        $this->dataInicio = $dataInicio;
    }

    public function setDataFim(?string $dataFim): void
    {
        $this->dataFim = $dataFim;
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
