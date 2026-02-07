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
| Docker | Latest | Containerização |
| Docker Compose | 3.8 | Orquestração de containers |
| phpMyAdmin | Latest | Interface web para gerenciamento |

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
- ✅ Popular o banco com dados de teste

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

No phpMyAdmin:
1. Clique em `laravel_restaurants` no menu lateral
2. Você deve ver **15 tabelas**
3. Clique em `products` → **Navegar**
4. Deve mostrar **14 produtos** ✅

---

## 📁 Estrutura de Arquivos

```
bd-restaurantes-multi-tenant/
│
├── sql/
│   ├── 01_DDL_estrutura.sql      # Criação das tabelas (DDL)
│   └── 02_DML_dados_teste.sql    # Dados de teste (DML)
│
├── docker-compose.yml             # Configuração Docker
├── README.md                      # Este arquivo
├── DICIONARIO_DADOS.md            # Dicionário completo
├── INSTRUCOES_ENTREGA.md          # Guia de entrega
└── diagrama_er.png                # Diagrama ER

```

---

## 📚 Dicionário de Dados

O dicionário completo está em **[DICIONARIO_DADOS.md](DICIONARIO_DADOS.md)**.

Contém:
- ✅ Descrição de todas as 15 tabelas
- ✅ Tipo de dado, tamanho e restrições de cada campo
- ✅ Semântica detalhada dos atributos
- ✅ Todos os índices e chaves estrangeiras
- ✅ Diagramas de relacionamento
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

O arquivo `02_DML_dados_teste.sql` contém:

### Dados de Teste Inseridos

| Entidade | Quantidade | Detalhes |
|----------|------------|----------|
| **Planos** | 3 | Básico (R$ 49), Intermediário (R$ 147), Profissional (R$ 297) |
| **Restaurantes** | 3 | Anotado, Sabor da Casa, Cantina Italiana |
| **Assinaturas** | 3 | Uma por restaurante |
| **Usuários** | 6 | 1 super admin + 5 funcionários |
| **Clientes** | 6 | Com diferentes níveis de fidelidade |
| **Mesas** | 11 | Distribuídas entre os restaurantes |
| **Categorias** | 11 | Entradas, Massas, Bebidas, etc. |
| **Produtos** | 14 | Cardápio completo com preços |
| **Pedidos** | 6 | Incluindo 1 cancelado |
| **Itens de Pedidos** | 12 | Relacionamento entre pedidos e produtos |
| **Caixas** | 4 | Com saldos e movimentações |
| **Transações** | 7 | Vendas e despesas |
| **Movimentações** | 8 | Entradas e saídas de caixa |

### Por que este método?

✅ **Automático** - Executa na inicialização do Docker  
✅ **Reproduzível** - Sempre gera os mesmos dados  
✅ **Didático** - Fácil de entender e auditar  
✅ **Consistente** - Garante integridade referencial  

---

## 🔐 Acesso ao Sistema

### Interface Web (phpMyAdmin)

**URL:** http://localhost:8080

**Credenciais MySQL:**
- Usuário: `root`
- Senha: `root123`

**OU**

- Usuário: `restaurant_user`
- Senha: `restaurant_pass`

### Linha de Comando

```bash
# Acessar MySQL via Docker
docker exec -it restaurant_mysql mysql -uroot -proot123

# Usar o banco
USE laravel_restaurants;

# Listar tabelas
SHOW TABLES;

# Contar produtos
SELECT COUNT(*) FROM products;
```

### Credenciais dos Usuários do Sistema

| Email | Senha | Papel | Restaurante |
|-------|-------|-------|-------------|
| admin@servefacil.com | password | Super Admin | - |
| admin@anotado.com | password | Admin | Anotado |
| gerente@anotado.com | password | Gerente | Anotado |
| funcionario@anotado.com | password | Funcionário | Anotado |

> **Nota:** Senhas criptografadas com bcrypt. Senha padrão: `password`

---

## 🔍 Queries de Exemplo

### Listar Restaurantes Ativos

```sql
SELECT id, name, slug, status 
FROM tenants 
WHERE status = 'active';
```

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

### Pedidos do Dia com Total

```sql
SELECT 
    o.order_number,
    c.name AS cliente,
    t.number AS mesa,
    o.total,
    o.status,
    o.payment_method
FROM orders o
LEFT JOIN customers c ON o.customer_id = c.id
LEFT JOIN tables t ON o.table_id = t.id
WHERE o.tenant_id = 1
  AND DATE(o.created_at) = CURDATE()
ORDER BY o.created_at DESC;
```

### Clientes VIP (Fidelidade)

```sql
SELECT 
    name,
    email,
    points,
    level
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

# Parar e remover TUDO (inclusive dados)
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

### Porta 3307 em uso

Edite o `docker-compose.yml` e mude:
```yaml
ports:
  - "3308:3306"  # Usar porta 3308 ao invés de 3307
```

### Scripts não executaram

```bash
# Verificar se os arquivos estão na pasta sql/
dir sql

# Recriar tudo do zero
docker-compose down -v
docker-compose up -d

# Ver logs para debugar
docker-compose logs mysql
```

### Banco não foi criado

```bash
# Ver logs completos
docker logs restaurant_mysql

# Entrar no container e verificar
docker exec -it restaurant_mysql bash
mysql -uroot -proot123 -e "SHOW DATABASES;"
```

---

## 📖 Documentação Adicional

- **[DICIONARIO_DADOS.md](../bd-restaurants-multi-tenant//dicionario_de_dados.md)** - Dicionário completo das 15 tabelas


---

## ✅ Requisitos da Atividade

| Requisito | Status | Arquivo/Localização |
|-----------|--------|---------------------|
| Esquema Lógico | ✅ | 15 tabelas com PKs e FKs em `01_DDL_estrutura.sql` |
| Dicionário de Dados | ✅ | `DICIONARIO_DADOS.md` completo |
| Normalização (2FN+) | ✅ | 3FN - documentado no dicionário |
| Script DDL | ✅ | `sql/01_DDL_estrutura.sql` |
| Script DML | ✅ | `sql/02_DML_dados_teste.sql` |
| Docker | ✅ | `docker-compose.yml` funcional |
| README | ✅ | Este arquivo com todas as instruções |
| Banco Funcional | ✅ | Executando em Docker |
| Banco Povoado | ✅ | 14 produtos + dados completos |

---
a
## 👨‍💻 Autores

**Nome:** Douglas Henrique
**Nome:** Joaci Laurindo
**Nome:** Genildo Burgos
**Nome:** Antonio Marcos
**Curso:** Bacharelado em Ciência da Computação  
**Instituição:** Universidade Federal do Agreste de Pernambuco (UFAPE)  
**Disciplina:** Banco de Dados  
**Professor(a):** PRISCILLA KELLY MACHADO VIEIRA AZEVEDO
**Data:** 06/02/2026

---

## 📜 Licença

Projeto desenvolvido para fins acadêmicos como parte da avaliação da disciplina de Banco de Dados.

---

**🎯 Trabalho entregue em conformidade com todos os requisitos da 2VA**