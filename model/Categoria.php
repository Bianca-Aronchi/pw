<?php
require_once "BaseDeDados.php";
class Categoria {
    public $id;
    public $tipo;
    public $icone;
    public $data_cadastro;
    public $data_alteracao;
    private $erro;

    public function getErro(){
        return $this->erro;
    }

    private function cx(){
        return (new BaseDeDados())->getConexao();
    }

    public function cadastrar() {
        try {

            $cmdSql = "INSERT INTO categoria(tipo, icone) VALUES (:tipo, :icone)";
            $cx_declarada = $this->cx()->prepare($cmdSql);
            $cx_declarada->bindParam('tipo', $this->tipo);
            $cx_declarada->bindParam('icone', $this->icone);            
            return $cx_declarada->execute();
        } catch (\PDOException $e) {
            $this->erro = "Erro ao cadastrar categoria: " . $e->getMessage();
            return false;
        }
    }

    public function alterar() {
		try {
            $cmdSql = "UPDATE categoria SET tipo = :tipo, icone = :icone WHERE categoria.id = :id";
            $cx_declarada = $this->cx()->prepare($cmdSql);
            $cx_declarada->bindParam(':tipo', $this->tipo);
            $cx_declarada->bindParam(':icone', $this->icone);
            $cx_declarada->bindParam(':id', $this->id);
            $cx_declarada->execute();
            return ($cx_declarada->rowCount() != 0);
        } catch (PDOException $e) {
            $this->erro = "Erro ao alterar categoria: " . $e->getMessage();
            return false;
        }
    }

    public function excluir() {
		try {
            $cmdSql = "DELETE FROM categoria WHERE categoria.id = :id";
            $cx_declarada = $this->cx()->prepare($cmdSql);
            $cx_declarada->bindParam(':id', $this->id);            
            $cx_declarada->execute();
            return ($cx_declarada->rowCount() != 0);
        } catch (PDOException $e) {
            $this->erro = "Erro ao excluir categoria. Código do erro: {$e->getCode()}";
            return false;
        }
    }

    public function consultar($filtro="") {
        try {
            $cmdSql = "SELECT * FROM categoria WHERE categoria.tipo LIKE :filtro";
            $cx_declarada = $this->cx()->prepare($cmdSql);
            $cx_declarada->bindValue(':filtro', '%'.$filtro.'%');          
            $cx_declarada->execute();
            $cx_declarada->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
            return $cx_declarada->fetchAll();
        } catch (\PDOException $e) {
            $this->erro = "Erro ao listar categorias: " . $e->getMessage();
            return false;
        }
    }

    private function carregar(Categoria $c){
        $this->id = $c->id;
        $this->tipo = $c->tipo;
        $this->icone = $c->icone;
        $this->data_cadastro = $c->data_cadastro;
        $this->data_alteracao = $c->data_alteracao;
    }

    public function consultarPorId($id) {
        try {
            $cmdSql = "SELECT * FROM categoria WHERE categoria.id = :id";
            $cx_declarada = $this->cx()->prepare($cmdSql);
            $cx_declarada->bindParam('id', $id);          
            $cx_declarada->execute();
            $cx_declarada->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
            $c = $cx_declarada->fetch();
            if($c){
                $this->carregar($c);
                return true;
            }
            return false;
        } catch (\PDOException $e) {
            $this->erro = "Erro ao consultar categoria: " . $e->getMessage();
            return false;
        }
    }
}


