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
.btn-pdf{background:rgba(224,82,82,.15);color:#ff8a8a;border:1px solid rgba(224,82,82,.3)}.btn-pdf:hover{background:rgba(224,82,82,.28)}
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
.actions{display:flex;gap:5px}
.slabel{display:flex;align-items:center;gap:10px;margin-bottom:14px;margin-top:8px}
.slabel h3{font-size:14px;font-weight:600;white-space:nowrap}
.slabel .line{flex:1;height:1px;background:var(--border)}
.slabel .tag{font-size:10px;background:rgba(91,155,213,.15);color:var(--info);padding:2px 7px;border-radius:10px;white-space:nowrap}
.view-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
.view-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:22px;text-align:center}
.view-card-num{font-family:'DM Serif Display',serif;font-size:36px;color:var(--accent)}
.view-card-name{font-size:14px;font-weight:500;margin-top:8px}
.view-card-sub{font-size:12px;color:var(--muted);margin-top:3px}
/* Charts */
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px}
.charts-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px}
.chart-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:20px}
.chart-title{font-size:13px;font-weight:600;margin-bottom:14px;color:var(--text)}
.chart-wrap{position:relative;height:230px}
/* Modal */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:200;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.overlay.open{display:flex}
.modal{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:28px;width:500px;max-height:90vh;overflow-y:auto}
.modal-title{font-family:'DM Serif Display',serif;font-size:22px;margin-bottom:20px}
.fg{margin-bottom:14px}
.fl{display:block;font-size:11px;font-weight:500;color:var(--muted);margin-bottom:5px;text-transform:uppercase;letter-spacing:.5px}
.fi{width:100%;padding:9px 12px;background:var(--bg);border:1px solid var(--border);border-radius:6px;color:var(--text);font-size:13px;font-family:'DM Sans',sans-serif}
.fi:focus{outline:none;border-color:var(--accent)}
.fr{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.modal-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)}
/* Toast */
.toast{position:fixed;bottom:24px;right:24px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:12px 18px;font-size:13px;z-index:300;opacity:0;transform:translateY(8px);transition:all .25s;pointer-events:none}
.toast.show{opacity:1;transform:translateY(0)}
.toast.ok{border-left:3px solid var(--success)}
.toast.err{border-left:3px solid var(--danger)}
.loading{text-align:center;padding:40px;color:var(--muted);font-size:13px}
.empty{text-align:center;padding:40px;color:var(--muted);font-size:13px}
.page{display:none}.page.active{display:block}
/* Report header (só aparece no PDF/print) */
.report-header{display:none}

/* ── ESTILOS DE IMPRESSÃO / PDF ─────────────────────── */
@media print {
  @page { margin: 15mm; }
  body { background:#fff !important; color:#000 !important; font-size:11px }
  .sidebar, .btn, .toolbar, .actions, .overlay { display:none !important }
  .main { margin-left:0 !important; padding:0 !important }
  .page { display:none !important }
  #page-reports.active { display:block !important }
  .report-header { display:block !important; border-bottom:2px solid #333; padding-bottom:12px; margin-bottom:20px }
  .report-header h1 { font-size:20px; font-family:serif }
  .report-header p  { font-size:11px; color:#555; margin-top:4px }
  .page-header { margin-bottom:16px }
  .page-title, .page-sub { color:#000 !important }
  .page-title em { color:#c07a00 !important }
  .stat-card, .card, .chart-card, .view-card {
    background:#fff !important; border:1px solid #bbb !important;
    break-inside:avoid; page-break-inside:avoid
  }
  .stat-value, .stat-label, .stat-hint { color:#000 !important }
  .chart-title, .card-title { color:#000 !important }
  .card-badge { background:#eee !important; color:#333 !important }
  th, td { color:#000 !important; border-color:#ccc !important }
  .badge { background:#eee !important; color:#333 !important; border:1px solid #bbb }
  .badge::before { display:none }
  .view-card-num, .view-card-name, .view-card-sub { color:#000 !important }
  .slabel h3 { color:#000 !important }
  .slabel .line { background:#ccc !important }
  .slabel .tag { background:#e8f0fe !important; color:#333 !important }
  .charts-grid { grid-template-columns:1fr 1fr !important }
  .charts-grid-3 { grid-template-columns:1fr 1fr 1fr !important }
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
    <div class="nav-item" data-page="orders"><span class="nav-icon">📋</span> Pedidos</div>
    <div class="nav-section">Cadastros</div>
    <div class="nav-item" data-page="products"><span class="nav-icon">📦</span> Estoque</div>
    <div class="nav-item" data-page="customers"><span class="nav-icon">👤</span> Clientes</div>
    <div class="nav-section">Análises</div>
    <div class="nav-item" data-page="reports"><span class="nav-icon">📈</span> Relatórios</div>
    <div class="nav-item" data-page="views"><span class="nav-icon">📊</span> Visões SQL</div>
  </nav>
</aside>

<main class="main">

  <!-- ══════════ DASHBOARD ══════════ -->
  <div id="page-dashboard" class="page active">
    <div class="page-header">
      <div>
        <div class="page-title">Bem-vindo, <em>Admin</em></div>
        <div class="page-sub" id="dash-date">Carregando...</div>
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

  <!-- ══════════ ESTOQUE / PRODUTOS ══════════ -->
  <div id="page-products" class="page">
    <div class="page-header">
      <div><div class="page-title">Estoque</div><div class="page-sub">Controle de insumos e produtos</div></div>
      <button class="btn btn-primary" onclick="openProductModal()">+ Novo Item</button>
    </div>
    <div class="toolbar">
      <input class="search" placeholder="Buscar produto..." id="prod-search" oninput="loadProducts()">
      <select class="search" style="max-width:200px" id="prod-tenant" onchange="loadProducts()">
        <option value="">Todos os restaurantes</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header"><span class="card-title">Itens em Estoque</span><span class="card-badge" id="prod-count">...</span></div>
      <table>
        <thead><tr><th>Nome</th><th>Categoria</th><th>Restaurante</th><th>Quantidade</th><th>Estoque Mín.</th><th>Custo</th><th>Fornecedor</th><th>Ações</th></tr></thead>
        <tbody id="prod-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ══════════ CLIENTES ══════════ -->
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

  <!-- ══════════ PEDIDOS ══════════ -->
  <div id="page-orders" class="page">
    <div class="page-header">
      <div><div class="page-title">Pedidos</div><div class="page-sub">Histórico completo</div></div>
    </div>
    <div class="card">
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Total</th><th>Status</th></tr></thead>
        <tbody id="orders-tbody"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ══════════ RELATÓRIOS ══════════ -->
  <div id="page-reports" class="page">
    <!-- Aparece apenas no PDF impresso -->
    <div class="report-header">
      <h1>ServeFacil — Relatório Gerencial</h1>
      <p id="report-print-date"></p>
    </div>

    <div class="page-header">
      <div>
        <div class="page-title">Relatórios <em>Gerenciais</em></div>
        <div class="page-sub">Dashboards e análises do sistema</div>
      </div>
      <button class="btn btn-pdf" onclick="exportPDF()">🖨️ Exportar PDF</button>
    </div>

    <!-- KPIs -->
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-label">Receita Total</div><div class="stat-value" id="rep-revenue">...</div><div class="stat-hint">pedidos entregues</div></div>
      <div class="stat-card" style="--c:var(--success)"><div class="stat-label">Ticket Médio</div><div class="stat-value" id="rep-ticket">...</div><div class="stat-hint">por pedido entregue</div></div>
      <div class="stat-card" style="--c:var(--info)"><div class="stat-label">Total de Pedidos</div><div class="stat-value" id="rep-total-orders">...</div><div class="stat-hint">todos os status</div></div>
      <div class="stat-card" style="--c:var(--warn)"><div class="stat-label">Clientes Ativos</div><div class="stat-value" id="rep-customers">...</div><div class="stat-hint">com pedido entregue</div></div>
    </div>

    <!-- Gráficos linha 1 -->
    <div class="charts-grid">
      <div class="chart-card">
        <div class="chart-title">📊 Receita por Restaurante</div>
        <div class="chart-wrap"><canvas id="chart-revenue"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">🥧 Distribuição de Status dos Pedidos</div>
        <div class="chart-wrap"><canvas id="chart-status"></canvas></div>
      </div>
    </div>

    <!-- Gráficos linha 2 -->
    <div class="charts-grid-3">
      <div class="chart-card">
        <div class="chart-title">📦 Top 5 Produtos Mais Vendidos</div>
        <div class="chart-wrap"><canvas id="chart-top-products"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">👑 Clientes por Nível de Fidelidade</div>
        <div class="chart-wrap"><canvas id="chart-levels"></canvas></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">💳 Vendas por Método de Pagamento</div>
        <div class="chart-wrap"><canvas id="chart-payment"></canvas></div>
      </div>
    </div>

    <!-- Tabela produtos mais vendidos -->
    <div class="slabel"><h3>Produtos Mais Vendidos</h3><div class="line"></div><span class="tag">ORDER BY qty_sold DESC</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Ranking de Vendas por Produto</span></div>
      <table>
        <thead><tr><th>Rank</th><th>Produto</th><th>Categoria</th><th>Qtd. Vendida</th><th>Receita Gerada</th><th>Estoque Atual</th></tr></thead>
        <tbody id="rep-top-products"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>

    <!-- Tabela top clientes -->
    <div class="slabel"><h3>Clientes — Maiores Gastos</h3><div class="line"></div><span class="tag">ORDER BY total_spent DESC</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Top 10 Clientes por Volume de Gasto</span></div>
      <table>
        <thead><tr><th>Cliente</th><th>Restaurante</th><th>Nível</th><th>Pontos</th><th>Total Gasto</th><th>Visitas</th></tr></thead>
        <tbody id="rep-top-customers"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>

    <!-- Tabela estoque crítico -->
    <div class="slabel"><h3>Estoque Crítico</h3><div class="line"></div><span class="tag">quantity &lt;= min_stock</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Produtos com Estoque Abaixo do Mínimo</span></div>
      <table>
        <thead><tr><th>Produto</th><th>Categoria</th><th>Restaurante</th><th>Qtd. Atual</th><th>Estoque Mín.</th><th>Situação</th></tr></thead>
        <tbody id="rep-low-stock"><tr><td colspan="6" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ══════════ VISÕES SQL ══════════ -->
  <div id="page-views" class="page">
    <div class="page-header">
      <div>
        <div class="page-title">Visões <em>SQL</em></div>
        <div class="page-sub">Dados das 3 views do banco de dados</div>
      </div>
    </div>
    <div class="slabel"><h3>vw_revenue_by_tenant</h3><div class="line"></div><span class="tag">Receita por restaurante</span></div>
    <div class="view-cards" id="view-revenue"><div class="loading">Carregando...</div></div>
    <div class="slabel"><h3>vw_order_summary</h3><div class="line"></div><span class="tag">JOIN de 5 tabelas</span></div>
    <div class="card" style="margin-bottom:24px">
      <div class="card-header"><span class="card-title">Resumo de Pedidos</span><span class="card-badge">Últimos 50</span></div>
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Itens</th><th>Subtotal</th><th>Total</th><th>Status</th></tr></thead>
        <tbody id="view-orders"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
    <div class="slabel"><h3>vw_cash_register_summary</h3><div class="line"></div><span class="tag">CASE WHEN + 4 tabelas</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Resumo dos Caixas</span></div>
      <table>
        <thead><tr><th>Caixa</th><th>Restaurante</th><th>Aberto por</th><th>Saldo Inicial</th><th>Total Entradas</th><th>Total Saídas</th><th>Status</th></tr></thead>
        <tbody id="view-cash"><tr><td colspan="7" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

</main>

<!-- ══════ MODAL PRODUTO/ESTOQUE ══════ -->
<div class="overlay" id="modal-product">
  <div class="modal">
    <div class="modal-title" id="prod-modal-title">Novo Item de Estoque</div>
    <input type="hidden" id="prod-id">
    <div class="fr">
      <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="prod-tenant-sel"><option value="">Selecione...</option></select></div>
      <div class="fg"><label class="fl">Categoria *</label><input class="fi" id="prod-cat" placeholder="Ex: Comidas, Bebidas"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Nome *</label><input class="fi" id="prod-name" placeholder="Ex: Feijoada Completa"></div>
      <div class="fg"><label class="fl">Unidade *</label><input class="fi" id="prod-unit" placeholder="Ex: porções, kg, unidades"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Quantidade</label><input class="fi" id="prod-qty" type="number" step="0.01" placeholder="0.00"></div>
      <div class="fg"><label class="fl">Estoque Mínimo</label><input class="fi" id="prod-min" type="number" step="0.01" placeholder="0.00"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Custo (R$)</label><input class="fi" id="prod-cost" type="number" step="0.01" placeholder="0.00"></div>
      <div class="fg"><label class="fl">Fornecedor</label><select class="fi" id="prod-supplier"><option value="">Nenhum</option></select></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-product')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveProduct()">Salvar</button>
    </div>
  </div>
</div>

<!-- ══════ MODAL CLIENTE ══════ -->
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
      <div class="fg"><label class="fl">Email</label><input class="fi" id="cust-email" type="email" placeholder="email@exemplo.com"></div>
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
  const el = $('toast'); el.textContent = msg;
  el.className = `toast show ${type}`;
  setTimeout(() => el.classList.remove('show'), 3000);
}

function showPage(name) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  $(`page-${name}`).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
  document.querySelector(`[data-page="${name}"]`).classList.add('active');
  ({dashboard:loadDashboard,products:loadProducts,customers:loadCustomers,
    orders:loadOrders,reports:loadReports,views:loadViews})[name]?.();
}

function openModal(id)  { $(id).classList.add('open'); }
function closeModal(id) { $(id).classList.remove('open'); }

async function api(resource, opts={}) {
  const url = `api.php?resource=${resource}` + (opts.params ? '&'+new URLSearchParams(opts.params) : '');
  const res = await fetch(url, {
    method: opts.method||'GET',
    headers: opts.body ? {'Content-Type':'application/json'} : {},
    body: opts.body ? JSON.stringify(opts.body) : undefined
  });
  return res.json();
}

function statusBadge(s) {
  const m = {delivered:'bg Entregue',canceled:'br Cancelado',cancelled:'br Cancelado',
             pending:'ba Pendente',preparing:'bb Preparando',ready:'bg Pronto',
             open:'bb Aberto',closed:'bm Fechado'};
  const [c,l] = (m[s]||'bm '+s).split(' ');
  return `<span class="badge ${c}">${l||s}</span>`;
}
function levelBadge(l) {
  const m = {gold:'ba Gold',silver:'bm Silver',bronze:'br Bronze'};
  const [c,lb] = (m[l]||'bm '+l).split(' ');
  return `<span class="badge ${c}">${lb||l}</span>`;
}

// ── TENANTS ──────────────────────────────────────────────
let tenantsCache = [];
async function loadTenants() {
  if (tenantsCache.length) return;
  tenantsCache = await api('tenants');
  ['prod-tenant','cust-tenant','prod-tenant-sel','cust-tenant-sel'].forEach(id => {
    const el = $(id); if (!el) return;
    const isFilter = id==='prod-tenant'||id==='cust-tenant';
    el.innerHTML = isFilter ? '<option value="">Todos os restaurantes</option>' : '<option value="">Selecione...</option>';
    tenantsCache.forEach(t => el.innerHTML += `<option value="${t.id}">${t.name}</option>`);
  });
}

// ── DASHBOARD ────────────────────────────────────────────
async function loadDashboard() {
  $('dash-date').textContent = new Date().toLocaleDateString('pt-BR',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  const [stats, orders] = await Promise.all([api('stats'), api('orders')]);
  $('s-revenue').textContent   = fmt(stats.revenue);
  $('s-orders').textContent    = fmtNum(stats.orders);
  $('s-customers').textContent = fmtNum(stats.customers);
  $('s-products').textContent  = fmtNum(stats.products);
  $('dash-orders').innerHTML = orders.slice(0,8).map(o => `
    <tr>
      <td style="font-weight:600">${o.id}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'<span style="color:var(--muted)">Sem cadastro</span>'}</td>
      <td>${o.table_number?'Mesa '+o.table_number:'—'}</td>
      <td>${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('') || '<tr><td colspan="6" class="empty">Nenhum pedido</td></tr>';
}

// ── PRODUCTS / ESTOQUE ───────────────────────────────────
async function loadProducts() {
  await loadTenants();
  const data = await api('products', {params:{search:$('prod-search').value, tenant_id:$('prod-tenant').value}});
  $('prod-count').textContent = `${data.length} registros`;
  $('prod-tbody').innerHTML = data.length ? data.map(p => {
    const low = parseFloat(p.quantity) <= parseFloat(p.min_stock);
    return `<tr>
      <td><strong>${p.name}</strong></td>
      <td><span class="badge bm">${p.category}</span></td>
      <td>${p.tenant_name||'—'}</td>
      <td style="font-weight:600;color:${low?'var(--danger)':'var(--text)'}">${p.quantity} ${p.unit}${low?' ⚠':''}</td>
      <td style="color:var(--muted)">${p.min_stock} ${p.unit}</td>
      <td>${fmt(p.cost)}</td>
      <td style="color:var(--muted)">${p.supplier_name||'—'}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick="editProduct(${p.id})">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.id},'${p.name.replace(/'/g,"\\'")}')">Excluir</button>
      </div></td>
    </tr>`;
  }).join('') : '<tr><td colspan="8" class="empty">Nenhum produto encontrado</td></tr>';
}

async function loadSuppliersForModal(tenantId=null, selectedId=null) {
  const data = await api('suppliers', {params:{tenant_id: tenantId||$('prod-tenant-sel').value}});
  const sel = $('prod-supplier');
  sel.innerHTML = '<option value="">Nenhum</option>';
  data.forEach(s => sel.innerHTML += `<option value="${s.id}">${s.name}</option>`);
  if (selectedId) sel.value = String(selectedId);
}

async function editProduct(id) {
  const p = await (await fetch(`api.php?resource=products&id=${id}`)).json();
  if (!p) { toast('Produto não encontrado','err'); return; }
  openProductModal(p);
}

async function openProductModal(data=null) {
  $('prod-modal-title').textContent = data ? 'Editar Item' : 'Novo Item de Estoque';
  $('prod-id').value   = data?.id||'';
  $('prod-name').value = data?.name||'';
  $('prod-cat').value  = data?.category||'';
  $('prod-unit').value = data?.unit||'';
  $('prod-qty').value  = data?.quantity||'';
  $('prod-min').value  = data?.min_stock||'';
  $('prod-cost').value = data?.cost||'';
  if (data?.tenant_id) $('prod-tenant-sel').value = data.tenant_id;
  await loadSuppliersForModal(data?.tenant_id||null, data?.supplier_id||null);
  openModal('modal-product');
}

async function saveProduct() {
  const id = $('prod-id').value;
  const body = {
    tenant_id:   $('prod-tenant-sel').value,
    name:        $('prod-name').value,
    category:    $('prod-cat').value,
    unit:        $('prod-unit').value,
    quantity:    $('prod-qty').value,
    min_stock:   $('prod-min').value,
    cost:        $('prod-cost').value,
    supplier_id: $('prod-supplier').value,
  };
  if (!body.tenant_id||!body.name||!body.unit) { toast('Preencha os campos obrigatórios','err'); return; }
  const res = await fetch(`api.php?resource=products${id?'&id='+id:''}`, {
    method: id?'PUT':'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body)
  });
  const r = await res.json();
  if (r.success) { toast(id?'Item atualizado!':'Item criado!'); closeModal('modal-product'); loadProducts(); }
  else toast('Erro: '+(r.error||''), 'err');
}

async function deleteProduct(id, name) {
  if (!confirm(`Excluir "${name}"?`)) return;
  const res = await fetch(`api.php?resource=products&id=${id}`, {method:'DELETE'});
  const r = await res.json();
  if (r.success) { toast('Item excluído!'); loadProducts(); }
  else toast('Erro: '+(r.error||''), 'err');
}

// ── CUSTOMERS ────────────────────────────────────────────
async function loadCustomers() {
  await loadTenants();
  const data = await api('customers', {params:{search:$('cust-search').value, tenant_id:$('cust-tenant').value}});
  $('cust-count').textContent = `${data.length} registros`;
  $('cust-tbody').innerHTML = data.length ? data.map(c => `
    <tr>
      <td><strong>${c.name}</strong></td>
      <td>${c.phone||'—'}</td>
      <td>${c.tenant_name||'—'}</td>
      <td>${levelBadge(c.level)}</td>
      <td>${fmtNum(c.points)} pts</td>
      <td style="font-weight:600">${fmt(c.total_spent)}</td>
      <td>${fmtNum(c.visits)}</td>
      <td><div class="actions">
        <button class="btn btn-ghost btn-sm" onclick="editCustomer(${c.id})">Editar</button>
        <button class="btn btn-danger btn-sm" onclick="deleteCustomer(${c.id},'${c.name.replace(/'/g,"\\'")}')">Excluir</button>
      </div></td>
    </tr>`).join('') : '<tr><td colspan="8" class="empty">Nenhum cliente encontrado</td></tr>';
}

function openCustomerModal(data=null) {
  $('cust-modal-title').textContent = data ? 'Editar Cliente' : 'Novo Cliente';
  $('cust-id').value    = data?.id||'';
  $('cust-name').value  = data?.name||'';
  $('cust-phone').value = data?.phone||'';
  $('cust-email').value = data?.email||'';
  $('cust-birth').value = data?.birthday||'';
  $('cust-addr').value  = data?.address||'';
  if (data?.tenant_id) $('cust-tenant-sel').value = data.tenant_id;
  openModal('modal-customer');
}

async function editCustomer(id) {
  const c = await (await fetch(`api.php?resource=customers&id=${id}`)).json();
  openCustomerModal(c);
}

async function saveCustomer() {
  const id = $('cust-id').value;
  const body = {
    tenant_id: $('cust-tenant-sel').value,
    name:      $('cust-name').value,
    phone:     $('cust-phone').value,
    email:     $('cust-email').value,
    birthday:  $('cust-birth').value,
    address:   $('cust-addr').value,
  };
  if (!body.tenant_id||!body.name||!body.phone) { toast('Preencha os campos obrigatórios','err'); return; }
  const res = await fetch(`api.php?resource=customers${id?'&id='+id:''}`, {
    method:id?'PUT':'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body)
  });
  const r = await res.json();
  if (r.success) { toast(id?'Cliente atualizado!':'Cliente criado!'); closeModal('modal-customer'); loadCustomers(); }
  else toast('Erro: '+(r.error||''), 'err');
}

async function deleteCustomer(id, name) {
  if (!confirm(`Excluir "${name}"?`)) return;
  const res = await fetch(`api.php?resource=customers&id=${id}`, {method:'DELETE'});
  const r = await res.json();
  if (r.success) { toast('Cliente excluído!'); loadCustomers(); }
  else toast('Erro ao excluir','err');
}

// ── ORDERS ───────────────────────────────────────────────
async function loadOrders() {
  const data = await api('orders');
  $('orders-tbody').innerHTML = data.length ? data.map(o => `
    <tr>
      <td style="font-weight:600">${o.id}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'<span style="color:var(--muted)">Sem cadastro</span>'}</td>
      <td>${o.table_number?'Mesa '+o.table_number:'—'}</td>
      <td>${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('') : '<tr><td colspan="6" class="empty">Nenhum pedido</td></tr>';
}

// ── REPORTS ──────────────────────────────────────────────
const CC = ['#e8a44a','#4caf7d','#5b9bd5','#e05252','#e8c44a','#c87dd4','#52b0e0','#f0a070'];
const CHART_BASE = {
  responsive:true, maintainAspectRatio:false,
  plugins:{legend:{labels:{color:'#f0ece4',font:{size:11}}}},
  scales:{x:{ticks:{color:'#7a7570'},grid:{color:'#2e2c29'}},y:{ticks:{color:'#7a7570'},grid:{color:'#2e2c29'}}}
};
const charts = {};
function killChart(id) { if(charts[id]){charts[id].destroy();delete charts[id];} }

async function loadReports() {
  const d = await api('report_data');
  const {kpis, revenue_by_tenant, status_dist, top_products, level_dist, payment_dist, top_customers, low_stock} = d;

  // KPIs
  $('rep-revenue').textContent      = fmt(kpis.revenue);
  $('rep-ticket').textContent       = fmt(kpis.ticket_medio);
  $('rep-total-orders').textContent = fmtNum(kpis.total_orders);
  $('rep-customers').textContent    = fmtNum(kpis.active_customers);
  $('report-print-date').textContent = 'Gerado em: '+new Date().toLocaleString('pt-BR');

  // Gráfico 1: Receita por restaurante (barras)
  killChart('chart-revenue');
  charts['chart-revenue'] = new Chart($('chart-revenue'), {
    type:'bar',
    data:{labels:revenue_by_tenant.map(r=>r.tenant_name),
          datasets:[{label:'Receita (R$)',data:revenue_by_tenant.map(r=>parseFloat(r.revenue)),backgroundColor:CC}]},
    options:{...CHART_BASE,plugins:{...CHART_BASE.plugins,legend:{display:false}}}
  });

  // Gráfico 2: Status dos pedidos (rosca)
  killChart('chart-status');
  charts['chart-status'] = new Chart($('chart-status'), {
    type:'doughnut',
    data:{labels:status_dist.map(s=>s.status),datasets:[{data:status_dist.map(s=>s.total),backgroundColor:CC}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#f0ece4',font:{size:11}}}}}
  });

  // Gráfico 3: Top produtos (barras horizontais)
  killChart('chart-top-products');
  const tp5 = top_products.slice(0,5);
  charts['chart-top-products'] = new Chart($('chart-top-products'), {
    type:'bar',
    data:{labels:tp5.map(p=>p.name.length>14?p.name.slice(0,14)+'…':p.name),
          datasets:[{label:'Qtd Vendida',data:tp5.map(p=>p.qty_sold),backgroundColor:'#e8a44a'}]},
    options:{...CHART_BASE,indexAxis:'y',plugins:{...CHART_BASE.plugins,legend:{display:false}}}
  });

  // Gráfico 4: Níveis de clientes (pizza)
  killChart('chart-levels');
  charts['chart-levels'] = new Chart($('chart-levels'), {
    type:'pie',
    data:{labels:level_dist.map(l=>l.level),datasets:[{data:level_dist.map(l=>l.total),backgroundColor:['#e8c44a','#7a7570','#e05252']}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{color:'#f0ece4',font:{size:11}}}}}
  });

  // Gráfico 5: Pagamento (barras)
  killChart('chart-payment');
  charts['chart-payment'] = new Chart($('chart-payment'), {
    type:'bar',
    data:{labels:payment_dist.map(p=>p.payment_method||'Outro'),
          datasets:[{label:'Total (R$)',data:payment_dist.map(p=>parseFloat(p.total)),backgroundColor:CC}]},
    options:{...CHART_BASE,plugins:{...CHART_BASE.plugins,legend:{display:false}}}
  });

  // Tabela top produtos
  $('rep-top-products').innerHTML = top_products.length ? top_products.map((p,i)=>`
    <tr>
      <td style="font-weight:700;color:var(--accent)">#${i+1}</td>
      <td><strong>${p.name}</strong></td>
      <td>${p.category||'—'}</td>
      <td style="font-weight:600">${fmtNum(p.qty_sold)}</td>
      <td>${fmt(p.revenue)}</td>
      <td style="color:${parseFloat(p.stock)<=parseFloat(p.min_stock)?'var(--danger)':'var(--text)'}">${p.stock} ${p.unit}</td>
    </tr>`).join('') : '<tr><td colspan="6" class="empty">Sem dados</td></tr>';

  // Tabela top clientes
  $('rep-top-customers').innerHTML = top_customers.length ? top_customers.map(c=>`
    <tr>
      <td><strong>${c.name}</strong></td>
      <td>${c.tenant_name||'—'}</td>
      <td>${levelBadge(c.level)}</td>
      <td>${fmtNum(c.points)} pts</td>
      <td style="font-weight:600">${fmt(c.total_spent)}</td>
      <td>${fmtNum(c.visits)}</td>
    </tr>`).join('') : '<tr><td colspan="6" class="empty">Sem dados</td></tr>';

  // Tabela estoque crítico
  $('rep-low-stock').innerHTML = low_stock.length ? low_stock.map(p=>{
    const pct = parseFloat(p.min_stock)>0 ? Math.round(parseFloat(p.quantity)/parseFloat(p.min_stock)*100) : 100;
    return `<tr>
      <td><strong>${p.name}</strong></td>
      <td>${p.category||'—'}</td>
      <td>${p.tenant_name||'—'}</td>
      <td style="font-weight:600;color:${pct<=50?'var(--danger)':'var(--warn)'}">${p.quantity} ${p.unit}</td>
      <td style="color:var(--muted)">${p.min_stock} ${p.unit}</td>
      <td><span class="badge br">⚠ Crítico</span></td>
    </tr>`;
  }).join('') : '<tr><td colspan="6" class="empty" style="color:var(--success)">✅ Todos os estoques OK</td></tr>';
}

function exportPDF() {
  $('report-print-date').textContent = 'Gerado em: '+new Date().toLocaleString('pt-BR');
  window.print();
}

// ── VIEWS ────────────────────────────────────────────────
async function loadViews() {
  const [revenue, orders, cash] = await Promise.all([api('view_revenue'),api('view_orders'),api('view_cash')]);
  $('view-revenue').innerHTML = revenue.length ? revenue.map(r=>`
    <div class="view-card">
      <div class="view-card-num">${fmt(r.revenue)}</div>
      <div class="view-card-name">${r.restaurant}</div>
      <div class="view-card-sub">${r.total_orders} pedidos</div>
    </div>`).join('') : '<div class="empty">Sem dados</div>';
  $('view-orders').innerHTML = orders.length ? orders.map(o=>`
    <tr>
      <td style="font-weight:600">${o.order_number}</td>
      <td>${o.restaurant||'—'}</td><td>${o.customer||'—'}</td>
      <td>${o.table_number?'Mesa '+o.table_number:'—'}</td>
      <td>${o.total_items||0}</td><td>${fmt(o.subtotal)}</td>
      <td style="font-weight:600">${fmt(o.total)}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('') : '<tr><td colspan="8" class="empty">Sem dados</td></tr>';
  $('view-cash').innerHTML = cash.length ? cash.map(c=>`
    <tr>
      <td><strong>${c.cash_register_name}</strong></td>
      <td>${c.tenant_name||'—'}</td><td>${c.opened_by_name||'—'}</td>
      <td>${fmt(c.opening_balance)}</td>
      <td style="color:var(--success)">${fmt(c.total_entries||0)}</td>
      <td style="color:var(--danger)">${fmt(c.total_exits||0)}</td>
      <td>${statusBadge(c.status)}</td>
    </tr>`).join('') : '<tr><td colspan="7" class="empty">Sem dados</td></tr>';
}

// ── NAV & INIT ───────────────────────────────────────────
document.querySelectorAll('.nav-item[data-page]').forEach(el => {
  el.addEventListener('click', () => showPage(el.dataset.page));
});
document.querySelectorAll('.overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) el.classList.remove('open'); });
});

loadTenants();
loadDashboard();
</script>
</body>
</html>