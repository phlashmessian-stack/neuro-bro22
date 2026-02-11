<!doctype html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NeuroBro — Кабинет</title>
    <link rel="icon" href="../dist/favicon.ico" />
    <link rel="stylesheet" href="assets/app.css" />
  </head>
  <body data-page="dashboard" class="min-h-screen bg-background flex flex-col">
    <div id="toast-viewport" class="fixed top-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]"></div>

    <div id="page-loading" class="min-h-screen bg-background flex items-center justify-center">
      <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div id="page-content" class="min-h-screen bg-background flex flex-col hidden">
      <header class="glass border-b border-border/30 px-4 h-14 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 rounded-lg bg-primary/20 flex items-center justify-center">
            <i data-lucide="bot" class="w-4 h-4 text-primary"></i>
          </div>
          <span class="font-bold text-sm">NeuroBro</span>
        </div>
        <div class="flex items-center gap-3">
          <button id="balance-btn" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-secondary/80 border border-border/50 text-sm hover:border-primary/30 transition-colors">
            <i data-lucide="coins" class="w-4 h-4 text-neon-green"></i>
            <span id="balance-value" class="font-mono font-medium">0</span>
          </button>
          <button id="admin-btn" class="hidden h-8 w-8 text-muted-foreground rounded-md hover:bg-secondary/60 flex items-center justify-center" title="Админ">
            <i data-lucide="settings" class="w-4 h-4"></i>
          </button>
          <button id="logout-btn" class="h-8 w-8 text-muted-foreground rounded-md hover:bg-secondary/60 flex items-center justify-center" title="Выйти">
            <i data-lucide="log-out" class="w-4 h-4"></i>
          </button>
        </div>
      </header>

      <div class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto scrollbar-thin p-4">
          <div id="panel-chat" data-panel class="h-full">
            <div class="flex flex-col h-full max-w-3xl mx-auto">
              <div id="chat-messages" class="flex-1 overflow-y-auto scrollbar-thin space-y-3 pb-4"></div>

              <div class="flex gap-2 pt-2 border-t border-border/30">
                <button id="chat-voice" class="shrink-0 transition-all border border-border/50 text-muted-foreground hover:text-foreground rounded-md px-3 py-2">
                  <i data-lucide="mic" class="w-4 h-4"></i>
                </button>
                <input id="chat-input" placeholder="Напишите сообщение..." class="w-full bg-secondary/50 border border-border/50 rounded-md px-3 py-2" />
                <button id="chat-send" class="bg-primary hover:bg-primary/90 shrink-0 rounded-md px-3 py-2 text-primary-foreground">
                  <i data-lucide="send" class="w-4 h-4"></i>
                </button>
              </div>
            </div>
          </div>

          <div id="panel-image" data-panel class="hidden">
            <div class="max-w-3xl mx-auto space-y-4">
              <div class="glass rounded-xl p-5 space-y-3">
                <h2 class="text-lg font-bold flex items-center gap-2">🎨 Генерация изображений</h2>
                <div class="space-y-2 text-sm">
                  <p>📐 Формат: <strong id="image-aspect-value">1:1</strong> (<span id="image-aspect-desc">Пост</span>)</p>
                  <p>💎 Качество: <strong id="image-quality-value">⭐ Стандарт</strong></p>
                  <p>🎭 Стиль: <strong id="image-style-value">📷 Фото</strong></p>
                  <p class="text-neon-green">💰 Стоимость: <strong id="image-cost">5 токенов</strong></p>
                </div>
              </div>

              <button id="image-tips" class="w-full glass rounded-xl p-4 text-left transition-all hover:border-primary/30">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-medium">💡 Как писать промпты:</span>
                  <span class="text-neon-pink text-lg">"</span>
                </div>
                <p class="text-sm text-muted-foreground mt-1">• Пиши коротко и чётко: "кот в космосе"</p>
                <div id="image-tips-more" class="space-y-1 mt-1 hidden">
                  <p class="text-sm text-muted-foreground">• Укажи стиль: "в стиле Ван Гога"</p>
                  <p class="text-sm text-muted-foreground">• Добавь детали: "закатное освещение, боке"</p>
                  <p class="text-sm text-muted-foreground">• Избегай абстрактных описаний</p>
                </div>
              </button>

              <div class="flex gap-2">
                <input id="image-prompt" placeholder="↓ Напиши описание картинки ↓" class="w-full bg-secondary/50 border border-border/50 rounded-md px-3 py-2" />
                <button id="image-generate" class="bg-primary hover:bg-primary/90 shrink-0 rounded-md px-3 py-2 text-primary-foreground">
                  <i data-lucide="wand-2" class="w-4 h-4 mr-2"></i>➤
                </button>
              </div>

              <div class="grid grid-cols-4 gap-2" id="image-aspect-options"></div>
              <div class="grid grid-cols-3 gap-2" id="image-quality-options"></div>
              <div class="grid grid-cols-3 gap-2" id="image-style-options"></div>

              <div id="image-loading" class="glass rounded-xl p-8 text-center hidden">
                <div class="w-12 h-12 rounded-full border-2 border-primary border-t-transparent animate-spin mx-auto mb-4"></div>
                <p class="text-sm text-muted-foreground">Генерация изображения...</p>
              </div>

              <div id="image-result" class="glass rounded-xl p-4 space-y-3 hidden">
                <img id="image-output" alt="Generated" class="rounded-lg w-full max-w-md mx-auto" />
                <div class="flex justify-center">
                  <button class="border border-border/50 rounded-md px-3 py-2 text-sm">Скачать</button>
                </div>
              </div>
            </div>
          </div>

          <div id="panel-video" data-panel class="hidden">
            <div class="max-w-3xl mx-auto space-y-4">
              <div class="glass rounded-xl p-5 space-y-3">
                <h2 class="text-lg font-bold flex items-center gap-2">🎬 Генерация видео</h2>
                <div class="space-y-2 text-sm">
                  <div class="flex items-center gap-2"><i data-lucide="clock" class="w-4 h-4 text-muted-foreground"></i><span>Длительность: <strong id="video-duration">5 сек</strong></span></div>
                  <div class="flex items-center gap-2"><i data-lucide="monitor" class="w-4 h-4 text-muted-foreground"></i><span>Качество: <strong id="video-quality">HD 720p</strong></span></div>
                  <div class="flex items-center gap-2"><i data-lucide="ratio" class="w-4 h-4 text-muted-foreground"></i><span>Формат: <strong id="video-aspect">16:9</strong></span></div>
                  <div class="flex items-center gap-2 text-neon-green"><span>💰 Стоимость: <strong id="video-cost">20 токенов</strong></span></div>
                </div>
              </div>

              <button id="video-tips" class="w-full glass rounded-xl p-4 text-left transition-all hover:border-primary/30">
                <div class="flex items-center justify-between">
                  <span class="text-sm font-medium">💡 Советы по промптам:</span>
                  <span class="text-neon-pink text-lg">"</span>
                </div>
                <p class="text-sm text-muted-foreground mt-1">• Опиши сцену детально: "кот играет с мячом на траве"</p>
                <div id="video-tips-more" class="space-y-1 mt-1 hidden">
                  <p class="text-sm text-muted-foreground">• Укажи стиль: "кинематографично, в стиле Pixar"</p>
                  <p class="text-sm text-muted-foreground">• Добавь движение: "камера медленно облетает вокруг"</p>
                  <p class="text-sm text-muted-foreground">• Укажи освещение: "закатное, мягкое, золотистое"</p>
                </div>
              </button>

              <div class="flex gap-2">
                <input id="video-prompt" placeholder="↓ Напиши описание видео ↓" class="w-full bg-secondary/50 border border-border/50 rounded-md px-3 py-2" />
                <button id="video-generate" class="bg-primary hover:bg-primary/90 shrink-0 rounded-md px-3 py-2 text-primary-foreground">
                  <i data-lucide="film" class="w-4 h-4 mr-2"></i>➤
                </button>
              </div>

              <div class="grid grid-cols-2 gap-2" id="video-duration-options"></div>
              <div class="grid grid-cols-2 gap-2" id="video-quality-options"></div>
              <div class="grid grid-cols-3 gap-2" id="video-aspect-options"></div>

              <div id="video-loading" class="glass rounded-xl p-8 text-center hidden">
                <div class="w-12 h-12 rounded-full border-2 border-primary border-t-transparent animate-spin mx-auto mb-4"></div>
                <p class="text-sm text-muted-foreground">Генерация видео может занять 1-3 минуты...</p>
              </div>
            </div>
          </div>

          <div id="panel-tokens" data-panel class="hidden">
            <div class="max-w-3xl mx-auto space-y-4">
              <div class="glass rounded-xl p-5">
                <h2 class="text-lg font-bold mb-3">💎 Магазин</h2>
                <p class="text-sm">Твой баланс: <strong id="tokens-balance" class="text-lg font-mono">0</strong> токенов</p>
              </div>

              <div class="glass rounded-xl p-5 space-y-3">
                <p class="text-sm font-semibold">Подписки:</p>
                <div class="space-y-1 text-sm">
                  <p>• <strong>Lite</strong> — 299₽ / 375⭐</p>
                  <p>• <strong>Pro</strong> — 599₽ / 750⭐</p>
                  <p>• <strong>Ultra</strong> — 999₽ / 1250⭐</p>
                </div>
              </div>

              <div class="glass rounded-xl p-5 space-y-3">
                <p class="text-sm font-semibold">Пакеты токенов:</p>
                <div class="space-y-1 text-sm">
                  <p>• <strong>5K</strong> — 99₽ / 125⭐</p>
                  <p>• <strong>20K</strong> — 299₽ / 375⭐</p>
                  <p>• <strong>50K</strong> — 699₽ / 875⭐</p>
                </div>
              </div>

              <button data-action="topup" class="w-full py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">💰 Купить токены</button>

              <div class="grid grid-cols-2 gap-2">
                <button data-action="topup" class="py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">⭐ Lite 299₽</button>
                <button data-action="topup" class="py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">👑 Pro 599₽</button>
              </div>
              <button data-action="topup" class="w-full py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">💎 Ultra 999₽</button>

              <div class="glass rounded-xl p-4">
                <p class="text-sm font-medium mb-2">Расход токенов:</p>
                <div class="space-y-1 text-sm text-muted-foreground">
                  <p>💬 Чат с AI — 1 токен / сообщение</p>
                  <p>🎨 Изображение — от 5 токенов</p>
                  <p>🎬 Видео — от 20 токенов</p>
                </div>
              </div>
            </div>
          </div>

          <div id="panel-profile" data-panel class="hidden">
            <div class="max-w-3xl mx-auto space-y-4">
              <div class="glass rounded-xl p-6 space-y-4">
                <div class="flex items-center gap-4">
                  <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center">
                    <i data-lucide="user" class="w-7 h-7 text-primary"></i>
                  </div>
                  <div class="min-w-0">
                    <p class="font-semibold text-lg">Личный кабинет</p>
                    <p id="profile-email" class="text-sm text-muted-foreground truncate">—</p>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div class="rounded-xl bg-secondary/50 border border-border/30 p-3.5">
                    <div class="flex items-center gap-2 mb-1">
                      <i data-lucide="diamond" class="w-4 h-4 text-neon-cyan"></i>
                      <span class="text-xs text-muted-foreground">Баланс</span>
                    </div>
                    <p id="profile-balance" class="font-mono font-bold text-xl">0</p>
                    <p class="text-xs text-muted-foreground">токенов</p>
                  </div>

                  <div class="rounded-xl bg-secondary/50 border border-border/30 p-3.5">
                    <div class="flex items-center gap-2 mb-1">
                      <i data-lucide="star" class="w-4 h-4 text-neon-green"></i>
                      <span class="text-xs text-muted-foreground">Подписка</span>
                    </div>
                    <div id="profile-subscription-none">
                      <p class="font-bold text-lg text-muted-foreground">Нет</p>
                      <p class="text-xs text-muted-foreground">не активна</p>
                    </div>
                    <div id="profile-subscription-active" class="hidden">
                      <p id="profile-subscription-name" class="font-bold text-xl text-neon-green">—</p>
                      <p id="profile-subscription-days" class="text-xs text-muted-foreground">—</p>
                    </div>
                  </div>
                </div>

                <div class="space-y-2 text-sm border-t border-border/30 pt-3">
                  <div class="flex items-center gap-2 text-muted-foreground"><i data-lucide="calendar" class="w-3.5 h-3.5"></i><span id="profile-created">Аккаунт создан: —</span></div>
                  <div id="profile-expires-wrap" class="flex items-center gap-2 text-muted-foreground hidden"><i data-lucide="clock" class="w-3.5 h-3.5"></i><span id="profile-expires">Подписка до: —</span></div>
                  <div class="flex items-center gap-2 text-muted-foreground"><i data-lucide="shield" class="w-3.5 h-3.5"></i><span>Реферальный код: <code id="profile-refcode" class="font-mono text-foreground">—</code></span></div>
                </div>
              </div>

              <div id="profile-upsell" class="glass rounded-xl p-5 border-l-4 border-neon-green/50 hidden">
                <p class="text-sm font-semibold mb-2">✨ Подключи подписку и получи больше возможностей:</p>
                <div class="space-y-1.5 text-sm text-muted-foreground">
                  <p>• <strong class="text-foreground">Lite</strong> — 299₽/мес — безлимит AI-чата</p>
                  <p>• <strong class="text-foreground">Pro</strong> — 599₽/мес — + 2 картинки/день + 1 видео/мес</p>
                  <p>• <strong class="text-foreground">Ultra</strong> — 999₽/мес — + 5 картинок/день + 2 видео/мес</p>
                </div>
              </div>

              <div id="profile-expiring" class="glass rounded-xl p-4 border-l-4 border-destructive/50 hidden">
                <p id="profile-expiring-text" class="text-sm font-semibold text-destructive">⚠️ Подписка истекает</p>
                <p class="text-xs text-muted-foreground mt-1">Продли подписку в Магазине, чтобы не потерять доступ.</p>
              </div>

              <div class="space-y-2">
                <button id="role-open" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">
                  <i data-lucide="crown" class="w-4 h-4 text-muted-foreground"></i>
                  Выбрать роль AI
                </button>
                <button id="daily-bonus" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">
                  <i data-lucide="gift" class="w-4 h-4 text-muted-foreground"></i>
                  Ежедневный бонус (+10 токенов)
                </button>
                <button id="ref-copy" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl border border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30 transition-all text-sm font-medium">
                  <i data-lucide="link-2" class="w-4 h-4 text-muted-foreground"></i>
                  Скопировать реферальную ссылку
                </button>
              </div>

              <div class="glass rounded-xl p-4">
                <p class="text-sm mb-2">🔗 <strong>Реферальная программа</strong></p>
                <button id="ref-link" class="text-sm text-primary hover:underline break-all text-left flex items-center gap-2"></button>
                <p class="text-xs text-muted-foreground mt-2">Приглашай друзей — получай <strong class="text-neon-green">+3,000 токенов</strong> за каждого!</p>
              </div>

              <button id="profile-logout" class="w-full border border-destructive/30 text-destructive hover:bg-destructive/10 rounded-md px-3 py-2">Выйти из аккаунта</button>
            </div>
          </div>

          <div id="panel-role" data-panel class="hidden">
            <div class="max-w-3xl mx-auto space-y-4">
              <div class="glass rounded-xl p-5 space-y-3">
                <h2 class="text-lg font-bold">🤖 Выбор роли</h2>
                <p class="text-sm">Текущая роль: <strong id="role-current">🤖 Универсальный ассистент</strong></p>
                <p class="text-sm text-muted-foreground">Роль задаёт AI специализацию и стиль ответов. Выбери подходящую:</p>
                <div class="glass rounded-lg p-3 border-l-4 border-neon-pink/50">
                  <p id="role-desc" class="text-sm italic text-muted-foreground">Помощник на все случаи жизни</p>
                </div>
              </div>

              <div class="space-y-2" id="role-options"></div>

              <div class="grid grid-cols-2 gap-2">
                <button id="role-clear" class="flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl border border-destructive/30 text-destructive text-sm font-medium hover:bg-destructive/10 transition-all">❌ Без роли</button>
                <button id="role-back" class="flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl border border-border/50 bg-secondary/40 text-sm font-medium hover:bg-secondary/70 transition-all">
                  <i data-lucide="arrow-left" class="w-4 h-4"></i> Назад
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="shrink-0 border-t border-border/30 bg-card/80 backdrop-blur-sm p-4">
          <div class="grid grid-cols-2 md:grid-cols-5 gap-2 max-w-3xl mx-auto">
            <button data-nav="chat" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-primary/50 bg-primary/10 text-primary glow-purple">
              <i data-lucide="bot" class="w-4 h-4 text-primary"></i>
              <span class="hidden md:inline">Чат с AI</span>
            </button>
            <button data-nav="image" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/50 text-foreground hover:border-primary/30 hover:bg-secondary">
              <i data-lucide="image" class="w-4 h-4 text-neon-cyan"></i>
              <span class="hidden md:inline">Изображения</span>
            </button>
            <button data-nav="video" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/50 text-foreground hover:border-primary/30 hover:bg-secondary">
              <i data-lucide="video" class="w-4 h-4 text-neon-pink"></i>
              <span class="hidden md:inline">Видео</span>
            </button>
            <button data-nav="tokens" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/50 text-foreground hover:border-primary/30 hover:bg-secondary">
              <i data-lucide="diamond" class="w-4 h-4 text-neon-green"></i>
              <span class="hidden md:inline">Магазин</span>
            </button>
            <button data-nav="profile" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/50 text-foreground hover:border-primary/30 hover:bg-secondary">
              <i data-lucide="user" class="w-4 h-4 text-muted-foreground"></i>
              <span class="hidden md:inline">Профиль</span>
            </button>
          </div>
        </div>
      </div>

      <div id="topup-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black/80"></div>
        <div class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-sm translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg sm:rounded-lg glass border-border/50">
          <button id="topup-close" class="absolute right-4 top-4 rounded-sm opacity-70 hover:opacity-100">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
          <div class="flex items-center gap-2">
            <i data-lucide="diamond" class="w-5 h-5 text-primary"></i>
            <h3 class="text-lg font-semibold leading-none tracking-tight">Пополните баланс</h3>
          </div>
          <p class="text-sm text-muted-foreground">Для отправки запросов нужны токены. Выберите пакет:</p>
          <div class="space-y-2">
            <button data-topup="100" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-border/50 bg-secondary/30 hover:border-primary/40 hover:bg-secondary/60 transition-all">
              <div class="flex items-center gap-3"><span class="font-mono font-bold text-lg">100</span><span class="text-sm text-muted-foreground">токенов</span></div>
              <div class="flex items-center gap-2"><span class="font-semibold">99 ₽</span><i data-lucide="credit-card" class="w-4 h-4 text-muted-foreground"></i></div>
            </button>
            <button data-topup="500" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-border/50 bg-secondary/30 hover:border-primary/40 hover:bg-secondary/60 transition-all">
              <div class="flex items-center gap-3"><span class="font-mono font-bold text-lg">500</span><span class="text-sm text-muted-foreground">токенов</span></div>
              <div class="flex items-center gap-2"><span class="font-semibold">399 ₽</span><i data-lucide="credit-card" class="w-4 h-4 text-muted-foreground"></i></div>
            </button>
            <button data-topup="2000" class="w-full flex items-center justify-between px-4 py-3 rounded-xl border border-border/50 bg-secondary/30 hover:border-primary/40 hover:bg-secondary/60 transition-all">
              <div class="flex items-center gap-3"><span class="font-mono font-bold text-lg">2000</span><span class="text-sm text-muted-foreground">токенов</span></div>
              <div class="flex items-center gap-2"><span class="font-semibold">1299 ₽</span><i data-lucide="credit-card" class="w-4 h-4 text-muted-foreground"></i></div>
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script type="module" src="assets/app.js"></script>
  </body>
</html>
