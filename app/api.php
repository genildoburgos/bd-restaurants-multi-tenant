<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/includes/db.php';

$method   = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? '';
$id       = $_GET['id']       ?? null;

function intOrNull($v): ?int   { return ($v === '' || $v === null) ? null : (int)$v; }
function floatOrZero($v): float { return (float)($v ?? 0); }

try {
    $db = getDB();

    // ══════════════════════════════════════════════════
    // PRODUCTS — usa estrutura real: category(varchar),
    //            quantity, unit, min_stock, cost, supplier_id
    // ══════════════════════════════════════════════════
    if ($resource === 'products') {

        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $search = $_GET['search']    ?? null;
            $sql = "SELECT p.*, t.name AS tenant_name, s.name AS supplier_name
                    FROM products p
                    LEFT JOIN tenants   t ON p.tenant_id   = t.id
                    LEFT JOIN suppliers s ON p.supplier_id = s.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND p.tenant_id = ?"; $params[] = (int)$tenant; }
            if ($search)  { $sql .= " AND p.name LIKE ?";  $params[] = "%$search%"; }
            $sql .= " ORDER BY t.name, p.category, p.name";
            $stmt = $db->prepare($sql); $stmt->execute($params);
            echo json_encode($stmt->fetchAll());

        } elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT p.*, t.name AS tenant_name, s.name AS supplier_name
                                  FROM products p
                                  LEFT JOIN tenants   t ON p.tenant_id   = t.id
                                  LEFT JOIN suppliers s ON p.supplier_id = s.id
                                  WHERE p.id = ?");
            $stmt->execute([(int)$id]);
            echo json_encode($stmt->fetch() ?: null);

        } elseif ($method === 'POST') {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO products
                (tenant_id, name, category, quantity, unit, min_stock, cost, supplier_id, last_purchase, created_at, updated_at)
                VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())");
            $stmt->execute([
                intOrNull($d['tenant_id']),
                $d['name'],
                $d['category'] ?? 'Geral',
                floatOrZero($d['quantity']),
                $d['unit'] ?? 'unidades',
                floatOrZero($d['min_stock']),
                floatOrZero($d['cost']),
                intOrNull($d['supplier_id']),
                $d['last_purchase'] ?: null,
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

        } elseif ($method === 'PUT' && $id) {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE products
                SET name=?, category=?, quantity=?, unit=?, min_stock=?, cost=?, supplier_id=?, updated_at=NOW()
                WHERE id=?");
            $stmt->execute([
                $d['name'],
                $d['category'] ?? 'Geral',
                floatOrZero($d['quantity']),
                $d['unit'] ?? 'unidades',
                floatOrZero($d['min_stock']),
                floatOrZero($d['cost']),
                intOrNull($d['supplier_id']),
                (int)$id,
            ]);
            echo json_encode(['success' => true]);

        } elseif ($method === 'DELETE' && $id) {
            try {
                $db->prepare("DELETE FROM products WHERE id = ?")->execute([(int)$id]);
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                // Captura o SIGNAL do trigger trg_bloqueia_exclusao_produto
                http_response_code(422);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }

    // ══════════════════════════════════════════════════
    // CUSTOMERS
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'customers') {

        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $search = $_GET['search']    ?? null;
            $sql = "SELECT c.*, t.name AS tenant_name
                    FROM customers c LEFT JOIN tenants t ON c.tenant_id = t.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND c.tenant_id = ?"; $params[] = (int)$tenant; }
            if ($search)  { $sql .= " AND (c.name LIKE ? OR c.phone LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
            $sql .= " ORDER BY c.total_spent DESC";
            $stmt = $db->prepare($sql); $stmt->execute($params);
            echo json_encode($stmt->fetchAll());

        } elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
            $stmt->execute([(int)$id]);
            echo json_encode($stmt->fetch() ?: null);

        } elseif ($method === 'POST') {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO customers
                (tenant_id, name, phone, email, address, birthday, join_date, points, level, total_spent, visits, created_at, updated_at)
                VALUES (?,?,?,?,?,?,CURDATE(),0,'bronze',0.00,0,NOW(),NOW())");
            $stmt->execute([
                intOrNull($d['tenant_id']),
                $d['name'],
                $d['phone'] ?? '',
                $d['email']    ?: null,
                $d['address']  ?: null,
                $d['birthday'] ?: null,
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

        } elseif ($method === 'PUT' && $id) {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE customers
                SET name=?, phone=?, email=?, address=?, birthday=?, updated_at=NOW()
                WHERE id=?");
            $stmt->execute([
                $d['name'], $d['phone'] ?? '',
                $d['email'] ?: null, $d['address'] ?: null,
                $d['birthday'] ?: null, (int)$id,
            ]);
            echo json_encode(['success' => true]);

        } elseif ($method === 'DELETE' && $id) {
            $db->prepare("DELETE FROM customers WHERE id = ?")->execute([(int)$id]);
            echo json_encode(['success' => true]);
        }

    // ══════════════════════════════════════════════════
    // SUPPLIERS
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'suppliers') {
        $tenant = $_GET['tenant_id'] ?? null;
        $sql = "SELECT id, name FROM suppliers WHERE status='active'";
        $params = [];
        if ($tenant) { $sql .= " AND tenant_id = ?"; $params[] = (int)$tenant; }
        $sql .= " ORDER BY name";
        $stmt = $db->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // CATEGORIES — valores distintos da coluna category
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'categories') {
        $tenant = $_GET['tenant_id'] ?? null;
        $sql = "SELECT DISTINCT category AS name FROM products WHERE 1=1";
        $params = [];
        if ($tenant) { $sql .= " AND tenant_id = ?"; $params[] = (int)$tenant; }
        $sql .= " ORDER BY category";
        $stmt = $db->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // TENANTS
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'tenants') {
        $stmt = $db->query("SELECT id, name FROM tenants WHERE status='active' ORDER BY name");
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // ORDERS
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'orders') {
        $stmt = $db->query(
            "SELECT o.*, t.name AS tenant_name, c.name AS customer_name, tb.number AS table_number
             FROM orders o
             LEFT JOIN tenants   t  ON o.tenant_id   = t.id
             LEFT JOIN customers c  ON o.customer_id = c.id
             LEFT JOIN tables    tb ON o.table_id    = tb.id
             ORDER BY o.created_at DESC LIMIT 100"
        );
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // STATS (dashboard)
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'stats') {
        echo json_encode([
            'tenants'   => $db->query("SELECT COUNT(*) FROM tenants WHERE status='active'")->fetchColumn(),
            'customers' => $db->query("SELECT COUNT(*) FROM customers")->fetchColumn(),
            'products'  => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'orders'    => $db->query("SELECT COUNT(*) FROM orders WHERE status='delivered'")->fetchColumn(),
            'revenue'   => $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered'")->fetchColumn(),
        ]);

    // ══════════════════════════════════════════════════
    // REPORT DATA — todos os dados para a página Relatórios
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'report_data') {

        $revenue       = $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered'")->fetchColumn();
        $total_orders  = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $delivered     = $db->query("SELECT COUNT(*) FROM orders WHERE status='delivered'")->fetchColumn();
        $ticket_medio  = $delivered > 0 ? $revenue / $delivered : 0;
        $active_cust   = $db->query("SELECT COUNT(DISTINCT customer_id) FROM orders WHERE status='delivered' AND customer_id IS NOT NULL")->fetchColumn();

        // Receita por restaurante
        $stmt = $db->query(
            "SELECT t.name AS tenant_name, COALESCE(SUM(o.total),0) AS revenue
             FROM tenants t
             LEFT JOIN orders o ON o.tenant_id = t.id AND o.status = 'delivered'
             WHERE t.status = 'active'
             GROUP BY t.id, t.name ORDER BY revenue DESC"
        );
        $revenue_by_tenant = $stmt->fetchAll();

        // Distribuição de status dos pedidos
        $stmt = $db->query("SELECT status, COUNT(*) AS total FROM orders GROUP BY status ORDER BY total DESC");
        $status_dist = $stmt->fetchAll();

        // Top produtos mais vendidos
        $stmt = $db->query(
            "SELECT p.name, p.category, p.quantity AS stock, p.unit, p.min_stock,
                    COALESCE(SUM(oi.quantity),0) AS qty_sold,
                    COALESCE(SUM(oi.quantity * oi.price),0) AS revenue
             FROM products p
             LEFT JOIN order_items oi ON oi.product_id = p.id
             GROUP BY p.id, p.name, p.category, p.quantity, p.unit, p.min_stock
             ORDER BY qty_sold DESC LIMIT 10"
        );
        $top_products = $stmt->fetchAll();

        // Distribuição de níveis de clientes
        $stmt = $db->query("SELECT level, COUNT(*) AS total FROM customers GROUP BY level ORDER BY total DESC");
        $level_dist = $stmt->fetchAll();

        // Vendas por método de pagamento (via transactions)
        $stmt = $db->query(
            "SELECT payment_method, COALESCE(SUM(amount),0) AS total
             FROM transactions
             WHERE type = 'income' AND payment_method IS NOT NULL
             GROUP BY payment_method ORDER BY total DESC"
        );
        $payment_dist = $stmt->fetchAll();

        // Top 10 clientes por gasto
        $stmt = $db->query(
            "SELECT c.name, c.level, c.points, c.total_spent, c.visits, t.name AS tenant_name
             FROM customers c LEFT JOIN tenants t ON c.tenant_id = t.id
             ORDER BY c.total_spent DESC LIMIT 10"
        );
        $top_customers = $stmt->fetchAll();

        // Estoque crítico (quantity <= min_stock)
        $stmt = $db->query(
            "SELECT p.name, p.category, p.quantity, p.unit, p.min_stock, t.name AS tenant_name
             FROM products p LEFT JOIN tenants t ON p.tenant_id = t.id
             WHERE p.quantity <= p.min_stock
             ORDER BY (p.quantity / NULLIF(p.min_stock, 0)) ASC"
        );
        $low_stock = $stmt->fetchAll();

        echo json_encode([
            'kpis' => [
                'revenue'          => (float)$revenue,
                'ticket_medio'     => (float)$ticket_medio,
                'total_orders'     => (int)$total_orders,
                'active_customers' => (int)$active_cust,
            ],
            'revenue_by_tenant' => $revenue_by_tenant,
            'status_dist'       => $status_dist,
            'top_products'      => $top_products,
            'level_dist'        => $level_dist,
            'payment_dist'      => $payment_dist,
            'top_customers'     => $top_customers,
            'low_stock'         => $low_stock,
        ]);

    // ══════════════════════════════════════════════════
    // VIEWS SQL (da entrega anterior)
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'view_revenue') {
        $stmt = $db->query("SELECT * FROM vw_revenue_by_tenant ORDER BY revenue DESC");
        echo json_encode($stmt->fetchAll());

    } elseif ($resource === 'view_orders') {
        $stmt = $db->query("SELECT * FROM vw_order_summary ORDER BY created_at DESC LIMIT 50");
        echo json_encode($stmt->fetchAll());

    } elseif ($resource === 'view_cash') {
        $stmt = $db->query("SELECT * FROM vw_cash_register_summary");
        echo json_encode($stmt->fetchAll());

    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Recurso não encontrado: ' . $resource]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}