<?php
class Admin
{
    // BANCO DE DADOS
    private $pdo;
    private $db_user;
    private $db_senha;
    private $db_name;
    private $db_host;
    
    // DATA E HORA
    public $gmtTimeZone;
    
    
    // MÉTODOS
    public function __construct()
    {
        include('conecta.php');
        $this->db_user = $db_username;
        $this->db_senha = $db_pass;
        $this->db_name = $db_name;
        $this->db_host = $db_host;
        $this->PDO();
        $this->gmtTimeZone = new DateTimeZone('America/Bahia');
        
        if(!isset($_SESSION)) {
            session_start();
        }
    }
    
    private function PDO()
    {
        // Conexão via PDO
        $this->pdo = new PDO ( "mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_senha );
        if (!$this->pdo) {
            die ( 'Erro ao criar a conexão' );
        }
        
        $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    
    public function UsuarioCarrega($id) // Retorno ARRAY
    {
        if(!is_int($id)) {
            return 'Tipo de argumento inválido. Esperando um inteiro.';
            exit();
        }
        
        $abc = $this->pdo->prepare('SELECT * FROM login WHERE id = :id');
        $abc->bindValue(':id', $id, PDO::PARAM_INT);
        $abc->execute();
        
        if($abc->rowCount() > 0) {
            $reg = $abc->fetch(PDO::FETCH_OBJ);
            
            $array_info = array('id' => $reg->id,
                'nome' => $reg->nome, 
                'sobrenome' => $reg->sobrenome, 
                'usuario' => $reg->usuario, 
                'nivel' => $reg->nivel, 
                'tel_res' => $reg->tel_res, 
                'tel_cel' => $reg->tel_cel, 
                'email' => $reg->email, 
                'tentativas' => $reg->tentativas, 
                'acessos' => $reg->login_qtd, 
                'ult_login' => $reg->login_data,
                'status_servidor' => true
            );
        } else {
            $array_info = array('status_servidor' => false);
        }
        
        
        return $array_info;
    }
    
    public function UsuarioSalva($array) // Recebe Array
    {
        if(!isset($array['usuid']) || $array['usuid'] == '') {
            return 'O código de identificação do usuário (ID) é nulo. Operação abortada.';
        } else if(!isset($array['nome']) || $array['nome'] == '') {
            return 'O campo Nome não pode ser vazio';
        } else if(!isset($array['sobrenome']) || $array['sobrenome'] == '') {
            return 'O campo Sobrenome não pode ser vazio';
        } else if(!isset($array['usuario']) || $array['usuario'] == '') {
            return 'O campo Usuário não pode ser vazio';
        } else if(!isset($array['nivel']) || $array['nivel'] == '') {
            return 'O campo Nível não pode ser vazio';
        } else {
            $sql = 'UPDATE login SET nome = :nome, sobrenome = :sobrenome, usuario = :usuario, nivel = :nivel, tel_res = :telres, tel_cel = :telcel, email = :email';
            
            if($array['senha_reset'] == 'Y') {
                 $senha = hash('sha256', '123');
                 $sql.= ', senha = "'.$senha.'"';
            }
            
            if($array['tentativas'] == 'Y') {
                $sql .= ', tentativas = 0';
            }
            
            $sql .= ' WHERE id = :usuid';
            
            try {
                $abc = $this->pdo->prepare($sql);
                $abc->bindValue(':nome', $array['nome'], PDO::PARAM_STR);
                $abc->bindValue(':sobrenome', $array['sobrenome'], PDO::PARAM_STR);
                $abc->bindValue(':usuario', $array['usuario'], PDO::PARAM_STR);
                $abc->bindValue(':nivel', $array['nivel'], PDO::PARAM_INT);
                $abc->bindValue(':telres', $array['tel_res'], PDO::PARAM_STR);
                $abc->bindValue(':telcel', $array['tel_cel'], PDO::PARAM_STR);
                $abc->bindValue(':email', $array['email'], PDO::PARAM_STR);
                $abc->bindValue(':usuid', $array['usuid'], PDO::PARAM_STR);
                
                $abc->execute();
                return 'success';
            } catch(PDOException $e) {
                return $e->getMessage();
            }
            
        }
    }
    
    public function UsuarioNovo($array)
    {
        if(!isset($array['nome']) || $array['nome'] == '') {
            return 'O campo Nome não pode ser vazio';
        } else if(!isset($array['sobrenome']) || $array['sobrenome'] == '') {
            return 'O campo Sobrenome não pode ser vazio';
        } else if(!isset($array['usuario']) || $array['usuario'] == '') {
            return 'O campo Usuário não pode ser vazio';
        } else if(!isset($array['senha']) || $array['senha'] == '') {
            return 'O campo Senha não pode ser vazio';
        } else if(!isset($array['nivel']) || $array['nivel'] == '') {
            return 'O campo Nível não pode ser vazio';
        } else {
            // Verifica se usuário já existe
            $abc = $this->pdo->prepare('SELECT * FROM login WHERE usuario = :usuario');
            $abc->bindValue(':usuario', $array['usuario'], PDO::PARAM_STR);
            $abc->execute();
            
            if($abc->rowCount() == 0) {
            
                $senha = hash('sha256', $array['senha']);
                
                
                try {
                    $abc = $this->pdo->prepare('INSERT INTO `login`(`id`, `nome`, `sobrenome`, `usuario`, `senha`, `nivel`, `criado`, `tel_res`, `tel_cel`, `email`, `tentativas`, `login_qtd`, `login_data`) VALUES (NULL, :nome, :sobrenome, :usuario, :senha, :nivel, :criado, :tel_res, :tel_cel, :email, 0, 0, "0000-00-00 00:00:00")');
                    $abc->bindValue(':nome', $array['nome'], PDO::PARAM_STR);
                    $abc->bindValue(':sobrenome', $array['sobrenome'], PDO::PARAM_STR);
                    $abc->bindValue(':usuario', $array['usuario'], PDO::PARAM_STR);
                    $abc->bindValue(':senha', $senha, PDO::PARAM_STR);
                    $abc->bindValue(':nivel', $array['nivel'], PDO::PARAM_INT);
                    $abc->bindValue(':criado', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                    $abc->bindValue(':tel_res', $array['tel_res'], PDO::PARAM_STR);
                    $abc->bindValue(':tel_cel', $array['tel_cel'], PDO::PARAM_STR);
                    $abc->bindValue(':email', $array['email'], PDO::PARAM_STR);
                    $abc->execute();
                    
                    
                    if($array['designacao'] != 0) {
                        // Primeiro verifica se essa designacao ainda continua sem solicitante
                        $abc = $this->pdo->prepare('SELECT solicitante_id FROM cidade WHERE id = :designacao');
                        $abc->bindValue(':designacao', $array['designacao'], PDO::PARAM_INT);
                        $abc->execute();
                        if($abc->rowCount() > 0) {
                            $reg = $abc->fetch(PDO::FETCH_OBJ);
                            
                            if($reg->solicitante_id == 0) {
                                // Faz a designação
                                $abc = $this->pdo->prepare('SELECT id FROM login WHERE usuario = :usuario');
                                $abc->bindValue(':usuario', $array['usuario'], PDO::PARAM_STR);
                                $abc->execute();
                                $reg = $abc->fetch(PDO::FETCH_OBJ);
                                
                                
                                $abc = $this->pdo->prepare('UPDATE cidade SET solicitante_id = :id WHERE id = :designacao');
                                $abc->bindValue(':id', $reg->id, PDO::PARAM_INT);
                                $abc->bindValue(':designacao', $array['designacao'], PDO::PARAM_INT);
                                $abc->execute();
                            }
                        }
                        
                        
                    }
                    
                    return 'success';
                    
                    
                } catch(PDOException $e) {
                    return $e->getMessage();
                }
            } else {
                return 'Já existe um cadastro usando esse nome de usuário.';
            }
        }
    }
    
    public function UsuarioApagar($usuid, $login, $senha)
    {
        if($usuid == 0 || $usuid == '') {
            return 'Usuário inválido.';
        } else if($login == '') {
            return 'Login inválido';
        } else if($senha == '') {
            return 'Senha inválido';
        } else {
            // Confirma o login e a senha
            try {
                $abc = $this->pdo->prepare('SELECT * FROM login WHERE usuario = :login');
                $abc->bindValue(':login', $login, PDO::PARAM_STR);
                $abc->execute();
                
                if($abc->rowCount() > 0){
                    $reg = $abc->fetch(PDO::FETCH_OBJ);
                    
                    if($reg->senha == hash('sha256', $senha)) {
                        // Senhas iguais, procura usuário para excluir
                        $abc = $this->pdo->prepare('SELECT * FROM login WHERE id = :usuid');
                        $abc->bindValue(':usuid', $usuid, PDO::PARAM_INT);
                        $abc->execute();
                        
                        if($abc->rowCount() > 0){
                            // Usuário ainda existe, prosseguir com exclusão
                            $abc = $this->pdo->prepare('DELETE FROM login WHERE id = :usuid');
                            $abc->bindValue(':usuid', $usuid, PDO::PARAM_INT);
                            $abc->execute();
                            
                            return 'success';
                        } else {
                            // Usuário não encontrado
                            return 'Usuário para excluir não foi encontrado. Talvez ele já tenha sido removido. Atualize a página.';
                        }
                    } else {
                        // Senha errada!
                        return 'Senha não confere.';
                    }
                } else {
                    return 'Login não existe no sistema. Não é possível confirmar a operação.';
                }
            } catch(PDOException $e) {
                return $e->getMessage();
            }
        }
    }
    
    public function CidadeNova($cidade, $estado, $hospedeiro)
    {
        if(!is_bool(boolval($hospedeiro))) {
            return 'Argumento 3 esperava um booleano. Tipo inválido.';
            exit();
        }
        
        // Verifica se a cidade já existe, para evitar duplicados
        $abc = $this->pdo->prepare('SELECT * FROM cidade WHERE cidade = :cidade AND estado = :estado');
        $abc->bindValue(':cidade', $cidade, PDO::PARAM_STR);
        $abc->bindValue(':estado', $estado, PDO::PARAM_STR);
        $abc->execute();
        
        if($abc->rowCount() > 0) {
            // Cidade já existe!
            return 'A cidade <i>'.$cidade.'/'.$estado.'</i> já existe no sistema. Verifique a lista de cidades... Se ela não aparecer, atualize a página.';
        } else {
            // Não existe.. cria novo
            try {
                $abc = $this->pdo->prepare('INSERT INTO cidade (id, cidade, estado, hospedeiro, solicitante_id, resp_id) VALUES (NULL, :cidade, :estado, :hospedeiro, 0, 0)');
                $abc->bindValue(':cidade', $cidade, PDO::PARAM_STR);
                $abc->bindValue(':estado', $estado, PDO::PARAM_STR);
                $abc->bindValue(':hospedeiro', $hospedeiro, PDO::PARAM_BOOL);
                
                $abc->execute();
                return 'success';
            } catch(PDOException $e) {
                return $e->getMessage();
            }
        }
        
    }
    
    public function CidadeApaga($id)
    {
        if($id == 0) {
            return 'Código de cidade inválido. [0]';
        } else {
            $abc = $this->pdo->prepare('SELECT id FROM cidade WHERE id = :id');
            $abc->bindValue(':id', $id, PDO::PARAM_INT);
            $abc->execute();
            
            if($abc->rowCount() > 0) {
                try {
                    $abc = $this->pdo->prepare('DELETE FROM cidade WHERE id = :id');
                    $abc->bindValue(':id', $id, PDO::PARAM_INT);
                    $abc->execute();
                    return 'success';
                } catch(PDOException $e) {
                    return $e->getMessage();
                }
            } else {
                return 'Essa cidade não existe! Ela já pode ter sido apagada.';
            }
        }
    }
    
    public function CidadeLista() // Retorno JSON
    {
        
    }
    
    public function Designacao($tipo, $cidade, $id) // RETORNO JSON
    {
        if($tipo == 'resp') { // Designa responsável
            // Verifica se cidade existe
            try {
                $abc = $this->pdo->prepare('SELECT cidade.* FROM cidade WHERE cidade.id = :id');
                $abc->bindValue(':id', $cidade, PDO::PARAM_INT);
                $abc->execute();
            } catch(PDOException $e) {
                return '{"tipo":"danger", "mensagem": "Erro: '.$e->getMessage().'"}';
            }
            
            if($abc->rowCount() > 0) {
                // Cidade existe
                $reg = $abc->fetch(PDO::FETCH_OBJ);
                
                // Verifica se o responsável atual é o mesmo que o novo.
                if($reg->resp_id == $id) {
                    // É igual, não faz alteração
                    return '{"tipo":"info", "mensagem": "Esse usuário já consta como responsável. Nenhuma alteração foi feita."}';
                } else {
                    // É diferente, continua com atualização
                    try {
                        $abc = $this->pdo->prepare('UPDATE cidade SET resp_id = :usuid WHERE id = :id');
                        $abc->bindValue(':id', $cidade, PDO::PARAM_INT);
                        $abc->bindValue(':usuid', $id, PDO::PARAM_INT);
                        $abc->execute();
                        
                        return '{"tipo":"success", "mensagem": "Designação realizada com sucesso."}';
                    } catch(PDOException $e) {
                        return '{"tipo":"danger", "mensagem": "Erro: '.$e->getMessage().'"}';
                    }
                }
                
            } else {
                // Cidade não existe
                return '{"tipo":"danger", "mensagem": "Essa cidade não foi encontrada no banco de dados. Talvez ela tenha sido excluída."}';
            }
        } else if($tipo == 'sol') { // Designa solicitante
            // Verifica se cidade existe
            try {
                $abc = $this->pdo->prepare('SELECT cidade.* FROM cidade WHERE cidade.id = :id');
                $abc->bindValue(':id', $cidade, PDO::PARAM_INT);
                $abc->execute();
            } catch(PDOException $e) {
                return '{"tipo":"danger", "mensagem": "Erro: '.$e->getMessage().'"}';
            }
            
            if($abc->rowCount() > 0) {
                // Cidade existe
                $reg = $abc->fetch(PDO::FETCH_OBJ);
                
                // Verifica se a cidade é hospedeira ou visitante. Hospedeira não pode ter um solicitante setado!
                if($reg->hospedeiro == true) {
                    // Cidade hospedeira! Cancela operação
                    return '{"tipo":"warning", "mensagem": "Cidades hospedeiras não possuem solicitante. Operação abortada."}';
                } else {
                    // Cidade visitante, prossegue com a atualização
                    // Verifica se o responsável atual é o mesmo que o novo.
                    if($reg->solicitante_id == $id) {
                        // É igual, não faz alteração
                        return '{"tipo":"info", "mensagem": "Esse usuário já consta como solicitante. Nenhuma alteração foi feita."}';
                    } else {
                        // É diferente, continua atualização
                        try {
                            $abc = $this->pdo->prepare('UPDATE cidade SET solicitante_id = :usuid WHERE id = :id');
                            $abc->bindValue(':id', $cidade, PDO::PARAM_INT);
                            $abc->bindValue(':usuid', $id, PDO::PARAM_INT);
                            $abc->execute();
                            
                            return '{"tipo":"success", "mensagem": "Designação realizada com sucesso."}';
                        } catch(PDOException $e) {
                            return '{"tipo":"danger", "mensagem": "Erro: '.$e->getMessage().'"}';
                        }
                    }
                }
            } else {
                // Cidade não existe
                return '{"tipo":"danger", "mensagem": "Essa cidade não foi encontrada no banco de dados. Talvez ela tenha sido excluída."}';
            }
            
        } else if($tipo == '') {
            return '{"tipo": "danger", "mensagem": "Tipo de operação indefinida."}';
        }
    }
    
    public function SistemaRedefinir($json) // Recebe JSON, Retorno JSON
    {
        
        $objson = json_decode($json);
        
        $retorno = array();
        
        // Desvincula PEH e FAC
        if($objson->opt1 === true) {
            try {
                $abc = $this->pdo->query('UPDATE peh SET fac_id = 0 WHERE 1');
                array_push($retorno, array('titulo' => 'DESVINCULANDO PEH E FAC:', 'mensagem' => 'OK !'));
            } catch(PDOException $e) {
                array_push($retorno, array('titulo' => 'DESVINCULANDO PEH E FAC:', 'mensagem' => 'Erro: '.$e->getMessage().'.'));
            }
        } else {
            array_push($retorno, array('titulo' => 'DESVINCULANDO PEH E FAC:', 'mensagem' => 'Ignorado'));
        }
        
        
        // PEH revisão
        if($objson->opt2 === true) {
            try {
                $abc = $this->pdo->query('UPDATE peh SET revisar = 1 WHERE 1');
                array_push($retorno, array('titulo' => 'REVISANDO PEH:', 'mensagem' => 'OK !'));
            } catch(PDOException $e) {
                array_push($retorno, array('titulo' => 'REVISANDO PEH:', 'mensagem' => 'Erro: '.$e->getMessage().'.'));
            }
        } else {
            array_push($retorno, array('titulo' => 'REVISANDO PEH:', 'mensagem' => 'Ignorado'));
        }
        
        
        // FAC revisão
        if($objson->opt3 === true) {
            try {
                $abc = $this->pdo->query('UPDATE fac SET revisar = 1 WHERE 1');
                array_push($retorno, array('titulo' => 'REVISANDO FAC:', 'mensagem' => 'OK !'));
            } catch(PDOException $e) {
                array_push($retorno, array('titulo' => 'REVISANDO FAC:', 'mensagem' => 'Erro: '.$e->getMessage().'.'));
            }
        } else {
            array_push($retorno, array('titulo' => 'REVISANDO FAC:', 'mensagem' => 'Ignorado'));
        }
        
        
        // Apagar datas PEH
        if($objson->opt4 === true) {
            try {
                $abc = $this->pdo->query('UPDATE peh SET check_in = "0000-00-00", check_out = "0000-00-00" WHERE 1');
                array_push($retorno, array('titulo' => 'REMOVENDO DATAS:', 'mensagem' => 'OK !'));
            } catch(PDOException $e) {
                array_push($retorno, array('titulo' => 'REMOVENDO DATAS:', 'mensagem' => 'Erro: '.$e->getMessage().'.'));
            }
        } else {
            array_push($retorno, array('titulo' => 'REMOVENDO DATAS:', 'mensagem' => 'Ignorado'));
        }
        
        // Apagar "CIDADE DO CONGRESSO"
        if($objson->opt5 === true) {
            try {
                $abc = $this->pdo->query('UPDATE peh SET congresso_cidade = "" WHERE 1');
                array_push($retorno, array('titulo' => 'REMOVENDO CIDADE DO CONGRESSO:', 'mensagem' => 'OK !'));
            } catch(PDOException $e) {
                array_push($retorno, array('titulo' => 'REMOVENDO CIDADE DO CONGRESSO:', 'mensagem' => 'Erro: '.$e->getMessage().'.'));
            }
        } else {
            array_push($retorno, array('titulo' => 'REMOVENDO CIDADE DO CONGRESSO:', 'mensagem' => 'Ignorado'));
        }
        
        
        
        // CONCLUIDO!
        array_push($retorno, array('titulo' => 'CONCLUÍDO!', 'mensagem' => ''));
        
        $retorno_json = json_encode($retorno);
        return $retorno_json;
        
    }
    
    
    
    
    
}