<?php 

    class Dashboard {

        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;
        public $clientesAtivos;
        public $clientesInativos;
        public $totalReclamantes;
        public $totalFeliz;
        public $totalSugestoes;
        public $totalPerdas;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $value) {
            $this->$attr= $value;
            return $this;
        }

    }


    class Conexao {

        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $password = '';

        public function conectar() {

            try {
                $conexao = new PDO(
                    "mysql:host=$this->host; dbname=$this->dbname",
                    "$this->user",
                    "$this->password"
                );
                $conexao->exec('set names utf8');
                return $conexao;


            } catch (PDOException $error) {
                echo '<p>Erro: '.$error->getMessage().' </p>';
            }

        }

    }

    class DataBase {

        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard) {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas() {
            $query = 'select COUNT(*) as numero_vendas from tb_vendas where data_venda between :data_inicio and :data_fim';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio',$this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim',$this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas; 
        }

        public function getTotalVendas() {
            $query = 'select SUM(total) as total_vendas from tb_vendas where data_venda between :data_inicio and :data_fim';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio',$this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim',$this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas; 
        }

        public function getClientesAtivos() {
            $query = 'select COUNT(*) as clientes_ativos from tb_clientes where cliente_ativo = 1';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_ativos; 
        }

        public function getClientesInativos() {
            $query = 'select COUNT(*) as clientes_inativos from tb_clientes where cliente_ativo = 0';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->clientes_inativos; 
        }

        public function getTotalReclamantes() {
            $query = 'select COUNT(*) as total_reclamantes from tb_contatos where tipo_contato = 1';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamantes; 
        }

        public function getTotalFeliz() {
            $query = 'select COUNT(*) as total_feliz from tb_contatos where tipo_contato = 2';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_feliz; 
        }
        
        public function getTotalSugestoes() {
            $query = 'select COUNT(*) as total_sugestoes from tb_contatos where tipo_contato = 3';
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes; 
        }

        public function getTotalPerdas() {
            $query = 'select SUM(total) as total_perdas from tb_despesas where data_despesa between :data_inicio and :data_fim';
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio',$this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim',$this->dashboard->__get('data_fim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_perdas; 
        }

    }


    $dashboard = new Dashboard();
    $conexao = new Conexao();

    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];
    $dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

    $database = new DataBase($conexao,$dashboard);

    $dashboard->__set("data_inicio", $ano.'-'.$mes.'-'.'01' );
    $dashboard->__set("data_fim", $ano.'-'.$mes.'-'.$dias_do_mes);

    $dashboard->__set('numeroVendas', $database->getNumeroVendas());
    $dashboard->__set('totalVendas', $database->getTotalVendas());
    $dashboard->__set('clientesAtivos', $database->getClientesAtivos());
    $dashboard->__set('clientesInativos', $database->getClientesInativos());
    $dashboard->__set('totalReclamantes', $database->getTotalReclamantes());
    $dashboard->__set('totalFeliz', $database->getTotalFeliz());
    $dashboard->__set('totalSugestoes', $database->getTotalSugestoes());
    $dashboard->__set('totalPerdas', $database->getTotalPerdas());


    echo json_encode($dashboard);

?>