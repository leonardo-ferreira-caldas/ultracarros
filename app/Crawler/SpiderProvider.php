<?php

namespace App\Crawler;

use App\Exceptions\ImageNotFoundException;
use App\Model\Carroceria;
use App\Model\Montadora;
use App\Model\Cidade;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use App\Model\Versao;
use Log;
use DB;
use Image;
use FatalErrorException;
use Exception;
use Storage;
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
        try {

            $anuncioDesativo = $this->dom->filter("body > div.content.detalhes-anuncio-inexistente.c-after > div.col-18.pad-d_gutter-t > div.size-xbigger.bold.mrg-gutter-b")->text();

            if (Str::contains($anuncioDesativo, "Desativado")) {
                return true;
            }

            return false;

        } catch (Exception $e) {
            try {
                $this->getAnoModelo();
                return false;
            } catch (Exception $e) {
                return true;
            }
        }
    }

    public function getCombustivel() {
        return $this->combustivel[$this->metas['wm.dt_combustivel']];
    }

    public function getCor() {
        return $this->cor[$this->metas['wm.dt_cor']];
    }

    public function getMontadora() {
        if (!isset($this->montadora[ucwords(mb_strtolower($this->metas['wm.dt_marca']))])) {
            try {
                $montadora = Montadora::where("descricao", '=', $this->metas['wm.dt_marca'])->firstOrFail();
                return $montadora->id_montadora;
            } catch (Exception $e) {
                throw new Exception("Montadora não encontrada.");
            }
        }
        return $this->montadora[ucwords(mb_strtolower($this->metas['wm.dt_marca']))];
    }

    public function getCambio() {
        return $this->cambio[$this->metas['wm.dt_cambio']];
    }

    public function getCarroceria() {
        if (isset($this->carroceria[$this->metas['wm.dt_carroceria']])) {
            return $this->carroceria[$this->metas['wm.dt_carroceria']];
        }
        $carroceria = new Carroceria();
        $carroceria->descricao = $this->metas['wm.dt_carroceria'];
        $carroceria->save();
        return $carroceria->id_carroceria;
    }

    public function getModelo() {
        if (!isset($this->modelo[$this->getMontadora()][$this->metas['wm.dt_mod']])) {
            throw new Exception("Modelo não encontrada.");
        }
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
        $opcionaisString = trim(str_replace("Opcionais", "", $opcionaisString));
        $opcionaisString = str_replace(["\n", "\r"], "", $opcionaisString);

        if (!empty($opcionaisString)) {
            $opcionaisArray = explode(",", $opcionaisString);
        } else {
            $opcionaisArray = [];
        }

        $opcionais = [];

        foreach ($opcionaisArray as $each) {
            if (isset($this->opcional[trim($each)])) {
                $opcionais[] = $this->opcional[trim($each)];
            }
        }

        if (isset($split[1]) && !empty($split[1])) {
            $documentacaoString = trim(strip_tags(explode("<br>", $split[1])[0]));

            if (!empty($documentacaoString)) {
                $documentacao = explode(",", $documentacaoString);
            } else {
                $documentacao = [];
            }

            $listaDocumentacao = [];

            foreach ($documentacao as $eachD) {
                if (isset($this->documentacao[trim($eachD)])) {
                    $listaDocumentacao[] = $this->documentacao[trim($eachD)];
                }
            }

            $observacao = trim(strip_tags(explode("<br>", $split[1])[1]));
        } else {
            $listaDocumentacao = [];
            $observacao = "";
        }

        return [$opcionais, $observacao, $listaDocumentacao];
    }

    public function getImagemURL($url) {
        return explode("?", $url)[0];
    }

    public function getExtensao($url) {
        $mime = explode(".", $url);
        return end($mime);
    }

    public function getImagens($idCarro) {

        $xpath = $this->dom->filter("#main-carousel > div > div.carousel-inner.c-after > a > img");
        $qtdImagens = count($xpath);
        $imagens = [];

        foreach ($xpath as $each) {

            try {

                $imgHttpUrl = $this->getImagemURL($each->getAttribute("src"));

                $img = Image::make($imgHttpUrl);
                $croppedImage = $img->stream();
                $img->destroy();

                $s3Name = uniqid('veiculo' . $idCarro . '_') . '.' . $this->getExtensao($imgHttpUrl);

                Storage::disk('s3')->put($s3Name, $croppedImage->__toString(), 'public');
                $croppedImage = null;
                unset($croppedImage);

                $imagens[] = $s3Name;

            } catch (FatalErrorException $fatalError) {
                foreach ($imagens as $imagem) {
                    Storage::disk('s3')->delete($imagem);
                }

                throw new ImageNotFoundException("Ocorreu um erro no download das imagens.");
            } catch (Exception $e) {
                foreach ($imagens as $imagem) {
                    Storage::disk('s3')->delete($imagem);
                }

                throw new ImageNotFoundException("Ocorreu um erro no download das imagens.");
            }

        }

        return $imagens;
    }

    
}