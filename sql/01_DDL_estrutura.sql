-- ============================================================================
-- PROJETO: Sistema de Gestao de Restaurantes Multi-Tenant
-- DISCIPLINA: Banco de Dados
-- ALUNO: [SEU NOME]
-- DATA: 06/02/2026
-- ============================================================================

-- Criacao do banco de dados
CREATE DATABASE IF NOT EXISTS `laravel_restaurants` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `laravel_restaurants`;

-- ============================================================================
-- TABELA: tenants
-- DESCRICaO: Armazena os restaurantes (inquilinos) do sistema multi-tenant
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do restaurante',
  `slug` VARCHAR(50) NOT NULL COMMENT 'Identificador único (URL-friendly)',
  `database_name` VARCHAR(64) DEFAULT NULL COMMENT 'Nome do banco de dados do tenant (se usar multi-database)',
  `domain` VARCHAR(255) DEFAULT NULL COMMENT 'Domínio personalizado do tenant',
  `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active' COMMENT 'Status do tenant: active=ativo, inactive=inativo, suspended=suspenso',
  `settings` JSON DEFAULT NULL COMMENT 'Configuracões personalizadas do tenant',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_slug_unique` (`slug`),
  KEY `tenants_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Restaurantes (inquilinos) do sistema';

-- ============================================================================
-- TABELA: tenant_plans
-- DESCRICAO: Planos de assinatura disponíveis para os tenants
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tenant_plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do plano',
  `slug` VARCHAR(50) NOT NULL COMMENT 'Identificador do plano',
  `description` TEXT DEFAULT NULL COMMENT 'Descricao do plano',
  `price` DECIMAL(10,2) NOT NULL COMMENT 'Preco mensal do plano',
  `billing_cycle` ENUM('monthly', 'quarterly', 'yearly') NOT NULL DEFAULT 'monthly' COMMENT 'Ciclo de cobranca',
  `trial_days` INT UNSIGNED DEFAULT 0 COMMENT 'Dias de trial gratuito',
  `features` JSON DEFAULT NULL COMMENT 'Recursos incluídos no plano',
  `limits` JSON DEFAULT NULL COMMENT 'Limites do plano (usuarios, mesas, produtos, etc)',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Se o plano esta ativo para contratacao',
  `sort_order` INT UNSIGNED DEFAULT 0 COMMENT 'Ordem de exibicao',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenant_plans_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Planos de assinatura para os restaurantes';

-- ============================================================================
-- TABELA: tenant_subscriptions
-- Descricao: Assinaturas ativas dos tenants aos planos
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tenant_subscriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `tenant_plan_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('active', 'cancelled', 'expired', 'suspended') NOT NULL DEFAULT 'active' COMMENT 'Status da assinatura',
  `starts_at` DATE NOT NULL COMMENT 'Data de início da assinatura',
  `expires_at` DATE DEFAULT NULL COMMENT 'Data de expiracao da assinatura',
  `amount` DECIMAL(10,2) NOT NULL COMMENT 'Valor cobrado',
  `payment_method` VARCHAR(50) DEFAULT NULL COMMENT 'Método de pagamento',
  `last_payment_date` DATE DEFAULT NULL COMMENT 'Data do último pagamento',
  `next_payment_date` DATE DEFAULT NULL COMMENT 'Data do próximo pagamento',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões sobre a assinatura',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_subscriptions_tenant_id_foreign` (`tenant_id`),
  KEY `tenant_subscriptions_tenant_plan_id_foreign` (`tenant_plan_id`),
  KEY `tenant_subscriptions_status_expires_at_index` (`status`, `expires_at`),
  CONSTRAINT `tenant_subscriptions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tenant_subscriptions_tenant_plan_id_foreign` FOREIGN KEY (`tenant_plan_id`) REFERENCES `tenant_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Assinaturas dos restaurantes aos planos';

-- ============================================================================
-- TABELA: customers
-- Descricao: Clientes dos restaurantes (sistema de fidelidade)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do cliente',
  `email` VARCHAR(100) DEFAULT NULL COMMENT 'E-mail do cliente',
  `phone` VARCHAR(20) DEFAULT NULL COMMENT 'Telefone do cliente',
  `cpf` VARCHAR(14) DEFAULT NULL COMMENT 'CPF do cliente',
  `birth_date` DATE DEFAULT NULL COMMENT 'Data de nascimento',
  `points` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Pontos acumulados no programa de fidelidade',
  `level` ENUM('bronze', 'silver', 'gold', 'platinum') NOT NULL DEFAULT 'bronze' COMMENT 'Nível no programa de fidelidade',
  `status` ENUM('active', 'inactive', 'blocked') NOT NULL DEFAULT 'active' COMMENT 'Status do cliente',
  `address` TEXT DEFAULT NULL COMMENT 'Endereco completo do cliente',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões sobre o cliente',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_tenant_id_status_index` (`tenant_id`, `status`),
  KEY `customers_tenant_id_level_index` (`tenant_id`, `level`),
  KEY `customers_email_index` (`email`),
  KEY `customers_phone_index` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Clientes dos restaurantes';

-- ============================================================================
-- TABELA: users
-- Descricao: Usuarios do sistema (funcionarios dos restaurantes)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'NULL para super admin',
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do usuario',
  `email` VARCHAR(100) NOT NULL COMMENT 'E-mail do usuario',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'Senha criptografada',
  `role` ENUM('admin', 'manager', 'employee') NOT NULL DEFAULT 'employee' COMMENT 'Papel do usuario: admin=administrador, manager=gerente, employee=funcionario',
  `avatar` VARCHAR(255) DEFAULT NULL COMMENT 'URL da foto do usuario',
  `permissions` JSON DEFAULT NULL COMMENT 'Permissões específicas do usuario',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active' COMMENT 'Status do usuario',
  `last_login` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data do último login',
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_tenant_id_email_index` (`tenant_id`, `email`),
  KEY `users_tenant_id_status_index` (`tenant_id`, `status`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Usuarios do sistema (funcionarios)';

-- ============================================================================
-- TABELA: tables
-- Descricao: Mesas dos restaurantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS `tables` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `number` VARCHAR(10) NOT NULL COMMENT 'Número ou identificador da mesa',
  `capacity` INT UNSIGNED NOT NULL COMMENT 'Capacidade de pessoas',
  `status` ENUM('available', 'occupied', 'reserved', 'maintenance') NOT NULL DEFAULT 'available' COMMENT 'Status da mesa: available=livre, occupied=ocupada, reserved=reservada, maintenance=manutencao',
  `location` VARCHAR(100) DEFAULT NULL COMMENT 'Localizacao no restaurante',
  `qr_code` VARCHAR(255) DEFAULT NULL COMMENT 'Código QR da mesa para pedidos',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões sobre a mesa',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tables_tenant_id_status_index` (`tenant_id`, `status`),
  KEY `tables_tenant_id_number_index` (`tenant_id`, `number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Mesas dos restaurantes';

-- ============================================================================
-- TABELA: categories
-- Descricao: Categorias de produtos
-- ============================================================================
CREATE TABLE IF NOT EXISTS `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome da categoria',
  `description` TEXT DEFAULT NULL COMMENT 'Descricao da categoria',
  `icon` VARCHAR(255) DEFAULT NULL COMMENT 'Ícone da categoria',
  `color` VARCHAR(7) DEFAULT NULL COMMENT 'Cor da categoria (hex)',
  `sort_order` INT UNSIGNED DEFAULT 0 COMMENT 'Ordem de exibicao',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Se a categoria esta ativa',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_tenant_id_is_active_index` (`tenant_id`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Categorias de produtos';

-- ============================================================================
-- TABELA: products
-- Descricao: Produtos/itens do cardapio dos restaurantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do produto',
  `description` TEXT DEFAULT NULL COMMENT 'Descricao do produto',
  `price` DECIMAL(10,2) NOT NULL COMMENT 'Preco do produto',
  `cost` DECIMAL(10,2) DEFAULT NULL COMMENT 'Custo do produto',
  `image` VARCHAR(255) DEFAULT NULL COMMENT 'URL da imagem do produto',
  `sku` VARCHAR(50) DEFAULT NULL COMMENT 'Código SKU do produto',
  `stock_quantity` INT DEFAULT NULL COMMENT 'Quantidade em estoque',
  `stock_control` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Se controla estoque',
  `is_available` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Se esta disponível para venda',
  `preparation_time` INT UNSIGNED DEFAULT NULL COMMENT 'Tempo de preparo em minutos',
  `calories` INT UNSIGNED DEFAULT NULL COMMENT 'Calorias do produto',
  `allergens` JSON DEFAULT NULL COMMENT 'Lista de alergênicos',
  `tags` JSON DEFAULT NULL COMMENT 'Tags do produto',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_tenant_id_category_id_index` (`tenant_id`, `category_id`),
  KEY `products_tenant_id_is_available_index` (`tenant_id`, `is_available`),
  KEY `products_sku_index` (`sku`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Produtos do cardapio';

-- ============================================================================
-- TABELA: orders
-- Descricao: Pedidos realizados nos restaurantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `table_id` BIGINT UNSIGNED DEFAULT NULL,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'Usuario que registrou o pedido',
  `order_number` VARCHAR(50) NOT NULL COMMENT 'Número do pedido',
  `status` ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Status do pedido',
  `type` ENUM('dine_in', 'takeaway', 'delivery') NOT NULL DEFAULT 'dine_in' COMMENT 'Tipo do pedido',
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal do pedido',
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Desconto aplicado',
  `service_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Taxa de servico',
  `delivery_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Taxa de entrega',
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total do pedido',
  `payment_status` ENUM('pending', 'paid', 'partially_paid', 'refunded') NOT NULL DEFAULT 'pending' COMMENT 'Status do pagamento',
  `payment_method` VARCHAR(50) DEFAULT NULL COMMENT 'Método de pagamento',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões do pedido',
  `cancelled_reason` TEXT DEFAULT NULL COMMENT 'Motivo do cancelamento',
  `delivered_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data/hora da entrega',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_tenant_id_status_index` (`tenant_id`, `status`),
  KEY `orders_tenant_id_created_at_index` (`tenant_id`, `created_at`),
  KEY `orders_table_id_foreign` (`table_id`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_order_number_index` (`order_number`),
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Pedidos dos restaurantes';

-- ============================================================================
-- TABELA: order_items
-- Descricao: Itens dos pedidos (relacao N:N entre orders e products)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL COMMENT 'Quantidade do produto',
  `unit_price` DECIMAL(10,2) NOT NULL COMMENT 'Preco unitario no momento do pedido',
  `subtotal` DECIMAL(10,2) NOT NULL COMMENT 'Subtotal (quantidade * preco unitario)',
  `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Desconto no item',
  `total` DECIMAL(10,2) NOT NULL COMMENT 'Total do item',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões do item',
  `status` ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Status do item',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_status_index` (`status`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Itens dos pedidos';

-- ============================================================================
-- TABELA: transactions
-- Descricao: Transacões financeiras dos restaurantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `order_id` BIGINT UNSIGNED DEFAULT NULL,
  `customer_id` BIGINT UNSIGNED DEFAULT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `type` ENUM('income', 'expense') NOT NULL COMMENT 'Tipo da transacao',
  `category` VARCHAR(50) NOT NULL COMMENT 'Categoria da transacao',
  `amount` DECIMAL(10,2) NOT NULL COMMENT 'Valor da transacao',
  `payment_method` VARCHAR(50) DEFAULT NULL COMMENT 'Método de pagamento',
  `description` TEXT NOT NULL COMMENT 'Descricao da transacao',
  `status` ENUM('pending', 'completed', 'cancelled', 'refunded') NOT NULL DEFAULT 'completed' COMMENT 'Status da transacao',
  `transaction_date` TIMESTAMP NOT NULL COMMENT 'Data da transacao',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_tenant_id_type_index` (`tenant_id`, `type`),
  KEY `transactions_tenant_id_transaction_date_index` (`tenant_id`, `transaction_date`),
  KEY `transactions_order_id_foreign` (`order_id`),
  KEY `transactions_customer_id_foreign` (`customer_id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Transacões financeiras';

-- ============================================================================
-- TABELA: cash_registers
-- Descricao: Caixas dos restaurantes
-- ============================================================================
CREATE TABLE IF NOT EXISTS `cash_registers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nome do caixa',
  `opening_balance` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Saldo de abertura',
  `current_balance` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Saldo atual',
  `status` ENUM('open', 'closed') NOT NULL DEFAULT 'closed' COMMENT 'Status do caixa',
  `opened_by` BIGINT UNSIGNED DEFAULT NULL COMMENT 'Usuario que abriu',
  `closed_by` BIGINT UNSIGNED DEFAULT NULL COMMENT 'Usuario que fechou',
  `opened_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data/hora abertura',
  `closed_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Data/hora fechamento',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_registers_tenant_id_status_index` (`tenant_id`, `status`),
  KEY `cash_registers_opened_by_foreign` (`opened_by`),
  KEY `cash_registers_closed_by_foreign` (`closed_by`),
  CONSTRAINT `cash_registers_opened_by_foreign` FOREIGN KEY (`opened_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cash_registers_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Caixas dos restaurantes';

-- ============================================================================
-- TABELA: cash_movements
-- Descricao: Movimentacões de caixa
-- ============================================================================
CREATE TABLE IF NOT EXISTS `cash_movements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tenant_id` BIGINT UNSIGNED DEFAULT NULL,
  `cash_register_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('deposit', 'withdrawal', 'sale', 'expense') NOT NULL COMMENT 'Tipo da movimentacao',
  `amount` DECIMAL(10,2) NOT NULL COMMENT 'Valor da movimentacao',
  `description` VARCHAR(255) NOT NULL COMMENT 'Descricao',
  `notes` TEXT DEFAULT NULL COMMENT 'Observacões',
  `order_id` BIGINT UNSIGNED DEFAULT NULL,
  `transaction_id` BIGINT UNSIGNED DEFAULT NULL,
  `payment_method` ENUM('cash', 'card', 'pix', 'other') DEFAULT NULL COMMENT 'Método de pagamento',
  `movement_date` TIMESTAMP NOT NULL COMMENT 'Data da movimentacao',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_movements_tenant_id_cash_register_id_index` (`tenant_id`, `cash_register_id`),
  KEY `cash_movements_cash_register_id_type_index` (`cash_register_id`, `type`),
  KEY `cash_movements_cash_register_id_movement_date_index` (`cash_register_id`, `movement_date`),
  KEY `cash_movements_user_id_movement_date_index` (`user_id`, `movement_date`),
  KEY `cash_movements_order_id_foreign` (`order_id`),
  KEY `cash_movements_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `cash_movements_cash_register_id_foreign` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cash_movements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cash_movements_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cash_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Movimentacões de caixa';

-- ============================================================================
-- TABELAS DO LARAVEL (Sistema de Cache e Autenticacao)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Sistema de cache do Laravel';

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Locks do sistema de cache';

-- ============================================================================
-- FIM DO SCRIPT DDL
-- ============================================================================