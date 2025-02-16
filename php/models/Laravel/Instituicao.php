<?php

declare(strict_types=1);

namespace Carlos\Organize\Model\Laravel;


class Instituicao {
    private $uuid;
    private $nome;
    private $instituicaoRamoId;
    private $republicaId;
    private $sigla;
    private $sinopse;
    private $respondeInstituicaoId;
    private $entidadeJuridicaId;

    public function __construct(string $nome, int $instituicaoRamoId, int $republicaId) {
        $this->uuid = $this->generateUuid(); // Método para gerar UUID
        $this->nome = $nome;
        $this->instituicaoRamoId = $instituicaoRamoId;
        $this->republicaId = $republicaId;
        $this->sigla = null;
        $this->sinopse = null;
        $this->respondeInstituicaoId = null;
        $this->entidadeJuridicaId = null;
    }

    private function generateUuid(): string {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public function setSigla(?string $sigla): self {
        $this->sigla = $sigla;
        return $this;
    }

    public function setSinopse(?string $sinopse): self {
        $this->sinopse = $sinopse;
        return $this;
    }

    public function setRespondeInstituicaoId(?int $id): self {
        $this->respondeInstituicaoId = $id;
        return $this;
    }

    public function setEntidadeJuridicaId(?int $id): self {
        $this->entidadeJuridicaId = $id;
        return $this;
    }

    public function fetchFromApi(string $apiEndpoint): void {
        // Método para chamar APIs (ex.: Wikidata) e completar os dados
        // Exemplo: buscar descrição ou sigla no Wikidata
        $client = new \GuzzleHttp\Client();
        $response = $client->get($apiEndpoint);
        $data = json_decode($response->getBody(), true);

        if (isset($data['sigla'])) {
            $this->sigla = $data['sigla'];
        }
        if (isset($data['description'])) {
            $this->sinopse = $data['description'];
        }
    }

    public function toSqlInsert(): string {
        return sprintf(
            "INSERT INTO instituicoes (uuid, nome, instituicao_ramo_id, republica_id, sigla, sinopse, responde_instituicao_id, entidade_juridica_id) VALUES ('%s', '%s', %d, %d, '%s', '%s', %s, %s)",
            $this->uuid,
            $this->nome,
            $this->instituicaoRamoId,
            $this->republicaId,
            $this->sigla ?? 'NULL',
            $this->sinopse ?? 'NULL',
            $this->respondeInstituicaoId !== null ? $this->respondeInstituicaoId : 'NULL',
            $this->entidadeJuridicaId !== null ? $this->entidadeJuridicaId : 'NULL'
        );
    }

    public function checkIfExists(\PDO $pdo): bool {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM instituicoes WHERE nome = ?");
        $stmt->execute([$this->nome]);
        return $stmt->fetchColumn() > 0;
    }
}

// Exemplo de uso
$instituicao = new Instituicao("Presidência da República", 1, 1); // Nome, Ramo ID, República ID
$instituicao->setSigla("PR")
            ->setSinopse("Órgão máximo do poder executivo.")
            ->setRespondeInstituicaoId(null) // Não responde a ninguém
            ->setEntidadeJuridicaId(1); // Organismos da Administração Pública

// Chamar API para completar dados (opcional)
$instituicao->fetchFromApi("https://www.wikidata.org/w/api.php?action=wbsearchentities&search=Presidência%20da%20República&language=pt");

if (!$instituicao->checkIfExists($pdo)) {
    $sql = $instituicao->toSqlInsert();
    $pdo->exec($sql);
}