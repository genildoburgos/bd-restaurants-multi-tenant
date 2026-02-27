<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ServeFacil — Gestao de Restaurantes</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0f0e0d; --surface: #1a1917; --surface2: #242220;
  --border: #2e2c29; --accent: #e8a44a; --text: #f0ece4;
  --muted: #7a7570; --success: #4caf7d; --danger: #e05252;
  --info: #5b9bd5; --warn: #e8c44a;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { background:var(--bg); color:var(--text); font-family:'DM Sans',sans-serif; font-size:14px; display:flex; min-height:100vh; }

/* ── SIDEBAR ── */
.sidebar { width:220px; min-height:100vh; background:var(--surface); border-right:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:50; }
.logo { padding:24px 20px 18px; border-bottom:1px solid var(--border); }
.logo-name { font-family:'DM Serif Display',serif; font-size:22px; color:var(--accent); }
.logo-sub { font-size:10px; color:var(--muted); text-transform:uppercase; letter-spacing:2px; margin-top:2px; }
.nav { padding:14px 0; flex:1; overflow-y:auto; }
.nav-section { padding:10px 20px 4px; font-size:10px; text-transform:uppercase; letter-spacing:1.5px; color:var(--muted); }
.nav-item { display:flex; align-items:center; gap:9px; padding:9px 20px; color:var(--muted); cursor:pointer; font-size:13px; font-weight:500; border-left:2px solid transparent; transition:all .15s; user-select:none; }
.nav-item:hover { color:var(--text); background:rgba(255,255,255,.03); }
.nav-item.active { color:var(--accent); border-left-color:var(--accent); background:rgba(232,164,74,.06); }
.nav-icon { font-size:15px; width:18px; text-align:center; }

/* ── MAIN ── */
.main { margin-left:220px; padding:32px; flex:1; min-height:100vh; }

/* ── HEADER ── */
.page-header { display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:26px; }
.page-title { font-family:'DM Serif Display',serif; font-size:30px; line-height:1; }
.page-title em { color:var(--accent); font-style:italic; }
.page-sub { color:var(--muted); font-size:13px; margin-top:4px; }

/* ── BUTTONS ── */
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:500; cursor:pointer; border:none; font-family:'DM Sans',sans-serif; transition:all .15s; }
.btn-primary { background:var(--accent); color:#1a1408; }
.btn-primary:hover { background:#f0b35a; }
.btn-ghost { background:transparent; color:var(--muted); border:1px solid var(--border); }
.btn-ghost:hover { color:var(--text); border-color:var(--muted); }
.btn-sm { padding:5px 10px; font-size:12px; border-radius:5px; }
.btn-danger { background:rgba(224,82,82,.12); color:var(--danger); border:1px solid rgba(224,82,82,.2); }

/* ── STATS ── */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:26px; }
.stat-card { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:18px 20px; position:relative; overflow:hidden; }
.stat-card::after { content:''; position:absolute; top:0; left:0; right:0; height:2px; background:var(--c,var(--accent)); }
.stat-label { font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--muted); }
.stat-value { font-family:'DM Serif Display',serif; font-size:30px; color:var(--text); margin:5px 0 2px; }
.stat-hint { font-size:12px; color:var(--muted); }

/* ── CARD / TABLE ── */
.card { background:var(--surface); border:1px solid var(--border); border-radius:10px; overflow:hidden; margin-bottom:20px; }
.card-header { padding:14px 18px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-size:14px; font-weight:600; }
.card-badge { font-size:11px; background:rgba(232,164,74,.12); color:var(--accent); padding:3px 8px; border-radius:20px; }
table { width:100%; border-collapse:collapse; }
th { padding:10px 16px; text-align:left; font-size:11px; font-weight:500; text-transform:uppercase; letter-spacing:.8px; color:var(--muted); border-bottom:1px solid var(--border); }
td { padding:12px 16px; border-bottom:1px solid rgba(46,44,41,.5); font-size:13px; vertical-align:middle; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:rgba(255,255,255,.015); }

/* ── BADGES ── */
.badge { display:inline-flex; align-items:center; gap:4px; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:500; white-space:nowrap; }
.badge::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; }
.bg { background:rgba(76,175,125,.12); color:var(--success); }
.br { background:rgba(224,82,82,.12); color:var(--danger); }
.ba { background:rgba(232,164,74,.12); color:var(--accent); }
.bb { background:rgba(91,155,213,.12); color:var(--info); }
.bm { background:rgba(122,117,112,.12); color:var(--muted); }

/* ── SEARCH BAR ── */
.toolbar { display:flex; gap:10px; margin-bottom:16px; align-items:center; }
.search { flex:1; padding:8px 12px; background:var(--surface); border:1px solid var(--border); border-radius:6px; color:var(--text); font-size:13px; font-family:'DM Sans',sans-serif; }
.search:focus { outline:none; border-color:var(--accent); }
select.search { cursor:pointer; }

/* ── ACTIONS ── */
.actions { display:flex; gap:5px; }

/* ── SECTION LABEL ── */
.slabel { display:flex; align-items:center; gap:10px; margin-bottom:14px; margin-top:8px; }
.slabel h3 { font-size:14px; font-weight:600; white-space:nowrap; }
.slabel .line { flex:1; height:1px; background:var(--border); }
.slabel .tag { font-size:10px; background:rgba(91,155,213,.15); color:var(--info); padding:2px 7px; border-radius:10px; white-space:nowrap; }

/* ── VIEWS CARDS ── */
.view-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; }
.view-card { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:22px; text-align:center; }
.view-card-num { font-family:'DM Serif Display',serif; font-size:38px; color:var(--accent); }
.view-card-name { font-size:14px; font-weight:500; margin-top:8px; }
.view-card-sub { font-size:12px; color:var(--muted); margin-top:3px; }

/* ── MODAL ── */
.overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); z-index:200; display:none; align-items:center; justify-content:center; backdrop-filter:blur(4px); }
.overlay.open { display:flex; }
.modal { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:28px; width:500px; max-height:90vh; overflow-y:auto; }
.modal-title { font-family:'DM Serif Display',serif; font-size:22px; margin-bottom:20px; }
.fg { margin-bottom:14px; }
.fl { display:block; font-size:11px; font-weight:500; color:var(--muted); margin-bottom:5px; text-transform:uppercase; letter-spacing:.5px; }
.fi { width:100%; padding:9px 12px; background:var(--bg); border:1px solid var(--border); border-radius:6px; color:var(--text); font-size:13px; font-family:'DM Sans',sans-serif; }
.fi:focus { outline:none; border-color:var(--accent); }
.fr { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.modal-footer { display:flex; gap:8px; justify-content:flex-end; margin-top:20px; padding-top:16px; border-top:1px solid var(--border); }

/* ── TOAST ── */
.toast { position:fixed; bottom:24px; right:24px; background:var(--surface2); border:1px solid var(--border); border-radius:8px; padding:12px 18px; font-size:13px; z-index:300; opacity:0; transform:translateY(8px); transition:all .25s; pointer-events:none; }
.toast.show { opacity:1; transform:translateY(0); }
.toast.ok { border-left:3px solid var(--success); }
.toast.err { border-left:3px solid var(--danger); }

/* ── LOADING ── */
.loading { text-align:center; padding:40px; color:var(--muted); font-size:13px; }
.empty { text-align:center; padding:40px; color:var(--muted); font-size:13px; }

/* ── PAGES ── */
.page { display:none; }
.page.active { display:block; }
</style>
</head>
<body>

<aside class="sidebar">
  <div class="logo">
    <div class="logo-name">ServeFacil</div>
    <div class="logo-sub">Multi-Tenant v1.0</div>
  </div>
  <nav class="nav">
    <div class="nav-section">Principal</div>
    <div class="nav-item active" data-page="dashboard"><span class="nav-icon">🏠</span> Dashboard</div>
    <div class="nav-item" data-page="orders"><span class="nav-icon">📋</span> Pedidos</div>
    <div class="nav-section">Cadastros</div>
    <div class="nav-item" data-page="products"><span class="nav-icon">🍽</span> Produtos</div>
    <div class="nav-item" data-page="customers"><span class="nav-icon">👤</span> Clientes</div>
    <div class="nav-section">Relatorios</div>
    <div class="nav-item" data-page="views"><span class="nav-icon">📊</span> Visoes SQL</div>
  </nav>
</aside>

<main class="main">

  <!-- ═══════════ DASHBOARD ═══════════ -->
  <div id="page-dashboard" class="page active">
    <div class="page-header">
      <div>
        <div class="page-title">Bem-vindo, <em>Admin</em></div>
        <div class="page-sub" id="dash-date">Carregando...</div>
      </div>
    </div>
    <div class="stats-grid" id="dash-stats">
      <div class="stat-card"><div class="stat-label">Receita Total</div><div class="stat-value" id="s-revenue">...</div><div class="stat-hint">pedidos pagos</div></div>
      <div class="stat-card" style="--c:var(--success)"><div class="stat-label">Pedidos Entregues</div><div class="stat-value" id="s-orders">...</div><div class="stat-hint">status delivered</div></div>
      <div class="stat-card" style="--c:var(--info)"><div class="stat-label">Clientes</div><div class="stat-value" id="s-customers">...</div><div class="stat-hint">cadastrados</div></div>
      <div class="stat-card" style="--c:var(--warn)"><div class="stat-label">Produtos Ativos</div><div class="stat-value" id="s-products">...</div><div class="stat-hint">disponiveis</div></div>
    </div>
    <div class="slabel"><h3>Ultimos Pedidos</h3><div class="line"></div></div>
    <div class="card">
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Tipo</th><th>Total</th><th>Status</th></tr></thead>
        <tbody id="dash-orders"><tr><td colspan="7" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ═══════════ PRODUTOS ═══════════ -->
  <div id="page-products" class="page">
    <div class="page-header">
      <div>
        <div class="page-title">Produtos</div>
        <div class="page-sub">Gerenciamento do cardapio</div>
      </div>
      <button class="btn btn-primary" onclick="openProductModal()">+ Novo Produto</button>
    </div>
    <div class="toolbar">
      <input class="search" placeholder="Buscar produto..." id="prod-search" oninput="loadProducts()">
      <select class="search" style="max-width:180px" id="prod-tenant" onchange="loadProducts(); loadCategories();">
        <option value="">Todos os restaurantes</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header">
        <span class="card-title">Cardapio</span>
        <span class="card-badge" id="prod-count">...</span>
      </div>
      <table>
        <thead><tr><th>Nome</th><th>SKU</th><th>Categoria</th><th>Restaurante</th><th>Preco</th><th>Custo</th><th>Status</th><th>Acoes</th></tr></thead>
        <tbody id="prod-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ═══════════ CLIENTES ═══════════ -->
  <div id="page-customers" class="page">
    <div class="page-header">
      <div>
        <div class="page-title">Clientes</div>
        <div class="page-sub">Programa de fidelidade</div>
      </div>
      <button class="btn btn-primary" onclick="openCustomerModal()">+ Novo Cliente</button>
    </div>
    <div class="toolbar">
      <input class="search" placeholder="Buscar cliente..." id="cust-search" oninput="loadCustomers()">
      <select class="search" style="max-width:180px" id="cust-tenant" onchange="loadCustomers()">
        <option value="">Todos os restaurantes</option>
      </select>
    </div>
    <div class="card">
      <div class="card-header">
        <span class="card-title">Clientes Cadastrados</span>
        <span class="card-badge" id="cust-count">...</span>
      </div>
      <table>
        <thead><tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Restaurante</th><th>Nivel</th><th>Pontos</th><th>Status</th><th>Acoes</th></tr></thead>
        <tbody id="cust-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ═══════════ PEDIDOS ═══════════ -->
  <div id="page-orders" class="page">
    <div class="page-header">
      <div><div class="page-title">Pedidos</div><div class="page-sub">Historico completo</div></div>
    </div>
    <div class="card">
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Tipo</th><th>Total</th><th>Pagamento</th><th>Status</th></tr></thead>
        <tbody id="orders-tbody"><tr><td colspan="8" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

  <!-- ═══════════ VISOES SQL ═══════════ -->
  <div id="page-views" class="page">
    <div class="page-header">
      <div>
        <div class="page-title">Visoes <em>SQL</em></div>
        <div class="page-sub">Dados das 3 views do banco de dados</div>
      </div>
    </div>

    <div class="slabel"><h3>vw_revenue_by_tenant</h3><div class="line"></div><span class="tag">Receita por restaurante</span></div>
    <div class="view-cards" id="view-revenue"><div class="loading">Carregando...</div></div>

    <div class="slabel"><h3>vw_order_summary</h3><div class="line"></div><span class="tag">JOIN de 5 tabelas</span></div>
    <div class="card" style="margin-bottom:24px">
      <div class="card-header"><span class="card-title">Resumo de Pedidos com dados completos</span><span class="card-badge">Ultimos 50</span></div>
      <table>
        <thead><tr><th>#</th><th>Restaurante</th><th>Cliente</th><th>Mesa</th><th>Itens</th><th>Subtotal</th><th>Desconto</th><th>Total</th><th>Status</th></tr></thead>
        <tbody id="view-orders"><tr><td colspan="9" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>

    <div class="slabel"><h3>vw_cash_register_summary</h3><div class="line"></div><span class="tag">CASE WHEN + 4 tabelas</span></div>
    <div class="card">
      <div class="card-header"><span class="card-title">Resumo dos Caixas</span></div>
      <table>
        <thead><tr><th>Caixa</th><th>Restaurante</th><th>Aberto por</th><th>Saldo Inicial</th><th>Total Entradas</th><th>Total Saidas</th><th>Status</th></tr></thead>
        <tbody id="view-cash"><tr><td colspan="7" class="loading">Carregando...</td></tr></tbody>
      </table>
    </div>
  </div>

</main>

<!-- ═══════════ MODAL PRODUTO ═══════════ -->
<div class="overlay" id="modal-product">
  <div class="modal">
    <div class="modal-title" id="prod-modal-title">Novo Produto</div>
    <input type="hidden" id="prod-id">
    <div class="fr">
      <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="prod-tenant-sel" onchange="loadCategoriesForSelect()"><option value="">Selecione...</option></select></div>
      <div class="fg"><label class="fl">Categoria *</label><select class="fi" id="prod-cat-sel"><option value="">Selecione...</option></select></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Nome *</label><input class="fi" id="prod-name" placeholder="Ex: File ao Molho Madeira"></div>
      <div class="fg"><label class="fl">SKU *</label><input class="fi" id="prod-sku" placeholder="Ex: PRATO010"></div>
    </div>
    <div class="fg"><label class="fl">Descricao</label><input class="fi" id="prod-desc" placeholder="Descricao breve do produto"></div>
    <div class="fr">
      <div class="fg"><label class="fl">Preco (R$) *</label><input class="fi" id="prod-price" type="number" step="0.01" placeholder="0.00"></div>
      <div class="fg"><label class="fl">Custo (R$)</label><input class="fi" id="prod-cost" type="number" step="0.01" placeholder="0.00"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Tempo Preparo (min)</label><input class="fi" id="prod-prep" type="number" value="15"></div>
      <div class="fg"><label class="fl">Disponivel</label><select class="fi" id="prod-avail"><option value="1">Sim</option><option value="0">Nao</option></select></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-product')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveProduct()">Salvar Produto</button>
    </div>
  </div>
</div>

<!-- ═══════════ MODAL CLIENTE ═══════════ -->
<div class="overlay" id="modal-customer">
  <div class="modal">
    <div class="modal-title" id="cust-modal-title">Novo Cliente</div>
    <input type="hidden" id="cust-id">
    <div class="fg"><label class="fl">Restaurante *</label><select class="fi" id="cust-tenant-sel"><option value="">Selecione...</option></select></div>
    <div class="fr">
      <div class="fg"><label class="fl">Nome *</label><input class="fi" id="cust-name" placeholder="Nome completo"></div>
      <div class="fg"><label class="fl">Email *</label><input class="fi" id="cust-email" type="email" placeholder="email@exemplo.com"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Telefone</label><input class="fi" id="cust-phone" placeholder="(81) 99999-0000"></div>
      <div class="fg"><label class="fl">CPF</label><input class="fi" id="cust-cpf" placeholder="000.000.000-00"></div>
    </div>
    <div class="fr">
      <div class="fg"><label class="fl">Data Nasc.</label><input class="fi" id="cust-birth" type="date"></div>
      <div class="fg"><label class="fl">Status</label><select class="fi" id="cust-status"><option value="active">Ativo</option><option value="inactive">Inativo</option></select></div>
    </div>
    <div class="fg"><label class="fl">Endereco</label><input class="fi" id="cust-addr" placeholder="Rua, numero - Cidade/UF"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal('modal-customer')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveCustomer()">Salvar Cliente</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast" id="toast"></div>

<script>
// ─── UTILS ───────────────────────────────────────────────────────────────────
const $ = id => document.getElementById(id);
const fmt = v => parseFloat(v||0).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
const fmtNum = v => parseInt(v||0).toLocaleString('pt-BR');

function toast(msg, type='ok') {
  const el = $('toast');
  el.textContent = msg;
  el.className = `toast show ${type}`;
  setTimeout(() => el.classList.remove('show'), 2800);
}

function showPage(name) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  $(`page-${name}`).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
  document.querySelector(`[data-page="${name}"]`).classList.add('active');
  const loaders = { dashboard: loadDashboard, products: loadProducts, customers: loadCustomers, orders: loadOrders, views: loadViews };
  if (loaders[name]) loaders[name]();
}

function openModal(id) { $(id).classList.add('open'); }
function closeModal(id) { $(id).classList.remove('open'); }

async function api(resource, options={}) {
  const base = `api.php?resource=${resource}`;
  const params = options.params ? '&' + new URLSearchParams(options.params).toString() : '';
  const res = await fetch(base + params, {
    method: options.method || 'GET',
    headers: options.body ? {'Content-Type':'application/json'} : {},
    body: options.body ? JSON.stringify(options.body) : undefined
  });
  return res.json();
}

function statusBadge(s) {
  const map = { delivered:'bg entregue', cancelled:'br cancelado', pending:'ba pendente', open:'bb aberto', closed:'bm fechado' };
  const [cls, label] = (map[s]||'bm '+s).split(' ');
  return `<span class="badge ${cls}">${label||s}</span>`;
}
function typeBadge(t) {
  const map = { dine_in:'bb dine_in', takeaway:'ba takeaway', delivery:'bg delivery' };
  const [cls, label] = (map[t]||'bm '+t).split(' ');
  return `<span class="badge ${cls}">${label||t}</span>`;
}
function levelBadge(l) {
  const map = { platinum:'bb platinum', gold:'ba gold', silver:'bm silver', bronze:'br bronze' };
  const [cls, label] = (map[l]||'bm '+l).split(' ');
  return `<span class="badge ${cls}">${label||l}</span>`;
}

// ─── TENANTS (shared) ────────────────────────────────────────────────────────
let tenantsCache = [];
async function loadTenants() {
  if (tenantsCache.length) return tenantsCache;
  tenantsCache = await api('tenants');
  ['prod-tenant','cust-tenant','prod-tenant-sel','cust-tenant-sel'].forEach(id => {
    const el = $(id); if (!el) return;
    const hasAll = id === 'prod-tenant' || id === 'cust-tenant';
    if (hasAll) el.innerHTML = '<option value="">Todos os restaurantes</option>';
    else el.innerHTML = '<option value="">Selecione...</option>';
    tenantsCache.forEach(t => el.innerHTML += `<option value="${t.id}">${t.name}</option>`);
  });
  return tenantsCache;
}

// ─── DASHBOARD ───────────────────────────────────────────────────────────────
async function loadDashboard() {
  const now = new Date();
  $('dash-date').textContent = now.toLocaleDateString('pt-BR',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

  const [stats, orders] = await Promise.all([api('stats'), api('orders')]);
  $('s-revenue').textContent   = fmt(stats.revenue);
  $('s-orders').textContent    = fmtNum(stats.orders);
  $('s-customers').textContent = fmtNum(stats.customers);
  $('s-products').textContent  = fmtNum(stats.products);

  const rows = orders.slice(0,8).map(o => `
    <tr>
      <td style="font-weight:600">${o.order_number}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'<span style="color:var(--muted)">Sem cadastro</span>'}</td>
      <td>${o.table_number ? 'Mesa '+o.table_number : '—'}</td>
      <td>${typeBadge(o.type)}</td>
      <td>${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('');
  $('dash-orders').innerHTML = rows || '<tr><td colspan="7" class="empty">Nenhum pedido</td></tr>';
}

// ─── PRODUCTS ────────────────────────────────────────────────────────────────
async function loadProducts() {
  await loadTenants();
  const search = $('prod-search').value;
  const tenant = $('prod-tenant').value;
  const data = await api('products', { params: { search, tenant_id: tenant } });
  $('prod-count').textContent = `${data.length} registros`;
  $('prod-tbody').innerHTML = data.length ? data.map(p => `
    <tr>
      <td><strong>${p.name}</strong></td>
      <td style="color:var(--muted);font-size:12px">${p.sku}</td>
      <td>${p.category_name||'—'}</td>
      <td>${p.tenant_name||'—'}</td>
      <td>${fmt(p.price)}</td>
      <td style="color:var(--muted)">${fmt(p.cost)}</td>
      <td>${p.is_available=='1'?'<span class="badge bg">disponivel</span>':'<span class="badge br">indisponivel</span>'}</td>
      <td>
        <div class="actions">
          <button class="btn btn-ghost btn-sm" onclick="editProduct(${p.id})">Editar</button>
          <button class="btn btn-danger btn-sm" onclick="deleteProduct(${p.id},'${p.name.replace(/'/g,"\\'")}')">Excluir</button>
        </div>
      </td>
    </tr>`).join('') : '<tr><td colspan="8" class="empty">Nenhum produto encontrado</td></tr>';
}

async function loadCategories() {
  const tenant = $('prod-tenant').value;
  const data = await api('categories', { params: { tenant_id: tenant } });
  const sel = $('prod-cat-sel');
  sel.innerHTML = '<option value="">Selecione...</option>';
  data.forEach(c => sel.innerHTML += `<option value="${c.id}">${c.name}</option>`);
}

async function loadCategoriesForSelect() {
  const tenant = $('prod-tenant-sel').value;
  const data = await api('categories', { params: { tenant_id: tenant } });
  const sel = $('prod-cat-sel');
  sel.innerHTML = '<option value="">Selecione...</option>';
  data.forEach(c => sel.innerHTML += `<option value="${c.id}">${c.name}</option>`);
}

function openProductModal(data=null) {
  $('prod-modal-title').textContent = data ? 'Editar Produto' : 'Novo Produto';
  $('prod-id').value       = data?.id || '';
  $('prod-name').value     = data?.name || '';
  $('prod-sku').value      = data?.sku || '';
  $('prod-desc').value     = data?.description || '';
  $('prod-price').value    = data?.price || '';
  $('prod-cost').value     = data?.cost || '';
  $('prod-prep').value     = data?.preparation_time || 15;
  $('prod-avail').value    = data?.is_available ?? 1;
  if (data?.tenant_id) $('prod-tenant-sel').value = data.tenant_id;
  if (data?.category_id) $('prod-cat-sel').value = data.category_id;
  openModal('modal-product');
}

async function editProduct(id) {
  const data = await api('products', { params: { id } });
  // Reuse fetch with id
  const res = await fetch(`api.php?resource=products&id=${id}`);
  const p = await res.json();
  openProductModal(p);
}

async function saveProduct() {
  const id = $('prod-id').value;
  const body = {
    tenant_id: $('prod-tenant-sel').value,
    category_id: $('prod-cat-sel').value,
    name: $('prod-name').value,
    sku: $('prod-sku').value,
    description: $('prod-desc').value,
    price: $('prod-price').value,
    cost: $('prod-cost').value,
    preparation_time: $('prod-prep').value,
    is_available: $('prod-avail').value
  };
  if (!body.tenant_id || !body.name || !body.price || !body.sku) { toast('Preencha os campos obrigatorios','err'); return; }
  const method = id ? 'PUT' : 'POST';
  const params = id ? `&id=${id}` : '';
  const res = await fetch(`api.php?resource=products${params}`, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const result = await res.json();
  if (result.success) {
    toast(id ? 'Produto atualizado!' : 'Produto criado!');
    closeModal('modal-product');
    loadProducts();
  } else { toast('Erro ao salvar: ' + (result.error||''), 'err'); }
}

async function deleteProduct(id, name) {
  if (!confirm(`Excluir o produto "${name}"?`)) return;
  const res = await fetch(`api.php?resource=products&id=${id}`, { method:'DELETE' });
  const result = await res.json();
  if (result.success) { toast('Produto excluido!'); loadProducts(); }
  else toast('Erro ao excluir', 'err');
}

// ─── CUSTOMERS ────────────────────────────────────────────────────────────────
async function loadCustomers() {
  await loadTenants();
  const search = $('cust-search').value;
  const tenant = $('cust-tenant').value;
  const data = await api('customers', { params: { search, tenant_id: tenant } });
  $('cust-count').textContent = `${data.length} registros`;
  $('cust-tbody').innerHTML = data.length ? data.map(c => `
    <tr>
      <td><strong>${c.name}</strong></td>
      <td style="color:var(--muted)">${c.email}</td>
      <td>${c.phone||'—'}</td>
      <td>${c.tenant_name||'—'}</td>
      <td>${levelBadge(c.level)}</td>
      <td>${fmtNum(c.points)} pts</td>
      <td>${c.status==='active'?'<span class="badge bg">ativo</span>':'<span class="badge br">inativo</span>'}</td>
      <td>
        <div class="actions">
          <button class="btn btn-ghost btn-sm" onclick="editCustomer(${c.id})">Editar</button>
          <button class="btn btn-danger btn-sm" onclick="deleteCustomer(${c.id},'${c.name.replace(/'/g,"\\'")}')">Excluir</button>
        </div>
      </td>
    </tr>`).join('') : '<tr><td colspan="8" class="empty">Nenhum cliente encontrado</td></tr>';
}

function openCustomerModal(data=null) {
  $('cust-modal-title').textContent = data ? 'Editar Cliente' : 'Novo Cliente';
  $('cust-id').value     = data?.id || '';
  $('cust-name').value   = data?.name || '';
  $('cust-email').value  = data?.email || '';
  $('cust-phone').value  = data?.phone || '';
  $('cust-cpf').value    = data?.cpf || '';
  $('cust-birth').value  = data?.birth_date || '';
  $('cust-addr').value   = data?.address || '';
  $('cust-status').value = data?.status || 'active';
  if (data?.tenant_id) $('cust-tenant-sel').value = data.tenant_id;
  openModal('modal-customer');
}

async function editCustomer(id) {
  const res = await fetch(`api.php?resource=customers&id=${id}`);
  const c = await res.json();
  openCustomerModal(c);
}

async function saveCustomer() {
  const id = $('cust-id').value;
  const body = {
    tenant_id: $('cust-tenant-sel').value,
    name: $('cust-name').value,
    email: $('cust-email').value,
    phone: $('cust-phone').value,
    cpf: $('cust-cpf').value,
    birth_date: $('cust-birth').value,
    address: $('cust-addr').value,
    status: $('cust-status').value
  };
  if (!body.tenant_id || !body.name || !body.email) { toast('Preencha os campos obrigatorios','err'); return; }
  const method = id ? 'PUT' : 'POST';
  const params = id ? `&id=${id}` : '';
  const res = await fetch(`api.php?resource=customers${params}`, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const result = await res.json();
  if (result.success) {
    toast(id ? 'Cliente atualizado!' : 'Cliente criado!');
    closeModal('modal-customer');
    loadCustomers();
  } else { toast('Erro ao salvar: ' + (result.error||''), 'err'); }
}

async function deleteCustomer(id, name) {
  if (!confirm(`Excluir o cliente "${name}"?`)) return;
  const res = await fetch(`api.php?resource=customers&id=${id}`, { method:'DELETE' });
  const result = await res.json();
  if (result.success) { toast('Cliente excluido!'); loadCustomers(); }
  else toast('Erro ao excluir', 'err');
}

// ─── ORDERS ──────────────────────────────────────────────────────────────────
async function loadOrders() {
  const data = await api('orders');
  $('orders-tbody').innerHTML = data.length ? data.map(o => `
    <tr>
      <td style="font-weight:600">${o.order_number}</td>
      <td>${o.tenant_name||'—'}</td>
      <td>${o.customer_name||'<span style="color:var(--muted)">Sem cadastro</span>'}</td>
      <td>${o.table_number ? 'Mesa '+o.table_number : '—'}</td>
      <td>${typeBadge(o.type)}</td>
      <td>${o.total>0?fmt(o.total):'—'}</td>
      <td>${o.payment_method||'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('') : '<tr><td colspan="8" class="empty">Nenhum pedido</td></tr>';
}

// ─── VIEWS ───────────────────────────────────────────────────────────────────
async function loadViews() {
  const [revenue, orders, cash] = await Promise.all([
    api('view_revenue'), api('view_orders'), api('view_cash')
  ]);

  // View 1: revenue cards
  $('view-revenue').innerHTML = revenue.length ? revenue.map(r => `
    <div class="view-card">
      <div class="view-card-num">${fmt(r.revenue)}</div>
      <div class="view-card-name">${r.restaurant}</div>
      <div class="view-card-sub">${r.total_orders} pedidos · tenant #${r.tenant_id}</div>
    </div>`).join('') : '<div class="empty">Sem dados</div>';

  // View 2: order summary
  $('view-orders').innerHTML = orders.length ? orders.map(o => `
    <tr>
      <td style="font-weight:600">${o.order_number}</td>
      <td>${o.restaurant||'—'}</td>
      <td>${o.customer||'<span style="color:var(--muted)">—</span>'}</td>
      <td>${o.table_number ? 'Mesa '+o.table_number : '—'}</td>
      <td style="text-align:center">${o.total_items||0}</td>
      <td>${fmt(o.subtotal)}</td>
      <td style="color:var(--danger)">${o.discount>0?'-'+fmt(o.discount):'—'}</td>
      <td style="font-weight:600">${o.total>0?fmt(o.total):'—'}</td>
      <td>${statusBadge(o.status)}</td>
    </tr>`).join('') : '<tr><td colspan="9" class="empty">Sem dados</td></tr>';

  // View 3: cash summary
  $('view-cash').innerHTML = cash.length ? cash.map(c => `
    <tr>
      <td><strong>${c.cash_register_name}</strong></td>
      <td>${c.tenant_name||'—'}</td>
      <td>${c.opened_by_name||'—'}</td>
      <td>${fmt(c.opening_balance)}</td>
      <td style="color:var(--success)">${fmt(c.total_entries||0)}</td>
      <td style="color:var(--danger)">${fmt(c.total_exits||0)}</td>
      <td>${statusBadge(c.status)}</td>
    </tr>`).join('') : '<tr><td colspan="7" class="empty">Sem dados</td></tr>';
}

// ─── NAV ─────────────────────────────────────────────────────────────────────
document.querySelectorAll('.nav-item[data-page]').forEach(el => {
  el.addEventListener('click', () => showPage(el.dataset.page));
});

// Close modals clicking outside
document.querySelectorAll('.overlay').forEach(el => {
  el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
});

// ─── INIT ─────────────────────────────────────────────────────────────────────
loadTenants();
loadDashboard();
</script>
</body>
</html>