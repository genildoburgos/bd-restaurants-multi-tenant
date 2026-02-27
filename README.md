# Sistema de Gestão de Restaurantes Multi-Tenant

**Disciplina:** Banco de Dados  
**Instituição:** Universidade Federal do Agreste de Pernambuco (UFAPE)  
**Alunos:** Douglas Henrique, Joaci Laurindo, Genildo Burgos, Antonio Marcos  
**Data de Entrega:** 06/02/2026

---

## 📋 Sobre o Projeto

Sistema completo de gestão de restaurantes com arquitetura **multi-tenant**, permitindo que múltiplos restaurantes compartilhem a mesma infraestrutura de banco de dados, mantendo isolamento lógico dos dados.

### Principais Funcionalidades

- ✅ **Multi-tenancy** - Múltiplos restaurantes na mesma base
- ✅ **Gestão de Clientes** - Programa de fidelidade com níveis e pontos
- ✅ **Controle de Pedidos** - Do pendente até entrega
- ✅ **Cardápio Digital** - Produtos organizados por categorias
- ✅ **Sistema de Caixa** - Controle financeiro completo
- ✅ **Planos de Assinatura** - Gestão de planos e cobrança
- ✅ **Controle de Mesas** - Status em tempo real
- ✅ **Transações Financeiras** - Receitas e despesas

---

## 🛠️ Tecnologias Utilizadas

| Tecnologia | Versão | Finalidade |
|------------|--------|------------|
| MySQL | 8.0 | Sistema Gerenciador de Banco de Dados |
| PHP | 8.2 | Backend da aplicação |
| Apache | 2.4 | Servidor web |
| HTML/CSS/JS | — | Frontend da aplicação |
| Docker | Latest | Containerização |
| Docker Compose | 3.8 | Orquestração de containers |
| phpMyAdmin | Latest | Interface web para gerenciamento do banco |

---

## 📊 Estrutura do Banco de Dados

### 13 Tabelas Principais

1. **tenants** - Restaurantes (inquilinos do sistema)
2. **tenant_plans** - Planos de assinatura disponíveis
3. **tenant_subscriptions** - Assinaturas ativas
4. **customers** - Clientes com programa de fidelidade
5. **users** - Usuários do sistema (funcionários)
6. **tables** - Mesas dos restaurantes
7. **categories** - Categorias de produtos
8. **products** - Produtos do cardápio
9. **orders** - Pedidos realizados
10. **order_items** - Itens dos pedidos
11. **transactions** - Transações financeiras
12. **cash_registers** - Caixas
13. **cash_movements** - Movimentações de caixa

### 3 Views SQL

| View | Tabelas Envolvidas | Finalidade |
|------|--------------------|------------|
| **vw_order_summary** | 5 tabelas + subquery | Resumo completo dos pedidos com totais |
| **vw_revenue_by_tenant** | 2 tabelas + GROUP BY | Receita total por restaurante |
| **vw_cash_register_summary** | 4 tabelas + CASE WHEN | Resumo dos caixas com entradas e saídas |

### Diagrama Conceitual

![Diagrama ER](img/Estrutura%20ER.png)

---

## ⚙️ Pré-requisitos

- [Docker](https://www.docker.com/get-started) (v20.10+)
- [Docker Compose](https://docs.docker.com/compose/install/) (v1.29+)

Verificar instalação:
```bash
docker --version
docker-compose --version
```

---

## 🚀 Como Rodar o Projeto

### 1. Clone o Repositório

```bash
git clone https://github.com/seu-usuario/bd-restaurantes-multi-tenant.git
cd bd-restaurantes-multi-tenant
```

### 2. Inicie os Containers

```bash
docker-compose up -d
```

Este comando irá:
- ✅ Baixar as imagens Docker necessárias
- ✅ Criar o container MySQL 8.0
- ✅ Criar o container phpMyAdmin
- ✅ Executar automaticamente os scripts SQL da pasta `sql/`
- ✅ Popular o banco com dados de teste (50+ registros por tabela)

### 3. Aguarde a Inicialização

```bash
# Acompanhar os logs
docker-compose logs -f mysql
```

Aguarde até ver a mensagem:
```
mysqld: ready for connections
```

### 4. Acesse o phpMyAdmin

Abra seu navegador em: **http://localhost:8080**

**Credenciais:**
- **Servidor:** `mysql`
- **Usuário:** `root`
- **Senha:** `root123`

### 5. Verificar o Banco

Execute no phpMyAdmin (aba SQL):

```sql
SELECT 'tenants'     AS tabela, COUNT(*) AS total FROM tenants
UNION ALL SELECT 'customers',   COUNT(*) FROM customers
UNION ALL SELECT 'products',    COUNT(*) FROM products
UNION ALL SELECT 'orders',      COUNT(*) FROM orders
UNION ALL SELECT 'order_items', COUNT(*) FROM order_items
UNION ALL SELECT 'transactions',COUNT(*) FROM transactions;
```

Resultado esperado:

| Tabela | Total |
|--------|-------|
| tenants | 3 |
| customers | 56 |
| products | 54 |
| orders | 56 |
| order_items | 100+ |
| transactions | 53+ |

---

## 📁 Estrutura de Arquivos

```
bd-restaurantes-multi-tenant/
│
├── sql/
│   ├── 01_DDL_estrutura.sql      # Criação das tabelas (DDL)
│   ├── 02_DML_dados_teste.sql    # Dados completos de teste (DML)
│   └── 03_VIEWS.sql              # Criação das 3 views SQL
│
├── app/                          # Aplicação PHP (backend + frontend)
│   ├── Dockerfile                # Imagem PHP 8.2 + Apache
│   ├── index.php                 # Frontend (SPA em HTML/CSS/JS)
│   ├── api.php                   # Backend REST (CRUD)
│   └── includes/
│       └── db.php                # Conexão com o banco de dados
│
├── img/
│   └── Estrutura ER.png          # Diagrama Entidade-Relacionamento
│
├── docker-compose.yml             # Configuração Docker (3 containers)
├── README.md                      # Este arquivo
└── DICIONARIO_DADOS.md            # Dicionário completo de dados
```

---

## 📚 Dicionário de Dados

O dicionário completo está em **[DICIONARIO_DADOS.md](DICIONARIO_DADOS.md)**.

Contém:
- ✅ Descrição de todas as 13 tabelas
- ✅ Tipo de dado, tamanho e restrições de cada campo
- ✅ Semântica detalhada dos atributos
- ✅ Todos os índices e chaves estrangeiras
- ✅ Explicação da normalização (3FN)

---

## 🔄 Normalização

O banco de dados está normalizado até a **Terceira Forma Normal (3FN)**.

### 1ª Forma Normal (1FN)
✅ Todos os atributos contêm valores atômicos  
✅ Não existem grupos repetidos  

### 2ª Forma Normal (2FN)
✅ Está na 1FN  
✅ Todos os atributos não-chave dependem completamente da PK  
✅ Não há dependências parciais  

### 3ª Forma Normal (3FN)
✅ Está na 2FN  
✅ Não há dependências transitivas  
✅ Atributos não-chave dependem apenas da PK  

**Exemplos de Normalização:**
- `order_items` implementa N:N entre `orders` e `products`
- `categories` separa informações de categorização
- `tenant_subscriptions` separa relacionamento entre tenants e planos

---

## 🌱 Povoamento do Banco

### Método Utilizado

Os dados foram inseridos via **script SQL (DML)** executado automaticamente pelo Docker na primeira inicialização.

O arquivo `02_DML_dados_teste.sql` contém todos os dados de teste, incluindo os 3 restaurantes (tenants), clientes, produtos, pedidos e transações — garantindo que o banco inicie populado e funcional sem nenhuma intervenção manual.

### Dados de Teste Inseridos

| Entidade | Quantidade | Detalhes |
|----------|------------|----------|
| **Planos** | 3 | Básico (R$ 49), Intermediário (R$ 147), Profissional (R$ 297) |
| **Restaurantes** | 3 | Anotado Restaurante, Sabor da Casa, Cantina Italiana |
| **Assinaturas** | 3 | Uma por restaurante |
| **Usuários** | 6 | 1 super admin + 5 funcionários distribuídos por restaurante |
| **Clientes** | 56 | Com níveis bronze, silver, gold e platinum |
| **Mesas** | 11 | Distribuídas entre os 3 restaurantes |
| **Categorias** | 11 | Entradas, Massas, Bebidas, Antipasti, Pizza, Vinhos, etc. |
| **Produtos** | 54 | Cardápio completo por restaurante com preços e custos |
| **Pedidos** | 56 | dine_in, takeaway e delivery — inclui cancelados |
| **Itens de Pedidos** | 100+ | Relacionamento entre pedidos e produtos |
| **Caixas** | 4 | Com saldos e status |
| **Transações** | 53+ | Vendas, despesas com fornecedores e folha |
| **Movimentações** | 8 | Entradas e saídas de caixa |

### Por que este método?

✅ **Automático** - Executa na inicialização do Docker  
✅ **Reproduzível** - Sempre gera os mesmos dados  
✅ **Didático** - Fácil de entender e auditar  
✅ **Consistente** - Garante integridade referencial  

---

## 🔍 Views SQL

As views foram criadas para simplificar consultas complexas envolvendo múltiplos JOINs e cálculos agregados.

### vw_order_summary
Resume todos os pedidos com dados do restaurante, mesa, cliente e totais calculados. Envolve 5 tabelas e subquery de agregação.

```sql
SELECT * FROM vw_order_summary LIMIT 10;
```

### vw_revenue_by_tenant
Calcula a receita total por restaurante, agrupando pedidos entregues e pagos.

```sql
SELECT * FROM vw_revenue_by_tenant;
```

### vw_cash_register_summary
Consolida o saldo de cada caixa com total de entradas e saídas usando `CASE WHEN`.

```sql
SELECT * FROM vw_cash_register_summary;
```

---

## 🔍 Queries de Exemplo

### Produtos por Categoria

```sql
SELECT 
    c.name AS categoria,
    p.name AS produto,
    p.price AS preco,
    p.is_available AS disponivel
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
WHERE p.tenant_id = 1
ORDER BY c.name, p.name;
```

### Clientes VIP (Fidelidade)

```sql
SELECT 
    name, email, points, level
FROM customers
WHERE tenant_id = 1
  AND level IN ('gold', 'platinum')
  AND status = 'active'
ORDER BY points DESC;
```

### Relatório de Vendas por Categoria

```sql
SELECT 
    c.name AS categoria,
    COUNT(DISTINCT o.id) AS pedidos,
    SUM(oi.quantity) AS itens_vendidos,
    SUM(oi.total) AS receita
FROM order_items oi
INNER JOIN products p ON oi.product_id = p.id
INNER JOIN categories c ON p.category_id = c.id
INNER JOIN orders o ON oi.order_id = o.id
WHERE o.tenant_id = 1
  AND o.status = 'delivered'
GROUP BY c.id, c.name
ORDER BY receita DESC;
```

---

## 🛑 Comandos Úteis

### Gerenciar Containers

```bash
# Parar containers
docker-compose stop

# Iniciar containers parados
docker-compose start

# Parar e remover
docker-compose down

# Parar e remover TUDO (inclusive dados) — use para recriar do zero
docker-compose down -v

# Ver logs
docker-compose logs -f mysql

# Status dos containers
docker-compose ps
```

### Backup e Restore

```bash
# Fazer backup
docker exec restaurant_mysql mysqldump -uroot -proot123 laravel_restaurants > backup.sql

# Restaurar backup
docker exec -i restaurant_mysql mysql -uroot -proot123 laravel_restaurants < backup.sql
```

---

## 🐛 Solução de Problemas

### Banco não populou (tabelas vazias)

O Docker só executa os scripts de inicialização **uma vez**. Se o container já existia sem dados, destrua tudo e recomece:

```bash
docker-compose down -v
docker-compose up -d
```

### Porta 8080 em uso

Edite o `docker-compose.yml` e mude a porta do phpMyAdmin:
```yaml
ports:
  - "8081:80"  # Usar porta 8081
```

### Ver logs completos

```bash
docker logs restaurant_mysql
```

---

## 🔐 Acesso ao Sistema

### Sistema Principal (PHP + Apache)

**URL:** http://localhost:3000

Funcionalidades disponíveis:
- Dashboard com estatísticas gerais
- CRUD de Produtos (criar, editar, excluir)
- CRUD de Clientes (criar, editar, excluir)
- Listagem de Pedidos
- Página de Visões SQL com as 3 views

### Interface de Banco de Dados (phpMyAdmin)

**URL:** http://localhost:8080

**Credenciais MySQL:**
- Usuário: `root`
- Senha: `root123`

### Credenciais dos Usuários do Sistema

| Email | Papel | Restaurante |
|-------|-------|-------------|
| admin@servefacil.com | Super Admin | Todos |
| admin@anotado.com | Admin | Anotado Restaurante |
| gerente@anotado.com | Gerente | Anotado Restaurante |
| admin@sabordacasa.com | Admin | Sabor da Casa |
| admin@cantina.com | Admin | Cantina Italiana |

> **Nota:** Senhas criptografadas com bcrypt. Senha padrão: `password`

---

## ✅ Requisitos da Atividade

| Requisito | Status | Arquivo/Localização |
|-----------|--------|---------------------|
| Esquema Lógico | ✅ | 13 tabelas com PKs e FKs em `01_DDL_estrutura.sql` |
| Mínimo 3 Views | ✅ | `vw_order_summary`, `vw_revenue_by_tenant`, `vw_cash_register_summary` |
| CRUD Funcional | ✅ | `app/api.php` — Produtos e Clientes (Create, Read, Update, Delete) |
| Frontend | ✅ | `app/index.php` — SPA em HTML/CSS/JS puro |
| Tela com as Views | ✅ | Página "Visoes SQL" em `app/index.php` exibe as 3 views |
| Dicionário de Dados | ✅ | `DICIONARIO_DADOS.md` completo |
| Normalização (3FN) | ✅ | Documentado no dicionário |
| Script DDL | ✅ | `sql/01_DDL_estrutura.sql` |
| Script DML | ✅ | `sql/02_DML_dados_teste.sql` |
| 50+ registros por tabela | ✅ | customers=56, products=54, orders=56 |
| Docker | ✅ | `docker-compose.yml` — sistema na porta `3000`, phpMyAdmin na porta `8080` |
| README | ✅ | Este arquivo |
| Banco Funcional | ✅ | Executando em Docker, inicialização automática |

---

## 👨‍💻 Autores

| Nome | Curso | Instituição |
|------|-------|-------------|
| Douglas Henrique | Bacharelado em Ciência da Computação | UFAPE |
| Joaci Laurindo | Bacharelado em Ciência da Computação | UFAPE |
| Genildo Burgos | Bacharelado em Ciência da Computação | UFAPE |
| Antonio Marcos | Bacharelado em Ciência da Computação | UFAPE |

**Disciplina:** Banco de Dados  
**Professor(a):** Priscilla Kelly Machado Vieira Azevedo  
**Data:** 06/02/2026

---

## 📜 Licença

Projeto desenvolvido para fins acadêmicos como parte da avaliação da disciplina de Banco de Dados — 2VA.

---

**🎯 Trabalho entregue em conformidade com todos os requisitos da 2VA**