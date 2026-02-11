<!doctype html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NeuroBro — Админ-панель</title>
    <link rel="icon" href="../dist/favicon.ico" />
    <link rel="stylesheet" href="assets/app.css" />
  </head>
  <body data-page="admin" class="min-h-screen bg-background flex">
    <div id="toast-viewport" class="fixed top-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]"></div>

    <div id="page-loading" class="min-h-screen bg-background flex items-center justify-center w-full">
      <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div id="page-content" class="min-h-screen bg-background flex w-full hidden">
      <aside class="w-64 border-r border-border/30 glass p-4 flex flex-col shrink-0 hidden md:flex">
        <div class="flex items-center gap-2 mb-8">
          <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center">
            <i data-lucide="bot" class="w-5 h-5 text-primary"></i>
          </div>
          <div>
            <p class="font-bold text-sm">NeuroBro</p>
            <p class="text-xs text-muted-foreground">Админ-панель</p>
          </div>
        </div>

        <nav class="space-y-1 flex-1">
          <button data-admin-tab="overview" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors bg-primary/10 text-primary border border-primary/20">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Обзор
          </button>
          <button data-admin-tab="users" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-muted-foreground hover:text-foreground hover:bg-secondary/50">
            <i data-lucide="users" class="w-4 h-4"></i> Пользователи
          </button>
          <button data-admin-tab="payments" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-muted-foreground hover:text-foreground hover:bg-secondary/50">
            <i data-lucide="credit-card" class="w-4 h-4"></i> Платежи
          </button>
          <button data-admin-tab="settings" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-muted-foreground hover:text-foreground hover:bg-secondary/50">
            <i data-lucide="settings" class="w-4 h-4"></i> Настройки
          </button>
        </nav>

        <button id="admin-back" class="text-muted-foreground justify-start text-sm py-2 px-2 rounded-md hover:bg-secondary/50 flex items-center">
          <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Назад
        </button>
      </aside>

      <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 border-t border-border/30 bg-card/90 backdrop-blur-sm p-2">
        <div class="grid grid-cols-4 gap-1">
          <button data-admin-tab="overview" class="flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-primary bg-primary/10">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Обзор
          </button>
          <button data-admin-tab="users" class="flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-muted-foreground">
            <i data-lucide="users" class="w-4 h-4"></i> Пользователи
          </button>
          <button data-admin-tab="payments" class="flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-muted-foreground">
            <i data-lucide="credit-card" class="w-4 h-4"></i> Платежи
          </button>
          <button data-admin-tab="settings" class="flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-muted-foreground">
            <i data-lucide="settings" class="w-4 h-4"></i> Настройки
          </button>
        </div>
      </div>

      <main class="flex-1 p-4 md:p-6 overflow-y-auto pb-20 md:pb-6">
        <div class="md:hidden flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <i data-lucide="bot" class="w-5 h-5 text-primary"></i>
            <span class="font-bold text-sm">Админ-панель</span>
          </div>
          <button id="admin-back-mobile" class="text-muted-foreground rounded-md px-2 py-1 hover:bg-secondary/50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
          </button>
        </div>

        <section id="admin-overview" data-admin-section>
          <div class="space-y-6">
            <div class="flex items-center justify-between">
              <h1 class="text-2xl font-bold">Обзор</h1>
              <button id="admin-refresh" class="border border-border/50 rounded-md px-3 py-2 text-sm hover:bg-secondary/50">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 inline-block"></i> Обновить
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="glass border-border/30 border rounded-lg p-4">
                <div class="flex items-center justify-between pb-2">
                  <p class="text-sm font-medium text-muted-foreground">Пользователей</p>
                  <i data-lucide="users" class="w-4 h-4 text-neon-purple"></i>
                </div>
                <p id="stat-total" class="text-2xl font-bold font-mono">0</p>
              </div>
              <div class="glass border-border/30 border rounded-lg p-4">
                <div class="flex items-center justify-between pb-2">
                  <p class="text-sm font-medium text-muted-foreground">С подпиской</p>
                  <i data-lucide="user-check" class="w-4 h-4 text-neon-cyan"></i>
                </div>
                <p id="stat-with-sub" class="text-2xl font-bold font-mono">0</p>
              </div>
              <div class="glass border-border/30 border rounded-lg p-4">
                <div class="flex items-center justify-between pb-2">
                  <p class="text-sm font-medium text-muted-foreground">Всего токенов</p>
                  <i data-lucide="coins" class="w-4 h-4 text-neon-green"></i>
                </div>
                <p id="stat-total-tokens" class="text-2xl font-bold font-mono">0</p>
              </div>
            </div>

            <div class="glass border-border/30 border rounded-lg">
              <div class="p-4 border-b border-border/20">
                <p class="text-base font-semibold">Последние пользователи</p>
              </div>
              <div id="overview-users" class="p-4 space-y-2"></div>
            </div>
          </div>
        </section>

        <section id="admin-users" data-admin-section class="hidden">
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <h1 class="text-2xl font-bold">Пользователи (<span id="users-count">0</span>)</h1>
              <button id="users-refresh" class="border border-border/50 rounded-md px-3 py-2 text-sm hover:bg-secondary/50">
                <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 inline-block"></i> Обновить
              </button>
            </div>

            <div class="relative">
              <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"></i>
              <input id="users-search" placeholder="Поиск по email или реф. коду..." class="w-full pl-10 bg-secondary/50 border border-border/50 rounded-md px-3 py-2" />
            </div>

            <div id="users-list" class="space-y-2"></div>
          </div>
        </section>

        <section id="admin-payments" data-admin-section class="hidden">
          <div class="space-y-6">
            <h1 class="text-2xl font-bold">Платежи</h1>
            <div class="glass rounded-xl p-6 text-center">
              <i data-lucide="credit-card" class="w-12 h-12 text-muted-foreground mx-auto mb-3"></i>
              <p class="text-muted-foreground">Подключите платёжную систему для приёма оплат.</p>
              <p class="text-xs text-muted-foreground mt-2">CloudPayments / ЮKassa</p>
            </div>
          </div>
        </section>

        <section id="admin-settings" data-admin-section class="hidden">
          <div class="space-y-6">
            <h1 class="text-2xl font-bold">Настройки</h1>
            <div class="glass border-border/30 border rounded-lg p-4">
              <p class="text-base font-semibold mb-2">Управление</p>
              <p class="text-sm text-muted-foreground">Здесь можно будет включать/отключать разделы, менять цены на токены и настраивать платёжную систему.</p>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script type="module" src="assets/app.js"></script>
  </body>
</html>
