<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Carlos\Organize\Lib\MigrationHandler;
use Carlos\Organize\Model\Tretas\DocumentDRe;
use Carlos\Organize\Model\Laravel\Cidadao\{Cidadao, CidadaoAnexo, CidadaoCargo, CidadaoCargoAnexo, CidadaoContacto, CidadaoMorada, CidadaoNacionalidade, CidadaoRelacao};
use Carlos\Organize\Model\Laravel\Instituicao\{Instituicao, InstituicaoAnexo, InstituicaoContacto, InstituicaoDados, InstituicaoMorada, InstituicaoCargo, InstituicaoCargoAnexo, InstituicaoCargoLei, InstituicaoComRamo, InstituicaoLegislatura, InstituicaoLegislaturaAnexo, InstituicaoPresidencial, InstituicaoPresidencialAnexo, InstituicaoNacionalidade, InstituicaoRelacao};
use Carlos\Organize\Model\Laravel\Lei\{Lei, LeiAnexo, LeiAdenda, DiarioRepublica, DiarioRepublicaPublicacao, DiarioRepublicaPublicacaoLei, DiarioRepublicaPublicacaoAnexo};
use Carlos\Organize\Model\Laravel\EntidadeJuridica\{EntidadeJuridica, EntidadeJuridicaAnexo, EntidadeJuridicaLei};

// Definição das opções de linha de comando
$options = getopt("", ["insert"]);

$shouldInsert = array_key_exists('insert', $options);

echo "Iniciando a Migração...\n";

$finalBDPdo = new \PDO("mysql:host=localhost;dbname=lumen_db", "local_user", "Qwerty.123");
$finalBDHandler = new MigrationHandler($finalBDPdo);

$importBDPdo = new \PDO("mysql:host=localhost;dbname=python_dre", "local_user", "Qwerty.123");
$importBDHandler = new MigrationHandler($importBDPdo);

if ($finalBDHandler->testConnection('diario_republicas') && $importBDHandler->testConnection('dreapp_document')) {
    echo "Conexão com as bases de dados estabelecida\n";

    $res = $importBDHandler->getDReDocuments("SELECT * FROM `dreapp_document` WHERE date < '1910-10-05' ORDER BY date ASC;");

    foreach ($res as $dreDocument) {
        try {
            $finalBDPdo->beginTransaction();
            
            // Inicialização dos objetos
            $cidadao = (new Cidadao())->create(["uuid" => null, "nome" => null, "data_nascimento" => null, "data_falecimento" => null, "genero" => null, "freguesia_id" => null, "nacional" => true]);
            $cidadaoAnexos = array();
            // $cidadaoAnexos.push(addCidaoAnexo($cidadao->getId(), ["uuid" => null, "nome" => null, "cidadao_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
            $cidadaoCargos = array();
            // $cidadaoCargos.push(addCidadaoCargo($cidadao->getId(), ["uuid" => null, "nome" => null, "cidadao_id" => null, "instituicao_id" => null, "cargo" => null, "data_inicio" => null, "data_fim" => null]));
            $cidadaoCargoAnexos = array();
            // $cidadaoCargoAnexos.push(addCidadaoCargoAnexo($cidadaoCargos[0]->getId(), ["uuid" => null, "nome" => null, "cidadao_cargo_id" => null, "anexo_tipo_id" => null, "path" => null, "src" => null]));
            $cidadaoContactos = array();
            // $cidadaoContactos.push(addCidadaoContacto($cidadao->getId(), ["contacto_tipo_id" => null, "contacto" => null]));
            $cidadaoMoradas = array();
            // $cidadaoMoradas.push(addCidadaoMorada($cidadao->getId(), ["cidadao_id" => null, "morada" => null, "codigo_postal" => null, "concelho_id" => null, "localidade" => null]));
            $cidadaoNacionalidade = (new CidadaoNacionalidade())->create(["cidadao_id" => null, "pais_id" => null]);
            $cidadaoRelacoes = new CidadaoRelacao();
            // $cidadaoRelacoes.push(addCidadaoRelacao($cidadao->getId(), ["nome" => null, "cidadao_id" => null, "relacao_tipo_id" => null, "onde" => null, "onde_id" => null]));

            $instituicao = (new Instituicao())->create(["id" => 0, "uuid" => null, "republicaId" => null, "nome" => null, "sigla" => null, "sinopse" => null, "respondeInstituicaoId" => null, "entidadeJuridicaId" => null, "nacional" => null, "createdAt" => date('Y-m-d H:i:s'), "updatedAt" => date('Y-m-d H:i:s')]);
            $instituicaoAnexos = new InstituicaoAnexo();
            $instituicaoContactos = new InstituicaoContacto();
            $instituicaoDado = new InstituicaoDados();
            $instituicaoMoradas = new InstituicaoMorada();
            $instituicaoCargos = new InstituicaoCargo();
            $instituicaoCargoAnexos = new InstituicaoCargoAnexo();
            $instituicaoCargoLeis = new InstituicaoCargoLei();
            $instituicaoComRamos = new InstituicaoComRamo();
            $instituicaoLegislaturas = new InstituicaoLegislatura();
            $instituicaoLegislaturaAnexos = new InstituicaoLegislaturaAnexo();
            $instituicaoPresidenciais = new InstituicaoPresidencial();
            $instituicaoPresidencialAnexos = new InstituicaoPresidencialAnexo();
            $instituicaoNacionalidade = new InstituicaoNacionalidade();
            $instituicaoRelacoes = array();
            // $instituicaoRelacoes.push(add);

            $lei = (new Lei())->create(["id" => 0, "uuid" => null, "codigo" => null, "nomeCompleto" => null, "proponente" => null, "sumario" => null, "texto" => null, "path" => null, "src" => null, "emVigor" => true, "dataToggle" => null]);
            $leiAnexos = new LeiAnexo();
            $leiAdendas = new LeiAdenda();
            $leiEmissores = new LeiEmissor();
            $diarioRepublica = (new DiarioRepublica())->create(["id" => 0, "uuid" => null, "nome" => null, "publicacao" => null]);
            $diarioRepublicaPublicacoes = new DiarioRepublicaPublicacao();
            $diarioRepulicaPublicacaoLeis = new DiarioRepublicaPublicacaoLei();
            $diarioRepublicaPublicacaoAnexos = new DiarioRepublicaPublicacaoAnexo();
            $entidadeJuridica = (new EntidadeJuridica())->create(["id" => 0, "uuid" => null, "nome" => null, "tipo" => null]);
            $entidadeJuridicaAnexos = new EntidadeJuridicaAnexo();
            $entidadeJuridicaLeis = new EntidadeJuridicaLei();

            // Exemplo de criação de uma instância de DiarioRepublica
            $diario = DiarioRepublica::create([
                'uuid' => $dreDocument->getUuid(),
                'nome' => $dreDocument->getNome(),
                'publicacao' => $dreDocument->getDate(), // Supondo que 'publicacao' é a data de publicação
            ]);



            // Se a flag --insert for passada, executa a inserção
            if ($shouldInsert) {
                $finalBDPdo->exec($diario->toSqlInsert());
                echo "Documento inserido com sucesso: " . $dreDocument->getId() . "\n";
            } else {
                // Caso contrário, apenas exibe a informação e pergunta se quer continuar
                echo "Preparado para inserir: " . $dreDocument->getId() . "\n";
                echo "Deseja continuar? (s/n): ";
                $handle = fopen("php://stdin", "r");
                $line = fgets($handle);
                if (trim($line) != 's') {
                    echo "Processo interrompido pelo utilizador.\n";
                    $finalBDPdo->rollBack();
                    break;
                }
            }
            
            // Aqui você deve implementar a lógica para preencher e inserir os outros objetos conforme necessário
            // Exemplo para $instituicao, similar para os outros:
            // $instituicao = Instituicao::create([...]);
            // if ($shouldInsert) {
            //     $finalBDPdo->exec($instituicao->toSqlInsert());
            // }

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
    $cidadaoAnexo = new CidadaoAnexo();
    $cidadaoAnexo->create($data);
    $cidadaoAnexo->setCidadaoId($cidadaoId);
    return $cidadaoAnexo;
}

function addCidadaoCargo($cidadaoId, $data) {
    $cidadaoCargo = new CidadaoCargo();
    $cidadaoCargo->create($data);
    $cidadaoCargo->setCidadaoId($cidadaoId);
    return $cidadaoCargo;
}

function addCidadaoCargoAnexo($cidadaoCargoId, $data) {
    $cidadaoCargoAnexo = new CidadaoCargoAnexo();
    $cidadaoCargoAnexo->create($data);
    $cidadaoCargoAnexo->setCidadaoCargoId($cidadaoCargoId);
    return $cidadaoCargoAnexo;
}

function addCidadaoMorada($cidadaoId, $data) {
    $cidadaoMorada = new CidadaoMorada();
    $cidadaoMorada->create($data);
    $cidadaoMorada->setCidadaoId($cidadaoId);
    return $cidadaoMorada;
}

function addCidadaoRelacao($cidadaoId, $data) {
    $cidadaoRelacao = new CidadaoRelacao();
    $cidadaoRelacao->create($data);
    $cidadaoRelacao->setCidadaoId($cidadaoId);
    return $cidadaoRelacao;
}