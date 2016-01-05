<?php

namespace App\Crawler;

use App\Model\Cidade;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Model\Versao;
use Log;
use DB;
use Image;
use Exception;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SpiderProvider {

    private $dom;
    private $metas;
    private $combustivel;
    private $cor;
    private $carroceria;
    private $cambio;
    private $montadora;
    private $modelo;
    private $opcional;
    private $documentacao;
    private $tipo_anunciante;
    private $discretDataLoaded = false;

    public function __construct(DomCrawler $dom) {
        $this->dom = $dom;
        $this->loadMetas();
        $this->loadDiscretData();
    }

    private function createAssociativeArray($table, $id, $name) {
        $result = [];
        foreach(DB::table($table)->get() as $each) {
            $result[$each->{$name}] = $each->{$id};
        }
        return $result;
    }

    private function createArrayModelo() {
        $result = [];
        foreach(DB::table("modelo")->get() as $each) {
            $result[$each->fk_montadora][$each->descricao] = $each->id_modelo;
        }
        return $result;
    }

    private function loadDiscretData() {
        if ($this->discretDataLoaded) {
            return;
        }

        $this->discretDataLoaded = true;

        $this->cor             = $this->createAssociativeArray('cor', 'id_cor', 'descricao');
        $this->opcional        = $this->createAssociativeArray('opcional', 'id_opcional', 'descricao');
        $this->montadora       = $this->createAssociativeArray('montadora', 'id_montadora', 'descricao');
        $this->tipo_anunciante = $this->createAssociativeArray('tipo_anunciante', 'id_tipo_anunciante', 'descricao');
        $this->combustivel     = $this->createAssociativeArray('combustivel', 'id_combustivel', 'descricao');
        $this->carroceria      = $this->createAssociativeArray('carroceria', 'id_carroceria', 'descricao');
        $this->cambio          = $this->createAssociativeArray('cambio', 'id_cambio', 'descricao');
        $this->documentacao    = $this->createAssociativeArray('documentacao', 'id_documentacao', 'descricao');
        $this->modelo          = $this->createArrayModelo();
    }

    private function loadMetas() {
        $xpath = $this->dom->filter("meta");
        $metas = [];

        foreach ($xpath as $meta) {
            $metas[$meta->getAttribute("name")] = $meta->getAttribute("content");
        }

        $this->metas = $metas;
    }

    public function isAnuncioDesativado() {
        $anuncioDesativo = $this->dom->filter("body > div.content.detalhes-anuncio-inexistente.c-after > div.col-18.pad-d_gutter-t > div.size-xbigger.bold.mrg-gutter-b")->text();

        if (Str::contains($anuncioDesativo, "Desativado")) {
            return true;
        }

        try {
            $this->getAnoModelo();
            return false;
        } catch (Exception $e) {
            return true;
        }
    }

    public function getCombustivel() {
        return $this->combustivel[$this->metas['wm.dt_combustivel']];
    }

    public function getCor() {
        return $this->cor[$this->metas['wm.dt_cor']];
    }

    public function getMontadora() {
        return $this->montadora[ucwords(strtolower($this->metas['wm.dt_marca']))];
    }

    public function getCambio() {
        return $this->cambio[$this->metas['wm.dt_cambio']];
    }

    public function getCarroceria() {
        return $this->carroceria[$this->metas['wm.dt_carroceria']];
    }

    public function getModelo() {
        return $this->modelo[$this->getMontadora()][$this->metas['wm.dt_mod']];
    }

    public function getPreco() {
        return $this->metas['wm.dt_prc'];
    }

    public function getDescricaoVeiculo() {
        return str_replace("|", " ", $this->metas['wm.dt_mmv']);
    }

    public function getTipoAnunciante() {
        return $this->tipo_anunciante[ucfirst(strtolower($this->metas['wm.dt_tipoa']))];
    }

    public function getIsVeiculoNovo() {
        return $this->metas["wm.dt_tipoc"] != "usado";
    }

    public function getEstado() {
        return rtrim(explode("(", $this->metas["wm.dt_estado"])[1], ")");
    }

    public function getCidade() {

        $estado = $this->getEstado();
        $nomeCidade = $this->metas["wm.dt_cidade"];
        $buscaCidade = Cidade::where("fk_estado", "=", $estado)
                             ->where("nome_cidade", "=", $nomeCidade)
                             ->first();

        if (empty($buscaCidade)) {
            $novaCidade = new Cidade();
            $novaCidade->fk_estado = $estado;
            $novaCidade->nome_cidade = $nomeCidade;
            $novaCidade->save();
            return $novaCidade->id_cidade;
        }

        return $buscaCidade->id_cidade;
    }

    public function getVersao() {
        $versao = explode("|", $this->metas["wm.dt_mmv"])[2];
        $buscaVersao = Versao::where('descricao', '=', $versao)->first();
        if (empty($buscaVersao)) {
            $novaVersao = new Versao();
            $novaVersao->descricao = $versao;
            $novaVersao->fk_modelo = $this->getModelo();
            $novaVersao->save();
            return $novaVersao->id_versao;
        }
        return $buscaVersao->id_versao;
    }

    public function getFinalPlaca() {
        return $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(1) > div.col-4.last.size-default.dados > div:nth-child(1) > div:nth-child(5) > div.dis-tc.col-4.last.valign-m > strong")->text();
    }

    public function getDataAnuncio() {
        $xpath = $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(3) > div > div.geral.informacoes > div.dis-t > div.dis-tc.col-7.b.gutter-left.size-default > div:nth-child(11)");
        $data = trim(str_replace("Data do anúncio:", "", $xpath->text()));
        return Carbon::createFromFormat("d/m/Y", $data)->toDateString();
    }

    public function getKilometragem() {
        $xpath = $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(1) > div.col-4.last.size-default.dados > div:nth-child(1) > div:nth-child(2) > div.dis-tc.col-4.last.valign-m > strong");
        return trim(str_replace(".", "", $xpath->text()));
    }

    public function getPortas() {
        $xpath = $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(3) > div > div.geral.informacoes > div.dis-t > div.dis-tc.col-7.b.gutter-left.size-default > div:nth-child(10)");
        return trim(str_replace("Portas:", "", $xpath->text()));
    }

    public function getAnoModelo() {
        $xpath = $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(1) > div.col-4.last.size-default.dados > div:nth-child(1) > div:nth-child(1) > div.dis-tc.col-4.last.valign-m > strong");
        return explode("/", $xpath->text());
    }

    public function getOpcionaisObservacaoDocumentacao() {
        $xpath = $this->dom->filter("body > div.content.detalhes-anuncio.c-after > div.col-main.col-12 > div:nth-child(3) > div > div.geral.informacoes > div.size-default.pad-h_gutter-t.pad-gutter-lr.lh-oh_gutter > p");
        $split = explode("Observações do vendedor", $xpath->html());
        $opcionaisString = trim(strip_tags($split[0]));

        if (!empty($result)) {
            $opcionaisArray = explode(",", $opcionaisString);
        } else {
            $opcionaisArray = [];
        }

        $opcionais = [];

        foreach ($opcionaisArray as $each) {
            $opcionais[] = $this->opcional[trim($each)];
        }

        $documentacaoString = trim(strip_tags(explode("<br>", $split[1])[0]));

        if (!empty($documentacaoString)) {
            $documentacao = explode(",", $documentacaoString);
        } else {
            $documentacao = [];
        }

        $listaDocumentacao = [];

        foreach ($documentacao as $eachD) {
            $listaDocumentacao[] = $this->documentacao[trim($eachD)];
        }

        $observacao = trim(strip_tags(explode("<br>", $split[1])[1]));

        return [$opcionais, $observacao, $listaDocumentacao];
    }

    public function getImagens($idCarro) {

        $xpath = $this->dom->filter("#main-carousel > div > div.carousel-inner.c-after > a > img");
        $imagens = [];

        foreach ($xpath as $each) {
            $src = explode("?", $each->getAttribute("src"));
            $mime = explode(".", $src[0]);
            $newImageName = "/tmp/" . uniqid() . "." . end($mime);
            copy($src[0], $newImageName);
            $img = Image::make($newImageName);
            $imagemCropada = "img/carros/" . uniqid($idCarro. "_") . ".jpg";
            $img->crop($img->width(), $img->height() - 72, 0, 36)->save(public_path($imagemCropada));
            unlink($newImageName);
            $imagens[] = $imagemCropada;
        }

        return $imagens;
    }

    
}