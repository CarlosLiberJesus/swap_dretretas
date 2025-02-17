<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel\Instituicao;

class Instituicao
{
    private $id;
    private $uuid;
    private $republicaId;
    private $nome;
    private $sigla;
    private $sinopse;
    private $respondeInstituicaoId;
    private $entidadeJuridicaId;
    private $nacional;
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
        $instance->republicaId = $data['republica_id'] ?? null;
        $instance->nome = $data['nome'] ?? null;
        $instance->sigla = $data['sigla'] ?? null;
        $instance->sinopse = $data['sinopse'] ?? null;
        $instance->respondeInstituicaoId = $data['responde_instituicao_id'] ?? null;
        $instance->entidadeJuridicaId = $data['entidade_juridica_id'] ?? null;
        $instance->nacional = $data['nacional'] ?? null;
        $instance->createdAt = $data['created_at'] ?? $instance->createdAt;
        $instance->updatedAt = $data['updated_at'] ?? $instance->updatedAt;

        return $instance;
    }

    public static function findById(\PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicoes WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::create($data);
        }

        return null;
    }

    public static function all(\PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM instituicoes");
        $instituicoes = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $instituicoes[] = self::create($data);
        }

        return $instituicoes;
    }

    public function toSqlInsert(): string
    {
        return sprintf(
            "INSERT INTO instituicoes (uuid, republica_id, nome, sigla, sinopse, responde_instituicao_id, entidade_juridica_id, nacional, created_at, updated_at) VALUES ('%s', %d, '%s', %s, %s, %s, %s, %d, '%s', '%s')",
            $this->uuid,
            $this->republicaId,
            $this->nome,
            $this->sigla !== null ? "'" . $this->sigla . "'" : "NULL",
            $this->sinopse !== null ? "'" . $this->sinopse . "'" : "NULL",
            $this->respondeInstituicaoId !== null ? $this->respondeInstituicaoId : "NULL",
            $this->entidadeJuridicaId !== null ? $this->entidadeJuridicaId : "NULL",
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

    public function getRepublicaId(): ?int
    {
        return $this->republicaId;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function getSinopse(): ?string
    {
        return $this->sinopse;
    }

    public function getRespondeInstituicaoId(): ?int
    {
        return $this->respondeInstituicaoId;
    }

    public function getEntidadeJuridicaId(): ?int
    {
        return $this->entidadeJuridicaId;
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

    public function setRepublicaId(int $republicaId): void
    {
        $this->republicaId = $republicaId;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setSigla(?string $sigla): void
    {
        $this->sigla = $sigla;
    }

    public function setSinopse(?string $sinopse): void
    {
        $this->sinopse = $sinopse;
    }

    public function setRespondeInstituicaoId(?int $respondeInstituicaoId): void
    {
        $this->respondeInstituicaoId = $respondeInstituicaoId;
    }

    public function setEntidadeJuridicaId(?int $entidadeJuridicaId): void
    {
        $this->entidadeJuridicaId = $entidadeJuridicaId;
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

    // Método para buscar os ramos relacionados
    public function ramos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_com_ramos WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $ramos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $instituicaoComRamo = InstituicaoComRamo::create($data);
            $ramo = $instituicaoComRamo->getInstituicaoRamo($pdo);
            if ($ramo) {
                $ramos[] = $ramo;
            }
        }

        return $ramos;
    }

    // Método para buscar os anexos relacionados
    public function anexos(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_anexos WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $anexos = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $anexos[] = InstituicaoAnexo::create($data);
        }

        return $anexos;
    }

    // Método para buscar o contacto relacionado
    public function contacto(\PDO $pdo): ?InstituicaoContacto
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_contactos WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return InstituicaoContacto::create($data);
        }

        return null;
    }

    // Método para buscar os dados relacionados
    public function dados(\PDO $pdo): ?InstituicaoDados
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_dados WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return InstituicaoDados::create($data);
        }

        return null;
    }

    // Método para buscar as leis relacionadas
    public function leis(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_leis WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $leis = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $leis[] = InstituicaoLei::create($data);
        }

        return $leis;
    }

    // Método para buscar a morada relacionada
    public function morada(\PDO $pdo): ?InstituicaoMorada
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_moradas WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return InstituicaoMorada::create($data);
        }

        return null;
    }

    // Método para buscar a nacionalidade relacionada
    public function nacionalidade(\PDO $pdo): ?InstituicaoNacionalidade
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_nacionalidades WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return InstituicaoNacionalidade::create($data);
        }

        return null;
    }

    // Método para buscar as legislaturas relacionadas
    public function legislaturas(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_legislaturas WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $legislaturas = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $legislaturas[] = InstituicaoLegislatura::create($data);
        }

        return $legislaturas;
    }

    // Método para buscar as presidenciais relacionadas
    public function presidenciais(\PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM instituicao_presidenciais WHERE instituicao_id = ?");
        $stmt->execute([$this->id]);
        $presidenciais = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $presidenciais[] = InstituicaoPresidencial::create($data);
        }

        return $presidenciais;
    }
}