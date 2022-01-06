<?php

    // Obter a conexão com o banco de dados 
    include('../../conexao/conn.php');

    // Obter o request vindo do datatable
    $requestData = $_REQUEST;

    // Obter as colunas vindas do request 
    $colunas = $requestData['columns'];

    // Preparar o comando sql para obter os dados da categoria 
    $sql = "SELECT ID, NOME FROM USUARIO WHERE 1=1 ";

    // Obter o total de registros cadastrados 
    $resultado = $pdo->query($sql);
    $qtdeLinhas = $resultado->rowCount();

    // Verificando se há filtro determinado 
    $filtro = $requestData['search']['value'];
    if( !empty( $filtro)){
        // Montar a expressão logica que ira compor os Filtros
        // Aqui você devera determinar quais colunas farão parte do filtro
        $sql .= "AND (ID LIKE '$filtro%'";
        $sql .= "OR LOGIN LIKE '$filtro%')";
    }

    // Obter o total dos dados Filtrados
    $resultado = $pdo->query($sql);
    $totalFiltrados = $resultado->rowCount();

    // Obter valores para ORDER BY
    $colunaOrdem = $requestData['order'][0]['coluna']; //Obtem a posição da coluna na ordenação
    $ordem = $colunas[$colunaOrdem]['data']; //Obtem o nome da coluna para a ordenação
    $direcao = $requestData['order'][0]['dir']; //Obtem a direção da ordenação

    // Obter valores para o LIMIT
    $inicio = $requestData['start']; //Obtem o inicio do limite
    $tamanho = $requestData['length']; //Obtem o tamanho do limite

    // Realizar o ORDER BY com LIMIT
    $sql == "ORDER BY $ordem $direcao LIMIT $inicio, $tamanho";
    $resultado = $pdo->query($sql);
    $dados = array();
    while($row = $resultado->fetch(POO::FETCH_ASSOC)){
        $dados[] = array_map('utf8_encode', $row);
    }

    // Monta o objeto json para retomar ao DataTable 
    $json_data = array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => intval("$qtdeLinhas"),
        "recordsFiltered" => intval($totalFiltrados),
        "data" => $dados
    );

    // Retorna o objeto json para o DataTable
    echo json_encode($json_data);



