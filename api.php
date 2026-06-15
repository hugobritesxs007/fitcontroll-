<?php
// api.php — FitControl SGPAV
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

// Garante que a conexão use utf8mb4 (suporta emojis)
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

try {
    // ========== LER TODOS OS DADOS ==========
    if ($action === 'get_all' && $method === 'GET') {
        $data = [];
        
        $data['produtos'] = $pdo->query("SELECT * FROM produtos WHERE ativo=1 ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $data['lotes']    = $pdo->query("SELECT * FROM lotes ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $data['clientes'] = $pdo->query("SELECT * FROM clientes WHERE ativo=1 ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $data['cupons']   = $pdo->query("SELECT * FROM cupons ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $data['alertas']  = $pdo->query("SELECT * FROM alertas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Vendas com seus itens
        $vendas = $pdo->query("SELECT * FROM vendas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($vendas as &$v) {
            $stmt = $pdo->prepare("SELECT * FROM itens_venda WHERE idVenda = ?");
            $stmt->execute([$v['id']]);
            $v['itens'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Converte total para número (evita NaN)
            $v['total'] = floatval($v['total']);
        }
        $data['vendas'] = $vendas;
        
        // Garante que os preços sejam números (não strings)
        foreach ($data['produtos'] as &$p) {
            $p['preco'] = floatval($p['preco']);
        }
        foreach ($data['lotes'] as &$l) {
            $l['custo'] = floatval($l['custo']);
            $l['qtdAtual'] = intval($l['qtdAtual']);
            $l['qtdInicial'] = intval($l['qtdInicial']);
        }
        
        // JSON_UNESCAPED_UNICODE preserva emojis (🥛, ⚡, etc.)
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    // ========== SALVAR PRODUTO ==========
    if ($action === 'save_produto' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            INSERT INTO produtos (nome, marca, categoria, unidade, icone, preco, estoque) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nome'], $data['marca'] ?? '', $data['categoria'],
            $data['unidade'] ?? 'UN', $data['icone'] ?? '📦',
            $data['preco'], $data['estoque'] ?? 0
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== ATUALIZAR PRODUTO ==========
    if ($action === 'update_produto' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("UPDATE produtos SET nome=?, marca=?, preco=? WHERE id=?");
        $stmt->execute([$data['nome'], $data['marca'], $data['preco'], $data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== EXCLUIR PRODUTO ==========
    if ($action === 'delete_produto' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([$data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== SALVAR LOTE ==========
    if ($action === 'save_lote' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            INSERT INTO lotes (idProduto, numero, dataFabricacao, dataValidade, qtdInicial, qtdAtual, custo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['produto_id'], $data['codigo_lote'],
            $data['dataFabricacao'] ?? date('Y-m-d'), $data['validade'],
            $data['qtd_inicial'], $data['qtd_inicial'], $data['custo']
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== ATUALIZAR LOTE ==========
    if ($action === 'update_lote' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            UPDATE lotes SET numero=?, dataFabricacao=?, dataValidade=?, 
            qtdAtual=?, qtdInicial=?, custo=? WHERE id=?
        ");
        $stmt->execute([
            $data['numero'], $data['dataFabricacao'], $data['dataValidade'],
            $data['qtdAtual'], $data['qtdInicial'], $data['custo'], $data['id']
        ]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== EXCLUIR LOTE ==========
    if ($action === 'delete_lote' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->prepare("DELETE FROM lotes WHERE id = ?")->execute([$data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== SALVAR CLIENTE ==========
    if ($action === 'save_cliente' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            INSERT INTO clientes (nome, email, telefone, cpf, nascimento, obs) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nome'], $data['email'], $data['telefone'], $data['cpf'],
            $data['nascimento'] ?? null, $data['observacoes'] ?? ''
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== ATUALIZAR CLIENTE ==========
    if ($action === 'update_cliente' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            UPDATE clientes SET nome=?, email=?, telefone=?, cpf=?, obs=? WHERE id=?
        ");
        $stmt->execute([
            $data['nome'], $data['email'], $data['telefone'], $data['cpf'],
            $data['observacoes'] ?? '', $data['id']
        ]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== EXCLUIR CLIENTE ==========
    if ($action === 'delete_cliente' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->prepare("DELETE FROM clientes WHERE id = ?")->execute([$data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== SALVAR VENDA ==========
    if ($action === 'save_venda' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO vendas (idCliente, idCupom, data, total) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['cliente_id'] ?? null,
            $data['cupom_id'] ?? null,
            $data['data'] ?? date('Y-m-d'),
            $data['total']
        ]);
        $idVenda = $pdo->lastInsertId();
        
        foreach ($data['itens'] as $item) {
            $pdo->prepare("
                INSERT INTO itens_venda (idVenda, idLote, qtd, preco) 
                VALUES (?, ?, ?, ?)
            ")->execute([$idVenda, $item['idLote'], $item['qtd'], $item['preco']]);
            
            $pdo->prepare("
                UPDATE lotes SET qtdAtual = qtdAtual - ? WHERE id = ?
            ")->execute([$item['qtd'], $item['idLote']]);
        }
        
        if (!empty($data['cupom_id'])) {
            $pdo->prepare("UPDATE cupons SET status = 'Utilizado' WHERE id = ?")
                ->execute([$data['cupom_id']]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true, 'id' => $idVenda], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== EXCLUIR VENDA ==========
    if ($action === 'delete_venda' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->prepare("DELETE FROM vendas WHERE id = ?")->execute([$data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== SALVAR CUPOM ==========
    if ($action === 'save_cupom' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            INSERT INTO cupons (idAlerta, idProduto, codigo, desconto, expiracao, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['idAlerta'] ?? null,
            $data['idProduto'] ?? null,
            $data['codigo'],
            $data['desconto'],
            $data['expiracao'],
            $data['status'] ?? 'Ativo'
        ]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== ATUALIZAR CUPOM ==========
    if ($action === 'update_cupom' && $method === 'POST') {
        $data = getJsonInput();
        $stmt = $pdo->prepare("
            UPDATE cupons SET codigo=?, desconto=?, expiracao=?, status=? WHERE id=?
        ");
        $stmt->execute([
            $data['codigo'], $data['desconto'], $data['expiracao'],
            $data['status'], $data['id']
        ]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // ========== EXCLUIR CUPOM ==========
    if ($action === 'delete_cupom' && $method === 'POST') {
        $data = getJsonInput();
        $pdo->prepare("DELETE FROM cupons WHERE id = ?")->execute([$data['id']]);
        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo json_encode(['error' => 'Ação não encontrada: ' . $action], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>