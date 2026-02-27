-- ============================================================================
-- PROJETO: Sistema de Gestao de Restaurantes Multi-Tenant
-- SCRIPT DML COMPLETO - Versao corrigida (sem acentos, sem erros de encoding)
-- DATA: 27/02/2026
-- ============================================================================

USE `laravel_restaurants`;

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ============================================================================
-- TENANT_PLANS
-- ============================================================================
INSERT INTO `tenant_plans` (`id`, `name`, `slug`, `description`, `price`, `billing_cycle`, `trial_days`, `features`, `limits`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Basico',        'basico',        'Plano basico para pequenos restaurantes',             49.00,  'monthly', 15, '["basic_reports","email_support","daily_backup"]',                                                              '{"users":3,"tables":10,"products":50,"storage_mb":500}',    1, 1, NOW(), NOW()),
(2, 'Intermediario', 'intermediario', 'Plano intermediario para restaurantes em crescimento', 147.00, 'monthly', 15, '["basic_reports","advanced_reports","priority_support","daily_backup"]',                                        '{"users":10,"tables":20,"products":-1,"storage_mb":2000}',  1, 2, NOW(), NOW()),
(3, 'Profissional',  'profissional',  'Plano completo para restaurantes estabelecidos',       297.00, 'monthly', 15, '["basic_reports","advanced_reports","custom_reports","priority_support","hourly_backup","api_access"]',          '{"users":-1,"tables":-1,"products":-1,"storage_mb":10000}', 1, 3, NOW(), NOW());

-- ============================================================================
-- TENANTS (Restaurantes)
-- ============================================================================
INSERT INTO `tenants` (`id`, `name`, `slug`, `database_name`, `domain`, `status`, `settings`, `created_at`, `updated_at`) VALUES
(1, 'Anotado Restaurante', 'anotado',          'tenant_anotado', NULL, 'active', '{"theme":"blue","currency":"BRL","timezone":"America/Sao_Paulo"}',   '2025-05-30 02:05:57', '2025-05-30 02:05:57'),
(2, 'Sabor da Casa',       'sabor-da-casa',    'tenant_sabor',   NULL, 'active', '{"theme":"green","currency":"BRL","timezone":"America/Sao_Paulo"}',  '2025-06-01 10:00:00', '2025-06-01 10:00:00'),
(3, 'Cantina Italiana',    'cantina-italiana', 'tenant_cantina', NULL, 'active', '{"theme":"red","currency":"BRL","timezone":"America/Sao_Paulo"}',    '2025-06-15 11:00:00', '2025-06-15 11:00:00');

-- ============================================================================
-- TENANT_SUBSCRIPTIONS
-- ============================================================================
INSERT INTO `tenant_subscriptions` (`id`, `tenant_id`, `tenant_plan_id`, `status`, `starts_at`, `expires_at`, `amount`, `payment_method`, `last_payment_date`, `next_payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'active', '2025-05-30', '2026-05-31', 147.00, 'pix',    '2025-05-30', '2026-05-31', NOW(), NOW()),
(2, 2, 1, 'active', '2025-06-01', '2026-06-01',  49.00, 'card',   '2025-06-01', '2026-06-01', NOW(), NOW()),
(3, 3, 3, 'active', '2025-06-15', '2026-06-15', 297.00, 'boleto', '2025-06-15', '2026-06-15', NOW(), NOW());

-- ============================================================================
-- USERS
-- ============================================================================
INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `email_verified_at`, `password`, `role`, `permissions`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Super Admin',        'admin@servefacil.com',      NOW(), '$2y$12$0jaCGxf3F9yPtEggCSmGMul.ZaWf2bU1Ex.BavLYRzVGW68pJ0Eoq', 'admin',    '["super_admin"]',                                                    'active', NOW(), NOW()),
(2, 1,    'Admin Anotado',      'admin@anotado.com',         NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin',    '["admin.access","orders.manage","products.manage","reports.view"]',  'active', NOW(), NOW()),
(3, 1,    'Gerente Anotado',    'gerente@anotado.com',       NOW(), '$2y$12$l1DadmC.QYGnYkBhjLdJUu0xVQCy40Cu.o68XpHj5tK12JA67FD5.', 'manager',  '["orders.manage","customers.manage","tables.manage"]',               'active', NOW(), NOW()),
(4, 1,    'Funcionario Anotado','funcionario@anotado.com',   NOW(), '$2y$12$elknLSNR/58JupWSVfgNfOy5C1gNIVAtNdZrWojraodSfI7d05DCu',  'employee', '["orders.view","orders.create"]',                                    'active', NOW(), NOW()),
(5, 2,    'Admin Sabor',        'admin@sabordacasa.com',     NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin',    '["admin.access","orders.manage","products.manage","reports.view"]',  'active', NOW(), NOW()),
(6, 3,    'Admin Cantina',      'admin@cantina.com',         NOW(), '$2y$12$CrrLJ8W9wuWAEpRrP0IGHevrw8IE8uC3qLEYDZ7XSucuHi.sZxP8S', 'admin',    '["admin.access","orders.manage","products.manage","reports.view"]',  'active', NOW(), NOW());

-- ============================================================================
-- CUSTOMERS (56 registros)
-- ============================================================================
INSERT INTO `customers` (`id`, `tenant_id`, `name`, `email`, `phone`, `cpf`, `birth_date`, `points`, `level`, `status`, `address`, `created_at`, `updated_at`) VALUES
-- Clientes originais do Tenant 1
(1,  1, 'Joao Silva',           'joao.silva@email.com',     '(81) 98888-1111', '111.222.333-44', '1985-03-15',  450, 'silver',   'active', 'Rua das Flores, 123 - Recife/PE',           NOW(), NOW()),
(2,  1, 'Maria Santos',         'maria.santos@email.com',   '(81) 98888-2222', '222.333.444-55', '1990-07-22', 1250, 'gold',     'active', 'Av. Boa Viagem, 456 - Recife/PE',           NOW(), NOW()),
(3,  1, 'Pedro Oliveira',       'pedro.oliveira@email.com', '(81) 98888-3333', '333.444.555-66', '1988-11-10',  180, 'bronze',   'active', 'Rua do Sol, 789 - Caruaru/PE',              NOW(), NOW()),
-- Clientes originais do Tenant 2
(4,  2, 'Ana Paula',            'ana.paula@email.com',      '(81) 97777-1111', '444.555.666-77', '1992-05-18',  320, 'silver',   'active', 'Rua da Paz, 321 - Olinda/PE',               NOW(), NOW()),
(5,  2, 'Carlos Eduardo',       'carlos.eduardo@email.com', '(81) 97777-2222', '555.666.777-88', '1987-09-25',   85, 'bronze',   'active', 'Av. Central, 654 - Jaboatao/PE',            NOW(), NOW()),
-- Clientes originais do Tenant 3
(6,  3, 'Fernanda Costa',       'fernanda.costa@email.com', '(11) 96666-1111', '666.777.888-99', '1991-12-08', 2100, 'platinum', 'active', 'Rua Italia, 987 - Sao Paulo/SP',            NOW(), NOW()),
-- Tenant 1 - novos clientes
(7,  1, 'Lucas Almeida',        'lucas.almeida@gmail.com',  '(81) 99111-0001', '001.001.001-01', '1993-01-10',  320, 'silver',   'active', 'Rua Amaro Bezerra, 10 - Recife/PE',         '2025-07-01 10:00:00', '2025-07-01 10:00:00'),
(8,  1, 'Isabela Ferreira',     'isabela.f@hotmail.com',    '(81) 99111-0002', '002.002.002-02', '1995-04-22',   90, 'bronze',   'active', 'Av. Norte, 200 - Recife/PE',                '2025-07-02 10:00:00', '2025-07-02 10:00:00'),
(9,  1, 'Rafael Mendes',        'rafael.mendes@yahoo.com',  '(81) 99111-0003', '003.003.003-03', '1988-09-05', 1800, 'gold',     'active', 'Rua do Hospicio, 33 - Recife/PE',           '2025-07-03 10:00:00', '2025-07-03 10:00:00'),
(10, 1, 'Camila Torres',        'camila.torres@gmail.com',  '(81) 99111-0004', '004.004.004-04', '1997-12-18',   45, 'bronze',   'active', 'Rua Gervasio Pires, 77 - Recife/PE',        '2025-07-04 10:00:00', '2025-07-04 10:00:00'),
(11, 1, 'Felipe Nascimento',    'felipe.nasc@gmail.com',    '(81) 99111-0005', '005.005.005-05', '1990-06-30',  620, 'silver',   'active', 'Av. Boa Viagem, 1500 - Recife/PE',          '2025-07-05 10:00:00', '2025-07-05 10:00:00'),
(12, 1, 'Leticia Barros',       'leticia.b@gmail.com',      '(81) 99111-0006', '006.006.006-06', '1994-03-14', 2800, 'platinum', 'active', 'Rua Setubal, 900 - Recife/PE',              '2025-07-06 10:00:00', '2025-07-06 10:00:00'),
(13, 1, 'Thiago Cardoso',       'thiago.cardoso@outlook.com','(81) 99111-0007','007.007.007-07', '1986-11-02',  150, 'bronze',   'active', 'Rua Corredor do Bispo, 5 - Recife/PE',      '2025-07-07 10:00:00', '2025-07-07 10:00:00'),
(14, 1, 'Mariana Lima',         'mari.lima@gmail.com',      '(81) 99111-0008', '008.008.008-08', '1999-08-19',  430, 'silver',   'active', 'Rua Ernesto de Paula, 44 - Recife/PE',      '2025-07-08 10:00:00', '2025-07-08 10:00:00'),
(15, 1, 'Gustavo Rocha',        'gus.rocha@gmail.com',      '(81) 99111-0009', '009.009.009-09', '1991-05-27', 1100, 'gold',     'active', 'Av. Caxanga, 700 - Recife/PE',              '2025-07-09 10:00:00', '2025-07-09 10:00:00'),
(16, 1, 'Aline Souza',          'aline.souza@gmail.com',    '(81) 99111-0010', '010.010.010-10', '1996-02-11',   75, 'bronze',   'inactive','Rua da Aurora, 120 - Recife/PE',           '2025-07-10 10:00:00', '2025-07-10 10:00:00'),
(17, 1, 'Bruno Cavalcanti',     'bruno.cav@gmail.com',      '(81) 99111-0011', '011.011.011-11', '1984-07-08',  940, 'gold',     'active', 'Rua Tobias Barreto, 88 - Recife/PE',        '2025-07-11 10:00:00', '2025-07-11 10:00:00'),
(18, 1, 'Priscila Moura',       'pri.moura@gmail.com',      '(81) 99111-0012', '012.012.012-12', '1993-10-25',  260, 'silver',   'active', 'Av. Abdias de Carvalho, 55 - Recife/PE',    '2025-07-12 10:00:00', '2025-07-12 10:00:00'),
(19, 1, 'Diego Lopes',          'diego.lopes@gmail.com',    '(81) 99111-0013', '013.013.013-13', '1989-04-16',   30, 'bronze',   'active', 'Rua da Palma, 18 - Recife/PE',              '2025-07-13 10:00:00', '2025-07-13 10:00:00'),
(20, 1, 'Natalia Cruz',         'natalia.cruz@gmail.com',   '(81) 99111-0014', '014.014.014-14', '1998-01-30',  510, 'silver',   'active', 'Rua Capiberibe, 302 - Recife/PE',           '2025-07-14 10:00:00', '2025-07-14 10:00:00'),
(21, 1, 'Rodrigo Freitas',      'rodrigo.f@gmail.com',      '(81) 99111-0015', '015.015.015-15', '1985-09-12', 1450, 'gold',     'active', 'Av. Recife, 400 - Recife/PE',               '2025-07-15 10:00:00', '2025-07-15 10:00:00'),
(22, 1, 'Vanessa Martins',      'vanessa.m@gmail.com',      '(81) 99111-0016', '016.016.016-16', '1992-06-04',  190, 'bronze',   'active', 'Rua Henrique Dias, 64 - Recife/PE',         '2025-07-16 10:00:00', '2025-07-16 10:00:00'),
(23, 1, 'Eduardo Pinto',        'edu.pinto@gmail.com',      '(81) 99111-0017', '017.017.017-17', '1987-12-21',  730, 'silver',   'active', 'Av. Dantas Barreto, 150 - Recife/PE',       '2025-07-17 10:00:00', '2025-07-17 10:00:00'),
(24, 1, 'Juliana Ramos',        'ju.ramos@gmail.com',       '(81) 99111-0018', '018.018.018-18', '1995-03-07',  880, 'silver',   'active', 'Rua Imperatriz, 22 - Recife/PE',            '2025-07-18 10:00:00', '2025-07-18 10:00:00'),
(25, 1, 'Anderson Vieira',      'anderson.v@gmail.com',     '(81) 99111-0019', '019.019.019-19', '1983-08-15',   60, 'bronze',   'active', 'Rua da Saudade, 99 - Recife/PE',            '2025-07-19 10:00:00', '2025-07-19 10:00:00'),
(26, 1, 'Tatiane Gomes',        'tati.gomes@gmail.com',     '(81) 99111-0020', '020.020.020-20', '1997-11-28', 3200, 'platinum', 'active', 'Av. Agamenon Magalhaes, 700 - Recife/PE',   '2025-07-20 10:00:00', '2025-07-20 10:00:00'),
-- Tenant 2 - novos clientes
(27, 2, 'Henrique Dias',        'henrique.d@gmail.com',     '(81) 98222-0001', '021.021.021-21', '1990-02-14',  210, 'silver',   'active', 'Rua Olinda Velha, 30 - Olinda/PE',          '2025-07-01 11:00:00', '2025-07-01 11:00:00'),
(28, 2, 'Sabrina Nunes',        'sabrina.n@gmail.com',      '(81) 98222-0002', '022.022.022-22', '1994-07-19',   55, 'bronze',   'active', 'Av. Liberdade, 88 - Olinda/PE',             '2025-07-02 11:00:00', '2025-07-02 11:00:00'),
(29, 2, 'Marcos Teixeira',      'marcos.t@gmail.com',       '(81) 98222-0003', '023.023.023-23', '1986-04-03', 1320, 'gold',     'active', 'Rua do Amparo, 15 - Olinda/PE',             '2025-07-03 11:00:00', '2025-07-03 11:00:00'),
(30, 2, 'Renata Correia',       'renata.c@gmail.com',       '(81) 98222-0004', '024.024.024-24', '1992-10-11',  480, 'silver',   'active', 'Av. Getulio Vargas, 300 - Olinda/PE',       '2025-07-04 11:00:00', '2025-07-04 11:00:00'),
(31, 2, 'Paulo Henrique',       'paulo.h@gmail.com',        '(81) 98222-0005', '025.025.025-25', '1988-01-25',   90, 'bronze',   'active', 'Rua Bom Jesus, 44 - Olinda/PE',             '2025-07-05 11:00:00', '2025-07-05 11:00:00'),
(32, 2, 'Claudia Barbosa',      'claudia.b@gmail.com',      '(81) 98222-0006', '026.026.026-26', '1985-05-17', 2100, 'platinum', 'active', 'Rua Prudente de Morais, 200 - Olinda/PE',   '2025-07-06 11:00:00', '2025-07-06 11:00:00'),
(33, 2, 'Sandro Araujo',        'sandro.a@gmail.com',       '(81) 98222-0007', '027.027.027-27', '1991-08-29',  160, 'bronze',   'active', 'Av. Gov. Agamenon, 100 - Olinda/PE',        '2025-07-07 11:00:00', '2025-07-07 11:00:00'),
(34, 2, 'Bianca Carvalho',      'bianca.c@gmail.com',       '(81) 98222-0008', '028.028.028-28', '1996-12-05',  390, 'silver',   'active', 'Rua do Sol, 70 - Olinda/PE',                '2025-07-08 11:00:00', '2025-07-08 11:00:00'),
(35, 2, 'Wesley Cunha',         'wesley.c@gmail.com',       '(81) 98222-0009', '029.029.029-29', '1993-03-22',  720, 'silver',   'active', 'Rua Sigismundo Goncalves, 55 - Olinda/PE',  '2025-07-09 11:00:00', '2025-07-09 11:00:00'),
(36, 2, 'Elaine Monteiro',      'elaine.m@gmail.com',       '(81) 98222-0010', '030.030.030-30', '1989-09-14',   40, 'bronze',   'inactive','Rua da Linha, 188 - Olinda/PE',            '2025-07-10 11:00:00', '2025-07-10 11:00:00'),
(37, 2, 'Fabio Andrade',        'fabio.a@gmail.com',        '(81) 98222-0011', '031.031.031-31', '1984-06-08',  980, 'gold',     'active', 'Av. Presidente Kennedy, 410 - Olinda/PE',   '2025-07-11 11:00:00', '2025-07-11 11:00:00'),
(38, 2, 'Cristina Farias',      'cris.f@gmail.com',         '(81) 98222-0012', '032.032.032-32', '1997-01-31',  130, 'bronze',   'active', 'Rua 27 de Janeiro, 9 - Olinda/PE',          '2025-07-12 11:00:00', '2025-07-12 11:00:00'),
(39, 2, 'Alexandre Melo',       'alex.melo@gmail.com',      '(81) 98222-0013', '033.033.033-33', '1987-07-20',  560, 'silver',   'active', 'Av. Tacaruna, 222 - Olinda/PE',             '2025-07-13 11:00:00', '2025-07-13 11:00:00'),
(40, 2, 'Patricia Vasconcelos', 'patricia.v@gmail.com',     '(81) 98222-0014', '034.034.034-34', '1995-04-09', 1650, 'gold',     'active', 'Rua do Bonfim, 60 - Olinda/PE',             '2025-07-14 11:00:00', '2025-07-14 11:00:00'),
(41, 2, 'Otavio Leite',         'otavio.l@gmail.com',       '(81) 98222-0015', '035.035.035-35', '1998-11-03',   25, 'bronze',   'active', 'Rua Rio Branco, 500 - Olinda/PE',           '2025-07-15 11:00:00', '2025-07-15 11:00:00'),
-- Tenant 3 - novos clientes
(42, 3, 'Roberto Figueiredo',   'roberto.fig@gmail.com',    '(11) 97333-0001', '036.036.036-36', '1982-03-18', 4100, 'platinum', 'active', 'Rua Itapeva, 500 - Sao Paulo/SP',           '2025-07-01 12:00:00', '2025-07-01 12:00:00'),
(43, 3, 'Amanda Silveira',      'amanda.s@gmail.com',       '(11) 97333-0002', '037.037.037-37', '1994-08-24',  780, 'silver',   'active', 'Av. Paulista, 1200 - Sao Paulo/SP',         '2025-07-02 12:00:00', '2025-07-02 12:00:00'),
(44, 3, 'Cesar Batista',        'cesar.b@gmail.com',        '(11) 97333-0003', '038.038.038-38', '1979-01-07', 1900, 'gold',     'active', 'Rua Augusta, 800 - Sao Paulo/SP',           '2025-07-03 12:00:00', '2025-07-03 12:00:00'),
(45, 3, 'Luciana Prado',        'luciana.p@gmail.com',      '(11) 97333-0004', '039.039.039-39', '1991-05-30',  350, 'silver',   'active', 'Rua Oscar Freire, 77 - Sao Paulo/SP',       '2025-07-04 12:00:00', '2025-07-04 12:00:00'),
(46, 3, 'Vitor Macedo',         'vitor.m@gmail.com',        '(11) 97333-0005', '040.040.040-40', '1996-10-15',  120, 'bronze',   'active', 'Al. Santos, 400 - Sao Paulo/SP',            '2025-07-05 12:00:00', '2025-07-05 12:00:00'),
(47, 3, 'Soraya Campos',        'soraya.c@gmail.com',       '(11) 97333-0006', '041.041.041-41', '1988-06-21', 2500, 'platinum', 'active', 'Rua Haddock Lobo, 150 - Sao Paulo/SP',      '2025-07-06 12:00:00', '2025-07-06 12:00:00'),
(48, 3, 'Davi Ribeiro',         'davi.r@gmail.com',         '(11) 97333-0007', '042.042.042-42', '1993-02-09',  600, 'silver',   'active', 'Rua Pamplona, 90 - Sao Paulo/SP',           '2025-07-07 12:00:00', '2025-07-07 12:00:00'),
(49, 3, 'Monique Ferraz',       'monique.f@gmail.com',      '(11) 97333-0008', '043.043.043-43', '1985-09-16', 1300, 'gold',     'active', 'Av. Brasil, 600 - Sao Paulo/SP',            '2025-07-08 12:00:00', '2025-07-08 12:00:00'),
(50, 3, 'Leandro Neves',        'leandro.n@gmail.com',      '(11) 97333-0009', '044.044.044-44', '1990-04-04',   80, 'bronze',   'active', 'Rua Bela Cintra, 300 - Sao Paulo/SP',       '2025-07-09 12:00:00', '2025-07-09 12:00:00'),
(51, 3, 'Simone Queiroz',       'simone.q@gmail.com',       '(11) 97333-0010', '045.045.045-45', '1987-12-12', 1700, 'gold',     'active', 'Rua Consolacao, 1100 - Sao Paulo/SP',       '2025-07-10 12:00:00', '2025-07-10 12:00:00'),
(52, 3, 'Nathan Peixoto',       'nathan.p@gmail.com',       '(11) 97333-0011', '046.046.046-46', '1999-07-01',   45, 'bronze',   'active', 'Rua Frei Caneca, 55 - Sao Paulo/SP',        '2025-07-11 12:00:00', '2025-07-11 12:00:00'),
(53, 3, 'Elisa Tavares',        'elisa.t@gmail.com',        '(11) 97333-0012', '047.047.047-47', '1992-03-25',  950, 'gold',     'active', 'Av. Reboucas, 230 - Sao Paulo/SP',          '2025-07-12 12:00:00', '2025-07-12 12:00:00'),
(54, 3, 'Murilo Santana',       'murilo.s@gmail.com',       '(11) 97333-0013', '048.048.048-48', '1983-11-18',  400, 'silver',   'active', 'Rua Teodoro Sampaio, 410 - Sao Paulo/SP',   '2025-07-13 12:00:00', '2025-07-13 12:00:00'),
(55, 3, 'Giovanna Castro',      'gi.castro@gmail.com',      '(11) 97333-0014', '049.049.049-49', '1997-08-06',  220, 'silver',   'active', 'Rua Dr. Melo Alves, 80 - Sao Paulo/SP',     '2025-07-14 12:00:00', '2025-07-14 12:00:00'),
(56, 3, 'Caio Assis',           'caio.assis@gmail.com',     '(11) 97333-0015', '050.050.050-50', '1995-05-13', 3800, 'platinum', 'active', 'Al. Lorena, 70 - Sao Paulo/SP',             '2025-07-15 12:00:00', '2025-07-15 12:00:00');

-- ============================================================================
-- TABLES (Mesas)
-- ============================================================================
INSERT INTO `tables` (`id`, `tenant_id`, `number`, `capacity`, `status`, `location`, `qr_code`, `created_at`, `updated_at`) VALUES
(1,  1, '01', 4, 'available', 'area interna',  'QR_TABLE_01', NOW(), NOW()),
(2,  1, '02', 4, 'available', 'area interna',  'QR_TABLE_02', NOW(), NOW()),
(3,  1, '03', 2, 'available', 'area interna',  'QR_TABLE_03', NOW(), NOW()),
(4,  1, '04', 6, 'available', 'area externa',  'QR_TABLE_04', NOW(), NOW()),
(5,  1, '05', 8, 'available', 'area VIP',      'QR_TABLE_05', NOW(), NOW()),
(6,  2, '10', 4, 'available', 'Salao principal','QR_TABLE_10', NOW(), NOW()),
(7,  2, '11', 2, 'available', 'Salao principal','QR_TABLE_11', NOW(), NOW()),
(8,  2, '12', 6, 'available', 'Varanda',        'QR_TABLE_12', NOW(), NOW()),
(9,  3, 'A1', 4, 'available', 'Terreo',         'QR_TABLE_A1', NOW(), NOW()),
(10, 3, 'A2', 2, 'available', 'Terreo',         'QR_TABLE_A2', NOW(), NOW()),
(11, 3, 'B1', 6, 'available', 'Mezanino',       'QR_TABLE_B1', NOW(), NOW());

-- ============================================================================
-- CATEGORIES
-- ============================================================================
INSERT INTO `categories` (`id`, `tenant_id`, `name`, `description`, `color`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1,  1, 'Entradas',        'Aperitivos e entradas',       '#FF5722', 1, 1, NOW(), NOW()),
(2,  1, 'Pratos Principais','Pratos principais do cardapio','#4CAF50', 2, 1, NOW(), NOW()),
(3,  1, 'Bebidas',         'Bebidas diversas',            '#2196F3', 3, 1, NOW(), NOW()),
(4,  1, 'Sobremesas',      'Doces e sobremesas',          '#E91E63', 4, 1, NOW(), NOW()),
(5,  2, 'Massas',          'Massas artesanais',           '#FF9800', 1, 1, NOW(), NOW()),
(6,  2, 'Carnes',          'Carnes e aves',               '#795548', 2, 1, NOW(), NOW()),
(7,  2, 'Bebidas',         'Sucos e refrigerantes',       '#00BCD4', 3, 1, NOW(), NOW()),
(8,  3, 'Antipasti',       'Entradas italianas',          '#8BC34A', 1, 1, NOW(), NOW()),
(9,  3, 'Pasta',           'Massas tradicionais',         '#FFC107', 2, 1, NOW(), NOW()),
(10, 3, 'Pizza',           'Pizzas artesanais',           '#F44336', 3, 1, NOW(), NOW()),
(11, 3, 'Vini',            'Vinhos importados',           '#9C27B0', 4, 1, NOW(), NOW());

-- ============================================================================
-- PRODUCTS (54 registros)
-- ============================================================================
INSERT INTO `products` (`id`, `tenant_id`, `category_id`, `name`, `description`, `price`, `cost`, `sku`, `stock_quantity`, `stock_control`, `is_available`, `preparation_time`, `calories`, `allergens`, `tags`, `created_at`, `updated_at`) VALUES
-- Tenant 1 original
(1,  1, 1, 'Bolinho de Bacalhau',    '6 unidades de bolinhos crocantes',          32.90, 15.00, 'ENT001',   50,   1, 1, 15, 280, '["gluten","fish"]',          '["tradicional","popular"]',   NOW(), NOW()),
(2,  1, 1, 'Bruschetta',             'Pao italiano com tomate e manjericao',      24.90, 10.00, 'ENT002',   NULL, 0, 1, 10, 180, '["gluten"]',                 '["vegetariano"]',             NOW(), NOW()),
(3,  1, 2, 'File a Parmegiana',      'File empanado com molho e queijo',          49.90, 22.00, 'PRATO001', 30,   1, 1, 25, 650, '["gluten","lactose"]',       '["popular"]',                 NOW(), NOW()),
(4,  1, 2, 'Salmao Grelhado',        'Salmao com legumes ao vapor',               69.90, 35.00, 'PRATO002', 20,   1, 1, 20, 420, '["fish"]',                   '["saudavel"]',                NOW(), NOW()),
(5,  1, 3, 'Suco Natural',           'Suco de frutas naturais 500ml',             12.00,  4.00, 'BEB001',   NULL, 0, 1,  5, 120, '[]',                         '["natural"]',                 NOW(), NOW()),
(6,  1, 3, 'Refrigerante Lata',      'Refrigerante 350ml',                         6.00,  2.50, 'BEB002',  100,   1, 1,  2, 140, '[]',                         '[]',                          NOW(), NOW()),
(7,  1, 4, 'Petit Gateau',           'Bolo quente com sorvete',                   22.00,  8.00, 'SOB001',   15,   1, 1, 15, 380, '["gluten","lactose","eggs"]', '["premium"]',                 NOW(), NOW()),
-- Tenant 2 original
(8,  2, 5, 'Espaguete a Carbonara',  'Massa com bacon e creme',                   38.90, 16.00, 'MASS001',  NULL, 0, 1, 20, 520, '["gluten","lactose"]',       '["tradicional"]',             NOW(), NOW()),
(9,  2, 6, 'Picanha na Chapa',       'Picanha 300g com acompanhamentos',          59.90, 28.00, 'CARNE001', 25,   1, 1, 25, 680, '[]',                         '["premium"]',                 NOW(), NOW()),
(10, 2, 7, 'Limonada Suica',         'Limonada com leite condensado',             14.90,  5.00, 'BEB003',   NULL, 0, 1,  5, 200, '["lactose"]',                '["especial"]',                NOW(), NOW()),
-- Tenant 3 original
(11, 3, 8, 'Carpaccio',              'Carpaccio de carne com rucula',             45.00, 20.00, 'ANT001',   NULL, 0, 1, 10, 280, '[]',                         '["premium"]',                 NOW(), NOW()),
(12, 3, 9, 'Lasanha Bolonhesa',      'Lasanha tradicional italiana',              42.00, 18.00, 'PAST001',  20,   1, 1, 30, 580, '["gluten","lactose"]',       '["tradicional"]',             NOW(), NOW()),
(13, 3, 10,'Pizza Margherita',       'Pizza classica com manjericao',             52.00, 22.00, 'PIZZ001',  NULL, 0, 1, 25, 720, '["gluten","lactose"]',       '["tradicional"]',             NOW(), NOW()),
(14, 3, 11,'Vinho Chianti',          'Vinho tinto italiano 750ml',                89.00, 45.00, 'VIN001',   30,   1, 1,  2, 180, '[]',                         '["importado","premium"]',     NOW(), NOW()),
-- Tenant 1 - novos
(15, 1, 1, 'Dadinho de Tapioca',     'Dadinhos crocantes com geleia de pimenta', 18.90,  7.00, 'ENT003',   NULL, 0, 1, 10, 200, '[]',                         '["vegetariano","popular"]',   NOW(), NOW()),
(16, 1, 1, 'Croquete de Carne',      '6 unidades com molho especial',             24.00,  9.00, 'ENT004',   40,   1, 1, 12, 310, '["gluten"]',                 '["tradicional"]',             NOW(), NOW()),
(17, 1, 1, 'Pao de Alho Recheado',   'Pao com alho e queijo mozzarella',          19.90,  6.00, 'ENT005',   NULL, 0, 1,  8, 250, '["gluten","lactose"]',       '["vegetariano"]',             NOW(), NOW()),
(18, 1, 2, 'Frango Grelhado',        'Frango com legumes salteados e arroz',      38.90, 16.00, 'PRATO003', 25,   1, 1, 20, 480, '[]',                         '["saudavel","popular"]',      NOW(), NOW()),
(19, 1, 2, 'Camarao na Moranga',     'Camarao cremoso servido na abobora',        79.90, 38.00, 'PRATO004', 15,   1, 1, 30, 560, '["shellfish"]',              '["premium","especial"]',      NOW(), NOW()),
(20, 1, 2, 'Picanha Suina',          'Lombo suino grelhado com farofa',           44.90, 20.00, 'PRATO005', 20,   1, 1, 25, 590, '[]',                         '["popular"]',                 NOW(), NOW()),
(21, 1, 2, 'Moqueca de Peixe',       'Peixe cozido no leite de coco',             58.90, 26.00, 'PRATO006', NULL, 0, 1, 35, 520, '["fish"]',                   '["regional","especial"]',     NOW(), NOW()),
(22, 1, 2, 'Risoto de Funghi',       'Risoto cremoso com cogumelos frescos',      52.90, 22.00, 'PRATO007', NULL, 0, 1, 25, 600, '["lactose"]',                '["vegetariano","premium"]',   NOW(), NOW()),
(23, 1, 3, 'Cerveja Long Neck',      'Cerveja gelada 355ml',                      12.00,  5.00, 'BEB004',   80,   1, 1,  2, 150, '["gluten"]',                 '[]',                          NOW(), NOW()),
(24, 1, 3, 'Caipirinha',             'Caipirinha de limao com cachaca artesanal', 18.00,  7.00, 'BEB005',   NULL, 0, 1,  3, 180, '[]',                         '["adulto"]',                  NOW(), NOW()),
(25, 1, 3, 'Vitamina de Frutas',     'Vitamina mista com leite ou iogurte',       14.00,  5.00, 'BEB006',   NULL, 0, 1,  5, 200, '["lactose"]',                '["natural","saudavel"]',      NOW(), NOW()),
(26, 1, 4, 'Pudim de Leite',         'Pudim tradicional com calda de caramelo',   16.00,  6.00, 'SOB002',   20,   1, 1,  5, 320, '["lactose","eggs"]',         '["tradicional"]',             NOW(), NOW()),
(27, 1, 4, 'Brownie com Sorvete',    'Brownie quente com sorvete de baunilha',    20.00,  8.00, 'SOB003',   15,   1, 1, 10, 410, '["gluten","lactose","eggs"]', '["popular","premium"]',       NOW(), NOW()),
(28, 1, 4, 'Crepe de Doce de Leite', 'Crepe recheado com doce de leite',          18.00,  7.00, 'SOB004',   NULL, 0, 1, 10, 370, '["gluten","lactose"]',       '["popular"]',                 NOW(), NOW()),
(29, 1, 2, 'File ao Molho Madeira',  'File mignon ao molho madeira com arroz',    62.90, 28.00, 'PRATO008', NULL, 0, 1, 25, 610, '[]',                         '["premium"]',                 NOW(), NOW()),
(30, 1, 4, 'Mousse de Maracuja',     'Mousse cremoso com calda de maracuja',      15.00,  5.50, 'SOB005',   20,   1, 1,  5, 280, '["lactose","eggs"]',         '["popular"]',                 NOW(), NOW()),
-- Tenant 2 - novos
(31, 2, 5, 'Talharim a Bolonhesa',   'Talharim com molho de carne tradicional',   35.90, 14.00, 'MASS002',  NULL, 0, 1, 20, 550, '["gluten"]',                 '["tradicional","popular"]',   NOW(), NOW()),
(32, 2, 5, 'Penne Arrabiata',        'Massa ao molho apimentado',                 33.00, 13.00, 'MASS003',  NULL, 0, 1, 15, 460, '["gluten"]',                 '["vegetariano","picante"]',   NOW(), NOW()),
(33, 2, 5, 'Gnocchi ao Sugo',        'Nhoque de batata com molho de tomate',      36.00, 14.00, 'MASS004',  NULL, 0, 1, 20, 480, '["gluten","eggs"]',          '["vegetariano"]',             NOW(), NOW()),
(34, 2, 6, 'Costela no Bafo',        'Costela bovina assada 12h com farofa',      72.00, 33.00, 'CARNE002', 15,   1, 1,180, 820, '[]',                         '["premium","especial"]',      NOW(), NOW()),
(35, 2, 6, 'Frango a Passarinho',    'Pedacos de frango crocantes temperados',    42.00, 18.00, 'CARNE003', 20,   1, 1, 25, 560, '["gluten"]',                 '["popular"]',                 NOW(), NOW()),
(36, 2, 6, 'Pernil Assado',          'Pernil suino com molho de laranja',         55.00, 24.00, 'CARNE004', NULL, 0, 1, 40, 680, '[]',                         '["especial"]',                NOW(), NOW()),
(37, 2, 7, 'Suco de Laranja',        'Suco de laranja natural 400ml',             11.00,  3.50, 'BEB007',   NULL, 0, 1,  5, 100, '[]',                         '["natural"]',                 NOW(), NOW()),
(38, 2, 7, 'Agua de Coco',           'Agua de coco gelada 300ml',                  8.00,  3.00, 'BEB008',   60,   1, 1,  2,  45, '[]',                         '["natural"]',                 NOW(), NOW()),
(39, 2, 7, 'Vitamina de Abacate',    'Vitamina cremosa com leite e mel',          14.00,  5.00, 'BEB009',   NULL, 0, 1,  5, 220, '["lactose"]',                '["natural","saudavel"]',      NOW(), NOW()),
-- Tenant 3 - novos
(40, 3, 8, 'Bruschetta al Pomodoro', 'Fatias de pao com tomate e azeite',         32.00, 12.00, 'ANT002',   NULL, 0, 1,  8, 200, '["gluten"]',                 '["vegetariano"]',             NOW(), NOW()),
(41, 3, 8, 'Burrata com Rucula',     'Queijo burrata com rucula e tomate cereja', 54.00, 24.00, 'ANT003',   NULL, 0, 1,  5, 290, '["lactose"]',                '["vegetariano","premium"]',   NOW(), NOW()),
(42, 3, 8, 'Caponata Siciliana',     'Berinjela agridoce com azeitonas',          38.00, 15.00, 'ANT004',   NULL, 0, 1, 10, 180, '[]',                         '["vegetariano","vegano"]',    NOW(), NOW()),
(43, 3, 9, 'Pappardelle ao Javali',  'Massa larga com ragu de javali',            78.00, 36.00, 'PAST002',  NULL, 0, 1, 35, 620, '["gluten"]',                 '["premium","especial"]',      NOW(), NOW()),
(44, 3, 9, 'Rigatoni alla Norma',    'Massa tubular com berinjela e ricota',      46.00, 19.00, 'PAST003',  NULL, 0, 1, 20, 500, '["gluten","lactose"]',       '["vegetariano"]',             NOW(), NOW()),
(45, 3, 9, 'Fettuccine Alfredo',     'Massa com manteiga, creme e parmesao',      49.00, 21.00, 'PAST004',  NULL, 0, 1, 20, 650, '["gluten","lactose"]',       '["tradicional"]',             NOW(), NOW()),
(46, 3, 9, 'Gnocchi alla Sorrentina','Nhoque ao molho de tomate com mozzarella',  44.00, 18.00, 'PAST005',  NULL, 0, 1, 25, 580, '["gluten","lactose","eggs"]','["popular"]',                 NOW(), NOW()),
(47, 3, 10,'Pizza Quattro Formaggi', 'Pizza com 4 tipos de queijo',               62.00, 26.00, 'PIZZ002',  NULL, 0, 1, 25, 780, '["gluten","lactose"]',       '["popular"]',                 NOW(), NOW()),
(48, 3, 10,'Pizza Diavola',          'Pizza com salame picante e pimenta',        58.00, 23.00, 'PIZZ003',  NULL, 0, 1, 25, 750, '["gluten","lactose"]',       '["picante"]',                 NOW(), NOW()),
(49, 3, 10,'Pizza Prosciutto',       'Pizza com presunto cru e rucula',           66.00, 28.00, 'PIZZ004',  NULL, 0, 1, 25, 700, '["gluten","lactose"]',       '["premium"]',                 NOW(), NOW()),
(50, 3, 10,'Pizza Funghi',           'Pizza com cogumelos e mozzarella',          58.00, 24.00, 'PIZZ005',  NULL, 0, 1, 25, 690, '["gluten","lactose"]',       '["vegetariano"]',             NOW(), NOW()),
(51, 3, 11,'Vinho Brunello',         'Vinho tinto toscano reserva 750ml',        195.00, 95.00, 'VIN002',   10,   1, 1,  2, 170, '[]',                         '["importado","premium"]',     NOW(), NOW()),
(52, 3, 11,'Vinho Pinot Grigio',     'Vinho branco norte-italiano 750ml',         75.00, 35.00, 'VIN003',   18,   1, 1,  2, 160, '[]',                         '["importado"]',               NOW(), NOW()),
(53, 3, 11,'Prosecco DOC',           'Espumante italiano DOC 750ml',              89.00, 42.00, 'VIN004',   12,   1, 1,  3, 160, '[]',                         '["importado","especial"]',    NOW(), NOW()),
(54, 3, 11,'Limoncello',             'Licor italiano de limao 50ml',              24.00, 10.00, 'VIN005',   30,   1, 1,  1, 110, '[]',                         '["digestivo"]',               NOW(), NOW());

-- ============================================================================
-- ORDERS (56 registros)
-- ============================================================================
INSERT INTO `orders` (`id`, `tenant_id`, `table_id`, `customer_id`, `user_id`, `order_number`, `status`, `type`, `subtotal`, `discount`, `service_fee`, `delivery_fee`, `total`, `payment_status`, `payment_method`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1,  1, 1,  1,  2, 'PED001', 'delivered', 'dine_in',   82.80,  0.00,  8.28, 0.00,  91.08, 'paid', 'credit_card', '2025-06-01 19:30:00', '2025-06-01 18:45:00', '2025-06-01 19:30:00'),
(2,  1, 2,  2,  3, 'PED002', 'delivered', 'dine_in',  119.80, 10.00, 10.98, 0.00, 120.78, 'paid', 'pix',         '2025-06-02 20:15:00', '2025-06-02 19:00:00', '2025-06-02 20:15:00'),
(3,  1, NULL,3, 4, 'PED003', 'delivered', 'takeaway',  49.90,  0.00,  0.00, 0.00,  49.90, 'paid', 'cash',        '2025-06-03 13:20:00', '2025-06-03 13:00:00', '2025-06-03 13:20:00'),
(4,  1, 3,  NULL,2,'PED004', 'cancelled', 'dine_in',   69.90,  0.00,  0.00, 0.00,   0.00, 'pending', NULL,        NULL,                  '2025-06-04 12:00:00', '2025-06-04 12:10:00'),
(5,  2, 6,  4,  5, 'PED005', 'delivered', 'dine_in',   98.80,  0.00,  9.88, 0.00, 108.68, 'paid', 'debit_card',  '2025-06-05 20:00:00', '2025-06-05 19:15:00', '2025-06-05 20:00:00'),
(6,  3, 9,  6,  6, 'PED006', 'delivered', 'dine_in',  228.00, 22.80, 20.52, 0.00, 225.72, 'paid', 'credit_card', '2025-06-06 21:30:00', '2025-06-06 20:00:00', '2025-06-06 21:30:00'),
(7,  1, 1,  7,  2, 'PED007', 'delivered', 'dine_in',   68.80,  0.00,  6.88, 0.00,  75.68, 'paid', 'pix',         '2025-08-01 20:10:00', '2025-08-01 19:20:00', '2025-08-01 20:10:00'),
(8,  1, 2,  8,  3, 'PED008', 'delivered', 'dine_in',   87.80,  0.00,  8.78, 0.00,  96.58, 'paid', 'credit_card', '2025-08-02 20:30:00', '2025-08-02 19:40:00', '2025-08-02 20:30:00'),
(9,  1, 3,  9,  2, 'PED009', 'delivered', 'dine_in',  103.80, 10.00,  9.38, 0.00, 103.18, 'paid', 'debit_card',  '2025-08-03 21:00:00', '2025-08-03 20:00:00', '2025-08-03 21:00:00'),
(10, 1, 4,  10, 3, 'PED010', 'delivered', 'dine_in',   38.90,  0.00,  3.89, 0.00,  42.79, 'paid', 'cash',        '2025-08-04 13:30:00', '2025-08-04 13:00:00', '2025-08-04 13:30:00'),
(11, 1, 5,  11, 2, 'PED011', 'delivered', 'dine_in',  159.80, 16.00, 14.38, 0.00, 158.18, 'paid', 'pix',         '2025-08-05 20:45:00', '2025-08-05 19:30:00', '2025-08-05 20:45:00'),
(12, 1, 1,  12, 3, 'PED012', 'delivered', 'dine_in',   52.90,  0.00,  5.29, 0.00,  58.19, 'paid', 'credit_card', '2025-08-06 20:00:00', '2025-08-06 19:15:00', '2025-08-06 20:00:00'),
(13, 1, 2,  13, 4, 'PED013', 'delivered', 'dine_in',   43.90,  0.00,  4.39, 0.00,  48.29, 'paid', 'cash',        '2025-08-07 13:45:00', '2025-08-07 13:10:00', '2025-08-07 13:45:00'),
(14, 1, NULL,14, 2,'PED014', 'delivered', 'takeaway',  69.90,  0.00,  0.00, 0.00,  69.90, 'paid', 'cash',        '2025-08-08 12:20:00', '2025-08-08 12:00:00', '2025-08-08 12:20:00'),
(15, 1, NULL,15, 3,'PED015', 'delivered', 'delivery',  49.90,  0.00,  0.00, 8.00,  57.90, 'paid', 'pix',         '2025-08-09 19:50:00', '2025-08-09 19:15:00', '2025-08-09 19:50:00'),
(16, 1, 3,  16, 2, 'PED016', 'delivered', 'dine_in',  134.80, 13.48, 12.13, 0.00, 133.45, 'paid', 'credit_card', '2025-08-10 21:20:00', '2025-08-10 20:10:00', '2025-08-10 21:20:00'),
(17, 1, 4,  17, 4, 'PED017', 'delivered', 'dine_in',   18.90,  0.00,  1.89, 0.00,  20.79, 'paid', 'cash',        '2025-08-11 14:00:00', '2025-08-11 13:30:00', '2025-08-11 14:00:00'),
(18, 1, 5,  18, 3, 'PED018', 'delivered', 'dine_in',   93.80,  0.00,  9.38, 0.00, 103.18, 'paid', 'pix',         '2025-08-12 20:30:00', '2025-08-12 19:45:00', '2025-08-12 20:30:00'),
(19, 1, 1,  19, 2, 'PED019', 'delivered', 'dine_in',   38.90,  0.00,  3.89, 0.00,  42.79, 'paid', 'debit_card',  '2025-08-13 13:15:00', '2025-08-13 12:50:00', '2025-08-13 13:15:00'),
(20, 1, 2,  20, 3, 'PED020', 'delivered', 'dine_in',  202.80, 20.28, 18.25, 0.00, 200.77, 'paid', 'credit_card', '2025-08-14 21:00:00', '2025-08-14 19:30:00', '2025-08-14 21:00:00'),
(21, 1, 3,  7,  4, 'PED021', 'cancelled', 'dine_in',   69.90,  0.00,  0.00, 0.00,   0.00, 'pending', NULL,        NULL,                  '2025-08-15 20:00:00', '2025-08-15 20:05:00'),
(22, 1, 4,  8,  2, 'PED022', 'delivered', 'dine_in',   71.80,  0.00,  7.18, 0.00,  78.98, 'paid', 'pix',         '2025-08-16 20:40:00', '2025-08-16 19:55:00', '2025-08-16 20:40:00'),
(23, 1, 5,  9,  3, 'PED023', 'delivered', 'dine_in',  125.80,  0.00, 12.58, 0.00, 138.38, 'paid', 'credit_card', '2025-08-17 21:10:00', '2025-08-17 20:00:00', '2025-08-17 21:10:00'),
(24, 1, 1,  10, 4, 'PED024', 'delivered', 'dine_in',   43.90,  0.00,  4.39, 0.00,  48.29, 'paid', 'cash',        '2025-08-18 13:30:00', '2025-08-18 13:00:00', '2025-08-18 13:30:00'),
(25, 1, NULL,11, 2,'PED025', 'delivered', 'delivery',  69.90,  0.00,  0.00, 8.00,  77.90, 'paid', 'pix',         '2025-08-19 20:00:00', '2025-08-19 19:20:00', '2025-08-19 20:00:00'),
(26, 1, 2,  12, 3, 'PED026', 'delivered', 'dine_in',   88.80,  8.88,  7.99, 0.00,  87.91, 'paid', 'credit_card', '2025-08-20 21:00:00', '2025-08-20 20:00:00', '2025-08-20 21:00:00'),
(27, 2, 6,  27, 5, 'PED027', 'delivered', 'dine_in',   72.80,  0.00,  7.28, 0.00,  80.08, 'paid', 'credit_card', '2025-08-01 20:30:00', '2025-08-01 19:45:00', '2025-08-01 20:30:00'),
(28, 2, 7,  28, 5, 'PED028', 'delivered', 'dine_in',   97.80,  9.78,  8.80, 0.00,  96.82, 'paid', 'pix',         '2025-08-02 20:45:00', '2025-08-02 20:00:00', '2025-08-02 20:45:00'),
(29, 2, 8,  29, 5, 'PED029', 'delivered', 'dine_in',   53.80,  0.00,  5.38, 0.00,  59.18, 'paid', 'cash',        '2025-08-03 13:30:00', '2025-08-03 13:00:00', '2025-08-03 13:30:00'),
(30, 2, 6,  30, 5, 'PED030', 'delivered', 'dine_in',   35.90,  0.00,  3.59, 0.00,  39.49, 'paid', 'debit_card',  '2025-08-04 13:45:00', '2025-08-04 13:15:00', '2025-08-04 13:45:00'),
(31, 2, 7,  31, 5, 'PED031', 'delivered', 'dine_in',  127.80,  0.00, 12.78, 0.00, 140.58, 'paid', 'credit_card', '2025-08-05 21:00:00', '2025-08-05 19:45:00', '2025-08-05 21:00:00'),
(32, 2, 8,  32, 5, 'PED032', 'delivered', 'dine_in',   46.90,  0.00,  4.69, 0.00,  51.59, 'paid', 'cash',        '2025-08-06 14:00:00', '2025-08-06 13:30:00', '2025-08-06 14:00:00'),
(33, 2, NULL,33, 5,'PED033', 'delivered', 'takeaway',  42.00,  0.00,  0.00, 0.00,  42.00, 'paid', 'cash',        '2025-08-07 12:15:00', '2025-08-07 12:00:00', '2025-08-07 12:15:00'),
(34, 2, 6,  34, 5, 'PED034', 'delivered', 'dine_in',  111.80, 11.18, 10.06, 0.00, 110.68, 'paid', 'pix',         '2025-08-08 21:30:00', '2025-08-08 20:15:00', '2025-08-08 21:30:00'),
(35, 2, 7,  35, 5, 'PED035', 'delivered', 'dine_in',   73.90,  0.00,  7.39, 0.00,  81.29, 'paid', 'credit_card', '2025-08-09 20:00:00', '2025-08-09 19:15:00', '2025-08-09 20:00:00'),
(36, 2, 8,  27, 5, 'PED036', 'cancelled', 'dine_in',   59.90,  0.00,  0.00, 0.00,   0.00, 'pending', NULL,        NULL,                  '2025-08-10 20:00:00', '2025-08-10 20:10:00'),
(37, 3, 9,  42, 6, 'PED037', 'delivered', 'dine_in',  190.00, 19.00, 17.10, 0.00, 188.10, 'paid', 'credit_card', '2025-08-01 22:00:00', '2025-08-01 20:30:00', '2025-08-01 22:00:00'),
(38, 3, 10, 43, 6, 'PED038', 'delivered', 'dine_in',   91.00,  0.00,  9.10, 0.00, 100.10, 'paid', 'pix',         '2025-08-02 21:30:00', '2025-08-02 20:15:00', '2025-08-02 21:30:00'),
(39, 3, 11, 44, 6, 'PED039', 'delivered', 'dine_in',  126.00,  0.00, 12.60, 0.00, 138.60, 'paid', 'credit_card', '2025-08-03 22:15:00', '2025-08-03 20:45:00', '2025-08-03 22:15:00'),
(40, 3, 9,  45, 6, 'PED040', 'delivered', 'dine_in',   62.00,  0.00,  6.20, 0.00,  68.20, 'paid', 'cash',        '2025-08-04 13:30:00', '2025-08-04 13:00:00', '2025-08-04 13:30:00'),
(41, 3, 10, 46, 6, 'PED041', 'delivered', 'dine_in',  284.00, 28.40, 25.56, 0.00, 281.16, 'paid', 'credit_card', '2025-08-05 22:30:00', '2025-08-05 20:30:00', '2025-08-05 22:30:00'),
(42, 3, 11, 47, 6, 'PED042', 'delivered', 'dine_in',  128.00,  0.00, 12.80, 0.00, 140.80, 'paid', 'pix',         '2025-08-06 22:00:00', '2025-08-06 20:30:00', '2025-08-06 22:00:00'),
(43, 3, 9,  48, 6, 'PED043', 'delivered', 'dine_in',  154.00, 15.40, 13.86, 0.00, 152.46, 'paid', 'credit_card', '2025-08-07 22:15:00', '2025-08-07 20:45:00', '2025-08-07 22:15:00'),
(44, 3, 10, 49, 6, 'PED044', 'delivered', 'dine_in',   86.00,  0.00,  8.60, 0.00,  94.60, 'paid', 'debit_card',  '2025-08-08 21:45:00', '2025-08-08 20:30:00', '2025-08-08 21:45:00'),
(45, 3, 11, 50, 6, 'PED045', 'delivered', 'dine_in',  230.00, 23.00, 20.70, 0.00, 227.70, 'paid', 'credit_card', '2025-08-09 22:30:00', '2025-08-09 20:30:00', '2025-08-09 22:30:00'),
(46, 3, 9,  51, 6, 'PED046', 'delivered', 'dine_in',  120.00, 12.00, 10.80, 0.00, 118.80, 'paid', 'pix',         '2025-08-10 22:00:00', '2025-08-10 20:30:00', '2025-08-10 22:00:00'),
(47, 1, 1,  7,  2, 'PED047', 'delivered', 'dine_in',   91.80,  0.00,  9.18, 0.00, 100.98, 'paid', 'credit_card', '2025-09-01 20:30:00', '2025-09-01 19:45:00', '2025-09-01 20:30:00'),
(48, 1, 2,  8,  3, 'PED048', 'delivered', 'dine_in',   70.80,  0.00,  7.08, 0.00,  77.88, 'paid', 'pix',         '2025-09-02 20:00:00', '2025-09-02 19:15:00', '2025-09-02 20:00:00'),
(49, 1, 3,  9,  4, 'PED049', 'delivered', 'dine_in',   38.90,  0.00,  3.89, 0.00,  42.79, 'paid', 'cash',        '2025-09-03 13:30:00', '2025-09-03 13:00:00', '2025-09-03 13:30:00'),
(50, 1, 4,  11, 2, 'PED050', 'delivered', 'dine_in',  103.80, 10.38,  9.34, 0.00, 102.76, 'paid', 'credit_card', '2025-09-04 21:00:00', '2025-09-04 19:45:00', '2025-09-04 21:00:00'),
(51, 1, 5,  12, 3, 'PED051', 'delivered', 'dine_in',   52.90,  0.00,  5.29, 0.00,  58.19, 'paid', 'pix',         '2025-09-05 20:15:00', '2025-09-05 19:30:00', '2025-09-05 20:15:00'),
(52, 1, NULL,14, 4,'PED052', 'delivered', 'takeaway',  49.90,  0.00,  0.00, 0.00,  49.90, 'paid', 'cash',        '2025-09-06 12:30:00', '2025-09-06 12:10:00', '2025-09-06 12:30:00'),
(53, 1, 1,  15, 2, 'PED053', 'delivered', 'dine_in',  134.80, 13.48, 12.13, 0.00, 133.45, 'paid', 'credit_card', '2025-09-07 21:30:00', '2025-09-07 20:00:00', '2025-09-07 21:30:00'),
(54, 1, 2,  16, 3, 'PED054', 'delivered', 'dine_in',   43.80,  0.00,  4.38, 0.00,  48.18, 'paid', 'pix',         '2025-09-08 20:45:00', '2025-09-08 20:00:00', '2025-09-08 20:45:00'),
(55, 1, 3,  17, 4, 'PED055', 'delivered', 'dine_in',   69.90,  0.00,  6.99, 0.00,  76.89, 'paid', 'cash',        '2025-09-09 13:30:00', '2025-09-09 13:00:00', '2025-09-09 13:30:00'),
(56, 1, 4,  26, 2, 'PED056', 'delivered', 'dine_in',  202.80, 20.28, 18.25, 0.00, 200.77, 'paid', 'credit_card', '2025-09-10 22:00:00', '2025-09-10 20:15:00', '2025-09-10 22:00:00');

-- ============================================================================
-- ORDER_ITEMS
-- ============================================================================
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `discount`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1,  1,  1, 32.90, 32.90, 0.00, 32.90, 'delivered', NOW(), NOW()),
(1,  3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(2,  4,  1, 69.90, 69.90, 0.00, 69.90, 'delivered', NOW(), NOW()),
(2,  3,  1, 49.90, 49.90,10.00, 39.90, 'delivered', NOW(), NOW()),
(3,  3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(4,  4,  1, 69.90, 69.90, 0.00, 69.90, 'cancelled', NOW(), NOW()),
(5,  8,  1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(5,  9,  1, 59.90, 59.90, 0.00, 59.90, 'delivered', NOW(), NOW()),
(6,  11, 2, 45.00, 90.00, 9.00, 81.00, 'delivered', NOW(), NOW()),
(6,  12, 1, 42.00, 42.00, 4.20, 37.80, 'delivered', NOW(), NOW()),
(6,  13, 1, 52.00, 52.00, 5.20, 46.80, 'delivered', NOW(), NOW()),
(6,  14, 1, 89.00, 89.00, 8.90, 80.10, 'delivered', NOW(), NOW()),
(7,  18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(7,  5,  2, 12.00, 24.00, 0.00, 24.00, 'delivered', NOW(), NOW()),
(7,  2,  1, 24.90, 24.90, 0.00, 24.90, 'delivered', NOW(), NOW()),
(8,  22, 1, 52.90, 52.90, 0.00, 52.90, 'delivered', NOW(), NOW()),
(8,  3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(8,  6,  2,  6.00, 12.00, 0.00, 12.00, 'delivered', NOW(), NOW()),
(9,  4,  1, 69.90, 69.90, 5.00, 64.90, 'delivered', NOW(), NOW()),
(9,  22, 1, 52.90, 52.90, 5.00, 47.90, 'delivered', NOW(), NOW()),
(10, 18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(11, 19, 1, 79.90, 79.90, 8.00, 71.90, 'delivered', NOW(), NOW()),
(11, 7,  1, 22.00, 22.00, 4.00, 18.00, 'delivered', NOW(), NOW()),
(11, 4,  1, 69.90, 69.90, 4.00, 65.90, 'delivered', NOW(), NOW()),
(12, 22, 1, 52.90, 52.90, 0.00, 52.90, 'delivered', NOW(), NOW()),
(13, 3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(14, 4,  1, 69.90, 69.90, 0.00, 69.90, 'delivered', NOW(), NOW()),
(15, 18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(15, 5,  1, 12.00, 12.00, 0.00, 12.00, 'delivered', NOW(), NOW()),
(16, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(16, 7,  2, 22.00, 44.00, 4.40, 39.60, 'delivered', NOW(), NOW()),
(16, 6,  2,  6.00, 12.00, 1.00, 11.00, 'delivered', NOW(), NOW()),
(17, 15, 1, 18.90, 18.90, 0.00, 18.90, 'delivered', NOW(), NOW()),
(18, 21, 1, 58.90, 58.90, 0.00, 58.90, 'delivered', NOW(), NOW()),
(18, 24, 1, 18.00, 18.00, 0.00, 18.00, 'delivered', NOW(), NOW()),
(18, 6,  1,  6.00,  6.00, 0.00,  6.00, 'delivered', NOW(), NOW()),
(19, 18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(20, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(20, 22, 1, 52.90, 52.90, 5.29, 47.61, 'delivered', NOW(), NOW()),
(20, 7,  1, 22.00, 22.00, 2.20, 19.80, 'delivered', NOW(), NOW()),
(20, 24, 1, 18.00, 18.00, 1.80, 16.20, 'delivered', NOW(), NOW()),
(22, 3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(22, 2,  1, 24.90, 24.90, 0.00, 24.90, 'delivered', NOW(), NOW()),
(23, 19, 1, 79.90, 79.90, 0.00, 79.90, 'delivered', NOW(), NOW()),
(23, 23, 2, 12.00, 24.00, 0.00, 24.00, 'delivered', NOW(), NOW()),
(24, 3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(25, 4,  1, 69.90, 69.90, 0.00, 69.90, 'delivered', NOW(), NOW()),
(26, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(26, 27, 1, 20.00, 20.00, 2.00, 18.00, 'delivered', NOW(), NOW()),
(27, 8,  1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(27, 9,  1, 59.90, 59.90, 0.00, 59.90, 'delivered', NOW(), NOW()),
(28, 34, 1, 72.00, 72.00, 7.20, 64.80, 'delivered', NOW(), NOW()),
(28, 10, 1, 14.90, 14.90, 1.49, 13.41, 'delivered', NOW(), NOW()),
(29, 8,  1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(29, 10, 1, 14.90, 14.90, 0.00, 14.90, 'delivered', NOW(), NOW()),
(30, 31, 1, 35.90, 35.90, 0.00, 35.90, 'delivered', NOW(), NOW()),
(31, 34, 1, 72.00, 72.00, 0.00, 72.00, 'delivered', NOW(), NOW()),
(31, 9,  1, 59.90, 59.90, 0.00, 59.90, 'delivered', NOW(), NOW()),
(31, 38, 1,  8.00,  8.00, 0.00,  8.00, 'delivered', NOW(), NOW()),
(32, 32, 1, 33.00, 33.00, 0.00, 33.00, 'delivered', NOW(), NOW()),
(32, 37, 1, 11.00, 11.00, 0.00, 11.00, 'delivered', NOW(), NOW()),
(33, 35, 1, 42.00, 42.00, 0.00, 42.00, 'delivered', NOW(), NOW()),
(34, 34, 1, 72.00, 72.00, 7.20, 64.80, 'delivered', NOW(), NOW()),
(34, 8,  1, 38.90, 38.90, 3.89, 35.01, 'delivered', NOW(), NOW()),
(35, 9,  1, 59.90, 59.90, 0.00, 59.90, 'delivered', NOW(), NOW()),
(35, 10, 1, 14.90, 14.90, 0.00, 14.90, 'delivered', NOW(), NOW()),
(37, 11, 2, 45.00, 90.00, 9.00, 81.00, 'delivered', NOW(), NOW()),
(37, 12, 1, 42.00, 42.00, 4.20, 37.80, 'delivered', NOW(), NOW()),
(37, 14, 1, 89.00, 89.00, 8.90, 80.10, 'delivered', NOW(), NOW()),
(38, 13, 1, 52.00, 52.00, 0.00, 52.00, 'delivered', NOW(), NOW()),
(38, 53, 1, 89.00, 89.00, 0.00, 89.00, 'delivered', NOW(), NOW()),
(39, 12, 2, 42.00, 84.00, 0.00, 84.00, 'delivered', NOW(), NOW()),
(39, 40, 1, 32.00, 32.00, 0.00, 32.00, 'delivered', NOW(), NOW()),
(40, 47, 1, 62.00, 62.00, 0.00, 62.00, 'delivered', NOW(), NOW()),
(41, 11, 2, 45.00, 90.00, 9.00, 81.00, 'delivered', NOW(), NOW()),
(41, 43, 1, 78.00, 78.00, 7.80, 70.20, 'delivered', NOW(), NOW()),
(41, 14, 1, 89.00, 89.00, 8.90, 80.10, 'delivered', NOW(), NOW()),
(41, 51, 1,195.00,195.00,19.50,175.50, 'delivered', NOW(), NOW()),
(42, 12, 1, 42.00, 42.00, 0.00, 42.00, 'delivered', NOW(), NOW()),
(42, 53, 1, 89.00, 89.00, 0.00, 89.00, 'delivered', NOW(), NOW()),
(43, 41, 1, 54.00, 54.00, 5.40, 48.60, 'delivered', NOW(), NOW()),
(43, 43, 1, 78.00, 78.00, 7.80, 70.20, 'delivered', NOW(), NOW()),
(43, 14, 1, 89.00, 89.00, 8.90, 80.10, 'delivered', NOW(), NOW()),
(44, 49, 1, 66.00, 66.00, 0.00, 66.00, 'delivered', NOW(), NOW()),
(44, 54, 1, 24.00, 24.00, 0.00, 24.00, 'delivered', NOW(), NOW()),
(45, 11, 1, 45.00, 45.00, 4.50, 40.50, 'delivered', NOW(), NOW()),
(45, 12, 1, 42.00, 42.00, 4.20, 37.80, 'delivered', NOW(), NOW()),
(45, 43, 1, 78.00, 78.00, 7.80, 70.20, 'delivered', NOW(), NOW()),
(45, 53, 1, 89.00, 89.00, 8.90, 80.10, 'delivered', NOW(), NOW()),
(46, 48, 1, 58.00, 58.00, 5.80, 52.20, 'delivered', NOW(), NOW()),
(46, 52, 1, 75.00, 75.00, 7.50, 67.50, 'delivered', NOW(), NOW()),
(47, 4,  1, 69.90, 69.90, 0.00, 69.90, 'delivered', NOW(), NOW()),
(47, 5,  2, 12.00, 24.00, 0.00, 24.00, 'delivered', NOW(), NOW()),
(48, 3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(48, 6,  2,  6.00, 12.00, 0.00, 12.00, 'delivered', NOW(), NOW()),
(49, 18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(50, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(50, 7,  1, 22.00, 22.00, 2.20, 19.80, 'delivered', NOW(), NOW()),
(51, 22, 1, 52.90, 52.90, 0.00, 52.90, 'delivered', NOW(), NOW()),
(52, 3,  1, 49.90, 49.90, 0.00, 49.90, 'delivered', NOW(), NOW()),
(53, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(53, 21, 1, 58.90, 58.90, 5.89, 53.01, 'delivered', NOW(), NOW()),
(54, 18, 1, 38.90, 38.90, 0.00, 38.90, 'delivered', NOW(), NOW()),
(54, 16, 1, 24.00, 24.00, 0.00, 24.00, 'delivered', NOW(), NOW()),
(55, 4,  1, 69.90, 69.90, 0.00, 69.90, 'delivered', NOW(), NOW()),
(56, 19, 1, 79.90, 79.90, 7.99, 71.91, 'delivered', NOW(), NOW()),
(56, 22, 1, 52.90, 52.90, 5.29, 47.61, 'delivered', NOW(), NOW()),
(56, 23, 2, 12.00, 24.00, 2.40, 21.60, 'delivered', NOW(), NOW()),
(56, 27, 1, 20.00, 20.00, 2.00, 18.00, 'delivered', NOW(), NOW());

-- ============================================================================
-- CASH_REGISTERS
-- ============================================================================
INSERT INTO `cash_registers` (`id`, `tenant_id`, `name`, `opening_balance`, `current_balance`, `status`, `opened_by`, `closed_by`, `opened_at`, `closed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Caixa Principal', 200.00, 200.00, 'closed', 2, 2, '2025-06-01 09:00:00', '2025-06-01 22:00:00', NOW(), NOW()),
(2, 1, 'Caixa Principal', 200.00, 661.66, 'open',   2, NULL,'2025-08-01 09:00:00', NULL,                 NOW(), NOW()),
(3, 2, 'Caixa 01',        150.00, 150.00, 'closed', 5, 5, '2025-08-01 10:00:00', '2025-08-01 23:00:00', NOW(), NOW()),
(4, 3, 'Caixa Central',   300.00, 300.00, 'closed', 6, 6, '2025-08-01 11:00:00', '2025-08-01 23:30:00', NOW(), NOW());

-- ============================================================================
-- TRANSACTIONS (53 registros)
-- ============================================================================
INSERT INTO `transactions` (`tenant_id`, `order_id`, `customer_id`, `user_id`, `type`, `category`, `amount`, `payment_method`, `description`, `status`, `transaction_date`, `created_at`, `updated_at`) VALUES
(1,  1,  1,  2, 'income',  'Vendas',        91.08,  'credit_card',  'Pedido #PED001', 'completed', '2025-06-01 19:30:00', NOW(), NOW()),
(1,  2,  2,  3, 'income',  'Vendas',       120.78,  'pix',          'Pedido #PED002', 'completed', '2025-06-02 20:15:00', NOW(), NOW()),
(1,  3,  3,  4, 'income',  'Vendas',        49.90,  'cash',         'Pedido #PED003', 'completed', '2025-06-03 13:20:00', NOW(), NOW()),
(1,  NULL,NULL,2,'expense', 'Fornecedores', 850.00,  'transferencia','Compra de ingredientes - jun', 'completed', '2025-06-04 10:00:00', NOW(), NOW()),
(1,  NULL,NULL,2,'expense', 'Utilidades',   320.50,  'boleto',       'Energia eletrica - mai', 'completed', '2025-06-05 14:00:00', NOW(), NOW()),
(2,  5,  4,  5, 'income',  'Vendas',       108.68,  'debit_card',   'Pedido #PED005', 'completed', '2025-06-05 20:00:00', NOW(), NOW()),
(3,  6,  6,  6, 'income',  'Vendas',       225.72,  'credit_card',  'Pedido #PED006', 'completed', '2025-06-06 21:30:00', NOW(), NOW()),
(1,  7,  7,  2, 'income',  'Vendas',        75.68,  'pix',          'Pedido #PED007', 'completed', '2025-08-01 20:10:00', NOW(), NOW()),
(1,  8,  8,  3, 'income',  'Vendas',        96.58,  'credit_card',  'Pedido #PED008', 'completed', '2025-08-02 20:30:00', NOW(), NOW()),
(1,  9,  9,  2, 'income',  'Vendas',       103.18,  'debit_card',   'Pedido #PED009', 'completed', '2025-08-03 21:00:00', NOW(), NOW()),
(1,  10, 10, 3, 'income',  'Vendas',        42.79,  'cash',         'Pedido #PED010', 'completed', '2025-08-04 13:30:00', NOW(), NOW()),
(1,  11, 11, 2, 'income',  'Vendas',       158.18,  'pix',          'Pedido #PED011', 'completed', '2025-08-05 20:45:00', NOW(), NOW()),
(1,  12, 12, 3, 'income',  'Vendas',        58.19,  'credit_card',  'Pedido #PED012', 'completed', '2025-08-06 20:00:00', NOW(), NOW()),
(1,  13, 13, 4, 'income',  'Vendas',        48.29,  'cash',         'Pedido #PED013', 'completed', '2025-08-07 13:45:00', NOW(), NOW()),
(1,  14, 14, 2, 'income',  'Vendas',        69.90,  'cash',         'Pedido #PED014', 'completed', '2025-08-08 12:20:00', NOW(), NOW()),
(1,  15, 15, 3, 'income',  'Vendas',        57.90,  'pix',          'Pedido #PED015', 'completed', '2025-08-09 19:50:00', NOW(), NOW()),
(1,  16, 16, 2, 'income',  'Vendas',       133.45,  'credit_card',  'Pedido #PED016', 'completed', '2025-08-10 21:20:00', NOW(), NOW()),
(1,  17, 17, 4, 'income',  'Vendas',        20.79,  'cash',         'Pedido #PED017', 'completed', '2025-08-11 14:00:00', NOW(), NOW()),
(1,  18, 18, 3, 'income',  'Vendas',       103.18,  'pix',          'Pedido #PED018', 'completed', '2025-08-12 20:30:00', NOW(), NOW()),
(1,  19, 19, 2, 'income',  'Vendas',        42.79,  'debit_card',   'Pedido #PED019', 'completed', '2025-08-13 13:15:00', NOW(), NOW()),
(1,  20, 20, 3, 'income',  'Vendas',       200.77,  'credit_card',  'Pedido #PED020', 'completed', '2025-08-14 21:00:00', NOW(), NOW()),
(1,  22, 8,  2, 'income',  'Vendas',        78.98,  'pix',          'Pedido #PED022', 'completed', '2025-08-16 20:40:00', NOW(), NOW()),
(1,  23, 9,  3, 'income',  'Vendas',       138.38,  'credit_card',  'Pedido #PED023', 'completed', '2025-08-17 21:10:00', NOW(), NOW()),
(1,  24, 10, 4, 'income',  'Vendas',        48.29,  'cash',         'Pedido #PED024', 'completed', '2025-08-18 13:30:00', NOW(), NOW()),
(1,  25, 11, 2, 'income',  'Vendas',        77.90,  'pix',          'Pedido #PED025', 'completed', '2025-08-19 20:00:00', NOW(), NOW()),
(1,  26, 12, 3, 'income',  'Vendas',        87.91,  'credit_card',  'Pedido #PED026', 'completed', '2025-08-20 21:00:00', NOW(), NOW()),
(1,  NULL,NULL,2,'expense', 'Fornecedores',1200.00,  'transferencia','Compra de insumos ago',    'completed', '2025-08-05 10:00:00', NOW(), NOW()),
(1,  NULL,NULL,2,'expense', 'Utilidades',   340.00,  'boleto',       'Energia eletrica ago',     'completed', '2025-08-10 09:00:00', NOW(), NOW()),
(1,  NULL,NULL,2,'expense', 'Pessoal',      900.00,  'transferencia','Pagamento funcionarios ago','completed', '2025-08-30 09:00:00', NOW(), NOW()),
(2,  27, 27, 5, 'income',  'Vendas',        80.08,  'credit_card',  'Pedido #PED027', 'completed', '2025-08-01 20:30:00', NOW(), NOW()),
(2,  28, 28, 5, 'income',  'Vendas',        96.82,  'pix',          'Pedido #PED028', 'completed', '2025-08-02 20:45:00', NOW(), NOW()),
(2,  29, 29, 5, 'income',  'Vendas',        59.18,  'cash',         'Pedido #PED029', 'completed', '2025-08-03 13:30:00', NOW(), NOW()),
(2,  30, 30, 5, 'income',  'Vendas',        39.49,  'debit_card',   'Pedido #PED030', 'completed', '2025-08-04 13:45:00', NOW(), NOW()),
(2,  31, 31, 5, 'income',  'Vendas',       140.58,  'credit_card',  'Pedido #PED031', 'completed', '2025-08-05 21:00:00', NOW(), NOW()),
(2,  32, 32, 5, 'income',  'Vendas',        51.59,  'cash',         'Pedido #PED032', 'completed', '2025-08-06 14:00:00', NOW(), NOW()),
(2,  33, 33, 5, 'income',  'Vendas',        42.00,  'cash',         'Pedido #PED033', 'completed', '2025-08-07 12:15:00', NOW(), NOW()),
(2,  34, 34, 5, 'income',  'Vendas',       110.68,  'pix',          'Pedido #PED034', 'completed', '2025-08-08 21:30:00', NOW(), NOW()),
(2,  35, 35, 5, 'income',  'Vendas',        81.29,  'credit_card',  'Pedido #PED035', 'completed', '2025-08-09 20:00:00', NOW(), NOW()),
(2,  NULL,NULL,5,'expense', 'Fornecedores', 800.00,  'transferencia','Compra de carnes ago',     'completed', '2025-08-05 11:00:00', NOW(), NOW()),
(3,  37, 42, 6, 'income',  'Vendas',       188.10,  'credit_card',  'Pedido #PED037', 'completed', '2025-08-01 22:00:00', NOW(), NOW()),
(3,  38, 43, 6, 'income',  'Vendas',       100.10,  'pix',          'Pedido #PED038', 'completed', '2025-08-02 21:30:00', NOW(), NOW()),
(3,  39, 44, 6, 'income',  'Vendas',       138.60,  'credit_card',  'Pedido #PED039', 'completed', '2025-08-03 22:15:00', NOW(), NOW()),
(3,  40, 45, 6, 'income',  'Vendas',        68.20,  'cash',         'Pedido #PED040', 'completed', '2025-08-04 13:30:00', NOW(), NOW()),
(3,  41, 46, 6, 'income',  'Vendas',       281.16,  'credit_card',  'Pedido #PED041', 'completed', '2025-08-05 22:30:00', NOW(), NOW()),
(3,  42, 47, 6, 'income',  'Vendas',       140.80,  'pix',          'Pedido #PED042', 'completed', '2025-08-06 22:00:00', NOW(), NOW()),
(3,  43, 48, 6, 'income',  'Vendas',       152.46,  'credit_card',  'Pedido #PED043', 'completed', '2025-08-07 22:15:00', NOW(), NOW()),
(3,  44, 49, 6, 'income',  'Vendas',        94.60,  'debit_card',   'Pedido #PED044', 'completed', '2025-08-08 21:45:00', NOW(), NOW()),
(3,  45, 50, 6, 'income',  'Vendas',       227.70,  'credit_card',  'Pedido #PED045', 'completed', '2025-08-09 22:30:00', NOW(), NOW()),
(3,  46, 51, 6, 'income',  'Vendas',       118.80,  'pix',          'Pedido #PED046', 'completed', '2025-08-10 22:00:00', NOW(), NOW()),
(3,  NULL,NULL,6,'expense', 'Fornecedores',1500.00,  'transferencia','Compra de vinhos importados','completed','2025-08-15 10:00:00', NOW(), NOW()),
(1,  47, 7,  2, 'income',  'Vendas',       100.98,  'credit_card',  'Pedido #PED047', 'completed', '2025-09-01 20:30:00', NOW(), NOW()),
(1,  48, 8,  3, 'income',  'Vendas',        77.88,  'pix',          'Pedido #PED048', 'completed', '2025-09-02 20:00:00', NOW(), NOW()),
(1,  49, 9,  4, 'income',  'Vendas',        42.79,  'cash',         'Pedido #PED049', 'completed', '2025-09-03 13:30:00', NOW(), NOW()),
(1,  50, 11, 2, 'income',  'Vendas',       102.76,  'credit_card',  'Pedido #PED050', 'completed', '2025-09-04 21:00:00', NOW(), NOW()),
(1,  53, 15, 2, 'income',  'Vendas',       133.45,  'credit_card',  'Pedido #PED053', 'completed', '2025-09-07 21:30:00', NOW(), NOW()),
(1,  56, 26, 2, 'income',  'Vendas',       200.77,  'credit_card',  'Pedido #PED056', 'completed', '2025-09-10 22:00:00', NOW(), NOW());

-- ============================================================================
-- CASH_MOVEMENTS
-- ============================================================================
INSERT INTO `cash_movements` (`tenant_id`, `cash_register_id`, `user_id`, `type`, `amount`, `description`, `order_id`, `transaction_id`, `payment_method`, `movement_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'deposit', 200.00, 'Abertura de caixa - troco inicial', NULL, NULL, 'cash', '2025-06-01 09:00:00', NOW(), NOW()),
(1, 1, 2, 'sale',     91.08, 'Venda - Pedido #PED001', 1,  1,  'card', '2025-06-01 19:30:00', NOW(), NOW()),
(1, 1, 3, 'sale',    120.78, 'Venda - Pedido #PED002', 2,  2,  'pix',  '2025-06-02 20:15:00', NOW(), NOW()),
(1, 1, 4, 'sale',     49.90, 'Venda - Pedido #PED003', 3,  3,  'cash', '2025-06-03 13:20:00', NOW(), NOW()),
(2, 3, 5, 'deposit', 150.00, 'Abertura de caixa - troco inicial', NULL, NULL, 'cash', '2025-08-01 10:00:00', NOW(), NOW()),
(2, 3, 5, 'sale',    108.68, 'Venda - Pedido #PED005', 5,  6,  'card', '2025-06-05 20:00:00', NOW(), NOW()),
(3, 4, 6, 'deposit', 300.00, 'Abertura de caixa - troco inicial', NULL, NULL, 'cash', '2025-08-01 11:00:00', NOW(), NOW()),
(3, 4, 6, 'sale',    225.72, 'Venda - Pedido #PED006', 6,  7,  'card', '2025-06-06 21:30:00', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- VERIFICACAO FINAL
-- ============================================================================
SELECT 'tenants'     AS tabela, COUNT(*) AS total FROM tenants
UNION ALL SELECT 'customers',   COUNT(*) FROM customers
UNION ALL SELECT 'products',    COUNT(*) FROM products
UNION ALL SELECT 'orders',      COUNT(*) FROM orders
UNION ALL SELECT 'order_items', COUNT(*) FROM order_items
UNION ALL SELECT 'transactions',COUNT(*) FROM transactions;