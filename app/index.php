<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ServeFacil — Gestão de Restaurantes</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
:root{--bg:#0f0e0d;--surface:#1a1917;--surface2:#242220;--border:#2e2c29;--accent:#e8a44a;--text:#f0ece4;--muted:#7a7570;--success:#4caf7d;--danger:#e05252;--info:#5b9bd5;--warn:#e8c44a}
*{margin:0;padding:0;box-sizing:border-box}
body{background:var(--bg);color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px;display:flex;min-height:100vh}
.sidebar{width:220px;min-height:100vh;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50}
.logo{padding:24px 20px 18px;border-bottom:1px solid var(--border)}
.logo-name{font-family:'DM Serif Display',serif;font-size:22px;color:var(--accent)}
.logo-sub{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:2px;margin-top:2px}
.nav{padding:14px 0;flex:1;overflow-y:auto}
.nav-section{padding:10px 20px 4px;font-size:10px;text-transform:uppercase;letter-spacing:1.5px;color:var(--muted)}
.nav-item{display:flex;align-items:center;gap:9px;padding:9px 20px;color:var(--muted);cursor:pointer;font-size:13px;font-weight:500;border-left:2px solid transparent;transition:all .15s;user-select:none}
.nav-item:hover{color:var(--text);background:rgba(255,255,255,.03)}
.nav-item.active{color:var(--accent);border-left-color:var(--accent);background:rgba(232,164,74,.06)}
.nav-icon{font-size:15px;width:18px;text-align:center}
.main{margin-left:220px;padding:32px;flex:1;min-height:100vh}
.page-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:26px}
.page-title{font-family:'DM Serif Display',serif;font-size:30px;line-height:1}
.page-title em{color:var(--accent);font-style:italic}
.page-sub{color:var(--muted);font-size:13px;margin-top:4px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;border:none;font-family:'DM Sans',sans-serif;transition:all .15s}
.btn-primary{background:var(--accent);color:#1a1408}.btn-primary:hover{background:#f0b35a}
.btn-ghost{background:transparent;color:var(--muted);border:1px solid var(--border)}.btn-ghost:hover{color:var(--text);border-color:var(--muted)}
.btn-sm{padding:5px 10px;font-size:12px;border-radius:5px}
.btn-danger{background:rgba(224,82,82,.12);color:var(--danger);border:1px solid rgba(224,82,82,.2)}
.btn-success{background:rgba(76,175,125,.15);color:var(--success);border:1px solid rgba(76,175,125,.3)}
.btn-info{background:rgba(91,155,213,.15);color:var(--info);border:1px solid rgba(91,155,213,.3)}
.btn-pdf{background:rgba(224,82,82,.15);color:#ff8a8a;border:1px solid rgba(224,82,82,.3)}.btn-pdf:hover{background:rgba(224,82,82,.28)}
.btn-csv{background:rgba(76,175,125,.15);color:#6fcf97;border:1px solid rgba(76,175,125,.3)}.btn-csv:hover{background:rgba(76,175,125,.28)}
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:26px}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:18px 20px;position:relative;overflow:hidden}
.stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--c,var(--accent))}
.stat-label{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted)}
.stat-value{font-family:'DM Serif Display',serif;font-size:28px;color:var(--text);margin:5px 0 2px}
.stat-hint{font-size:12px;color:var(--muted)}
.card{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:20px}
.card-header{padding:14px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.card-title{font-size:14px;font-weight:600}
.card-badge{font-size:11px;background:rgba(232,164,74,.12);color:var(--accent);padding:3px 8px;border-radius:20px}
table{width:100%;border-collapse:collapse}
th{padding:10px 16px;text-align:left;font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:.8px;color:var(--muted);border-bottom:1px solid var(--border)}
td{padding:12px 16px;border-bottom:1px solid rgba(46,44,41,.5);font-size:13px;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:rgba(255,255,255,.015)}
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:20px;font-size:11px;font-weight:500;white-space:nowrap}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor}
.bg{background:rgba(76,175,125,.12);color:var(--success)}
.br{background:rgba(224,82,82,.12);color:var(--danger)}
.ba{background:rgba(232,164,74,.12);color:var(--accent)}
.bb{background:rgba(91,155,213,.12);color:var(--info)}
.bm{background:rgba(122,117,112,.12);color:var(--muted)}
.toolbar{display:flex;gap:10px;margin-bottom:16px;align-items:center}
.search{flex:1;padding:8px 12px;background:var(--surface);border:1px solid var(--border);border-radius:6px;color:var(--text);font-size:13px;font-family:'DM Sans',sans-serif}
.search:focus{outline:none;border-color:var(--accent)}
select.search{cursor:pointer}
.actions{display:flex;gap:5px;flex-wrap:wrap}
.slabel{display:flex;align-items:center;gap:10px;margin-bottom:14px;margin-top:8px}
.slabel h3{font-size:14px;font-weight:600;white-space:nowrap}
.slabel .line{flex:1;height:1px;background:var(--border)}
.slabel .tag{font-size:10px;background:rgba(91,155,213,.15);color:var(--info);padding:2px 7px;border-radius:10px;white-space:nowrap}
.view-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.view-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:22px;text-align:center}
.view-card-num{font-family:'DM Serif Display',serif;font-size:36px;color:var(--accent)}
.view-card-name{font-size:14px;font-weight:500;margin-top:8px}
.view-card-sub{font-size:12px;color:var(--muted);margin-top:3px}
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px}
.charts-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px}
.chart-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:20px}
.chart-title{font-size:13px;font-weight:600;margin-bottom:14px;color:var(--text)}
.chart-wrap{position:relative;height:230px}
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:200;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.overlay.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:560px;max-height:90vh;overflow-y:auto}
.modal-lg{width:700px}
.modal-title{font-family:'DM Serif Display',serif;font-size:22px;margin-bottom:20px}
.fg{margin-bottom:14px}
.fl{display:block;font-size:11px;font-weight:500;color:var(--muted);margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px}
.fi{width:100%;padding:9px 12px;background:var(--bg);border:1px solid var(--border);border-radius:6px;color:var(--text);font-size:13px;font-family:'DM Sans',sans-serif}
.fi:focus{outline:none;border-color:var(--accent)}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.fr3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}
.modal-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)}
.item-row{display:grid;grid-template-columns:1fr 80px 100px 32px;gap:8px;align-items:center;margin-bottom:8px}
.item-row .fi{margin:0}
.add-item-btn{display:flex;align-items:center;gap:6px;padding:8px;background:rgba(232,164,74,.08);border:1px dashed var(--accent);border-radius:6px;color:var(--accent);cursor:pointer;font-size:12px;width:100%;justify-content:center;margin-bottom:14px}
.add-item-btn:hover{background:rgba(232,164,74,.15)}
.remove-btn{background:rgba(224,82,82,.1);color:var(--danger);border:none;border-radius:4px;cursor:pointer;padding:6px 8px;font-size:13px}
.order-detail{padding:16px 18px}
.order-detail-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px}
.order-items-list{border:1px solid var(--border);border-radius:8px;overflow:hidden}
.order-item-row{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-bottom:1px solid rgba(46,44,41,.5)}
.order-item-row:last-child{border-bottom:none}
.toast{position:fixed;bottom:24px;right:24px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:12px 18px;font-size:13px;z-index:300;opacity:0;transform:translateY(8px);transition:all .25s;pointer-events:none}
.toast.show{opacity:1;transform:translateY(0)}
.toast.ok{border-left:3px solid var(--success)}
.toast.err{border-left:3px solid var(--danger)}
.loading{text-align:center;padding:40px;color:var(--muted);font-size:13px}
.empty{text-align:center;padding:40px;color:var(--muted);font-size:13px}
.page{display:none}.page.active{display:block}
.report-header{display:none}
@media print {
  @page{margin:15mm}
  body{background:#fff!important;color:#000!important;font-size:11px}
  .sidebar,.btn,.toolbar,.actions,.overlay{display:none!important}
  .main{margin-left:0!important;padding:0!important}
  .page{display:none!important}
  #page-reports.active{display:block!important}
  .report-header{display:block!important;border-bottom:2px solid #333;padding-bottom:12px;margin-bottom:20px}
  .stat-card,.card,.chart-card,.view-card{background:#fff!important;border:1px solid #bbb!important;break-inside:avoid}
  .stat-value,.stat-label,.stat-hint,.chart-title,.card-title,.page-title,.page-sub{color:#000!important}
  th,td{color:#000!important;border-color:#ccc!important}
  .badge{background:#eee!important;color:#333!important;border:1px solid #bbb}.badge::before{display:none}
}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="logo">
    <div class="logo-name">ServeFacil</div>
    <div class="logo-sub">Multi-Tenant v3.0</div>
  </div>
  <nav class="nav">
    <div class="nav-section">Principal</div>
    <div class="nav-item active" data-page="dashboard"><span class="nav-icon">🏠</span> Dashboard</div>
    <div class="nav-section">Cadastros</div>
    <div class="nav-item" data-page="orders"><span class="nav-icon">📋</span> Pedidos</div>
    <div class="nav-item" data-page="products"><span class="nav-icon">📦</span> Estoque</div>
    <div class="nav-item" data-page="customers"><span class="nav-icon">👤</span> Clientes</div>
    <div class="nav-section">Análises</div>
    <div class="nav-item" data-page="reports"><span class="nav-icon">📈</span> Relatórios</div>
    <div class="nav-item" data-page="views"><span class="nav-icon">📊</span> Visões SQL</div>
  </nav>
</aside>

<main class="main">

  <!-- DASHBOARD -->
  <div id="page-dashboard" class="page active">
    <div class="page-header">
      <div>
        <div class="page-title">Bem-vindo, <em>Admin</em></div>
        <div class="page-sub" id="dash-date"></div>
      </div>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-label">Receita Total</div><div class="stat-value" id="s-revenue">...</div><div class="stat-hint">pedidos entregues</div></div>
      <div class="stat-card" style="--c:var(--success)"><div class="stat-label">Pedidos Entregues</div><div class="stat-value" id="s-orders">...</div><div class="stat-hint">status delivered</div></div>
      <div class="stat-card" style="--c:var(--info)"><div class="stat-label">Clientes</div><div class="stat-value" id="s-customers">...</div><div class="stat-hint">cadastrados</div></div>
      <div class="stat-card" style="--c:var(--warn)"><div class="stat-label">Produtos</div><div class="stat-value" id="s-products">...</div><div class="stat-hint">em estoque</div></div>
    </div>
    <div class="slabel"><h3>Últimos Pedidos</h3><div class="line"></div></div>
    <div class="card">
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Total</th><th>Status</th></tr></thead>
        <tbody id="dash-orders"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- PEDIDOS -->
  <div id="page-orders" class="page">
    <div class="page-header">
      <div><div class="page-title">Pedidos</div><div class="page-sub">Histórico completo</div></div>
      <button class="btn btn-primary" onclick="openOrderModal()">+ Novo Pedido</button>
    </div>
    <div class="toolbar">
      <select class="search" style="max-width:200px" id="ord-tenant" onchange="loadOrders()">
        <option value="">Todos os restaurantes</option>
      </select>
      <select class="search" style="max-width:160px" id="ord-status" onchange="loadOrders()">
        <option value="">Todos os status</option>
        <option value="pending">Pendente</option>
        <option value="preparing">Preparando</option>
        <option value="ready">Pronto</option>
        <option value="delivered">Entregue</option>
        <option value="cancelled">Cancelado</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">Todos os Pedidos</span><span class="card-badge" id="ord-count">...</span></div>
      <table>
        <thead><tr><th>#</th><th>Nº Pedido</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Total</th><th>Status</th><th>Ações</th></tr></thead>
        <tbody id="ord-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ESTOQUE / PRODUTOS -->
  <div id="page-products" class="page">
    <div class="page-header">
      <div><div class="page-title">Estoque</div><div class="page-sub">Controle de produtos do cardápio</div></div>
      <button class="btn btn-primary" onclick="openProductModal()">+ Novo Item</button>
    </div>
    <div class="toolbar">
      <input class="search" placeholder="Buscar produto..." id="prod-search" oninput="loadProducts()">
      <select class="search" style="max-width:200px" id="prod-tenant" onchange="loadProducts()">
        <option value="">Todos os restaurantes</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">Produtos Cadastrados</span><span class="card-badge" id="prod-count">...</span></div>
      <table>
        <thead><tr><th>Nome</th><th>Categoria</th><th>Restaurante</th><th>Preço</th><th>Estoque</th><th>Disponível</th><th>Ações</th></tr></thead>
        <tbody id="prod-tbody"><tr><td colspan="7" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- CLIENTES -->
  <div id="page-customers" class="page">
    <div class="page-header">
      <div><div class="page-title">Clientes</div><div class="page-sub">Programa de fidelidade</div></div>
      <button class="btn btn-primary" onclick="openCustomerModal()">+ Novo Cliente</button>
    </div>
    <div class="toolbar">
      <input class="search" placeholder="Buscar cliente..." id="cust-search" oninput="loadCustomers()">
      <select class="search" style="max-width:200px" id="cust-tenant" onchange="loadCustomers()">
        <option value="">Todos os restaurantes</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">Clientes Cadastrados</span><span class="card-badge" id="cust-count">...</span></div>
      <table>
        <thead><tr><th>Nome</th><th>Telefone</th><th>Restaurante</th><th>Nível</th><th>Pontos</th><th>Total Gasto</th><th>Visitas</th><th>Ações</th></tr></thead>
        <tbody id="cust-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- RELATÓRIOS -->
  <div id="page-reports" class="page">
    <div class="report-header"><h1>ServeFacil — Relatório Gerencial</h1><p id="report-print-date"></p></div>
    <div class="page-header">
      <div><div class="page-title">Relatórios <em>Gerenciais</em></div><div class="page-sub">Dashboards e análises do sistema</div></div>
      <div style="display:flex;gap:8px"><button class="btn btn-csv" onclick="openCSVModal()">📥 Exportar CSV</button><button class="btn btn-pdf" onclick="exportPDF()">🖨️ Exportar PDF</button></div>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-label">Receita Total</div><div class="stat-value" id="rep-revenue">...</div><div class="stat-hint">pedidos entregues</div></div>
      <div class="stat-card" style="--c:var(--success)"><div class="stat-label">Ticket Médio</div><div class="stat-value" id="rep-ticket">...</div><div class="stat-hint">por pedido entregue</div></div>
      <div class="stat-card" style="--c:var(--info)"><div class="stat-label">Total de Pedidos</div><div class="stat-value" id="rep-total-orders">...</div><div class="stat-hint">todos os status</div></div>
      <div class="stat-card" style="--c:var(--warn)"><div class="stat-label">Clientes Ativos</div><div class="stat-value" id="rep-customers">...</div><div class="stat-hint">com pedido entregue</div></div>
    </div>
    <div class="charts-grid">
      <div class="chart-card"><div class="chart-title">📊 Receita por Restaurante</div><div class="chart-wrap"><canvas id="chart-revenue"></canvas></div></div>
      <div class="chart-card"><div class="chart-title">🥧 Distribuição de Status dos Pedidos</div><div class="chart-wrap"><canvas id="chart-status"></canvas></div></div>
    </div>
    <div class="charts-grid-3">
      <div class="chart-card"><div class="chart-title">📦 Top 5 Produtos Mais Vendidos</div><div class="chart-wrap"><canvas id="chart-top-products"></canvas></div></div>
      <div class="chart-card"><div class="chart-title">👑 Clientes por Nível de Fidelidade</div><div class="chart-wrap"><canvas id="chart-levels"></canvas></div></div>
      <div class="chart-card"><div class="chart-title">💳 Vendas por Método de Pagamento</div><div class="chart-wrap"><canvas id="chart-payment"></canvas></div></div>
    </div>
    <div class="slabel"><h3>Produtos Mais Vendidos</h3><div class="line"></div><span class="tag">ORDER BY qty_sold DESC</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Ranking de Vendas por Produto</span></div>
      <table><thead><tr><th>Rank</th><th>Produto</th><th>Categoria</th><th>Qtd. Vendida</th><th>Receita Gerada</th><th>Estoque</th></tr></thead>
      <tbody id="rep-top-products"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody></table>
    </div>
    <div class="slabel"><h3>Clientes — Maiores Gastos</h3><div class="line"></div><span class="tag">ORDER BY total_spent DESC</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Top 10 Clientes por Volume de Gasto</span></div>
      <table><thead><tr><th>Cliente</th><th>Restaurante</th><th>Nível</th><th>Pontos</th><th>Total Gasto</th><th>Visitas</th></tr></thead>
      <tbody id="rep-top-customers"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody></table>
    </div>
    <div class="slabel"><h3>Estoque Crítico</h3><div class="line"></div><span class="tag">stock_quantity &lt; 10</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Produtos com Estoque Baixo</span></div>
      <table><thead><tr><th>Produto</th><th>Categoria</th><th>Restaurante</th><th>Qtd. em Estoque</th><th>Situação</th></tr></thead>
      <tbody id="rep-low-stock"><tr><td colspan="5" class="loading">Carregando...</td></tr></tbody></table>
    </div>
  </div>

  <!-- VISÕES SQL -->
  <div id="page-views" class="page">
    <div class="page-header"><div><div class="page-title">Visões <em>SQL</em></div><div class="page-sub">Dados das 3 views do banco de dados</div></div></div>
    <div class="slabel"><h3>vw_revenue_by_tenant</h3><div class="line"></div><span class="tag">Receita por restaurante</span></div>
    <div class="view-cards" id="view-revenue"><div class="loading">Carregando...</div></div>
    <div class="slabel"><h3>vw_order_summary</h3><div class="line"></div><span class="tag">JOIN de 5 tabelas</span></div>
    <div class="card" style="margin-bottom:24px">
      <div class="card-header"><span class="card-title">Resumo de Pedidos</span><span class="card-badge">Últimos 50</span></div>
      <table><thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Itens</th><th>Subtotal</th><th>Total</th><th>Status</th></tr></thead>
      <tbody id="view-orders"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody></table>
    </div>
    <div class="slabel"><h3>vw_cash_register_summary</h3><div class="line"></div><span class="tag">CASE WHEN + 4 tabelas</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Resumo dos Caixas</span></div>
      <table><thead><tr><th>Caixa</th><th>Restaurante</th><th>Aberto por</th><th>Saldo Inicial</th><th>Total Entradas</th><th>Total Saídas</th><th>Status</th></tr></thead>
      <tbody id="view-cash"><tr><td colspan="7" class="loading">Carregando...</td></tr></tbody></table>
    </div>
  </div>

</main>

<!-- MODAL NOVO PEDIDO -->
<div class="overlay" id="modal-order">
  <div class="modal modal-lg">
    <div class="modal-title">Novo Pedido</div>
    <div class="fr">
      <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="ord-tenant-sel" onchange="onOrderTenantChange()"><option value="">Selecione...</option></select></div>
      <div class="fg"><label class="fl">Tipo *</label>
        <select class="fi" id="ord-type">
          <option value="dine_in">Mesa (Dine In)</option>
          <option value="takeaway">Retirada</option>
          <option value="delivery">Delivery</option>
        </select>
      </div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Mesa</label><select class="fi" id="ord-table"><option value="">Sem mesa</option></select></div>
      <div class="fg"><label class="fl">Cliente</label><select class="fi" id="ord-customer"><option value="">Sem cliente</option></select></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Pagamento</label>
        <select class="fi" id="ord-payment">
          <option value="">A definir</option>
          <option value="cash">Dinheiro</option>
          <option value="card">Cartão</option>
          <option value="pix">PIX</option>
        </select>
      </div>
      <div class="fg"><label class="fl">Observações</label><input class="fi" id="ord-notes" placeholder="Observações do pedido"></div>
    </div>
    <div class="slabel"><h3>Itens do Pedido</h3><div class="line"></div></div>
    <div id="order-items-container"></div>
    <div class="add-item-btn" onclick="addOrderItemRow()">+ Adicionar Item</div>
    <div style="background:var(--surface2);border-radius:8px;padding:12px 16px;margin-bottom:14px">
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
        <span style="color:var(--muted)">Subtotal</span>
        <span id="ord-subtotal">R$ 0,00</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:600">
        <span>Total</span>
        <span id="ord-total" style="color:var(--accent)">R$ 0,00</span>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-order')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveOrder()">Criar Pedido</button>
    </div>
  </div>
</div>

<!-- MODAL VER PEDIDO -->
<div class="overlay" id="modal-order-view">
  <div class="modal modal-lg">
    <div class="modal-title" id="view-order-title">Pedido</div>
    <div id="view-order-body"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-order-view')">Fechar</button>
    </div>
  </div>
</div>

<!-- MODAL STATUS PEDIDO -->
<div class="overlay" id="modal-order-status">
  <div class="modal" style="width:400px">
    <div class="modal-title">Atualizar Status</div>
    <input type="hidden" id="status-order-id">
    <div class="fg"><label class="fl">Novo Status</label>
      <select class="fi" id="status-new">
        <option value="pending">Pendente</option>
        <option value="confirmed">Confirmado</option>
        <option value="preparing">Preparando</option>
        <option value="ready">Pronto</option>
        <option value="delivered">Entregue</option>
        <option value="cancelled">Cancelado</option>
      </select>
    </div>
    <div class="fg"><label class="fl">Pagamento</label>
      <select class="fi" id="status-payment">
        <option value="">Manter atual</option>
        <option value="pending">Pendente</option>
        <option value="paid">Pago</option>
        <option value="refunded">Estornado</option>
      </select>
    </div>
    <div class="fg"><label class="fl">Método de Pagamento</label>
      <select class="fi" id="status-payment-method">
        <option value="">Manter atual</option>
        <option value="cash">Dinheiro</option>
        <option value="card">Cartão</option>
        <option value="pix">PIX</option>
      </select>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-order-status')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveOrderStatus()">Salvar</button>
    </div>
  </div>
</div>

<!-- MODAL PRODUTO -->
<div class="overlay" id="modal-product">
  <div class="modal">
    <div class="modal-title" id="prod-modal-title">Novo Produto</div>
    <input type="hidden" id="prod-id">
    <div class="fr">
      <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="prod-tenant-sel"><option value="">Selecione...</option></select></div>
      <div class="fg"><label class="fl">Categoria *</label><select class="fi" id="prod-cat-sel"><option value="">Selecione...</option></select></div>
    </div>
    <div class="fg"><label class="fl">Nome *</label><input class="fi" id="prod-name" placeholder="Ex: Feijoada Completa"></div>
    <div class="fg"><label class="fl">Descrição</label><input class="fi" id="prod-desc" placeholder="Breve descrição"></div>
    <div class="fr">
      <div class="fg"><label class="fl">Preço (R$) *</label><input class="fi" id="prod-price" type="number" step="0.01" placeholder="0.00"></div>
      <div class="fg"><label class="fl">Custo (R$)</label><input class="fi" id="prod-cost" type="number" step="0.01" placeholder="0.00"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Estoque</label><input class="fi" id="prod-stock" type="number" step="1" placeholder="Qtd"></div>
      <div class="fg"><label class="fl">Controla Estoque?</label>
        <select class="fi" id="prod-stock-ctrl"><option value="0">Não</option><option value="1">Sim</option></select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-product')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveProduct()">Salvar</button>
    </div>
  </div>
</div>

<!-- MODAL EXPORTAR CSV -->
<div class="overlay" id="modal-csv">
  <div class="modal" style="width:400px">
    <div class="modal-title">Exportar CSV</div>
    <p style="color:var(--muted);font-size:13px;margin-bottom:18px">Selecione os relatórios que deseja exportar:</p>
    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:8px">
        <input type="checkbox" id="csv-produtos" checked style="width:16px;height:16px;accent-color:var(--accent)">
        <div><div style="font-weight:500">📦 Produtos Mais Vendidos</div><div style="font-size:12px;color:var(--muted)">Ranking com qtd vendida e receita</div></div>
      </label>
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:8px">
        <input type="checkbox" id="csv-clientes" checked style="width:16px;height:16px;accent-color:var(--accent)">
        <div><div style="font-weight:500">👤 Top Clientes por Gasto</div><div style="font-size:12px;color:var(--muted)">Nível, pontos e total gasto</div></div>
      </label>
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:8px">
        <input type="checkbox" id="csv-receita" checked style="width:16px;height:16px;accent-color:var(--accent)">
        <div><div style="font-weight:500">💰 Receita por Restaurante</div><div style="font-size:12px;color:var(--muted)">Total faturado por tenant</div></div>
      </label>
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:8px">
        <input type="checkbox" id="csv-estoque" style="width:16px;height:16px;accent-color:var(--accent)">
        <div><div style="font-weight:500">⚠️ Estoque Crítico</div><div style="font-size:12px;color:var(--muted)">Produtos com quantidade abaixo de 10</div></div>
      </label>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-csv')">Cancelar</button>
      <button class="btn btn-csv" onclick="doExportCSV()">📥 Baixar Selecionados</button>
    </div>
  </div>
</div>

<!-- MODAL CLIENTE -->
<div class="overlay" id="modal-customer">
  <div class="modal">
    <div class="modal-title" id="cust-modal-title">Novo Cliente</div>
    <input type="hidden" id="cust-id">
    <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="cust-tenant-sel"><option value="">Selecione...</option></select></div>
    <div class="fr">
      <div class="fg"><label class="fl">Nome *</label><input class="fi" id="cust-name" placeholder="Nome completo"></div>
      <div class="fg"><label class="fl">Telefone *</label><input class="fi" id="cust-phone" placeholder="(87) 99999-0000"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Email</label><input class="fi" id="cust-email" type="email"></div>
      <div class="fg"><label class="fl">Aniversário</label><input class="fi" id="cust-birth" type="date"></div>
    </div>
    <div class="fg"><label class="fl">Endereço</label><input class="fi" id="cust-addr" placeholder="Rua, número - Cidade/UF"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-customer')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveCustomer()">Salvar</button>
    </div>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const $ = id => document.getElementById(id);
const fmt    = v => parseFloat(v||0).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
const fmtNum = v => parseInt(v||0).toLocaleString('pt-BR');

function toast(msg, type='ok') {
  const el=$('toast'); el.textContent=msg;
  el.className=`toast show ${type}`;
  setTimeout(()=>el.classList.remove('show'),3500);
}
function showPage(name) {
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  $(`page-${name}`).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(i=>i.classList.remove('active'));
  document.querySelector(`[data-page="${name}"]`).classList.add('active');
  ({dashboard:loadDashboard,orders:loadOrders,products:loadProducts,
    customers:loadCustomers,reports:loadReports,views:loadViews})[name]?.();
}
function openModal(id){$(id).classList.add('open')}
function closeModal(id){$(id).classList.remove('open')}

async function api(resource, opts={}) {
  const url=`api.php?resource=${resource}`+(opts.params?'&'+new URLSearchParams(opts.params):'');
  const res=await fetch(url,{
    method:opts.method||'GET',
    headers:opts.body?{'Content-Type':'application/json'}:{},
    body:opts.body?JSON.stringify(opts.body):undefined
  });
  return res.json();
}

function statusBadge(s){
  const m={delivered:'bg Entregue',canceled:'br Cancelado',cancelled:'br Cancelado',
           pending:'ba Pendente',confirmed:'bb Confirmado',preparing:'bb Preparando',
           ready:'bg Pronto',open:'bb Aberto',closed:'bm Fechado'};
  const [c,l]=(m[s]||'bm '+s).split(' ');
  return `<span class="badge ${c}">${l||s}</span>`;
}
function levelBadge(l){
  const m={gold:'ba Gold',silver:'bm Silver',bronze:'br Bronze',platinum:'bb Platinum'};
  const [c,lb]=(m[l]||'bm '+l).split(' ');
  return `<span class="badge ${c}">${lb||l}</span>`;
}
function payBadge(s){
  const m={paid:'bg Pago',pending:'ba Pendente',refunded:'bb Estornado',partially_paid:'ba Parcial'};
  const [c,l]=(m[s]||'bm '+s).split(' ');
  return `<span class="badge ${c}">${l||s}</span>`;
}

// TENANTS / CACHE
let tenantsCache=[], productsCache=[], customersCache=[], tablesCache=[];
async function loadTenants() {
  if(tenantsCache.length) return;
  tenantsCache=await api('tenants');
  ['prod-tenant','cust-tenant','ord-tenant','prod-tenant-sel','cust-tenant-sel','ord-tenant-sel'].forEach(id=>{
    const el=$(id); if(!el) return;
    const isFilter=['prod-tenant','cust-tenant','ord-tenant'].includes(id);
    el.innerHTML=isFilter?'<option value="">Todos os restaurantes</option>':'<option value="">Selecione...</option>';
    tenantsCache.forEach(t=>el.innerHTML+=`<option value="${t.id}">${t.name}</option>`);
  });
}

// DASHBOARD
async function loadDashboard() {
  $('dash-date').textContent=new Date().toLocaleDateString('pt-BR',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  const [stats,orders]=await Promise.all([api('stats'),api('orders')]);
  $('s-revenue').textContent=fmt(stats.revenue);
  $('s-orders').textContent=fmtNum(stats.orders);
  $('s-customers').textContent=fmtNum(stats.customers);
  $('s-products').textContent=fmtNum(stats.products);
  $('dash-orders').innerHTML=orders.slice(0,8).map(o=>`
    <tr>
      <td style="font-weight:600">${o.id}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'<span style="color:var(--muted)">—</span>'}</td>
      <td>${o.table_number?'Mesa '+o.table_number:'—'}</td>
      <td>${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('')||'<tr><td colspan="6" class="empty">Nenhum pedido</td></tr>';
}

// ══ ORDERS CRUD ══
async function loadOrders() {
  await loadTenants();
  const data=await api('orders',{params:{tenant_id:$('ord-tenant').value,status:$('ord-status').value}});
  $('ord-count').textContent=`${data.length} registros`;
  $('ord-tbody').innerHTML=data.length?data.map(o=>`
    <tr>
      <td style="font-weight:600">${o.id}</td>
      <td style="font-size:12px;color:var(--muted)">${o.order_number}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'—'}</td>
      <td>${o.table_number?'Mesa '+o.table_number:'—'}</td>
      <td style="font-weight:600">${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
      <td><div class="actions">
        <button class="btn btn-info btn-sm" onclick="viewOrder(${o.id})">Ver</button>
        <button class="btn btn-ghost btn-sm" onclick="openStatusModal(${o.id},'${o.status}','${o.payment_status}')">Status</button>
        <button class="btn btn-danger btn-sm" onclick="cancelOrder(${o.id})" ${o.status==='cancelled'||o.status==='delivered'?'disabled':''}>Cancelar</button>
      </div></td>
    </tr>`).join(''):'<tr><td colspan="8" class="empty">Nenhum pedido encontrado</td></tr>';
}

async function viewOrder(id) {
  const o=await (await fetch(`api.php?resource=orders&id=${id}`)).json();
  $('view-order-title').textContent=`Pedido #${o.id} — ${o.order_number}`;
  $('view-order-body').innerHTML=`
    <div class="order-detail">
      <div class="order-detail-header">
        <div>
          <div style="font-size:13px;color:var(--muted)">Restaurante: <strong style="color:var(--text)">${o.tenant_name||'—'}</strong></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Cliente: <strong style="color:var(--text)">${o.customer_name||'—'}</strong></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Mesa: <strong style="color:var(--text)">${o.table_number?'Mesa '+o.table_number:'—'}</strong></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Tipo: <strong style="color:var(--text)">${o.type}</strong></div>
        </div>
        <div style="text-align:right">
          ${statusBadge(o.status)}
          <div style="margin-top:6px">${payBadge(o.payment_status)}</div>
          <div style="font-size:12px;color:var(--muted);margin-top:6px">${o.payment_method||'—'}</div>
        </div>
      </div>
      <div class="slabel" style="margin-bottom:10px"><h3>Itens</h3><div class="line"></div></div>
      <div class="order-items-list">
        ${(o.items||[]).map(i=>`
          <div class="order-item-row">
            <div><strong>${i.product_name||'Produto #'+i.product_id}</strong>${i.notes?`<div style="font-size:11px;color:var(--muted)">${i.notes}</div>`:''}</div>
            <div style="color:var(--muted)">${i.quantity}x ${fmt(i.unit_price)}</div>
            <div style="font-weight:600">${fmt(i.total)}</div>
          </div>`).join('')||'<div class="empty">Sem itens</div>'}
      </div>
      <div style="margin-top:16px;padding:12px 16px;background:var(--surface2);border-radius:8px">
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span style="color:var(--muted)">Subtotal</span><span>${fmt(o.subtotal)}</span></div>
        ${parseFloat(o.discount)>0?`<div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span style="color:var(--muted)">Desconto</span><span style="color:var(--danger)">-${fmt(o.discount)}</span></div>`:''}
        ${parseFloat(o.service_fee)>0?`<div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px"><span style="color:var(--muted)">Taxa de serviço</span><span>${fmt(o.service_fee)}</span></div>`:''}
        <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700;margin-top:8px;padding-top:8px;border-top:1px solid var(--border)"><span>Total</span><span style="color:var(--accent)">${fmt(o.total)}</span></div>
      </div>
      ${o.notes?`<div style="margin-top:12px;font-size:13px;color:var(--muted)">Obs: ${o.notes}</div>`:''}
    </div>`;
  openModal('modal-order-view');
}

function openStatusModal(id, status, payStatus) {
  $('status-order-id').value=id;
  $('status-new').value=status;
  $('status-payment').value='';
  $('status-payment-method').value='';
  openModal('modal-order-status');
}

async function saveOrderStatus() {
  const id=$('status-order-id').value;
  const body={status:$('status-new').value};
  if($('status-payment').value) body.payment_status=$('status-payment').value;
  if($('status-payment-method').value) body.payment_method=$('status-payment-method').value;
  const res=await fetch(`api.php?resource=orders&id=${id}`,{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
  const r=await res.json();
  if(r.success){toast('Status atualizado!');closeModal('modal-order-status');loadOrders();}
  else toast('Erro: '+(r.error||''),'err');
}

async function cancelOrder(id) {
  if(!confirm('Cancelar este pedido?')) return;
  const res=await fetch(`api.php?resource=orders&id=${id}`,{method:'DELETE'});
  const r=await res.json();
  if(r.success){toast('Pedido cancelado!');loadOrders();}
  else toast('Erro ao cancelar','err');
}

// Novo pedido — itens dinâmicos
let orderProducts=[];
async function onOrderTenantChange() {
  const tenantId=$('ord-tenant-sel').value;
  if(!tenantId) return;

  // Carrega mesas
  const tables=await api('tables',{params:{tenant_id:tenantId}});
  $('ord-table').innerHTML='<option value="">Sem mesa</option>';
  tables.forEach(t=>$('ord-table').innerHTML+=`<option value="${t.id}">Mesa ${t.number} (cap. ${t.capacity})</option>`);

  // Carrega clientes
  const customers=await api('customers',{params:{tenant_id:tenantId}});
  $('ord-customer').innerHTML='<option value="">Sem cliente</option>';
  customers.forEach(c=>$('ord-customer').innerHTML+=`<option value="${c.id}">${c.name}</option>`);

  // Carrega produtos para os selects de itens
  orderProducts=await api('products',{params:{tenant_id:tenantId}});
  // Atualiza selects já existentes
  document.querySelectorAll('.item-product-sel').forEach(sel=>{
    const cur=sel.value;
    sel.innerHTML='<option value="">Selecione produto...</option>';
    orderProducts.forEach(p=>sel.innerHTML+=`<option value="${p.id}" data-price="${p.price}">${p.name} — ${fmt(p.price)}</option>`);
    if(cur) sel.value=cur;
  });
}

function addOrderItemRow() {
  const container=$('order-items-container');
  const idx=Date.now();
  const row=document.createElement('div');
  row.className='item-row'; row.id=`item-${idx}`;
  row.innerHTML=`
    <select class="fi item-product-sel" onchange="onItemProductChange(this,${idx})">
      <option value="">Selecione produto...</option>
      ${orderProducts.map(p=>`<option value="${p.id}" data-price="${p.price}">${p.name} — ${fmt(p.price)}</option>`).join('')}
    </select>
    <input class="fi item-qty" type="number" min="1" value="1" placeholder="Qtd" oninput="calcOrderTotal()">
    <input class="fi item-price" type="number" step="0.01" placeholder="Preço" oninput="calcOrderTotal()" readonly>
    <button class="remove-btn" onclick="document.getElementById('item-${idx}').remove();calcOrderTotal()">✕</button>`;
  container.appendChild(row);
}

function onItemProductChange(sel, idx) {
  const opt=sel.options[sel.selectedIndex];
  const price=opt.getAttribute('data-price')||'';
  const row=document.getElementById(`item-${idx}`);
  if(row) row.querySelector('.item-price').value=price;
  calcOrderTotal();
}

function calcOrderTotal() {
  let sub=0;
  document.querySelectorAll('#order-items-container .item-row').forEach(row=>{
    const qty=parseFloat(row.querySelector('.item-qty')?.value||0);
    const price=parseFloat(row.querySelector('.item-price')?.value||0);
    sub+=qty*price;
  });
  $('ord-subtotal').textContent=fmt(sub);
  $('ord-total').textContent=fmt(sub);
}

async function openOrderModal() {
  $('order-items-container').innerHTML='';
  $('ord-notes').value='';
  $('ord-type').value='dine_in';
  $('ord-payment').value='';
  $('ord-table').innerHTML='<option value="">Sem mesa</option>';
  $('ord-customer').innerHTML='<option value="">Sem cliente</option>';
  $('ord-subtotal').textContent='R$ 0,00';
  $('ord-total').textContent='R$ 0,00';
  orderProducts=[];
  addOrderItemRow();
  openModal('modal-order');
}

async function saveOrder() {
  const tenantId=$('ord-tenant-sel').value;
  if(!tenantId){toast('Selecione um restaurante','err');return;}

  const items=[];
  let valid=true;
  document.querySelectorAll('#order-items-container .item-row').forEach(row=>{
    const productId=row.querySelector('.item-product-sel')?.value;
    const qty=parseInt(row.querySelector('.item-qty')?.value||0);
    const price=parseFloat(row.querySelector('.item-price')?.value||0);
    if(!productId||qty<1){valid=false;return;}
    items.push({product_id:productId,quantity:qty,unit_price:price,discount:0});
  });

  if(!valid||items.length===0){toast('Adicione pelo menos 1 item válido','err');return;}

  const body={
    tenant_id:tenantId,
    table_id:$('ord-table').value||null,
    customer_id:$('ord-customer').value||null,
    type:$('ord-type').value,
    payment_method:$('ord-payment').value||null,
    notes:$('ord-notes').value||null,
    items
  };

  const res=await fetch('api.php?resource=orders',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
  const r=await res.json();
  if(r.success){toast(`Pedido ${r.order_number} criado!`);closeModal('modal-order');loadOrders();}
  else toast('Erro: '+(r.error||''),'err');
}

// PRODUCTS
async function loadCategories(tenantId, selectedId=null) {
  const data=await api('categories',{params:{tenant_id:tenantId||''}});
  const sel=$('prod-cat-sel');
  sel.innerHTML='<option value="">Selecione...</option>';
  data.forEach(c=>sel.innerHTML+=`<option value="${c.id}">${c.name}</option>`);
  if(selectedId) sel.value=String(selectedId);
}
async function loadProducts() {
  await loadTenants();
  const data=await api('products',{params:{search:$('prod-search').value,tenant_id:$('prod-tenant').value}});
  $('prod-count').textContent=`${data.length} registros`;
  $('prod-tbody').innerHTML=data.length?data.map(p=>{
    const temEstoque=p.stock_control==1;
    const estoqueBaixo=temEstoque&&parseInt(p.stock_quantity||0)<10;
    return`<tr>
      <td><strong>${p.name}</strong></td>
      <td><span class="badge bm">${p.category_name||'—'}</span></td>
      <td>${p.tenant_name||'—'}</td>
      <td style="font-weight:600">${fmt(p.price)}</td>
      <td style="color:${estoqueBaixo?'var(--danger)':'var(--text)'}">${temEstoque?(p.stock_quantity||0)+' un'+(estoqueBaixo?' ⚠':''):'—'}</td>
      <td>${p.is_available==1?'<span class="badge bg">Sim</span>':'<span class="badge br">Não</span>'}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick="editProduct(${p.id})">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.id},'${p.name.replace(/'/g,"\\'")}')">Excluir</button>
      </div></td></tr>`;
  }).join(''):'<tr><td colspan="7" class="empty">Nenhum produto encontrado</td></tr>';
}
async function openProductModal(data=null) {
  $('prod-modal-title').textContent=data?'Editar Produto':'Novo Produto';
  $('prod-id').value=data?.id||'';
  $('prod-name').value=data?.name||'';
  $('prod-desc').value=data?.description||'';
  $('prod-price').value=data?.price||'';
  $('prod-cost').value=data?.cost||'';
  $('prod-stock').value=data?.stock_quantity||'';
  $('prod-stock-ctrl').value=data?.stock_control||'0';
  if(data?.tenant_id) $('prod-tenant-sel').value=data.tenant_id;
  await loadCategories(data?.tenant_id||$('prod-tenant').value,data?.category_id||null);
  openModal('modal-product');
}
async function editProduct(id) {
  const p=await(await fetch(`api.php?resource=products&id=${id}`)).json();
  if(!p){toast('Produto não encontrado','err');return;}
  openProductModal(p);
}
async function saveProduct() {
  const id=$('prod-id').value;
  const body={
    tenant_id:$('prod-tenant-sel').value,
    category_id:$('prod-cat-sel').value||null,
    name:$('prod-name').value,
    description:$('prod-desc').value,
    price:$('prod-price').value,
    cost:$('prod-cost').value,
    stock_quantity:$('prod-stock').value||null,
    stock_control:$('prod-stock-ctrl').value,
  };
  if(!body.tenant_id||!body.name||!body.price){toast('Preencha os campos obrigatórios','err');return;}
  const res=await fetch(`api.php?resource=products${id?'&id='+id:''}`,{method:id?'PUT':'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
  const r=await res.json();
  if(r.success){toast(id?'Produto atualizado!':'Produto criado!');closeModal('modal-product');loadProducts();}
  else toast('Erro: '+(r.error||''),'err');
}
async function deleteProduct(id,name) {
  if(!confirm(`Excluir "${name}"?`)) return;
  const res=await fetch(`api.php?resource=products&id=${id}`,{method:'DELETE'});
  const r=await res.json();
  if(r.success){toast('Produto excluído!');loadProducts();}
  else toast('Erro: '+(r.error||''),'err');
}

// CUSTOMERS
async function loadCustomers() {
  await loadTenants();
  const data=await api('customers',{params:{search:$('cust-search').value,tenant_id:$('cust-tenant').value}});
  $('cust-count').textContent=`${data.length} registros`;
  $('cust-tbody').innerHTML=data.length?data.map(c=>`
    <tr>
      <td><strong>${c.name}</strong></td><td>${c.phone||'—'}</td>
      <td>${c.tenant_name||'—'}</td><td>${levelBadge(c.level)}</td>
      <td>${fmtNum(c.points)} pts</td>
      <td style="font-weight:600">${fmt(c.total_spent)}</td>
      <td>${fmtNum(c.visits)}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick="editCustomer(${c.id})">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="deleteCustomer(${c.id},'${c.name.replace(/'/g,"\\'")}')">Excluir</button>
      </div></td>
    </tr>`).join(''):'<tr><td colspan="8" class="empty">Nenhum cliente encontrado</td></tr>';
}
function openCustomerModal(data=null) {
  $('cust-modal-title').textContent=data?'Editar Cliente':'Novo Cliente';
  $('cust-id').value=data?.id||'';
  $('cust-name').value=data?.name||'';
  $('cust-phone').value=data?.phone||'';
  $('cust-email').value=data?.email||'';
  $('cust-birth').value=data?.birth_date||'';
  $('cust-addr').value=data?.address||'';
  if(data?.tenant_id) $('cust-tenant-sel').value=data.tenant_id;
  openModal('modal-customer');
}
async function editCustomer(id) {
  const c=await(await fetch(`api.php?resource=customers&id=${id}`)).json();
  openCustomerModal(c);
}
async function saveCustomer() {
  const id=$('cust-id').value;
  const body={
    tenant_id:$('cust-tenant-sel').value,
    name:$('cust-name').value,phone:$('cust-phone').value,
    email:$('cust-email').value,birth_date:$('cust-birth').value,
    address:$('cust-addr').value,
  };
  if(!body.tenant_id||!body.name||!body.phone){toast('Preencha os campos obrigatórios','err');return;}
  const res=await fetch(`api.php?resource=customers${id?'&id='+id:''}`,{method:id?'PUT':'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});
  const r=await res.json();
  if(r.success){toast(id?'Cliente atualizado!':'Cliente criado!');closeModal('modal-customer');loadCustomers();}
  else toast('Erro: '+(r.error||''),'err');
}
async function deleteCustomer(id,name) {
  if(!confirm(`Excluir "${name}"?`)) return;
  const res=await fetch(`api.php?resource=customers&id=${id}`,{method:'DELETE'});
  const r=await res.json();
  if(r.success){toast('Cliente excluído!');loadCustomers();}
  else toast('Erro ao excluir','err');
}

// REPORTS
const CC=['#e8a44a','#4caf7d','#5b9bd5','#e05252','#e8c44a','#c87dd4','#52b0e0','#f0a070'];
const CB={responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{color:'#f0ece4',font:{size:11}}}},scales:{x:{ticks:{color:'#7a7570'},grid:{color:'#2e2c29'}},y:{ticks:{color:'#7a7570'},grid:{color:'#2e2c29'}}}};
const charts={};
function killChart(id){if(charts[id]){charts[id].destroy();delete charts[id];}}
async function loadReports() {
  if (reportDataCache) return;
  const d=await api('report_data');
  reportDataCache = d;
  const{kpis,revenue_by_tenant,status_dist,top_products,level_dist,payment_dist,top_customers,low_stock}=d;
  $('rep-revenue').textContent=fmt(kpis.revenue);
  $('rep-ticket').textContent=fmt(kpis.ticket_medio);
  $('rep-total-orders').textContent=fmtNum(kpis.total_orders);
  $('rep-customers').textContent=fmtNum(kpis.active_customers);
  $('report-print-date').textContent='Gerado em: '+new Date().toLocaleString('pt-BR');
  killChart('chart-revenue');
  charts['chart-revenue']=new Chart($('chart-revenue'),{type:'bar',data:{labels:revenue_by_tenant.map(r=>r.tenant_name),datasets:[{label:'Receita',data:revenue_by_tenant.map(r=>parseFloat(r.revenue)),backgroundColor:CC}]},options:{...CB,plugins:{...CB.plugins,legend:{display:false}}}});
  killChart('chart-status');
  charts['chart-status']=new Chart($('chart-status'),{type:'doughnut',data:{labels:status_dist.map(s=>s.status),datasets:[{data:status_dist.map(s=>s.total),backgroundColor:CC}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#f0ece4',font:{size:11}}}}}});
  killChart('chart-top-products');
  const tp5=top_products.slice(0,5);
  charts['chart-top-products']=new Chart($('chart-top-products'),{type:'bar',data:{labels:tp5.map(p=>p.name.length>14?p.name.slice(0,14)+'…':p.name),datasets:[{label:'Qtd',data:tp5.map(p=>p.qty_sold),backgroundColor:'#e8a44a'}]},options:{...CB,indexAxis:'y',plugins:{...CB.plugins,legend:{display:false}}}});
  killChart('chart-levels');
  charts['chart-levels']=new Chart($('chart-levels'),{type:'pie',data:{labels:level_dist.map(l=>l.level),datasets:[{data:level_dist.map(l=>l.total),backgroundColor:['#e8c44a','#7a7570','#e05252','#5b9bd5']}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#f0ece4',font:{size:11}}}}}});
  killChart('chart-payment');
  charts['chart-payment']=new Chart($('chart-payment'),{type:'bar',data:{labels:payment_dist.map(p=>p.payment_method||'Outro'),datasets:[{label:'Total',data:payment_dist.map(p=>parseFloat(p.total)),backgroundColor:CC}]},options:{...CB,plugins:{...CB.plugins,legend:{display:false}}}});
  $('rep-top-products').innerHTML=top_products.length?top_products.map((p,i)=>`<tr><td style="font-weight:700;color:var(--accent)">#${i+1}</td><td><strong>${p.name}</strong></td><td>${p.category||'—'}</td><td style="font-weight:600">${fmtNum(p.qty_sold)}</td><td>${fmt(p.revenue)}</td><td>${p.stock!==null?p.stock+' un':'—'}</td></tr>`).join(''):'<tr><td colspan="6" class="empty">Sem dados</td></tr>';
  $('rep-top-customers').innerHTML=top_customers.length?top_customers.map(c=>`<tr><td><strong>${c.name}</strong></td><td>${c.tenant_name||'—'}</td><td>${levelBadge(c.level)}</td><td>${fmtNum(c.points)} pts</td><td style="font-weight:600">${fmt(c.total_spent)}</td><td>${fmtNum(c.visits)}</td></tr>`).join(''):'<tr><td colspan="6" class="empty">Sem dados</td></tr>';
  $('rep-low-stock').innerHTML=low_stock.length?low_stock.map(p=>`<tr><td><strong>${p.name}</strong></td><td>${p.category||'—'}</td><td>${p.tenant_name||'—'}</td><td style="color:var(--danger)">${p.stock_quantity} un</td><td><span class="badge br">⚠ Crítico</span></td></tr>`).join(''):'<tr><td colspan="5" class="empty" style="color:var(--success)">✅ Todos os estoques OK</td></tr>';
}
function exportPDF(){$('report-print-date').textContent='Gerado em: '+new Date().toLocaleString('pt-BR');window.print();}

// CSV EXPORT
let reportDataCache = null;
function csvEscape(v) {
  if (v === null || v === undefined) return "";
  const s = String(v).replace(/"/g, "\"");
  return s.includes(",") || s.includes("\n") || s.includes("\"") ? `"${s}"` : s;
}
function downloadCSV(filename, rows) {
  const bom = "\uFEFF";
  const content = bom + rows.map(r => r.map(csvEscape).join(",")).join("\n");
  const blob = new Blob([content], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.href = url; a.download = filename; a.click();
  URL.revokeObjectURL(url);
}
function openCSVModal() {
  if (!reportDataCache) { toast("Abra a página de Relatórios primeiro", "err"); return; }
  openModal('modal-csv');
}
function doExportCSV() {
  if (!reportDataCache) { toast("Sem dados para exportar", "err"); return; }
  const { top_products, top_customers, low_stock, revenue_by_tenant } = reportDataCache;
  const now = new Date().toLocaleString("pt-BR").replace(/[/:, /]/g, "-");
  let delay = 0;
  if (document.getElementById('csv-produtos').checked) {
    const rows = [["Rank","Produto","Categoria","Qtd Vendida","Receita (R$)","Estoque"]];
    top_products.forEach((p,i) => rows.push([i+1, p.name, p.category||"—", p.qty_sold, parseFloat(p.revenue).toFixed(2), p.stock??'—']));
    setTimeout(() => downloadCSV(`produtos_${now}.csv`, rows), delay); delay += 300;
  }
  if (document.getElementById('csv-clientes').checked) {
    const rows = [["Cliente","Restaurante","Nivel","Pontos","Total Gasto (R$)","Visitas"]];
    top_customers.forEach(c => rows.push([c.name, c.tenant_name||"—", c.level, c.points, parseFloat(c.total_spent).toFixed(2), c.visits]));
    setTimeout(() => downloadCSV(`clientes_${now}.csv`, rows), delay); delay += 300;
  }
  if (document.getElementById('csv-receita').checked) {
    const rows = [["Restaurante","Receita (R$)"]];
    revenue_by_tenant.forEach(r => rows.push([r.tenant_name, parseFloat(r.revenue).toFixed(2)]));
    setTimeout(() => downloadCSV(`receita_${now}.csv`, rows), delay); delay += 300;
  }
  if (document.getElementById('csv-estoque').checked && low_stock.length > 0) {
    const rows = [["Produto","Categoria","Restaurante","Qtd em Estoque"]];
    low_stock.forEach(p => rows.push([p.name, p.category||"—", p.tenant_name||"—", p.stock_quantity]));
    setTimeout(() => downloadCSV(`estoque_critico_${now}.csv`, rows), delay);
  }
  closeModal('modal-csv');
  toast("Downloads iniciados!", "ok");
}

// VIEWS
async function loadViews() {
  const[revenue,orders,cash]=await Promise.all([api('view_revenue'),api('view_orders'),api('view_cash')]);
  $('view-revenue').innerHTML=revenue.length?revenue.map(r=>`<div class="view-card"><div class="view-card-num">${fmt(r.revenue)}</div><div class="view-card-name">${r.restaurant}</div><div class="view-card-sub">${r.total_orders} pedidos</div></div>`).join(''):'<div class="empty">Sem dados</div>';
  $('view-orders').innerHTML=orders.length?orders.map(o=>`<tr><td style="font-weight:600">${o.order_number}</td><td>${o.restaurant||'—'}</td><td>${o.customer||'—'}</td><td>${o.table_number?'Mesa '+o.table_number:'—'}</td><td>${o.total_items||0}</td><td>${fmt(o.subtotal)}</td><td style="font-weight:600">${fmt(o.total)}</td><td>${statusBadge(o.status)}</td></tr>`).join(''):'<tr><td colspan="8" class="empty">Sem dados</td></tr>';
  $('view-cash').innerHTML=cash.length?cash.map(c=>`<tr><td><strong>${c.cash_register_name}</strong></td><td>${c.tenant_name||'—'}</td><td>${c.opened_by_name||'—'}</td><td>${fmt(c.opening_balance)}</td><td style="color:var(--success)">${fmt(c.total_entries||0)}</td><td style="color:var(--danger)">${fmt(c.total_exits||0)}</td><td>${statusBadge(c.status)}</td></tr>`).join(''):'<tr><td colspan="7" class="empty">Sem dados</td></tr>';
}

// NAV
document.querySelectorAll('.nav-item[data-page]').forEach(el=>{
  el.addEventListener('click',()=>showPage(el.dataset.page));
});
document.querySelectorAll('.overlay').forEach(el=>{
  el.addEventListener('click',e=>{if(e.target===el)el.classList.remove('open');});
});
$('prod-tenant-sel').addEventListener('change',()=>loadCategories($('prod-tenant-sel').value));

loadTenants();
loadDashboard();
</script>
</body>
</html>