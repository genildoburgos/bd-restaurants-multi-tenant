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
    // PRODUCTS
    // ══════════════════════════════════════════════════
    if ($resource === 'products') {

        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $search = $_GET['search']    ?? null;
            $sql = "SELECT p.*, t.name AS tenant_name, c.name AS category_name
                    FROM products p
                    LEFT JOIN tenants    t ON p.tenant_id   = t.id
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND p.tenant_id = ?"; $params[] = (int)$tenant; }
            if ($search)  { $sql .= " AND p.name LIKE ?";  $params[] = "%$search%"; }
            $sql .= " ORDER BY t.name, c.name, p.name";
            $stmt = $db->prepare($sql); $stmt->execute($params);
            echo json_encode($stmt->fetchAll());

        } elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT p.*, t.name AS tenant_name, c.name AS category_name
                                  FROM products p
                                  LEFT JOIN tenants    t ON p.tenant_id   = t.id
                                  LEFT JOIN categories c ON p.category_id = c.id
                                  WHERE p.id = ?");
            $stmt->execute([(int)$id]);
            echo json_encode($stmt->fetch() ?: null);

        } elseif ($method === 'POST') {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO products
                (tenant_id, category_id, name, description, price, cost,
                 stock_quantity, stock_control, is_available, preparation_time, created_at, updated_at)
                VALUES (?,?,?,?,?,?,?,?,1,?,NOW(),NOW())");
            $stmt->execute([
                intOrNull($d['tenant_id']),
                intOrNull($d['category_id'] ?? null),
                $d['name'],
                $d['description'] ?? null,
                floatOrZero($d['price']),
                floatOrZero($d['cost']),
                intOrNull($d['stock_quantity'] ?? null),
                isset($d['stock_control']) ? (int)$d['stock_control'] : 0,
                intOrNull($d['preparation_time'] ?? null),
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

        } elseif ($method === 'PUT' && $id) {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE products
                SET category_id=?, name=?, description=?, price=?, cost=?,
                    stock_quantity=?, stock_control=?, preparation_time=?, updated_at=NOW()
                WHERE id=?");
            $stmt->execute([
                intOrNull($d['category_id'] ?? null),
                $d['name'],
                $d['description'] ?? null,
                floatOrZero($d['price']),
                floatOrZero($d['cost']),
                intOrNull($d['stock_quantity'] ?? null),
                isset($d['stock_control']) ? (int)$d['stock_control'] : 0,
                intOrNull($d['preparation_time'] ?? null),
                (int)$id,
            ]);
            echo json_encode(['success' => true]);

        } elseif ($method === 'DELETE' && $id) {
            try {
                $db->prepare("DELETE FROM products WHERE id = ?")->execute([(int)$id]);
                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
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
            $sql = "SELECT c.*,
                           t.name AS tenant_name,
                           COALESCE(c.points, 0) AS points,
                           COALESCE(c.level, 'bronze') AS level,
                           COALESCE((
                               SELECT SUM(o.total) FROM orders o
                               WHERE o.customer_id = c.id AND o.status = 'delivered'
                           ), 0) AS total_spent,
                           COALESCE((
                               SELECT COUNT(*) FROM orders o
                               WHERE o.customer_id = c.id AND o.status = 'delivered'
                           ), 0) AS visits
                    FROM customers c
                    LEFT JOIN tenants t ON c.tenant_id = t.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND c.tenant_id = ?"; $params[] = (int)$tenant; }
            if ($search)  { $sql .= " AND (c.name LIKE ? OR c.phone LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
            $sql .= " ORDER BY c.points DESC";
            $stmt = $db->prepare($sql); $stmt->execute($params);
            echo json_encode($stmt->fetchAll());

        } elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
            $stmt->execute([(int)$id]);
            echo json_encode($stmt->fetch() ?: null);

        } elseif ($method === 'POST') {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO customers
                (tenant_id, name, phone, email, address, birth_date, points, level, status, created_at, updated_at)
                VALUES (?,?,?,?,?,?,0,'bronze','active',NOW(),NOW())");
            $stmt->execute([
                intOrNull($d['tenant_id']),
                $d['name'],
                $d['phone'] ?? '',
                $d['email']      ?: null,
                $d['address']    ?: null,
                $d['birth_date'] ?: null,
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

        } elseif ($method === 'PUT' && $id) {
            $d = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE customers
                SET name=?, phone=?, email=?, address=?, birth_date=?, updated_at=NOW()
                WHERE id=?");
            $stmt->execute([
                $d['name'],
                $d['phone']      ?? '',
                $d['email']      ?: null,
                $d['address']    ?: null,
                $d['birth_date'] ?: null,
                (int)$id,
            ]);
            echo json_encode(['success' => true]);

        } elseif ($resource === 'customers' && $method === 'DELETE' && $id) {
            $db->prepare("DELETE FROM customers WHERE id = ?")->execute([(int)$id]);
            echo json_encode(['success' => true]);
        }

    // ══════════════════════════════════════════════════
    // ORDERS — CRUD completo
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'orders') {

        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $status = $_GET['status']    ?? null;
            $sql = "SELECT o.*, t.name AS tenant_name, c.name AS customer_name,
                           tb.number AS table_number
                    FROM orders o
                    LEFT JOIN tenants   t  ON o.tenant_id   = t.id
                    LEFT JOIN customers c  ON o.customer_id = c.id
                    LEFT JOIN tables    tb ON o.table_id    = tb.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND o.tenant_id = ?"; $params[] = (int)$tenant; }
            if ($status)  { $sql .= " AND o.status = ?";   $params[] = $status; }
            $sql .= " ORDER BY o.created_at DESC LIMIT 100";
            $stmt = $db->prepare($sql); $stmt->execute($params);
            echo json_encode($stmt->fetchAll());

        } elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare(
                "SELECT o.*, t.name AS tenant_name, c.name AS customer_name,
                        tb.number AS table_number
                 FROM orders o
                 LEFT JOIN tenants   t  ON o.tenant_id   = t.id
                 LEFT JOIN customers c  ON o.customer_id = c.id
                 LEFT JOIN tables    tb ON o.table_id    = tb.id
                 WHERE o.id = ?"
            );
            $stmt->execute([(int)$id]);
            $order = $stmt->fetch();
            if ($order) {
                $stmt2 = $db->prepare(
                    "SELECT oi.*, p.name AS product_name
                     FROM order_items oi
                     LEFT JOIN products p ON oi.product_id = p.id
                     WHERE oi.order_id = ?"
                );
                $stmt2->execute([(int)$id]);
                $order['items'] = $stmt2->fetchAll();
            }
            echo json_encode($order ?: null);

        } elseif ($method === 'POST') {
            $d     = json_decode(file_get_contents('php://input'), true);
            $items = $d['items'] ?? [];

            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += floatOrZero($item['unit_price']) * intval($item['quantity']);
            }
            $service_fee  = floatOrZero($d['service_fee']  ?? 0);
            $delivery_fee = floatOrZero($d['delivery_fee'] ?? 0);
            $discount     = floatOrZero($d['discount']     ?? 0);
            $total        = $subtotal + $service_fee + $delivery_fee - $discount;
            $order_number = 'PED' . date('YmdHis') . rand(10,99);

            $db->beginTransaction();
            try {
                $stmt = $db->prepare(
                    "INSERT INTO orders
                     (tenant_id, table_id, customer_id, order_number, status, type,
                      subtotal, discount, service_fee, delivery_fee, total,
                      payment_status, payment_method, notes, created_at, updated_at)
                     VALUES (?,?,?,?,'pending',?,?,?,?,?,?,'pending',?,?,NOW(),NOW())"
                );
                $stmt->execute([
                    intOrNull($d['tenant_id']),
                    intOrNull($d['table_id']    ?? null),
                    intOrNull($d['customer_id'] ?? null),
                    $order_number,
                    $d['type']           ?? 'dine_in',
                    $subtotal, $discount, $service_fee, $delivery_fee, $total,
                    $d['payment_method'] ?? null,
                    $d['notes']          ?? null,
                ]);
                $order_id = $db->lastInsertId();

                foreach ($items as $item) {
                    $qty        = intval($item['quantity']);
                    $unit_price = floatOrZero($item['unit_price']);
                    $item_sub   = $unit_price * $qty;
                    $item_disc  = floatOrZero($item['discount'] ?? 0);
                    $item_total = $item_sub - $item_disc;
                    $db->prepare(
                        "INSERT INTO order_items
                         (order_id, product_id, quantity, unit_price, subtotal, discount, total, notes, status, created_at, updated_at)
                         VALUES (?,?,?,?,?,?,?,?,  'pending',NOW(),NOW())"
                    )->execute([
                        $order_id,
                        intOrNull($item['product_id']),
                        $qty, $unit_price, $item_sub, $item_disc, $item_total,
                        $item['notes'] ?? '',
                    ]);
                }
                $db->commit();
                echo json_encode(['success' => true, 'id' => $order_id, 'order_number' => $order_number]);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }

        } elseif ($method === 'PUT' && $id) {
            $d = json_decode(file_get_contents('php://input'), true);
            $fields = []; $params = [];
            if (isset($d['status'])) {
                $fields[] = 'status=?'; $params[] = $d['status'];
                if ($d['status'] === 'delivered') { $fields[] = 'delivered_at=NOW()'; }
            }
            if (isset($d['payment_status']))   { $fields[] = 'payment_status=?';   $params[] = $d['payment_status']; }
            if (isset($d['payment_method']))   { $fields[] = 'payment_method=?';   $params[] = $d['payment_method']; }
            if (isset($d['notes']))            { $fields[] = 'notes=?';            $params[] = $d['notes']; }
            if (isset($d['cancelled_reason'])) { $fields[] = 'cancelled_reason=?'; $params[] = $d['cancelled_reason']; }
            $fields[] = 'updated_at=NOW()';
            $params[] = (int)$id;
            $db->prepare("UPDATE orders SET ".implode(',',$fields)." WHERE id=?")->execute($params);
            echo json_encode(['success' => true]);

        } elseif ($method === 'DELETE' && $id) {
            // Cancela logicamente (não deleta)
            $db->prepare("UPDATE orders SET status='cancelled', updated_at=NOW() WHERE id=?")->execute([(int)$id]);
            echo json_encode(['success' => true]);
        }

    // ══════════════════════════════════════════════════
    // TABLES
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'tables') {
        $tenant = $_GET['tenant_id'] ?? null;
        $sql = "SELECT id, number, capacity, status FROM tables WHERE 1=1";
        $params = [];
        if ($tenant) { $sql .= " AND tenant_id = ?"; $params[] = (int)$tenant; }
        $sql .= " ORDER BY number";
        $stmt = $db->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // CATEGORIES
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'categories') {
        $tenant = $_GET['tenant_id'] ?? null;
        $sql = "SELECT id, name FROM categories WHERE is_active = 1";
        $params = [];
        if ($tenant) { $sql .= " AND tenant_id = ?"; $params[] = (int)$tenant; }
        $sql .= " ORDER BY sort_order, name";
        $stmt = $db->prepare($sql); $stmt->execute($params);
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // TENANTS
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'tenants') {
        $stmt = $db->query("SELECT id, name FROM tenants WHERE status='active' ORDER BY name");
        echo json_encode($stmt->fetchAll());

    // ══════════════════════════════════════════════════
    // STATS
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
    // REPORT DATA
    // ══════════════════════════════════════════════════
    } elseif ($resource === 'report_data') {

        $revenue      = $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered'")->fetchColumn();
        $delivered    = $db->query("SELECT COUNT(*) FROM orders WHERE status='delivered'")->fetchColumn();
        $total_orders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        $ticket_medio = $delivered > 0 ? $revenue / $delivered : 0;
        $active_cust  = $db->query("SELECT COUNT(DISTINCT customer_id) FROM orders WHERE status='delivered' AND customer_id IS NOT NULL")->fetchColumn();

        $stmt = $db->query(
            "SELECT t.name AS tenant_name, COALESCE(SUM(o.total),0) AS revenue
             FROM tenants t LEFT JOIN orders o ON o.tenant_id = t.id AND o.status = 'delivered'
             WHERE t.status = 'active' GROUP BY t.id, t.name ORDER BY revenue DESC"
        );
        $revenue_by_tenant = $stmt->fetchAll();

        $stmt = $db->query("SELECT status, COUNT(*) AS total FROM orders GROUP BY status ORDER BY total DESC");
        $status_dist = $stmt->fetchAll();

        $stmt = $db->query(
            "SELECT p.name, c.name AS category, p.stock_quantity AS stock,
                    COALESCE(SUM(oi.quantity),0) AS qty_sold,
                    COALESCE(SUM(oi.total),0) AS revenue
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN order_items oi ON oi.product_id = p.id
             GROUP BY p.id, p.name, c.name, p.stock_quantity
             ORDER BY qty_sold DESC LIMIT 10"
        );
        $top_products = $stmt->fetchAll();

        $stmt = $db->query("SELECT level, COUNT(*) AS total FROM customers GROUP BY level ORDER BY total DESC");
        $level_dist = $stmt->fetchAll();

        $stmt = $db->query(
            "SELECT payment_method, COALESCE(SUM(amount),0) AS total
             FROM transactions WHERE type='income' AND payment_method IS NOT NULL
             GROUP BY payment_method ORDER BY total DESC"
        );
        $payment_dist = $stmt->fetchAll();

        $stmt = $db->query(
            "SELECT c.name, c.level, c.points,
                    COALESCE(SUM(o.total),0) AS total_spent,
                    COUNT(o.id) AS visits, t.name AS tenant_name
             FROM customers c
             LEFT JOIN tenants t ON c.tenant_id = t.id
             LEFT JOIN orders o ON o.customer_id = c.id AND o.status = 'delivered'
             GROUP BY c.id, c.name, c.level, c.points, t.name
             ORDER BY total_spent DESC LIMIT 10"
        );
        $top_customers = $stmt->fetchAll();

        $stmt = $db->query(
            "SELECT p.name, c.name AS category, p.stock_quantity, t.name AS tenant_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             LEFT JOIN tenants    t ON p.tenant_id   = t.id
             WHERE p.stock_control = 1 AND p.stock_quantity IS NOT NULL AND p.stock_quantity < 10
             ORDER BY p.stock_quantity ASC"
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
    // VIEWS SQL
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