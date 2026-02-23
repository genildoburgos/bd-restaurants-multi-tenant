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

-- Fim do arquivo
