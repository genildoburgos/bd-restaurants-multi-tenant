-- ============================================================================
-- VIEWS AUXILIARES
-- Arquivo: 03_VIEWS.sql
-- Descricao: Views para simplificar consultas complexas no sistema de restaurantes
-- Criado em: 23/02/2026
-- ============================================================================

USE `laravel_restaurants`;

-- View: vw_order_summary
-- Objetivo: trazer uma linha por pedido (orders) com dados consolidados do tenant,
-- mesa, cliente, atendente e agregados dos itens (quantidade de itens e soma dos valores).
-- Esta view simplifica consultas frequentes de relatórios e dashboards.
DROP VIEW IF EXISTS `vw_order_summary`;
CREATE VIEW `vw_order_summary` AS
SELECT
  o.id AS order_id,
  o.tenant_id,
  t.name AS tenant_name,
  o.order_number,
  o.status AS order_status,
  o.type AS order_type,
  o.subtotal,
  o.discount,
  o.service_fee,
  o.delivery_fee,
  o.total AS order_total,
  o.payment_status,
  o.payment_method,
  o.created_at AS created_at,
  o.updated_at AS updated_at,
  o.delivered_at,
  tab.id AS table_id,
  tab.number AS table_number,
  cust.id AS customer_id,
  cust.name AS customer_name,
  u.id AS user_id,
  u.name AS user_name,
  COALESCE(oi_agg.items_count, 0) AS items_count,
  COALESCE(oi_agg.total_quantity, 0) AS items_quantity,
  COALESCE(oi_agg.items_total, 0.00) AS items_total
FROM orders o
LEFT JOIN tenants t ON o.tenant_id = t.id
LEFT JOIN tables tab ON o.table_id = tab.id
LEFT JOIN customers cust ON o.customer_id = cust.id
LEFT JOIN users u ON o.user_id = u.id
LEFT JOIN (
  SELECT
    order_id,
    COUNT(*) AS items_count,
    SUM(quantity) AS total_quantity,
    SUM(total) AS items_total
  FROM order_items
  GROUP BY order_id
) oi_agg ON oi_agg.order_id = o.id;

-- Observacoes/como usar:
-- 1) Consultas rápidas de pedidos com totais e contagem de itens:
--    SELECT * FROM vw_order_summary WHERE tenant_id = 1 AND DATE(created_at) = CURDATE();

-- 2) Relatórios agregando por restaurante/diariamente:
--    SELECT tenant_id, tenant_name, DATE(created_at) AS dt, COUNT(*) AS pedidos, SUM(order_total) AS receita
--    FROM vw_order_summary
--    WHERE order_status = 'delivered' AND tenant_id = 1
--    GROUP BY tenant_id, tenant_name, DATE(created_at);

-- 3) Filtro por atendente / período:
--    SELECT * FROM vw_order_summary WHERE user_id = 5 AND created_at >= '2026-02-01';

-- View: vw_revenue_by_tenant
-- Objetivo: apresentar o faturamento consolidado por restaurante (tenant),
-- trazendo uma linha por tenant com a quantidade total de pedidos concluídos
-- e a receita total gerada a partir dos pedidos com status 'delivered'.
-- Esta view simplifica consultas financeiras e análises de desempenho,
-- permitindo a construção rápida de relatórios administrativos e dashboards
-- sem a necessidade de realizar agregações diretamente sobre a tabela orders.
DROP VIEW IF EXISTS `vw_revenue_by_tenant`;
CREATE VIEW vw_revenue_by_tenant AS
SELECT
    t.id AS tenant_id,
    t.name AS restaurant,
    COUNT(o.id) AS total_orders,
    SUM(o.total) AS revenue
FROM tenants t
LEFT JOIN orders o 
    ON o.tenant_id = t.id
    AND o.status = 'delivered'
GROUP BY t.id, t.name;

-- Observacoes/como usar:
-- 1) Consultar faturamento geral de todos os restaurantes:
--    SELECT * FROM vw_revenue_by_tenant;

-- 2) Buscar faturamento de um restaurante específico:
--    SELECT *
--    FROM vw_revenue_by_tenant
--    WHERE tenant_id = 1;

-- 3) Ranking de restaurantes por receita:
--    SELECT *
--    FROM vw_revenue_by_tenant
--    ORDER BY revenue DESC;

-- 4) Restaurantes com maior volume de pedidos:
--    SELECT restaurant, total_orders
--    FROM vw_revenue_by_tenant
--    ORDER BY total_orders DESC;

-- 5) Identificar restaurantes sem vendas:
--    SELECT *
--    FROM vw_revenue_by_tenant
--    WHERE revenue IS NULL OR revenue = 0;

-- View: vw_cash_register_summary
-- Objetivo: Apresentar o resumo financeiro de cada caixa aberto ou fechado,
-- consolidando todas as movimentacoes (entradas e saidas) do turno.
-- Esta view simplifica o processo critico de fechamento de caixa, escondendo
-- a complexidade de somas condicionais (CASE WHEN) e multiplos JOINS.
DROP VIEW IF EXISTS `vw_cash_register_summary`;
CREATE VIEW `vw_cash_register_summary` AS
SELECT 
    cr.tenant_id,
    t.name AS restaurant_name,
    cr.id AS cash_register_id,
    cr.name AS register_name,
    cr.status,
    cr.opened_at,
    cr.closed_at,
    u_open.name AS opened_by_name,
    u_close.name AS closed_by_name,
    cr.opening_balance,
    -- Calculos de entradas
    COALESCE(SUM(CASE WHEN cm.type = 'deposit' THEN cm.amount ELSE 0 END), 0) AS total_deposits,
    COALESCE(SUM(CASE WHEN cm.type = 'sale' THEN cm.amount ELSE 0 END), 0) AS total_sales,
    -- Calculos de saidas
    COALESCE(SUM(CASE WHEN cm.type IN ('withdrawal', 'expense') THEN cm.amount ELSE 0 END), 0) AS total_outflows,
    -- Saldo que deveria ter fisicamente na gaveta
    (cr.opening_balance + 
     COALESCE(SUM(CASE WHEN cm.type IN ('deposit', 'sale') THEN cm.amount ELSE 0 END), 0) - 
     COALESCE(SUM(CASE WHEN cm.type IN ('withdrawal', 'expense') THEN cm.amount ELSE 0 END), 0)
    ) AS expected_balance,
    cr.current_balance AS system_registered_balance
FROM cash_registers cr
LEFT JOIN tenants t ON cr.tenant_id = t.id
LEFT JOIN users u_open ON cr.opened_by = u_open.id
LEFT JOIN users u_close ON cr.closed_by = u_close.id
LEFT JOIN cash_movements cm ON cr.id = cm.cash_register_id
GROUP BY 
    cr.tenant_id, t.name, cr.id, cr.name, cr.status, 
    cr.opened_at, cr.closed_at, u_open.name, u_close.name, 
    cr.opening_balance, cr.current_balance;

-- Observacoes/como usar:
-- 1) Auditoria rapida: listar caixas fechados onde o saldo registrado 
--    nao bate com o saldo esperado (furo de caixa):
--    SELECT restaurant_name, register_name, closed_by_name, expected_balance, system_registered_balance 
--    FROM vw_cash_register_summary
--    WHERE status = 'closed' AND expected_balance != system_registered_balance;

-- 2) Acompanhar saldos de caixas abertos em tempo real:
--    SELECT register_name, opened_by_name, total_sales, expected_balance
--    FROM vw_cash_register_summary
--    WHERE tenant_id = 1 AND status = 'open';

-- Fim do arquivo
