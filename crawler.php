<?php
require_once __DIR__.'/vendor/autoload.php';

use Goutte\Client;

$client = new Client();

$fp = fopen("dados.csv", "a");

for ($i=0; $i <= 20000;$i++) {
    $crawler = $client->request('GET', 'http://www.crn6.org.br/curriculum-detalhes.php?idCurriculum='.$i)->filter('#content')->filter('div')->eq(2);
    $string = trim($crawler->text());


    $dados = explode("\n", $string);

    if (count($dados)>4) {

        $nome = trim($dados[0]);
        $tipo = trim($dados[2]);
        $endereco = trim($dados[3]);

        $posicaoUF = strpos($endereco, 'UF: ');
        $posicaoCidade = strpos($endereco, 'Cidade: ');
        $posicaoEndereco = strpos($endereco, 'Endereço: ');
        $posicaoTelefone = strpos($endereco, 'Telefone: ');
        $posicaoCelular = strpos($endereco, 'Celular: ');
        $posicaoEmail = strpos($endereco, 'Email: ');

        $uf = trim(substr($endereco, $posicaoUF + 4, $posicaoCidade - $posicaoUF - 8));
        $cidade = trim(substr($endereco, $posicaoCidade + 8, $posicaoEndereco - $posicaoCidade - 10));
        $end = trim(substr($endereco, $posicaoEndereco + 10, $posicaoTelefone - $posicaoEndereco - 10));
        $telefone = trim(substr($endereco, $posicaoTelefone + 10, $posicaoCelular - $posicaoTelefone - 10));
        $celular = trim(substr($endereco, $posicaoCelular + 9, $posicaoEmail - $posicaoCelular - 9));
        $email = trim(substr($endereco, $posicaoEmail + 7));

        $linha = $i. ";" . $tipo . ";" . $nome . ";" . $end . ";" . $cidade . ";" . $uf . ";" . $telefone . ";" . $celular . ";" . $telefone . ";" . $email . "\n";
        fwrite($fp, $linha);

    }
    echo $i . "\n";
}
fclose($fp);
