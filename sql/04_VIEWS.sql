-- ============================================================================
-- SCRIPT: Recriacao das Views com colunas compativeis com o frontend
-- Execute DEPOIS do 01_DDL_estrutura.sql
-- ============================================================================

USE `laravel_restaurants`;

DROP VIEW IF EXISTS vw_revenue_by_tenant;
DROP VIEW IF EXISTS vw_order_summary;
DROP VIEW IF EXISTS vw_cash_register_summary;

-- ============================================================================
-- VIEW 1: vw_revenue_by_tenant
-- Une tenants e orders para mostrar receita total por restaurante
-- ============================================================================
CREATE VIEW vw_revenue_by_tenant AS
SELECT
    t.id                                        AS tenant_id,
    t.name                                      AS restaurant,
    COUNT(o.id)                                 AS total_orders,
    COALESCE(SUM(o.total), 0)                   AS revenue
FROM tenants t
LEFT JOIN orders o
    ON o.tenant_id = t.id
    AND o.status = 'delivered'
    AND o.payment_status = 'paid'
WHERE t.status = 'active'
GROUP BY t.id, t.name
ORDER BY revenue DESC;

-- ============================================================================
-- VIEW 2: vw_order_summary
-- Une orders, tenants, tables, customers e users + subquery de order_items
-- ============================================================================
CREATE VIEW vw_order_summary AS
SELECT
    o.id                                        AS order_id,
    o.order_number,
    t.name                                      AS restaurant,
    COALESCE(c.name, 'Sem cadastro')            AS customer,
    tb.number                                   AS table_number,
    COALESCE(items.total_items, 0)              AS total_items,
    o.subtotal,
    o.discount,
    o.service_fee,
    o.delivery_fee,
    o.total,
    o.status,
    o.type,
    o.payment_method,
    o.payment_status,
    o.created_at
FROM orders o
INNER JOIN tenants t     ON o.tenant_id    = t.id
LEFT  JOIN tables tb     ON o.table_id     = tb.id
LEFT  JOIN customers c   ON o.customer_id  = c.id
LEFT  JOIN (
    SELECT order_id,
           COUNT(*)        AS total_items,
           SUM(quantity)   AS total_qty
    FROM order_items
    GROUP BY order_id
) items ON items.order_id = o.id;

-- ============================================================================
-- VIEW 3: vw_cash_register_summary
-- Une cash_registers, tenants, users e cash_movements com CASE WHEN
-- ============================================================================
CREATE VIEW vw_cash_register_summary AS
SELECT
    cr.id                                       AS register_id,
    cr.name                                     AS cash_register_name,
    t.name                                      AS tenant_name,
    u_open.name                                 AS opened_by_name,
    u_close.name                                AS closed_by_name,
    cr.opening_balance,
    cr.current_balance,
    cr.status,
    cr.opened_at,
    cr.closed_at,
    COALESCE(SUM(CASE WHEN cm.type IN ('sale','deposit') THEN cm.amount ELSE 0 END), 0) AS total_entries,
    COALESCE(SUM(CASE WHEN cm.type IN ('withdrawal','expense') THEN cm.amount ELSE 0 END), 0) AS total_exits
FROM cash_registers cr
INNER JOIN tenants t        ON cr.tenant_id   = t.id
LEFT  JOIN users u_open     ON cr.opened_by   = u_open.id
LEFT  JOIN users u_close    ON cr.closed_by   = u_close.id
LEFT  JOIN cash_movements cm ON cm.cash_register_id = cr.id
GROUP BY
    cr.id, cr.name, t.name,
    u_open.name, u_close.name,
    cr.opening_balance, cr.current_balance,
    cr.status, cr.opened_at, cr.closed_at;