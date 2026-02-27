<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? '';
$id = $_GET['id'] ?? null;

try {
    $db = getDB();

    // ===================== PRODUCTS =====================
    if ($resource === 'products') {
        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $search = $_GET['search'] ?? null;
            $sql = "SELECT p.*, c.name as category_name, t.name as tenant_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN tenants t ON p.tenant_id = t.id
                    WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND p.tenant_id = ?"; $params[] = $tenant; }
            if ($search) { $sql .= " AND p.name LIKE ?"; $params[] = "%$search%"; }
            $sql .= " ORDER BY t.name, c.name, p.name";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll());
        }
        elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO products (tenant_id, category_id, name, description, price, cost, sku, stock_quantity, stock_control, is_available, preparation_time, allergens, tags) VALUES (?,?,?,?,?,?,?,?,?,?,?,'[]','[]')");
            $stmt->execute([
                $data['tenant_id'], $data['category_id'], $data['name'],
                $data['description'] ?? '', $data['price'], $data['cost'] ?? 0,
                $data['sku'], $data['stock_quantity'] ?? null,
                $data['stock_control'] ?? 0, $data['is_available'] ?? 1,
                $data['preparation_time'] ?? 15
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        }
        elseif ($method === 'PUT' && $id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, cost=?, sku=?, category_id=?, is_available=?, preparation_time=? WHERE id=?");
            $stmt->execute([
                $data['name'], $data['description'] ?? '',
                $data['price'], $data['cost'] ?? 0, $data['sku'],
                $data['category_id'], $data['is_available'] ?? 1,
                $data['preparation_time'] ?? 15, $id
            ]);
            echo json_encode(['success' => true]);
        }
        elseif ($method === 'DELETE' && $id) {
            $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        }
    }

    // ===================== CUSTOMERS =====================
    elseif ($resource === 'customers') {
        if ($method === 'GET' && !$id) {
            $tenant = $_GET['tenant_id'] ?? null;
            $search = $_GET['search'] ?? null;
            $sql = "SELECT c.*, t.name as tenant_name FROM customers c LEFT JOIN tenants t ON c.tenant_id = t.id WHERE 1=1";
            $params = [];
            if ($tenant) { $sql .= " AND c.tenant_id = ?"; $params[] = $tenant; }
            if ($search) { $sql .= " AND (c.name LIKE ? OR c.email LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
            $sql .= " ORDER BY c.name";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll());
        }
        elseif ($method === 'GET' && $id) {
            $stmt = $db->prepare("SELECT * FROM customers WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO customers (tenant_id, name, email, phone, cpf, birth_date, points, level, status, address) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $data['tenant_id'], $data['name'], $data['email'],
                $data['phone'] ?? null, $data['cpf'] ?? null,
                $data['birth_date'] ?? null, 0, 'bronze', 'active',
                $data['address'] ?? null
            ]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        }
        elseif ($method === 'PUT' && $id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE customers SET name=?, email=?, phone=?, address=?, status=? WHERE id=?");
            $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? null, $data['address'] ?? null, $data['status'] ?? 'active', $id]);
            echo json_encode(['success' => true]);
        }
        elseif ($method === 'DELETE' && $id) {
            $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        }
    }

    // ===================== TENANTS =====================
    elseif ($resource === 'tenants') {
        $stmt = $db->query("SELECT id, name FROM tenants WHERE status = 'active' ORDER BY name");
        echo json_encode($stmt->fetchAll());
    }

    // ===================== CATEGORIES =====================
    elseif ($resource === 'categories') {
        $tenant = $_GET['tenant_id'] ?? null;
        $sql = "SELECT * FROM categories WHERE is_active = 1";
        $params = [];
        if ($tenant) { $sql .= " AND tenant_id = ?"; $params[] = $tenant; }
        $sql .= " ORDER BY name";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        echo json_encode($stmt->fetchAll());
    }

    // ===================== ORDERS =====================
    elseif ($resource === 'orders') {
        $sql = "SELECT o.*, t.name as tenant_name, c.name as customer_name, tb.number as table_number
                FROM orders o
                LEFT JOIN tenants t ON o.tenant_id = t.id
                LEFT JOIN customers c ON o.customer_id = c.id
                LEFT JOIN tables tb ON o.table_id = tb.id
                ORDER BY o.created_at DESC LIMIT 100";
        $stmt = $db->query($sql);
        echo json_encode($stmt->fetchAll());
    }

    // ===================== DASHBOARD STATS =====================
    elseif ($resource === 'stats') {
        $stats = [];
        $stats['tenants']      = $db->query("SELECT COUNT(*) FROM tenants WHERE status='active'")->fetchColumn();
        $stats['customers']    = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();
        $stats['products']     = $db->query("SELECT COUNT(*) FROM products WHERE is_available=1")->fetchColumn();
        $stats['orders']       = $db->query("SELECT COUNT(*) FROM orders WHERE status='delivered'")->fetchColumn();
        $stats['revenue']      = $db->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='delivered' AND payment_status='paid'")->fetchColumn();
        $stats['orders_today'] = $db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
        echo json_encode($stats);
    }

    // ===================== VIEWS =====================
    elseif ($resource === 'view_revenue') {
        $stmt = $db->query("SELECT * FROM vw_revenue_by_tenant ORDER BY revenue DESC");
        echo json_encode($stmt->fetchAll());
    }
    elseif ($resource === 'view_orders') {
        $stmt = $db->query("SELECT * FROM vw_order_summary ORDER BY created_at DESC LIMIT 50");
        echo json_encode($stmt->fetchAll());
    }
    elseif ($resource === 'view_cash') {
        $stmt = $db->query("SELECT * FROM vw_cash_register_summary");
        echo json_encode($stmt->fetchAll());
    }

    else {
        http_response_code(404);
        echo json_encode(['error' => 'Recurso nao encontrado']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}