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

    public function __construct(
        int $id,
        string $uuid,
        int $republicaId,
        string $nome,
        ?string $sigla,
        ?string $sinopse,
        ?int $respondeInstituicaoId,
        ?int $entidadeJuridicaId,
        bool $nacional
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->republicaId = $republicaId;
        $this->nome = $nome;
        $this->sigla = $sigla;
        $this->sinopse = $sinopse;
        $this->respondeInstituicaoId = $respondeInstituicaoId;
        $this->entidadeJuridicaId = $entidadeJuridicaId;
        $this->nacional = $nacional;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
            $data['uuid'],
            $data['republica_id'],
            $data['nome'],
            $data['sigla'] ?? null,
            $data['sinopse'] ?? null,
            $data['responde_instituicao_id'] ?? null,
            $data['entidade_juridica_id'] ?? null,
            (bool) $data['nacional']
        );
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
}