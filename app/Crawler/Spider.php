<?php

namespace App\Crawler;

use App\Exceptions\ImageNotFoundException;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Model\Crawler as WebCrawler;
use Log;
use DB;
use Exception;
use Storage;
use App\Model\Carro;
use App\Model\CarroFoto;
use App\Model\CarroOpcional;
use App\Model\CarroDocumentacao;
use App\Model\CarroCrawler;
use Carbon\Carbon;

class Spider {

    private $crawler;
    private $dom;
    private $imagens;

    public function __construct(WebCrawler $crawler, DomCrawler $dom) {
        $this->crawler = $crawler;
        $this->dom = $dom;
    }

    public function get() {

        try {

            if (CarroCrawler::where("url", "=", $this->crawler->url)->count()) {
                return;
            }

            $provider = new SpiderProvider($this->dom);

            if ($provider->isAnuncioDesativado()) {
                return;
            }

            list($ano, $modelo) = $provider->getAnoModelo();
            list($opcionais, $observacao, $documentacoes) = $provider->getOpcionaisObservacaoDocumentacao();

            $carro = new Carro();
            $carro->preco = $provider->getPreco();
            $carro->nome_carro = $provider->getDescricaoVeiculo();
            $carro->kilometragem = $provider->getKilometragem();
            $carro->fk_cor = $provider->getCor();
            $carro->fk_cambio = $provider->getCambio();
            $carro->fk_combustivel = $provider->getCombustivel();
            $carro->fk_montadora = $provider->getMontadora();
            $carro->fk_versao = $provider->getVersao();
            $carro->fk_cidade = $provider->getCidade();
            $carro->fk_modelo = $provider->getModelo();
            $carro->fk_carroceria = $provider->getCarroceria();
            $carro->fk_tipo_anunciante = $provider->getTipoAnunciante();
            $carro->ind_veiculo_novo = $provider->getIsVeiculoNovo();
            $carro->portas = $provider->getPortas();
            $carro->final_placa = $provider->getFinalPlaca();
            $carro->data_anuncio = $provider->getDataAnuncio();
            $carro->ano = $ano;
            $carro->modelo = $modelo;
            $carro->descricao_anunciante = $observacao;
            $carro->saveOrFail();

            $carroCrawler = new CarroCrawler();
            $carroCrawler->fk_carro = $carro->id_carro;
            $carroCrawler->url = $this->crawler->url;
            $carroCrawler->ultima_atualizacao = Carbon::now()->toDateTimeString();
            $carroCrawler->save();

            $this->imagens = $provider->getImagens($carro->id_carro);

            foreach ($this->imagens as $imagem) {
                $carroFoto = new CarroFoto();
                $carroFoto->fk_carro = $carro->id_carro;
                $carroFoto->nome_foto = $imagem;
                $carroFoto->save();
            }

            foreach ($opcionais as $opcional) {
                $carroOpcional = new CarroOpcional();
                $carroOpcional->fk_carro = $carro->id_carro;
                $carroOpcional->fk_opcional = $opcional;
                $carroOpcional->save();
            }

            foreach ($documentacoes as $documentacao) {
                $carroDocumentacao = new CarroDocumentacao();
                $carroDocumentacao->fk_carro = $carro->id_carro;
                $carroDocumentacao->fk_documentacao = $documentacao;
                $carroDocumentacao->save();
            }

            $dom = null;


        } catch (ImageNotFoundException $e) {

            Log::info($e->getMessage());

            $this->crawler->delete();

        } catch (Exception $e) {

            Log::info($e->getMessage());

            throw $e;

        }

    }

}