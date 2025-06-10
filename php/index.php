<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Carlos\Organize\Lib\MigrationHandler;
use Carlos\Organize\Model\Tretas\DocumentDRe;
use Carlos\Organize\Model\Laravel\Cidadao\{Cidadao, CidadaoAnexo, CidadaoCargo, CidadaoCargoAnexo, CidadaoContacto, CidadaoMorada, CidadaoNacionalidade, CidadaoRelacao};
use Carlos\Organize\Model\Laravel\Instituicao\{Instituicao, InstituicaoAnexo, InstituicaoContacto, InstituicaoDados, InstituicaoMorada, InstituicaoCargo, InstituicaoCargoAnexo, InstituicaoCargoLei, InstituicaoComRamo, InstituicaoLegislatura, InstituicaoLegislaturaAnexo, InstituicaoPresidencial, InstituicaoPresidencialAnexo, InstituicaoNacionalidade, InstituicaoRelacao};
use Carlos\Organize\Model\Laravel\Lei\{Lei, LeiAnexo, LeiAdenda, LeiEmissor, DiarioRepublica, DiarioRepublicaPublicacao, DiarioRepublicaPublicacaoLei, DiarioRepublicaPublicacaoAnexo};
use Carlos\Organize\Model\Laravel\EntidadeJuridica\{EntidadeJuridica, EntidadeJuridicaAnexo, EntidadeJuridicaLei};
use Ramsey\Uuid\Uuid;

// Definição das opções de linha de comando
$options = getopt("", ["insert"]);

$shouldInsert = array_key_exists('insert', $options);

echo "Iniciando a Migração...\n";

$finalBDPdo = new \PDO("mysql:host=localhost;dbname=lumen_db", "local_user", "Qwerty.123");
$finalBDHandler = new MigrationHandler($finalBDPdo);

$importBDPdo = new \PDO("mysql:host=localhost;dbname=python_dre", "local_user", "Qwerty.123");
$importBDHandler = new MigrationHandler($importBDPdo);

/**
 * OBJECTOS QUE PODEMOS INSERIR
 *
 *           $cidadao = (new Cidadao(0))->create(["uuid" => null, "nome" => null, "data_nascimento" => null, "data_falecimento" => null, "genero" => null, "freguesia_id" => null, "nacional" => true]);
 *           $cidadaoAnexos = array();
 *           // $cidadaoAnexos.push(addCidaoAnexo($cidadao->getId(), ["uuid" => null, "nome" => null, "cidadao_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $cidadaoCargos = array();
 *           // $cidadaoCargos.push(addCidadaoCargo($cidadao->getId(), ["uuid" => null, "nome" => null, "cidadao_id" => null, "instituicao_id" => null, "cargo" => null, "data_inicio" => null, "data_fim" => null]));
 *           $cidadaoCargoAnexos = array();
 *           // $cidadaoCargoAnexos.push(addCidadaoCargoAnexo($cidadaoCargos[0]->getId(), ["uuid" => null, "nome" => null, "cidadao_cargo_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $cidadaoContactos = array();
 *           // $cidadaoContactos.push(addCidadaoContacto($cidadao->getId(), ["contacto_tipo_id" => null, "contacto" => null]));
 *           $cidadaoMoradas = array();
 *           // $cidadaoMoradas.push(addCidadaoMorada($cidadao->getId(), ["cidadao_id" => null, "morada" => null, "codigo_postal" => null, "concelho_id" => null, "localidade" => null]));
 *           $cidadaoNacionalidade = (new CidadaoNacionalidade(0))->create(["cidadao_id" => null, "pais_id" => null]);
 *           $cidadaoRelacoes = new CidadaoRelacao();
 *           // $cidadaoRelacoes.push(addCidadaoRelacao($cidadao->getId(), ["nome" => null, "com_cidadao_id" => null, "relacao_tipo_id" => null, "onde" => null, "onde_id" => null]));
 *
 *           $instituicao = (new Instituicao(0))->create(["uuid" => null, "republicaId" => null, "nome" => null, "sigla" => null, "sinopse" => null, "respondeInstituicaoId" => null, "entidadeJuridicaId" => null, "nacional" => null, "createdAt" => date('Y-m-d H:i:s'), "updatedAt" => date('Y-m-d H:i:s')]);
 *           $instituicaoAnexos = array();
 *           // $instituicaoAnexos.push(addInstituicaoAnexo($instituicao->getId(), ["uuid" => null, "nome" => null, "instituicao_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $instituicaoContactos = (new InstituicaoContacto(0))->create(["instituicao_id" => null, "contacto_tipo_id" => null, "contacto" => null]);
 *           $instituicaoDado = (new InstituicaoDados(0))->create(["instituicao_id" => null, "nif" => null, "certidao_permanente" => null, "descricao" => null]);
 *           $instituicaoMoradas = array();
 *           // $instituicaoMoradas.push(addInstituicaoMorada($instituicao->getId(), ["instituicao_id" => null, "morada" => null, "codigo_postal" => null, "concelho_id" => null, "localidade" => null]));
 *           $instituicaoCargos = array();
 *           // $instituicaoCargos.push(addInstituicaoCargo($instituicao->getId(), ["instituicao_id" => null, "cargo" => null, "data_inicio" => null, "data_fim" => null]));
 *           $instituicaoCargoAnexos = array();
 *           // $instituicaoCargoAnexos.push(addInstituicaoCargoAnexo($instituicaoCargos[0]->getId(), ["instituicao_cargo_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $instituicaoCargoLeis = array();
 *           // $instituicaoCargoLeis.push(addInstituicaoCargoLei($instituicaoCargos[0]->getId(), ["lei_id" => null, "instituicao_cargo_id" => null]));
 *           $instituicaoComRamos = array();
 *           // $instituicaoComRamos.push(addInstituicaoComRamo($instituicao->getId(), ["instituicao_id" => null, "ramo_id" => null]));
 *           $instituicaoLegislaturas = array();
 *           // $instituicaoLegislaturas.push(addInstituicaoLegislatura($instituicao->getId(), ["nome" => null, "sinopse" => null, "legislatura_id" => null, "instituicao_id" => null, "data_inicio" => null, "data_fim" => null]));
 *           $instituicaoLegislaturaAnexos = array();
 *           // $instituicaoLegislaturaAnexos.push(addInstituicaoLegislaturaAnexo($instituicaoLegislaturas[0]->getId(), ["uuid" => null, "nome" => null, "instituicao_legislatura_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $instituicaoPresidenciais = array();
 *           // $instituicaoPresidenciais.push(addInstituicaoPresidencial($instituicao->getId(), ["nome" => null, "sinopse" => null, "presidencial_id" => null, "instituicao_id" => null, "data_inicio" => null, "data_fim" => null, "presidente" => null]));
 *           $instituicaoPresidencialAnexos = new InstituicaoPresidencialAnexo();
 *           // $instituicaoPresidencialAnexos.push(addInstituicaoPresidencialAnexo($instituicaoPresidenciais[0]->getId(), ["uuid" => null, "nome" => null, "instituicao_presidencial_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $instituicaoNacionalidade = (new InstituicaoNacionalidade(0))->create(["instituicao_id" => null, "pais_id" => null]);
 *           $instituicaoRelacoes = array();
 *           // $instituicaoRelacoes.push(addInstituicaoRelacao($instituicao->getId(), ["nome" => null, "com_cidadao_id" => null, "com_instituicao_id" => null, "relacao_tipo_id" => null, "onde" => null, "onde_id" => null]));
 *
 *           $lei = (new Lei(0))->create(["uuid" => null, "codigo" => null, "nomeCompleto" => null, "proponente" => null, "sumario" => null, "texto" => null, "path" => null, "src" => null, "emVigor" => true, "dataToggle" => null]);
 *           $leiAnexos = array();
 *           // $leiAnexos.push(addLeiAnexo($lei->getId(), ["uuid" => null, "nome" => null, "lei_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
 *           $leiAdendas = array();
 *           // $leiAdendas.push(addLeiAdenda($lei->getId(), ["lei_original_id" => null, "lei_adenda_id" => null]));
 *           $leiEmissores = new LeiEmissor();
 *           // $leiEmissores.push(addLeiEmissor($lei->getId(), ["emissor_tipo" => null, "lei_id" => null, "emissor_id" => null]));
 *           $diarioRepublica = (new DiarioRepublica(0))->create(["uuid" => null, "nome" => null, "publicacao" => null]);
 *           $diarioRepublicaPublicacoes = array();
 *           // $diarioRepublicaPublicacoes.push(addDiarioRepublicaPublicacao($diarioRepublica->getId(), ["diario_republica_id" => null, "uuid" => null, "nome" => null, "src" => null, "serie_id" => null]));
 *           $diarioRepulicaPublicacaoLeis = new DiarioRepublicaPublicacaoLei();
 *           // $diarioRepulicaPublicacaoLeis.push(addDiarioRepublicaPublicacaoLei($diarioRepublicaPublicacoes[0]->getId(), ["dr_publicacao_id" => null, "lei_id" => null, "src" => null, "pagina" => null]));
 *           $diarioRepublicaPublicacaoAnexos = new DiarioRepublicaPublicacaoAnexo();
 *           $entidadeJuridica = (new EntidadeJuridica(0))->create(["uuid" => null, "nome" => null, "tipo" => null]);
 *           $entidadeJuridicaAnexos = new EntidadeJuridicaAnexo();
 *           $entidadeJuridicaLeis = new EntidadeJuridicaLei();
 **/


if ($finalBDHandler->testConnection('diario_republicas') && $importBDHandler->testConnection('dreapp_document')) {
    echo "Conexão com as bases de dados estabelecida\n";

    $res = $importBDHandler->getDReDocuments("SELECT * FROM `dreapp_document` WHERE id IN (240818, 315219) ORDER BY date ASC;");

    foreach ($res as $dreDocument) {
        try {
            $finalBDPdo->beginTransaction();
            
            
            print_r($dreDocument);
            echo $dreDocument->generateContent() . "\n";

            $drNome = $dreDocument->getSource();
            if (substr($drNome, -1) === '.') {
                $drNome = rtrim($drNome, '.');
            }
            
            $diarioRepublica = (new DiarioRepublica(0))->create(["uuid" => Uuid::uuid4()->toString(), "nome" => $drNome, "publicacao" => $dreDocument->getDate()]);

            // Se a flag --insert for passada, executa a inserção
            if ($shouldInsert) {
                $finalBDPdo->exec($diario->toSqlInsert());
                echo "Documento inserido com sucesso: " . $dreDocument->getId() . "\n";
            } else {
                // Caso contrário, apenas exibe a informação e pergunta se quer continuar
                echo $diarioRepublica->toSqlInsert() . "\n";
                echo "Deseja continuar? (s/n): ";
                $handle = fopen("php://stdin", "r");
                $line = fgets($handle);
                if (trim($line) != 's') {
                    echo "Processo interrompido pelo utilizador.\n";
                    $finalBDPdo->rollBack();
                    break;
                }
            }

            $finalBDPdo->commit();
        } catch (\Exception $e) {
            $finalBDPdo->rollBack();
            echo "Erro ao processar o documento " . $dreDocument->getId() . ": " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "Erro a conectar com as bases de dados.\n";
    exit(1);
}

echo "Migração " . ($shouldInsert ? "executada" : "simulada") . " com sucesso!\n";

function addCidaoAnexo($cidadaoId, $data) {
    $cidadaoAnexo = new CidadaoAnexo(0);
    $cidadaoAnexo->create($data);
    $cidadaoAnexo->setCidadaoId($cidadaoId);
    return $cidadaoAnexo;
}

function addCidadaoCargo($cidadaoId, $data) {
    $cidadaoCargo = new CidadaoCargo(0);
    $cidadaoCargo->create($data);
    $cidadaoCargo->setCidadaoId($cidadaoId);
    return $cidadaoCargo;
}

function addCidadaoCargoAnexo($cidadaoCargoId, $data) {
    $cidadaoCargoAnexo = new CidadaoCargoAnexo(0);
    $cidadaoCargoAnexo->create($data);
    $cidadaoCargoAnexo->setCidadaoCargoId($cidadaoCargoId);
    return $cidadaoCargoAnexo;
}

function addCidadaoMorada($cidadaoId, $data) {
    $cidadaoMorada = new CidadaoMorada(0);
    $cidadaoMorada->create($data);
    $cidadaoMorada->setCidadaoId($cidadaoId);
    return $cidadaoMorada;
}

function addCidadaoRelacao($cidadaoId, $data) {
    $cidadaoRelacao = new CidadaoRelacao(0);
    $cidadaoRelacao->create($data);
    $cidadaoRelacao->setCidadaoId($cidadaoId);
    return $cidadaoRelacao;
}

function addInstituicaoAnexo($instituicaoId, $data) {
    $instituicaoAnexo = new InstituicaoAnexo(0);
    $instituicaoAnexo->create($data);
    $instituicaoAnexo->setInstituicaoId($instituicaoId);
    return $instituicaoAnexo;
}

function addInstituicaoMorada($instituicaoId, $data) {
    $instituicaoMorada = new InstituicaoMorada(0);
    $instituicaoMorada->create($data);
    $instituicaoMorada->setInstituicaoId($instituicaoId);
    return $instituicaoMorada;
}

function addInstituicaoCargo($instituicaoId, $data) {
    $instituicaoCargo = new InstituicaoCargo(0);
    $instituicaoCargo->create($data);
    $instituicaoCargo->setInstituicaoId($instituicaoId);
    return $instituicaoCargo;
}

function addInstituicaoCargoAnexo($instituicaoCargoId, $data) {
    $instituicaoCargoAnexo = new InstituicaoCargoAnexo(0);
    $instituicaoCargoAnexo->create($data);
    $instituicaoCargoAnexo->setInstituicaoCargoId($instituicaoCargoId);
    return $instituicaoCargoAnexo;
}

function addInstituicaoCargoLei($instituicaoCargoId, $data) {
    $instituicaoCargoLei = new InstituicaoCargoLei(0);
    $instituicaoCargoLei->create($data);
    $instituicaoCargoLei->setInstituicaoCargoId($instituicaoCargoId);
    return $instituicaoCargoLei;
}

//TODO: IntituicaoComRamo é uma relacao com InstituicaoRamo
function addInstituicaoComRamo($instituicaoId, $data) {
    $instituicaoComRamo = new InstituicaoComRamo(0);
    $instituicaoComRamo->create($data);
    $instituicaoComRamo->setInstituicaoId($instituicaoId);
    return $instituicaoComRamo;
}

function addInstituicaoLegislatura($instituicaoId, $data) {
    $instituicaoLegislatura = new InstituicaoLegislatura(0);
    $instituicaoLegislatura->create($data);
    $instituicaoLegislatura->setInstituicaoId($instituicaoId);
    return $instituicaoLegislatura;
}

function addInstituicaoLegislaturaAnexo($instituicaoLegislaturaId, $data) {
    $instituicaoLegislaturaAnexo = new InstituicaoLegislaturaAnexo(0);
    $instituicaoLegislaturaAnexo->create($data);
    $instituicaoLegislaturaAnexo->setInstituicaoLegislaturaId($instituicaoLegislaturaId);
    return $instituicaoLegislaturaAnexo;
}

function addInstituicaoPresidencial($instituicaoId, $data) {
    $instituicaoPresidencial = new InstituicaoPresidencial(0);
    $instituicaoPresidencial->create($data);
    $instituicaoPresidencial->setInstituicaoId($instituicaoId);
    return $instituicaoPresidencial;
}

function addInstituicaoPresidencialAnexo($instituicaoPresidencialId, $data) {
    $instituicaoPresidencialAnexo = new InstituicaoPresidencialAnexo(0);
    $instituicaoPresidencialAnexo->create($data);
    $instituicaoPresidencialAnexo->setInstituicaoPresidencialId($instituicaoPresidencialId);
    return $instituicaoPresidencialAnexo;
}

function addInstituicaoRelacao($instituicaoId, $data) {
    $instituicaoRelacao = new InstituicaoRelacao(0);
    $instituicaoRelacao->create($data);
    $instituicaoRelacao->setInstituicaoId($instituicaoId);
    return $instituicaoRelacao;
}

function addLeiAnexo($leiId, $data) {
    $leiAnexo = new LeiAnexo(0);
    $leiAnexo->create($data);
    $leiAnexo->setLeiId($leiId);
    return $leiAnexo;
}

//TODO algures deve haver uma procura pelas leis existes que a nova adenda
function addLeiAdenda($leiId, $data) {
    $leiAdenda = new LeiAdenda(0);
    $leiAdenda->create($data);
    return $leiAdenda;
}

//TODO: LeiEmissor é um array de Instituicao_* definido em LeiEmissor->emissorTipo
function addLeiEmissor($leiId, $data) {
    $leiEmissor = new LeiEmissor(0);
    $leiEmissor->create($data);
    $leiEmissor->setLeiId($leiId);
    return $leiEmissor;
}

function addDiarioRepublicaPublicacao($diarioRepublicaId, $data) {
    $diarioRepublicaPublicacao = new DiarioRepublicaPublicacao(0);
    $diarioRepublicaPublicacao->create($data);
    $diarioRepublicaPublicacao->setDiarioRepublicaId($diarioRepublicaId);
    return $diarioRepublicaPublicacao;
}

function addDiarioRepublicaPublicacaoLei($diarioRepublicaPublicacaoId, $data) {
    $diarioRepublicaPublicacaoLei = new DiarioRepublicaPublicacaoLei(0);
    $diarioRepublicaPublicacaoLei->create($data);
    $diarioRepublicaPublicacaoLei->setDiarioRepublicaPublicacaoId($diarioRepublicaPublicacaoId);
    return $diarioRepublicaPublicacaoLei;
}

function addDiarioRepublicaPublicacaoAnexo($diarioRepublicaPublicacaoId, $data) {
    $diarioRepublicaPublicacaoAnexo = new DiarioRepublicaPublicacaoAnexo(0);
    $diarioRepublicaPublicacaoAnexo->create($data);
    $diarioRepublicaPublicacaoAnexo->setDiarioRepublicaPublicacaoId($diarioRepublicaPublicacaoId);
    return $diarioRepublicaPublicacaoAnexo;
}