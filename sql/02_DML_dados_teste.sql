-- ============================================================================
-- PROJETO: Sistema de Gestao de Restaurantes Multi-Tenant
-- SCRIPT DML - Carga de Dados de Teste
-- DATA: 06/02/2026
-- ============================================================================

USE `laravel_restaurants`;

-- ============================================================================
-- INSERcaO DE DADOS: tenant_plans
-- ============================================================================
INSERT INTO `tenant_plans` (`id`, `name`, `slug`, `description`, `price`, `billing_cycle`, `trial_days`, `features`, `limits`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Basico', 'basico', 'Plano basico para pequenos restaurantes', 49.00, 'monthly', 15, 
'["basic_reports", "email_support", "daily_backup"]', 
'{"users": 3, "tables": 10, "products": 50, "storage_mb": 500}', 
1, 1, NOW(), NOW()),

(2, 'Intermediario', 'intermediario', 'Plano intermediario para restaurantes em crescimento', 147.00, 'monthly', 15, 
'["basic_reports", "advanced_reports", "email_reports", "smart_notifications", "priority_whatsapp_support", "daily_backup", "basic_customization"]', 
'{"users": 10, "tables": 20, "products": -1, "storage_mb": 2000}', 
1, 2, NOW(), NOW()),

(3, 'Profissional', 'profissional', 'Plano completo para restaurantes estabelecidos', 297.00, 'monthly', 15, 
'["basic_reports", "advanced_reports", "custom_reports", "email_reports", "smart_notifications", "priority_support", "hourly_backup", "full_customization", "api_access", "multi_location"]', 
'{"users": -1, "tables": -1, "products": -1, "storage_mb": 10000}', 
1, 3, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: tenants (Restaurantes)
-- ============================================================================
INSERT INTO `tenants` (`id`, `name`, `slug`, `database_name`, `domain`, `status`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'Anotado Restaurante', 'anotado', 'tenant_anotado', NULL, 'active', 
'{"theme": "blue", "currency": "BRL", "timezone": "America/Sao_Paulo"}', 
'2025-05-30 02:05:57', '2025-05-31 00:58:45'),

(2, 'Sabor da Casa', 'sabor-da-casa', 'tenant_sabor', NULL, 'active', 
'{"theme": "green", "currency": "BRL", "timezone": "America/Sao_Paulo"}', 
NOW(), NOW()),

(3, 'Cantina Italiana', 'cantina-italiana', 'tenant_cantina', NULL, 'active', 
'{"theme": "red", "currency": "BRL", "timezone": "America/Sao_Paulo"}', 
NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: tenant_subscriptions
-- ============================================================================
INSERT INTO `tenant_subscriptions` (`id`, `tenant_id`, `tenant_plan_id`, `status`, `starts_at`, `expires_at`, `amount`, `payment_method`, `last_payment_date`, `next_payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'active', '2025-05-30', '2026-05-31', 147.00, 'pix', '2025-05-30', '2026-05-31', NULL, '2025-05-30 02:05:58', '2025-05-31 00:58:45'),
(2, 2, 1, 'active', '2025-06-01', '2026-06-01', 49.00, 'card', '2025-06-01', '2026-06-01', NULL, NOW(), NOW()),
(3, 3, 3, 'active', '2025-06-15', '2026-06-15', 297.00, 'boleto', '2025-06-15', '2026-06-15', NULL, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: users (Usuarios do Sistema)
-- ============================================================================
INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `avatar`, `permissions`, `status`, `last_login`, `remember_token`, `created_at`, `updated_at`) VALUES
-- Super Admin (sem tenant)
(1, NULL, 'Super Admin', 'admin@servefacil.com', NOW(), '$2y$12$0jaCGxf3F9yPtEggCSmGMul.ZaWf2bU1Ex.BavLYRzVGW68pJ0Eoq', 'admin', NULL, 
'["super_admin", "admin.access", "tenants.manage"]', 
'active', NULL, NULL, NOW(), NOW()),

-- Tenant 1: Anotado Restaurante
(2, 1, 'Admin Anotado', 'admin@anotado.com', NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin', NULL, 
'["admin.access", "orders.manage", "customers.manage", "products.manage", "reports.view", "settings.manage"]', 
'active', NULL, NULL, NOW(), NOW()),

(3, 1, 'Gerente Anotado', 'gerente@anotado.com', NOW(), '$2y$12$l1DadmC.QYGnYkBhjLdJUu0xVQCy40Cu.o68XpHj5tK12JA67FD5.', 'manager', NULL, 
'["orders.manage", "customers.manage", "products.view", "products.manage", "tables.manage", "transactions.view", "reports.view"]', 
'active', NULL, NULL, NOW(), NOW()),

(4, 1, 'Funcionario Anotado', 'funcionario@anotado.com', NOW(), '$2y$12$elknLSNR/58JupWSVfgNfOy5C1gNIVAtNdZrWojraodSfI7d05DCu', 'employee', NULL, 
'["orders.view", "orders.create", "customers.view"]', 
'active', NULL, NULL, NOW(), NOW()),

-- Tenant 2: Sabor da Casa
(5, 2, 'Admin Sabor', 'admin@sabordacasa.com', NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin', NULL, 
'["admin.access", "orders.manage", "customers.manage", "products.manage", "reports.view", "settings.manage"]', 
'active', NULL, NULL, NOW(), NOW()),

-- Tenant 3: Cantina Italiana
(6, 3, 'Admin Cantina', 'admin@cantina.com', NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin', NULL, 
'["admin.access", "orders.manage", "customers.manage", "products.manage", "reports.view", "settings.manage"]', 
'active', NULL, NULL, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: customers (Clientes)
-- ============================================================================
INSERT INTO `customers` (`id`, `tenant_id`, `name`, `email`, `phone`, `cpf`, `birth_date`, `points`, `level`, `status`, `address`, `notes`, `created_at`, `updated_at`) VALUES
-- Clientes do Tenant 1 (Anotado)
(1, 1, 'Joao Silva', 'joao.silva@email.com', '(81) 98888-1111', '111.222.333-44', '1985-03-15', 450, 'silver', 'active', 'Rua das Flores, 123 - Recife/PE', NULL, NOW(), NOW()),
(2, 1, 'Maria Santos', 'maria.santos@email.com', '(81) 98888-2222', '222.333.444-55', '1990-07-22', 1250, 'gold', 'active', 'Av. Boa Viagem, 456 - Recife/PE', 'Cliente VIP', NOW(), NOW()),
(3, 1, 'Pedro Oliveira', 'pedro.oliveira@email.com', '(81) 98888-3333', '333.444.555-66', '1988-11-10', 180, 'bronze', 'active', 'Rua do Sol, 789 - Caruaru/PE', NULL, NOW(), NOW()),

-- Clientes do Tenant 2 (Sabor da Casa)
(4, 2, 'Ana Paula', 'ana.paula@email.com', '(81) 97777-1111', '444.555.666-77', '1992-05-18', 320, 'silver', 'active', 'Rua da Paz, 321 - Olinda/PE', NULL, NOW(), NOW()),
(5, 2, 'Carlos Eduardo', 'carlos.eduardo@email.com', '(81) 97777-2222', '555.666.777-88', '1987-09-25', 85, 'bronze', 'active', 'Av. Central, 654 - Jaboatao/PE', NULL, NOW(), NOW()),

-- Clientes do Tenant 3 (Cantina Italiana)
(6, 3, 'Fernanda Costa', 'fernanda.costa@email.com', '(11) 96666-1111', '666.777.888-99', '1991-12-08', 2100, 'platinum', 'active', 'Rua Italia, 987 - Sao Paulo/SP', 'Cliente platinum desde 2024', NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: tables (Mesas)
-- ============================================================================
INSERT INTO `tables` (`id`, `tenant_id`, `number`, `capacity`, `status`, `location`, `qr_code`, `notes`, `created_at`, `updated_at`) VALUES
-- Mesas do Tenant 1 (Anotado)
(1, 1, '01', 4, 'available', 'area interna', 'QR_TABLE_01', NULL, NOW(), NOW()),
(2, 1, '02', 4, 'available', 'area interna', 'QR_TABLE_02', NULL, NOW(), NOW()),
(3, 1, '03', 2, 'available', 'area interna', 'QR_TABLE_03', NULL, NOW(), NOW()),
(4, 1, '04', 6, 'available', 'area externa', 'QR_TABLE_04', NULL, NOW(), NOW()),
(5, 1, '05', 8, 'available', 'area VIP', 'QR_TABLE_05', 'Mesa para grupos', NOW(), NOW()),

-- Mesas do Tenant 2 (Sabor da Casa)
(6, 2, '10', 4, 'available', 'Salao principal', 'QR_TABLE_10', NULL, NOW(), NOW()),
(7, 2, '11', 2, 'available', 'Salao principal', 'QR_TABLE_11', NULL, NOW(), NOW()),
(8, 2, '12', 6, 'available', 'Varanda', 'QR_TABLE_12', NULL, NOW(), NOW()),

-- Mesas do Tenant 3 (Cantina Italiana)
(9, 3, 'A1', 4, 'available', 'Térreo', 'QR_TABLE_A1', NULL, NOW(), NOW()),
(10, 3, 'A2', 2, 'available', 'Térreo', 'QR_TABLE_A2', NULL, NOW(), NOW()),
(11, 3, 'B1', 6, 'available', 'Mezanino', 'QR_TABLE_B1', NULL, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: categories (Categorias de Produtos)
-- ============================================================================
INSERT INTO `categories` (`id`, `tenant_id`, `name`, `description`, `icon`, `color`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
-- Categorias do Tenant 1 (Anotado)
(1, 1, 'Entradas', 'Aperitivos e entradas', 'appetizer', '#FF5722', 1, 1, NOW(), NOW()),
(2, 1, 'Pratos Principais', 'Pratos principais do cardapio', 'restaurant', '#4CAF50', 2, 1, NOW(), NOW()),
(3, 1, 'Bebidas', 'Bebidas diversas', 'local_bar', '#2196F3', 3, 1, NOW(), NOW()),
(4, 1, 'Sobremesas', 'Doces e sobremesas', 'cake', '#E91E63', 4, 1, NOW(), NOW()),

-- Categorias do Tenant 2 (Sabor da Casa)
(5, 2, 'Massas', 'Massas artesanais', 'restaurant', '#FF9800', 1, 1, NOW(), NOW()),
(6, 2, 'Carnes', 'Carnes e aves', 'restaurant', '#795548', 2, 1, NOW(), NOW()),
(7, 2, 'Bebidas', 'Sucos e refrigerantes', 'local_bar', '#00BCD4', 3, 1, NOW(), NOW()),

-- Categorias do Tenant 3 (Cantina Italiana)
(8, 3, 'Antipasti', 'Entradas italianas', 'restaurant', '#8BC34A', 1, 1, NOW(), NOW()),
(9, 3, 'Pasta', 'Massas tradicionais', 'restaurant', '#FFC107', 2, 1, NOW(), NOW()),
(10, 3, 'Pizza', 'Pizzas artesanais', 'local_pizza', '#F44336', 3, 1, NOW(), NOW()),
(11, 3, 'Vini', 'Vinhos importados', 'wine_bar', '#9C27B0', 4, 1, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: products (Produtos)
-- ============================================================================
INSERT INTO `products` (`id`, `tenant_id`, `category_id`, `name`, `description`, `price`, `cost`, `image`, `sku`, `stock_quantity`, `stock_control`, `is_available`, `preparation_time`, `calories`, `allergens`, `tags`, `created_at`, `updated_at`) VALUES
-- Produtos do Tenant 1 (Anotado)
(1, 1, 1, 'Bolinho de Bacalhau', '6 unidades de bolinhos crocantes', 32.90, 15.00, NULL, 'ENT001', 50, 1, 1, 15, 280, '["gluten", "fish"]', '["tradicional", "popular"]', NOW(), NOW()),
(2, 1, 1, 'Bruschetta', 'Pao italiano com tomate e manjericao', 24.90, 10.00, NULL, 'ENT002', NULL, 0, 1, 10, 180, '["gluten"]', '["vegetariano"]', NOW(), NOW()),
(3, 1, 2, 'Filé à Parmegiana', 'Filé empanado com molho e queijo', 49.90, 22.00, NULL, 'PRATO001', 30, 1, 1, 25, 650, '["gluten", "lactose"]', '["popular"]', NOW(), NOW()),
(4, 1, 2, 'Salmao Grelhado', 'Salmao com legumes ao vapor', 69.90, 35.00, NULL, 'PRATO002', 20, 1, 1, 20, 420, '["fish"]', '["saudavel"]', NOW(), NOW()),
(5, 1, 3, 'Suco Natural', 'Suco de frutas naturais 500ml', 12.00, 4.00, NULL, 'BEB001', NULL, 0, 1, 5, 120, NULL, '["natural"]', NOW(), NOW()),
(6, 1, 3, 'Refrigerante Lata', 'Refrigerante 350ml', 6.00, 2.50, NULL, 'BEB002', 100, 1, 1, 2, 140, NULL, NULL, NOW(), NOW()),
(7, 1, 4, 'Petit Gateau', 'Bolo quente com sorvete', 22.00, 8.00, NULL, 'SOB001', 15, 1, 1, 15, 380, '["gluten", "lactose", "eggs"]', '["premium"]', NOW(), NOW()),

-- Produtos do Tenant 2 (Sabor da Casa)
(8, 2, 5, 'Espaguete à Carbonara', 'Massa com bacon e creme', 38.90, 16.00, NULL, 'MASS001', NULL, 0, 1, 20, 520, '["gluten", "lactose"]', '["tradicional"]', NOW(), NOW()),
(9, 2, 6, 'Picanha na Chapa', 'Picanha 300g com acompanhamentos', 59.90, 28.00, NULL, 'CARNE001', 25, 1, 1, 25, 680, NULL, '["premium"]', NOW(), NOW()),
(10, 2, 7, 'Limonada Suica', 'Limonada com leite condensado', 14.90, 5.00, NULL, 'BEB003', NULL, 0, 1, 5, 200, '["lactose"]', '["especial"]', NOW(), NOW()),

-- Produtos do Tenant 3 (Cantina Italiana)
(11, 3, 8, 'Carpaccio', 'Carpaccio de carne com rúcula', 45.00, 20.00, NULL, 'ANT001', NULL, 0, 1, 10, 280, NULL, '["premium"]', NOW(), NOW()),
(12, 3, 9, 'Lasanha Bolonhesa', 'Lasanha tradicional italiana', 42.00, 18.00, NULL, 'PAST001', 20, 1, 1, 30, 580, '["gluten", "lactose"]', '["tradicional"]', NOW(), NOW()),
(13, 3, 10, 'Pizza Margherita', 'Pizza classica com manjericao', 52.00, 22.00, NULL, 'PIZZ001', NULL, 0, 1, 25, 720, '["gluten", "lactose"]', '["tradicional"]', NOW(), NOW()),
(14, 3, 11, 'Vinho Chianti', 'Vinho tinto italiano 750ml', 89.00, 45.00, NULL, 'VIN001', 30, 1, 1, 2, 180, NULL, '["importado", "premium"]', NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: orders (Pedidos)
-- ============================================================================
INSERT INTO `orders` (`id`, `tenant_id`, `table_id`, `customer_id`, `user_id`, `order_number`, `status`, `type`, `subtotal`, `discount`, `service_fee`, `delivery_fee`, `total`, `payment_status`, `payment_method`, `notes`, `cancelled_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
-- Pedidos do Tenant 1 (Anotado)
(1, 1, 1, 1, 2, 'PED001', 'delivered', 'dine_in', 82.80, 0.00, 8.28, 0.00, 91.08, 'paid', 'Cartao de Crédito', NULL, NULL, '2025-06-01 19:30:00', '2025-06-01 18:45:00', '2025-06-01 19:30:00'),
(2, 1, 2, 2, 3, 'PED002', 'delivered', 'dine_in', 119.80, 10.00, 10.98, 0.00, 120.78, 'paid', 'PIX', 'Cliente VIP - desconto especial', NULL, '2025-06-02 20:15:00', '2025-06-02 19:00:00', '2025-06-02 20:15:00'),
(3, 1, NULL, 3, 4, 'PED003', 'delivered', 'takeaway', 49.90, 0.00, 0.00, 0.00, 49.90, 'paid', 'Dinheiro', NULL, NULL, '2025-06-03 13:20:00', '2025-06-03 13:00:00', '2025-06-03 13:20:00'),
(4, 1, 3, NULL, 2, 'PED004', 'cancelled', 'dine_in', 69.90, 0.00, 0.00, 0.00, 0.00, 'pending', NULL, NULL, 'Cliente desistiu do pedido', NULL, '2025-06-04 12:00:00', '2025-06-04 12:10:00'),

-- Pedidos do Tenant 2 (Sabor da Casa)
(5, 2, 6, 4, 5, 'PED005', 'delivered', 'dine_in', 98.80, 0.00, 9.88, 0.00, 108.68, 'paid', 'Cartao de Débito', NULL, NULL, '2025-06-05 20:00:00', '2025-06-05 19:15:00', '2025-06-05 20:00:00'),

-- Pedidos do Tenant 3 (Cantina Italiana)
(6, 3, 9, 6, 6, 'PED006', 'delivered', 'dine_in', 228.00, 22.80, 20.52, 0.00, 225.72, 'paid', 'Cartao de Crédito', 'Cliente platinum - 10% desconto', NULL, '2025-06-06 21:30:00', '2025-06-06 20:00:00', '2025-06-06 21:30:00');

-- ============================================================================
-- INSERcaO DE DADOS: order_items (Itens dos Pedidos)
-- ============================================================================
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `discount`, `total`, `notes`, `status`, `created_at`, `updated_at`) VALUES
-- Itens do Pedido 1 (PED001 - Tenant 1)
(1, 1, 1, 1, 32.90, 32.90, 0.00, 32.90, NULL, 'delivered', NOW(), NOW()),
(2, 1, 3, 1, 49.90, 49.90, 0.00, 49.90, NULL, 'delivered', NOW(), NOW()),

-- Itens do Pedido 2 (PED002 - Tenant 1)
(3, 2, 4, 1, 69.90, 69.90, 0.00, 69.90, NULL, 'delivered', NOW(), NOW()),
(4, 2, 3, 1, 49.90, 49.90, 10.00, 39.90, 'Desconto VIP', 'delivered', NOW(), NOW()),

-- Itens do Pedido 3 (PED003 - Tenant 1)
(5, 3, 3, 1, 49.90, 49.90, 0.00, 49.90, 'Para viagem', 'delivered', NOW(), NOW()),

-- Itens do Pedido 4 (PED004 - Tenant 1 - Cancelado)
(6, 4, 4, 1, 69.90, 69.90, 0.00, 69.90, NULL, 'cancelled', NOW(), NOW()),

-- Itens do Pedido 5 (PED005 - Tenant 2)
(7, 5, 8, 1, 38.90, 38.90, 0.00, 38.90, NULL, 'delivered', NOW(), NOW()),
(8, 5, 9, 1, 59.90, 59.90, 0.00, 59.90, 'Ao ponto', 'delivered', NOW(), NOW()),

-- Itens do Pedido 6 (PED006 - Tenant 3)
(9, 6, 11, 2, 45.00, 90.00, 9.00, 81.00, NULL, 'delivered', NOW(), NOW()),
(10, 6, 12, 1, 42.00, 42.00, 4.20, 37.80, NULL, 'delivered', NOW(), NOW()),
(11, 6, 13, 1, 52.00, 52.00, 5.20, 46.80, NULL, 'delivered', NOW(), NOW()),
(12, 6, 14, 1, 89.00, 89.00, 8.90, 80.10, NULL, 'delivered', NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: cash_registers (Caixas)
-- ============================================================================
INSERT INTO `cash_registers` (`id`, `tenant_id`, `name`, `opening_balance`, `current_balance`, `status`, `opened_by`, `closed_by`, `opened_at`, `closed_at`, `notes`, `created_at`, `updated_at`) VALUES
-- Caixas do Tenant 1 (Anotado)
(1, 1, 'Caixa Principal', 200.00, 200.00, 'closed', 2, 2, '2025-06-01 09:00:00', '2025-06-01 22:00:00', 'Movimento normal', NOW(), NOW()),
(2, 1, 'Caixa Principal', 200.00, 661.66, 'open', 2, NULL, '2025-06-07 09:00:00', NULL, 'Caixa aberto', NOW(), NOW()),

-- Caixas do Tenant 2 (Sabor da Casa)
(3, 2, 'Caixa 01', 150.00, 150.00, 'closed', 5, 5, '2025-06-05 10:00:00', '2025-06-05 23:00:00', NULL, NOW(), NOW()),

-- Caixas do Tenant 3 (Cantina Italiana)
(4, 3, 'Caixa Central', 300.00, 300.00, 'closed', 6, 6, '2025-06-06 11:00:00', '2025-06-06 23:30:00', NULL, NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: transactions (Transacões Financeiras)
-- ============================================================================
INSERT INTO `transactions` (`id`, `tenant_id`, `order_id`, `customer_id`, `user_id`, `type`, `category`, `amount`, `payment_method`, `description`, `status`, `transaction_date`, `notes`, `created_at`, `updated_at`) VALUES
-- Transacões do Tenant 1 (Anotado)
(1, 1, 1, 1, 2, 'income', 'Vendas', 91.08, 'Cartao de Crédito', 'Pedido #PED001', 'completed', '2025-06-01 19:30:00', NULL, NOW(), NOW()),
(2, 1, 2, 2, 3, 'income', 'Vendas', 120.78, 'PIX', 'Pedido #PED002', 'completed', '2025-06-02 20:15:00', 'Cliente VIP', NOW(), NOW()),
(3, 1, 3, 3, 4, 'income', 'Vendas', 49.90, 'Dinheiro', 'Pedido #PED003', 'completed', '2025-06-03 13:20:00', NULL, NOW(), NOW()),
(4, 1, NULL, NULL, 2, 'expense', 'Fornecedores', 850.00, 'Transferência', 'Compra de ingredientes - Fornecedor ABC', 'completed', '2025-06-04 10:00:00', 'Nota fiscal #12345', NOW(), NOW()),
(5, 1, NULL, NULL, 2, 'expense', 'Utilidades', 320.50, 'Boleto', 'Conta de energia elétrica', 'completed', '2025-06-05 14:00:00', 'Referente ao mês de maio', NOW(), NOW()),

-- Transacões do Tenant 2 (Sabor da Casa)
(6, 2, 5, 4, 5, 'income', 'Vendas', 108.68, 'Cartao de Débito', 'Pedido #PED005', 'completed', '2025-06-05 20:00:00', NULL, NOW(), NOW()),

-- Transacões do Tenant 3 (Cantina Italiana)
(7, 3, 6, 6, 6, 'income', 'Vendas', 225.72, 'Cartao de Crédito', 'Pedido #PED006', 'completed', '2025-06-06 21:30:00', 'Cliente platinum', NOW(), NOW());

-- ============================================================================
-- INSERcaO DE DADOS: cash_movements (Movimentacões de Caixa)
-- ============================================================================
INSERT INTO `cash_movements` (`id`, `tenant_id`, `cash_register_id`, `user_id`, `type`, `amount`, `description`, `notes`, `order_id`, `transaction_id`, `payment_method`, `movement_date`, `created_at`, `updated_at`) VALUES
-- Movimentacões do Tenant 1 (Anotado)
(1, 1, 1, 2, 'deposit', 200.00, 'Abertura de caixa', 'Troco inicial', NULL, NULL, 'cash', '2025-06-01 09:00:00', NOW(), NOW()),
(2, 1, 1, 2, 'sale', 91.08, 'Venda - Pedido #PED001', NULL, 1, 1, 'card', '2025-06-01 19:30:00', NOW(), NOW()),
(3, 1, 1, 3, 'sale', 120.78, 'Venda - Pedido #PED002', NULL, 2, 2, 'pix', '2025-06-02 20:15:00', NOW(), NOW()),
(4, 1, 1, 4, 'sale', 49.90, 'Venda - Pedido #PED003', NULL, 3, 3, 'cash', '2025-06-03 13:20:00', NOW(), NOW()),

-- Movimentacões do Tenant 2 (Sabor da Casa)
(5, 2, 3, 5, 'deposit', 150.00, 'Abertura de caixa', 'Troco inicial', NULL, NULL, 'cash', '2025-06-05 10:00:00', NOW(), NOW()),
(6, 2, 3, 5, 'sale', 108.68, 'Venda - Pedido #PED005', NULL, 5, 6, 'card', '2025-06-05 20:00:00', NOW(), NOW()),

-- Movimentacões do Tenant 3 (Cantina Italiana)
(7, 3, 4, 6, 'deposit', 300.00, 'Abertura de caixa', 'Troco inicial', NULL, NULL, 'cash', '2025-06-06 11:00:00', NOW(), NOW()),
(8, 3, 4, 6, 'sale', 225.72, 'Venda - Pedido #PED006', NULL, 6, 7, 'card', '2025-06-06 21:30:00', NOW(), NOW());

-- ============================================================================
-- FIM DO SCRIPT DML
-- ============================================================================