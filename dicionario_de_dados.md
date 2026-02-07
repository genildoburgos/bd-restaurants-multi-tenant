# DICIONÁRIO DE DADOS
## Sistema de Gestão de Restaurantes Multi-Tenant

**Data:** 06/02/2026  
**Banco de Dados:** laravel_restaurants  
**SGBD:** MySQL 8.0  
**Charset:** utf8mb4_unicode_ci

---

## ÍNDICE
1. [tenants](#1-tenants)
2. [tenant_plans](#2-tenant_plans)
3. [tenant_subscriptions](#3-tenant_subscriptions)
4. [customers](#4-customers)
5. [users](#5-users)
6. [tables](#6-tables)
7. [categories](#7-categories)
8. [products](#8-products)
9. [orders](#9-orders)
10. [order_items](#10-order_items)
11. [transactions](#11-transactions)
12. [cash_registers](#12-cash_registers)
13. [cash_movements](#13-cash_movements)
14. [cache](#14-cache)
15. [cache_locks](#15-cache_locks)

---

## 1. tenants
**Descrição:** Armazena os restaurantes (inquilinos) do sistema multi-tenant. Cada tenant representa um restaurante independente com seus próprios dados.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do tenant |
| name | VARCHAR | 100 | NOT NULL | Nome do restaurante |
| slug | VARCHAR | 50 | NOT NULL, UNIQUE | Identificador único amigável para URLs (ex: "anotado") |
| database_name | VARCHAR | 64 | NULL | Nome do banco de dados específico do tenant (quando usa estratégia multi-database) |
| domain | VARCHAR | 255 | NULL | Domínio personalizado do tenant (ex: "meurestaurante.com") |
| status | ENUM | - | NOT NULL, DEFAULT 'active' | Status do tenant: **active**=ativo, **inactive**=inativo, **suspended**=suspenso |
| settings | JSON | - | NULL | Configurações personalizadas em formato JSON (tema, moeda, timezone, etc.) |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE (slug)
- INDEX (status)

---

## 2. tenant_plans
**Descrição:** Define os planos de assinatura disponíveis para contratação pelos restaurantes. Controla recursos e limites de cada plano.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do plano |
| name | VARCHAR | 100 | NOT NULL | Nome do plano (ex: "Básico", "Profissional") |
| slug | VARCHAR | 50 | NOT NULL, UNIQUE | Identificador único do plano |
| description | TEXT | - | NULL | Descrição detalhada do plano |
| price | DECIMAL | 10,2 | NOT NULL | Preço mensal do plano em reais |
| billing_cycle | ENUM | - | NOT NULL, DEFAULT 'monthly' | Ciclo de cobrança: **monthly**=mensal, **quarterly**=trimestral, **yearly**=anual |
| trial_days | INT UNSIGNED | - | DEFAULT 0 | Quantidade de dias de teste gratuito |
| features | JSON | - | NULL | Array JSON com recursos incluídos (ex: ["reports", "api_access"]) |
| limits | JSON | - | NULL | Limites do plano em JSON (ex: {"users": 10, "tables": 20}) |
| is_active | TINYINT(1) | - | NOT NULL, DEFAULT 1 | Se o plano está disponível para contratação: **1**=sim, **0**=não |
| sort_order | INT UNSIGNED | - | DEFAULT 0 | Ordem de exibição dos planos |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- UNIQUE (slug)

---

## 3. tenant_subscriptions
**Descrição:** Registra as assinaturas ativas dos restaurantes aos planos contratados. Controla período, pagamento e status da assinatura.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único da assinatura |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Referência ao restaurante (tabela tenants) |
| tenant_plan_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Referência ao plano contratado (tabela tenant_plans) |
| status | ENUM | - | NOT NULL, DEFAULT 'active' | Status: **active**=ativo, **cancelled**=cancelado, **expired**=expirado, **suspended**=suspenso |
| starts_at | DATE | - | NOT NULL | Data de início da assinatura |
| expires_at | DATE | - | NULL | Data de expiração da assinatura |
| amount | DECIMAL | 10,2 | NOT NULL | Valor cobrado pela assinatura |
| payment_method | VARCHAR | 50 | NULL | Método de pagamento utilizado |
| last_payment_date | DATE | - | NULL | Data do último pagamento realizado |
| next_payment_date | DATE | - | NULL | Data do próximo pagamento |
| notes | TEXT | - | NULL | Observações sobre a assinatura |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
- FOREIGN KEY (tenant_plan_id) REFERENCES tenant_plans(id) ON DELETE CASCADE
- INDEX (status, expires_at)

---

## 4. customers
**Descrição:** Armazena os clientes dos restaurantes participantes do programa de fidelidade. Cada cliente pertence a um tenant específico.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do cliente |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| name | VARCHAR | 100 | NOT NULL | Nome completo do cliente |
| email | VARCHAR | 100 | NULL | E-mail do cliente |
| phone | VARCHAR | 20 | NULL | Telefone com DDD (ex: "(81) 98888-1111") |
| cpf | VARCHAR | 14 | NULL | CPF formatado (ex: "111.222.333-44") |
| birth_date | DATE | - | NULL | Data de nascimento do cliente |
| points | INT UNSIGNED | - | NOT NULL, DEFAULT 0 | Pontos acumulados no programa de fidelidade |
| level | ENUM | - | NOT NULL, DEFAULT 'bronze' | Nível de fidelidade: **bronze**, **silver**, **gold**, **platinum** |
| status | ENUM | - | NOT NULL, DEFAULT 'active' | Status: **active**=ativo, **inactive**=inativo, **blocked**=bloqueado |
| address | TEXT | - | NULL | Endereço completo do cliente |
| notes | TEXT | - | NULL | Observações sobre o cliente |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- INDEX (tenant_id, status)
- INDEX (tenant_id, level)
- INDEX (email)
- INDEX (phone)

---

## 5. users
**Descrição:** Usuários do sistema (funcionários dos restaurantes). Controla autenticação, permissões e papéis de acesso.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do usuário |
| tenant_id | BIGINT UNSIGNED | - | NULL | Referência ao restaurante (NULL para super admin) |
| name | VARCHAR | 100 | NOT NULL | Nome completo do usuário |
| email | VARCHAR | 100 | NOT NULL | E-mail para login |
| email_verified_at | TIMESTAMP | - | NULL | Data/hora de verificação do e-mail |
| password | VARCHAR | 255 | NOT NULL | Senha criptografada (hash bcrypt) |
| role | ENUM | - | NOT NULL, DEFAULT 'employee' | Papel: **admin**=administrador, **manager**=gerente, **employee**=funcionário |
| avatar | VARCHAR | 255 | NULL | URL da foto de perfil do usuário |
| permissions | JSON | - | NULL | Array JSON com permissões específicas |
| status | ENUM | - | NOT NULL, DEFAULT 'active' | Status: **active**=ativo, **inactive**=inativo |
| last_login | TIMESTAMP | - | NULL | Data/hora do último login realizado |
| remember_token | VARCHAR | 100 | NULL | Token para "lembrar de mim" |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- INDEX (tenant_id, email)
- INDEX (tenant_id, status)
- INDEX (email)

---

## 6. tables
**Descrição:** Mesas dos restaurantes. Controla disponibilidade, capacidade e status de ocupação.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único da mesa |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| number | VARCHAR | 10 | NOT NULL | Número ou identificador da mesa (ex: "01", "A1") |
| capacity | INT UNSIGNED | - | NOT NULL | Capacidade de pessoas |
| status | ENUM | - | NOT NULL, DEFAULT 'available' | Status: **available**=livre, **occupied**=ocupada, **reserved**=reservada, **maintenance**=manutenção |
| location | VARCHAR | 100 | NULL | Localização física (ex: "Área interna", "Varanda") |
| qr_code | VARCHAR | 255 | NULL | Código QR único para pedidos via app |
| notes | TEXT | - | NULL | Observações sobre a mesa |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- INDEX (tenant_id, status)
- INDEX (tenant_id, number)

---

## 7. categories
**Descrição:** Categorias de produtos do cardápio. Organiza os produtos por tipo.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único da categoria |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| name | VARCHAR | 100 | NOT NULL | Nome da categoria (ex: "Entradas", "Bebidas") |
| description | TEXT | - | NULL | Descrição detalhada da categoria |
| icon | VARCHAR | 255 | NULL | Nome do ícone para exibição |
| color | VARCHAR | 7 | NULL | Cor em hexadecimal (ex: "#FF5722") |
| sort_order | INT UNSIGNED | - | DEFAULT 0 | Ordem de exibição no cardápio |
| is_active | TINYINT(1) | - | NOT NULL, DEFAULT 1 | Se está ativa: **1**=sim, **0**=não |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- INDEX (tenant_id, is_active)

---

## 8. products
**Descrição:** Produtos/itens do cardápio dos restaurantes. Inclui pratos, bebidas e sobremesas.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do produto |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| category_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência à categoria do produto |
| name | VARCHAR | 100 | NOT NULL | Nome do produto |
| description | TEXT | - | NULL | Descrição detalhada do produto |
| price | DECIMAL | 10,2 | NOT NULL | Preço de venda do produto |
| cost | DECIMAL | 10,2 | NULL | Custo do produto (para cálculo de margem) |
| image | VARCHAR | 255 | NULL | URL da imagem do produto |
| sku | VARCHAR | 50 | NULL | Código SKU do produto |
| stock_quantity | INT | - | NULL | Quantidade em estoque |
| stock_control | TINYINT(1) | - | NOT NULL, DEFAULT 0 | Se controla estoque: **1**=sim, **0**=não |
| is_available | TINYINT(1) | - | NOT NULL, DEFAULT 1 | Se está disponível para venda: **1**=sim, **0**=não |
| preparation_time | INT UNSIGNED | - | NULL | Tempo de preparo em minutos |
| calories | INT UNSIGNED | - | NULL | Calorias do produto |
| allergens | JSON | - | NULL | Array JSON com alergênicos (ex: ["gluten", "lactose"]) |
| tags | JSON | - | NULL | Tags do produto (ex: ["vegetariano", "premium"]) |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
- INDEX (tenant_id, category_id)
- INDEX (tenant_id, is_available)
- INDEX (sku)

---

## 9. orders
**Descrição:** Pedidos realizados nos restaurantes. Centraliza informações de venda e relaciona com mesas, clientes e itens.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do pedido |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| table_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência à mesa (NULL para takeaway/delivery) |
| customer_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência ao cliente (programa de fidelidade) |
| user_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Usuário que registrou o pedido |
| order_number | VARCHAR | 50 | NOT NULL | Número único do pedido para identificação |
| status | ENUM | - | NOT NULL, DEFAULT 'pending' | Status: **pending**=pendente, **confirmed**=confirmado, **preparing**=em preparo, **ready**=pronto, **delivered**=entregue, **cancelled**=cancelado |
| type | ENUM | - | NOT NULL, DEFAULT 'dine_in' | Tipo: **dine_in**=no salão, **takeaway**=para viagem, **delivery**=entrega |
| subtotal | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Subtotal dos itens (antes de taxas e descontos) |
| discount | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Valor de desconto aplicado |
| service_fee | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Taxa de serviço (geralmente 10%) |
| delivery_fee | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Taxa de entrega (para delivery) |
| total | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Valor total do pedido |
| payment_status | ENUM | - | NOT NULL, DEFAULT 'pending' | Status do pagamento: **pending**=pendente, **paid**=pago, **partially_paid**=parcialmente pago, **refunded**=reembolsado |
| payment_method | VARCHAR | 50 | NULL | Método de pagamento utilizado |
| notes | TEXT | - | NULL | Observações do pedido |
| cancelled_reason | TEXT | - | NULL | Motivo do cancelamento (quando aplicável) |
| delivered_at | TIMESTAMP | - | NULL | Data/hora da entrega do pedido |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE SET NULL
- FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
- INDEX (tenant_id, status)
- INDEX (tenant_id, created_at)
- INDEX (order_number)

---

## 10. order_items
**Descrição:** Itens individuais dos pedidos. Implementa o relacionamento N:N entre pedidos e produtos, armazenando quantidade e valores.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do item |
| order_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Referência ao pedido (tabela orders) |
| product_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Referência ao produto (tabela products) |
| quantity | INT UNSIGNED | - | NOT NULL | Quantidade do produto no pedido |
| unit_price | DECIMAL | 10,2 | NOT NULL | Preço unitário no momento do pedido |
| subtotal | DECIMAL | 10,2 | NOT NULL | Subtotal (quantidade × preço unitário) |
| discount | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Desconto aplicado ao item |
| total | DECIMAL | 10,2 | NOT NULL | Total do item (subtotal - desconto) |
| notes | TEXT | - | NULL | Observações específicas do item |
| status | ENUM | - | NOT NULL, DEFAULT 'pending' | Status: **pending**=pendente, **preparing**=em preparo, **ready**=pronto, **delivered**=entregue, **cancelled**=cancelado |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
- FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
- INDEX (status)

---

## 11. transactions
**Descrição:** Registra todas as transações financeiras dos restaurantes (receitas e despesas). Permite controle financeiro completo.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único da transação |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| order_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência ao pedido (para vendas) |
| customer_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência ao cliente |
| user_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Usuário responsável pela transação |
| type | ENUM | - | NOT NULL | Tipo: **income**=receita, **expense**=despesa |
| category | VARCHAR | 50 | NOT NULL | Categoria da transação (ex: "Vendas", "Fornecedores", "Utilidades") |
| amount | DECIMAL | 10,2 | NOT NULL | Valor da transação |
| payment_method | VARCHAR | 50 | NULL | Método de pagamento utilizado |
| description | TEXT | - | NOT NULL | Descrição detalhada da transação |
| status | ENUM | - | NOT NULL, DEFAULT 'completed' | Status: **pending**=pendente, **completed**=concluída, **cancelled**=cancelada, **refunded**=reembolsada |
| transaction_date | TIMESTAMP | - | NOT NULL | Data/hora em que a transação ocorreu |
| notes | TEXT | - | NULL | Observações adicionais |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
- FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
- INDEX (tenant_id, type)
- INDEX (tenant_id, transaction_date)

---

## 12. cash_registers
**Descrição:** Caixas dos restaurantes. Controla abertura, fechamento e saldo de cada caixa.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único do caixa |
| tenant_id | BIGINT UNSIGNED | - | NOT NULL | Referência ao restaurante proprietário |
| name | VARCHAR | 100 | NOT NULL | Nome do caixa (ex: "Caixa Principal") |
| opening_balance | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Saldo de abertura (troco inicial) |
| current_balance | DECIMAL | 10,2 | NOT NULL, DEFAULT 0.00 | Saldo atual do caixa |
| status | ENUM | - | NOT NULL, DEFAULT 'closed' | Status: **open**=aberto, **closed**=fechado |
| opened_by | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Usuário que abriu o caixa |
| closed_by | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Usuário que fechou o caixa |
| opened_at | TIMESTAMP | - | NULL | Data/hora de abertura |
| closed_at | TIMESTAMP | - | NULL | Data/hora de fechamento |
| notes | TEXT | - | NULL | Observações sobre o caixa |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (opened_by) REFERENCES users(id) ON DELETE SET NULL
- FOREIGN KEY (closed_by) REFERENCES users(id) ON DELETE SET NULL
- INDEX (tenant_id, status)

---

## 13. cash_movements
**Descrição:** Movimentações de caixa (entradas e saídas). Rastreia todas as operações financeiras realizadas no caixa.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| id | BIGINT UNSIGNED | - | PRIMARY KEY, AUTO_INCREMENT, NOT NULL | Identificador único da movimentação |
| tenant_id | BIGINT UNSIGNED | - | NULL | Referência ao restaurante proprietário |
| cash_register_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Referência ao caixa (tabela cash_registers) |
| user_id | BIGINT UNSIGNED | - | NOT NULL, FOREIGN KEY | Usuário que realizou a movimentação |
| type | ENUM | - | NOT NULL | Tipo: **deposit**=depósito, **withdrawal**=retirada, **sale**=venda, **expense**=despesa |
| amount | DECIMAL | 10,2 | NOT NULL | Valor da movimentação |
| description | VARCHAR | 255 | NOT NULL | Descrição da movimentação |
| notes | TEXT | - | NULL | Observações adicionais |
| order_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência ao pedido (quando aplicável) |
| transaction_id | BIGINT UNSIGNED | - | NULL, FOREIGN KEY | Referência à transação financeira |
| payment_method | ENUM | - | NULL | Método: **cash**=dinheiro, **card**=cartão, **pix**=pix, **other**=outro |
| movement_date | TIMESTAMP | - | NOT NULL | Data/hora da movimentação |
| created_at | TIMESTAMP | - | NULL | Data/hora de criação do registro |
| updated_at | TIMESTAMP | - | NULL | Data/hora da última atualização |

**Índices:**
- PRIMARY KEY (id)
- FOREIGN KEY (cash_register_id) REFERENCES cash_registers(id) ON DELETE CASCADE
- FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
- FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE SET NULL
- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
- INDEX (tenant_id, cash_register_id)
- INDEX (cash_register_id, type)
- INDEX (cash_register_id, movement_date)
- INDEX (user_id, movement_date)

---

## 14. cache
**Descrição:** Tabela do sistema de cache do Laravel. Armazena dados temporários para otimização de performance.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| key | VARCHAR | 255 | PRIMARY KEY, NOT NULL | Chave única do cache |
| value | MEDIUMTEXT | - | NOT NULL | Valor armazenado em cache |
| expiration | INT | - | NOT NULL | Timestamp de expiração do cache |

**Índices:**
- PRIMARY KEY (key)

---

## 15. cache_locks
**Descrição:** Tabela de locks do sistema de cache do Laravel. Controla bloqueios de cache para evitar condições de corrida.

| Atributo | Tipo | Tamanho | Restrições | Semântica |
|----------|------|---------|------------|-----------|
| key | VARCHAR | 255 | PRIMARY KEY, NOT NULL | Chave única do lock |
| owner | VARCHAR | 255 | NOT NULL | Identificador do proprietário do lock |
| expiration | INT | - | NOT NULL | Timestamp de expiração do lock |

**Índices:**
- PRIMARY KEY (key)

---

## NORMALIZAÇÃO

O banco de dados está normalizado até a **Terceira Forma Normal (3FN)**:

### 1ª Forma Normal (1FN)
✅ Todos os atributos contêm apenas valores atômicos  
✅ Não existem grupos repetidos  
✅ Cada atributo contém apenas um valor por linha

### 2ª Forma Normal (2FN)
✅ Está na 1FN  
✅ Todos os atributos não-chave dependem completamente da chave primária  
✅ Não existem dependências parciais

### 3ª Forma Normal (3FN)
✅ Está na 2FN  
✅ Não existem dependências transitivas  
✅ Todos os atributos não-chave dependem apenas da chave primária

**Exemplo de normalização:**
- A tabela `order_items` remove a dependência transitiva entre `orders` e `products`
- A tabela `categories` separa informações de categorização dos produtos
- A tabela `tenant_subscriptions` separa o relacionamento entre tenants e planos

---

## RELACIONAMENTOS

```
tenants (1) ----< (N) tenant_subscriptions (N) >---- (1) tenant_plans
tenants (1) ----< (N) users
tenants (1) ----< (N) customers
tenants (1) ----< (N) tables
tenants (1) ----< (N) categories
tenants (1) ----< (N) products
tenants (1) ----< (N) orders
tenants (1) ----< (N) transactions
tenants (1) ----< (N) cash_registers

categories (1) ----< (N) products

orders (1) ----< (N) order_items (N) >---- (1) products
orders (N) >---- (1) tables
orders (N) >---- (1) customers
orders (N) >---- (1) users

cash_registers (1) ----< (N) cash_movements
cash_movements (N) >---- (1) users
cash_movements (N) >---- (1) orders
cash_movements (N) >---- (1) transactions

transactions (N) >---- (1) orders
transactions (N) >---- (1) customers
transactions (N) >---- (1) users
```

---

**Legenda:**
- `(1)` = Um
- `(N)` = Muitos
- `----<` = Relacionamento um-para-muitos
- `>----` = Relacionamento muitos-para-um

---

**Fim do Dicionário de Dados**