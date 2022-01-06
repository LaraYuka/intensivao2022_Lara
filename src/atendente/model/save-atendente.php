<?php

    // Obter a nossa conexão com o banco de dados 
    include('../../conexao/conn.php');

    // Obter todos os campos enviados pelo formulario por meio do $_REQUEST
    $requestData = $_REQUEST;

    // Verificaçãode campos obrigatorios do formulario
    if(empty($requestData['LOGIN'])){
        // Caso ele esteja vazioretorne a seguinte mensagem
        $dados = array(
            "tipo" => 'error'
            "mensagem" => 'Existe(m) campo(s) obrigatorio(s) não preenchido(s)'
        );
    }else{
        // Caso não existam campos obrigatorios vazios execute a função
        $ID = isset($requestData['ID']) ? $requestData['ID'] : '';
        $operacao = isset($requestData['operacao']) ? $requestData['operacao'] : '';

        // Verificar se é para cadastrar um novo registro ou atualizar um registro existente
        if($operacao == 'insert'){
            // Prepara o comando INSERT para ser executado
            try{
                $stmt = $pdo->prepare('INSERT INTO ATENDENTE (NOME, LOGIN, SENHA) VALUES (:a, :b, :c)');
                $stmt->execute(array(
                    ':a' => utf8_decode($requestData['NOME']),
                    ':b' => $requestData['LOGIN'],
                    ':c' => md5($requestData['SENHA'])
                ));
                $dados = array(
                    "tipo" => 'success'
                    "mensagem" => 'Registro salvo com sucesso!'
                );
            }catch (PDOException $e){
                $dados = array(
                    "tipo" => 'erro',
                    "mensagem" => 'Erro ao tentar salvar registro: '.$e
                );
            }
        }else{
            try{
                $stmt = $pdo->prepare('UPDATE ATENDENTE SET NOME = :a, LOGIN = :b, SENHA = :c WHERE ID = :id');
                $stmt->execute(array(
                    ':id' => $ID,
                    ':a' => utf8_decode($requestData['NOME']),
                    ':b' => $requestData['LOGIN'],
                    ':c' => md5($requestData['SENHA'])
                ));
                $dados = array(
                    "tipo" => 'success'
                    "mensagem" => 'Registro salvo com sucesso!'
                );
            }catch (PDOException $e){
                $dados = array(
                    "tipo" => 'erro',
                    "mensagem" => 'Erro ao tentar salvar registro: '.$e
                );
            }
        }
    }

    // Gerar o retorno para o Front-end 
    echo json_encode($dados);