-- ============================================================================
-- QUERIES ÚTEIS PARA TESTES E CONSULTAS
-- Sistema de Gestão de Restaurantes Multi-Tenant
-- ============================================================================

USE laravel_restaurants;

-- ============================================================================
-- CONSULTAS BÁSICAS
-- ============================================================================

-- 1. Listar todos os restaurantes (tenants)
SELECT 
    id,
    name AS restaurante,
    slug,
    status,
    created_at AS cadastrado_em
FROM tenants
ORDER BY created_at;

-- 2. Listar todos os usuários com seus restaurantes
SELECT 
    u.id,
    u.name AS usuario,
    u.email,
    u.role AS papel,
    t.name AS restaurante,
    u.status
FROM users u
LEFT JOIN tenants t ON u.tenant_id = t.id
ORDER BY t.name, u.role;

-- 3. Listar todas as mesas disponíveis
SELECT 
    t.id,
    te.name AS restaurante,
    t.number AS mesa,
    t.capacity AS capacidade,
    t.status,
    t.location AS localizacao
FROM tables t
INNER JOIN tenants te ON t.tenant_id = te.id
WHERE t.status = 'available'
ORDER BY te.name, t.number;

-- ============================================================================
-- CONSULTAS DE PRODUTOS E CARDÁPIO
-- ============================================================================

-- 4. Cardápio completo por restaurante
SELECT 
    c.name AS categoria,
    p.name AS produto,
    p.description AS descricao,
    p.price AS preco,
    p.is_available AS disponivel
FROM products p
INNER JOIN categories c ON p.category_id = c.id
WHERE p.tenant_id = 1
ORDER BY c.sort_order, p.name;

-- 5. Produtos mais vendidos
SELECT 
    p.name AS produto,
    c.name AS categoria,
    COUNT(oi.id) AS vezes_pedido,
    SUM(oi.quantity) AS quantidade_total,
    SUM(oi.total) AS receita_total
FROM order_items oi
INNER JOIN products p ON oi.product_id = p.id
INNER JOIN categories c ON p.category_id = c.id
INNER JOIN orders o ON oi.order_id = o.id
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
GROUP BY p.id, p.name, c.name
ORDER BY quantidade_total DESC
LIMIT 10;

-- 6. Produtos com estoque baixo
SELECT 
    p.name AS produto,
    c.name AS categoria,
    p.stock_quantity AS estoque,
    p.price AS preco
FROM products p
INNER JOIN categories c ON p.category_id = c.id
WHERE p.tenant_id = 1
  AND p.stock_control = 1
  AND p.stock_quantity < 10
ORDER BY p.stock_quantity;

-- ============================================================================
-- CONSULTAS DE PEDIDOS
-- ============================================================================

-- 7. Pedidos do dia
SELECT 
    o.order_number AS pedido,
    t.number AS mesa,
    c.name AS cliente,
    u.name AS atendente,
    o.status,
    o.total,
    o.payment_method AS pagamento,
    o.created_at AS hora
FROM orders o
LEFT JOIN tables t ON o.table_id = t.id
LEFT JOIN customers c ON o.customer_id = c.id
LEFT JOIN users u ON o.user_id = u.id
WHERE o.tenant_id = 1
  AND DATE(o.created_at) = CURDATE()
ORDER BY o.created_at DESC;

-- 8. Detalhes de um pedido específico
SELECT 
    o.order_number AS pedido,
    t.number AS mesa,
    p.name AS produto,
    oi.quantity AS quantidade,
    oi.unit_price AS preco_unit,
    oi.total AS total_item,
    oi.notes AS observacoes
FROM order_items oi
INNER JOIN orders o ON oi.order_id = o.id
INNER JOIN products p ON oi.product_id = p.id
LEFT JOIN tables t ON o.table_id = t.id
WHERE o.order_number = 'PED001';

-- 9. Pedidos pendentes/em preparo
SELECT 
    o.order_number AS pedido,
    t.number AS mesa,
    o.status,
    COUNT(oi.id) AS total_itens,
    o.total,
    TIMESTAMPDIFF(MINUTE, o.created_at, NOW()) AS tempo_espera_min
FROM orders o
LEFT JOIN tables t ON o.table_id = t.id
LEFT JOIN order_items oi ON o.order_id = oi.order_id
WHERE o.tenant_id = 1
  AND o.status IN ('pending', 'confirmed', 'preparing')
GROUP BY o.id
ORDER BY o.created_at;

-- ============================================================================
-- CONSULTAS FINANCEIRAS
-- ============================================================================

-- 10. Resumo de vendas do dia
SELECT 
    DATE(o.created_at) AS data,
    COUNT(o.id) AS total_pedidos,
    SUM(o.subtotal) AS subtotal,
    SUM(o.discount) AS descontos,
    SUM(o.service_fee) AS taxa_servico,
    SUM(o.total) AS total_vendas
FROM orders o
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
  AND DATE(o.created_at) = CURDATE()
GROUP BY DATE(o.created_at);

-- 11. Vendas por método de pagamento
SELECT 
    o.payment_method AS metodo_pagamento,
    COUNT(o.id) AS quantidade_pedidos,
    SUM(o.total) AS total
FROM orders o
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
  AND MONTH(o.created_at) = MONTH(CURDATE())
GROUP BY o.payment_method
ORDER BY total DESC;

-- 12. Movimentação de caixa
SELECT 
    cm.movement_date AS data_hora,
    cm.type AS tipo,
    cm.description AS descricao,
    cm.amount AS valor,
    cm.payment_method AS metodo,
    u.name AS usuario
FROM cash_movements cm
INNER JOIN users u ON cm.user_id = u.id
WHERE cm.tenant_id = 1
  AND DATE(cm.movement_date) = CURDATE()
ORDER BY cm.movement_date DESC;

-- 13. Balanço financeiro mensal
SELECT 
    type AS tipo,
    category AS categoria,
    COUNT(*) AS quantidade,
    SUM(amount) AS total
FROM transactions
WHERE tenant_id = 1
  AND MONTH(transaction_date) = MONTH(CURDATE())
  AND YEAR(transaction_date) = YEAR(CURDATE())
GROUP BY type, category
ORDER BY type, total DESC;

-- ============================================================================
-- CONSULTAS DE CLIENTES E FIDELIDADE
-- ============================================================================

-- 14. Clientes VIP (Gold e Platinum)
SELECT 
    name AS cliente,
    email,
    phone AS telefone,
    points AS pontos,
    level AS nivel,
    status
FROM customers
WHERE tenant_id = 1
  AND level IN ('gold', 'platinum')
  AND status = 'active'
ORDER BY points DESC;

-- 15. Ranking de clientes por consumo
SELECT 
    c.name AS cliente,
    c.level AS nivel,
    c.points AS pontos,
    COUNT(o.id) AS total_pedidos,
    SUM(o.total) AS total_gasto
FROM customers c
INNER JOIN orders o ON c.id = o.customer_id
WHERE c.tenant_id = 1
  AND o.status = 'delivered'
GROUP BY c.id
ORDER BY total_gasto DESC
LIMIT 20;

-- 16. Clientes que não compram há mais de 30 dias
SELECT 
    c.name AS cliente,
    c.email,
    c.phone,
    MAX(o.created_at) AS ultima_compra,
    DATEDIFF(CURDATE(), MAX(o.created_at)) AS dias_sem_comprar
FROM customers c
LEFT JOIN orders o ON c.id = o.customer_id AND o.status = 'delivered'
WHERE c.tenant_id = 1
  AND c.status = 'active'
GROUP BY c.id
HAVING dias_sem_comprar > 30 OR ultima_compra IS NULL
ORDER BY dias_sem_comprar DESC;

-- ============================================================================
-- RELATÓRIOS GERENCIAIS
-- ============================================================================

-- 17. Desempenho por categoria
SELECT 
    c.name AS categoria,
    COUNT(DISTINCT o.id) AS pedidos,
    SUM(oi.quantity) AS itens_vendidos,
    SUM(oi.total) AS receita,
    AVG(oi.unit_price) AS ticket_medio
FROM order_items oi
INNER JOIN products p ON oi.product_id = p.id
INNER JOIN categories c ON p.category_id = c.id
INNER JOIN orders o ON oi.order_id = o.id
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
  AND o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY c.id
ORDER BY receita DESC;

-- 18. Desempenho de funcionários
SELECT 
    u.name AS funcionario,
    u.role AS papel,
    COUNT(o.id) AS pedidos_atendidos,
    SUM(o.total) AS total_vendido,
    AVG(o.total) AS ticket_medio
FROM orders o
INNER JOIN users u ON o.user_id = u.id
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
  AND o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY u.id
ORDER BY total_vendido DESC;

-- 19. Horários de pico
SELECT 
    HOUR(created_at) AS hora,
    COUNT(*) AS total_pedidos,
    SUM(total) AS receita
FROM orders
WHERE tenant_id = 1
  AND status = 'delivered'
  AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY HOUR(created_at)
ORDER BY hora;

-- 20. Taxa de cancelamento
SELECT 
    DATE(created_at) AS data,
    COUNT(*) AS total_pedidos,
    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) AS entregues,
    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelados,
    ROUND(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) AS taxa_cancelamento_pct
FROM orders
WHERE tenant_id = 1
  AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(created_at)
ORDER BY data DESC;

-- ============================================================================
-- QUERIES DE MANUTENÇÃO E AUDITORIA
-- ============================================================================

-- 21. Verificar integridade referencial
SELECT 
    'Orders sem table_id' AS verificacao,
    COUNT(*) AS quantidade
FROM orders
WHERE table_id IS NOT NULL 
  AND table_id NOT IN (SELECT id FROM tables)

UNION ALL

SELECT 
    'Orders sem customer_id',
    COUNT(*)
FROM orders
WHERE customer_id IS NOT NULL 
  AND customer_id NOT IN (SELECT id FROM customers)

UNION ALL

SELECT 
    'Products sem category_id',
    COUNT(*)
FROM products
WHERE category_id IS NOT NULL 
  AND category_id NOT IN (SELECT id FROM categories);

-- 22. Auditoria de dados
SELECT 
    'Tenants' AS tabela,
    COUNT(*) AS total_registros,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS ativos
FROM tenants

UNION ALL

SELECT 
    'Users',
    COUNT(*),
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END)
FROM users

UNION ALL

SELECT 
    'Customers',
    COUNT(*),
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END)
FROM customers

UNION ALL

SELECT 
    'Products',
    COUNT(*),
    SUM(CASE WHEN is_available = 1 THEN 1 ELSE 0 END)
FROM products

UNION ALL

SELECT 
    'Orders',
    COUNT(*),
    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END)
FROM orders;

-- 23. Espaço utilizado por tabela
SELECT 
    table_name AS tabela,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS tamanho_mb,
    table_rows AS registros
FROM information_schema.TABLES
WHERE table_schema = 'laravel_restaurants'
ORDER BY (data_length + index_length) DESC;

-- ============================================================================
-- FIM DAS QUERIES ÚTEIS
-- ============================================================================