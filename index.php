<?php
  $year = date('Y');
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NeuroBro — 9 нейросетей без VPN из России</title>
    <meta name="description" content="ChatGPT-5, Gemini, Claude, DALL·E, Midjourney, Stable Diffusion, Sora, Veo 3, Kling — все нейросети без VPN из России." />

    <meta property="og:title" content="NeuroBro — 9 нейросетей без VPN" />
    <meta property="og:description" content="ChatGPT, DALL·E, Midjourney, Sora и другие нейросети без VPN из России" />
    <meta property="og:type" content="website" />

    <meta name="twitter:card" content="summary_large_image" />
    <link rel="icon" href="../dist/favicon.ico" />
    <link rel="stylesheet" href="assets/app.css" />
  </head>

  <body data-page="landing" class="min-h-screen bg-gradient-hero overflow-hidden">
    <div id="toast-viewport" class="fixed top-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]"></div>

    <nav class="fixed top-0 w-full z-50 glass border-b border-border/30">
      <div class="container mx-auto px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center glow-purple">
            <i data-lucide="bot" class="w-5 h-5 text-primary"></i>
          </div>
          <span class="font-bold text-lg tracking-tight">NeuroBro</span>
        </div>
        <div class="flex items-center gap-3">
          <button data-scroll="models" class="text-muted-foreground text-sm px-3 py-1.5 rounded-md hover:text-foreground">Модели</button>
          <button data-scroll="pricing" class="text-muted-foreground text-sm px-3 py-1.5 rounded-md hover:text-foreground">Цены</button>
          <button data-scroll="register" class="text-sm px-3 py-1.5 rounded-md bg-primary text-primary-foreground hover:bg-primary/90">Начать</button>
        </div>
      </div>
    </nav>

    <section class="pt-28 pb-16 px-6">
      <div class="container mx-auto max-w-5xl">
        <div class="text-center">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-primary/30 bg-primary/5 text-sm text-primary mb-6">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
            ChatGPT · DALL·E · Midjourney · Sora · Veo 3 — всё в одном
          </div>
          <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-[1.05] mb-6">
            <span class="text-gradient">9 нейросетей</span>
            <br />без VPN из России
          </h1>
          <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto mb-4">
            ChatGPT-5, Gemini, Claude, DALL·E, Midjourney, Stable Diffusion, Sora, Veo 3, Kling —
            все заблокированные нейросети работают у нас <strong class="text-foreground">без VPN</strong>.
          </p>
          <p class="text-base text-primary font-medium mb-10">
            Регистрация за 5 секунд. Пароль на почту. Всё.
          </p>

          <div class="flex items-center justify-center gap-8 md:gap-12 mb-12">
            <div class="text-center">
              <p class="text-2xl md:text-3xl font-bold font-mono text-gradient">15,000+</p>
              <p class="text-xs text-muted-foreground mt-1">пользователей</p>
            </div>
            <div class="text-center">
              <p class="text-2xl md:text-3xl font-bold font-mono text-gradient">1,200,000+</p>
              <p class="text-xs text-muted-foreground mt-1">запросов обработано</p>
            </div>
            <div class="text-center">
              <p class="text-2xl md:text-3xl font-bold font-mono text-gradient">99.9%</p>
              <p class="text-xs text-muted-foreground mt-1">аптайм</p>
            </div>
          </div>
        </div>

        <div id="register" class="max-w-md mx-auto">
          <form id="auth-form" class="glass rounded-2xl p-6 glow-purple">
            <div class="flex gap-2 mb-4">
              <button type="button" id="mode-register" class="flex-1 py-2 rounded-lg text-sm font-medium transition-colors bg-primary text-primary-foreground">Регистрация</button>
              <button type="button" id="mode-login" class="flex-1 py-2 rounded-lg text-sm font-medium transition-colors text-muted-foreground hover:text-foreground">Вход</button>
            </div>

            <div class="space-y-3">
              <input id="auth-email" type="email" placeholder="your@email.com" class="w-full bg-secondary/50 border border-border/50 focus:border-primary rounded-md px-3 py-2" required />

              <div id="password-wrap" class="relative hidden">
                <input id="auth-password" type="password" placeholder="Пароль" class="w-full bg-secondary/50 border border-border/50 focus:border-primary rounded-md px-3 py-2 pr-10" />
                <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
              </div>

              <button id="auth-submit" type="submit" class="w-full bg-primary hover:bg-primary/90 text-primary-foreground rounded-md px-3 py-2">
                Создать аккаунт
              </button>
            </div>

            <div id="generated-password" class="mt-3 p-3 rounded-lg bg-neon-green/10 border border-neon-green/20 text-sm hidden">
              <p class="font-medium text-neon-green mb-1">🎉 Аккаунт создан! Ваш пароль:</p>
              <div class="flex items-center gap-2 mb-2">
                <code id="generated-password-value" class="font-mono text-foreground text-lg bg-secondary/50 px-3 py-1 rounded flex-1"></code>
                <button type="button" id="copy-generated" class="p-2 rounded-lg bg-secondary/50 hover:bg-secondary text-muted-foreground hover:text-foreground transition-colors">
                  <i data-lucide="copy" class="w-4 h-4"></i>
                </button>
              </div>
              <p class="text-xs text-muted-foreground mb-3">⚠️ Сохраните пароль! Он понадобится для входа.</p>
              <button type="button" id="go-dashboard" class="w-full bg-neon-green/90 hover:bg-neon-green text-background font-semibold rounded-md px-3 py-2">
                Перейти в кабинет <i data-lucide="arrow-right" class="w-4 h-4 ml-2 inline-block"></i>
              </button>
            </div>

            <p id="auth-hint" class="text-xs text-muted-foreground mt-3 text-center">
              Бесплатно. Без карты. Без VPN.
            </p>
          </form>
        </div>
      </div>
    </section>

    <section class="py-16 px-6">
      <div class="container mx-auto max-w-5xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="text-center p-5">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
              <i data-lucide="globe" class="w-6 h-6 text-primary"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1">Без VPN</h3>
            <p class="text-xs text-muted-foreground">Работает из России напрямую, без танцев с бубном</p>
          </div>
          <div class="text-center p-5">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
              <i data-lucide="lock" class="w-6 h-6 text-primary"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1">Анонимно</h3>
            <p class="text-xs text-muted-foreground">Регистрация только по email, никаких паспортов</p>
          </div>
          <div class="text-center p-5">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
              <i data-lucide="zap" class="w-6 h-6 text-primary"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1">Мгновенно</h3>
            <p class="text-xs text-muted-foreground">Регистрация за 5 секунд — пароль на почту</p>
          </div>
          <div class="text-center p-5">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3">
              <i data-lucide="clock" class="w-6 h-6 text-primary"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1">24/7</h3>
            <p class="text-xs text-muted-foreground">Все модели доступны круглосуточно без перебоев</p>
          </div>
        </div>
      </div>
    </section>

    <section id="models" class="py-16 px-6">
      <div class="container mx-auto max-w-5xl">
        <div class="text-center mb-10">
          <h2 class="text-3xl md:text-4xl font-bold mb-3">Все топовые модели <span class="text-gradient">в одном месте</span></h2>
          <p class="text-muted-foreground">Не нужно 10 подписок — всё доступно через NeuroBro</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-purple/10">
                <i data-lucide="message-square" class="w-4 h-4 text-neon-purple"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">ChatGPT-5</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-purple/10 text-neon-purple">Чат</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Самая мощная языковая модель OpenAI</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-purple/10">
                <i data-lucide="message-square" class="w-4 h-4 text-neon-purple"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Gemini Pro</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-purple/10 text-neon-purple">Чат</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Google AI нового поколения</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-purple/10">
                <i data-lucide="message-square" class="w-4 h-4 text-neon-purple"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Claude 4</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-purple/10 text-neon-purple">Чат</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Anthropic — глубокий анализ и код</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-cyan/10">
                <i data-lucide="palette" class="w-4 h-4 text-neon-cyan"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">DALL·E 3</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-cyan/10 text-neon-cyan">Картинки</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Фотореалистичные изображения от OpenAI</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-cyan/10">
                <i data-lucide="palette" class="w-4 h-4 text-neon-cyan"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Midjourney v7</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-cyan/10 text-neon-cyan">Картинки</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Лучший арт и дизайн</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-cyan/10">
                <i data-lucide="palette" class="w-4 h-4 text-neon-cyan"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Stable Diffusion 3</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-cyan/10 text-neon-cyan">Картинки</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Открытая модель, любые стили</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-pink/10">
                <i data-lucide="film" class="w-4 h-4 text-neon-pink"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Veo 3</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-pink/10 text-neon-pink">Видео</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Видеогенерация от Google DeepMind</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-pink/10">
                <i data-lucide="film" class="w-4 h-4 text-neon-pink"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Sora</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-pink/10 text-neon-pink">Видео</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Кинематографичное видео от OpenAI</p>
              </div>
            </div>
          </div>
          <div class="glass rounded-xl p-4 hover:border-primary/30 transition-all group">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-neon-pink/10">
                <i data-lucide="film" class="w-4 h-4 text-neon-pink"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h3 class="font-semibold text-sm">Kling AI</h3>
                  <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neon-pink/10 text-neon-pink">Видео</span>
                </div>
                <p class="text-xs text-muted-foreground mt-0.5">Реалистичное видео из текста</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="pricing" class="py-16 px-6">
      <div class="container mx-auto max-w-4xl">
        <div class="text-center mb-10">
          <h2 class="text-3xl md:text-4xl font-bold mb-3">Простые <span class="text-gradient">подписки</span></h2>
          <p class="text-muted-foreground">Или покупай токены по отдельности</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="glass rounded-xl p-6 border border-border/50 relative">
            <h3 class="text-xl font-bold mb-1">Lite</h3>
            <div class="flex items-baseline gap-1 mb-4">
              <span class="text-3xl font-mono font-bold">299₽</span>
              <span class="text-sm text-muted-foreground">/мес</span>
            </div>
            <ul class="space-y-2 mb-6">
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> Безлимит AI-чата</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> ChatGPT-5 + Gemini + Claude</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> История сообщений</li>
            </ul>
            <button data-scroll="register" class="w-full bg-secondary hover:bg-secondary/80 rounded-md px-3 py-2">Начать</button>
          </div>
          <div class="glass rounded-xl p-6 border border-primary/50 glow-purple relative">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-primary text-primary-foreground text-xs font-semibold flex items-center gap-1">
              <i data-lucide="star" class="w-3 h-3"></i> Популярный
            </div>
            <h3 class="text-xl font-bold mb-1">Pro</h3>
            <div class="flex items-baseline gap-1 mb-4">
              <span class="text-3xl font-mono font-bold">599₽</span>
              <span class="text-sm text-muted-foreground">/мес</span>
            </div>
            <ul class="space-y-2 mb-6">
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> Всё из Lite</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> + 2 картинки/день</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> + 1 видео/месяц</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> Приоритетная очередь</li>
            </ul>
            <button data-scroll="register" class="w-full bg-primary hover:bg-primary/90 text-primary-foreground rounded-md px-3 py-2">Начать</button>
          </div>
          <div class="glass rounded-xl p-6 border border-neon-cyan/50 glow-cyan relative">
            <h3 class="text-xl font-bold mb-1">Ultra</h3>
            <div class="flex items-baseline gap-1 mb-4">
              <span class="text-3xl font-mono font-bold">999₽</span>
              <span class="text-sm text-muted-foreground">/мес</span>
            </div>
            <ul class="space-y-2 mb-6">
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> Всё из Pro</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> + 5 картинок/день</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> + 2 видео/месяц</li>
              <li class="flex items-center gap-2 text-sm"><i data-lucide="check" class="w-4 h-4 text-neon-green shrink-0"></i> Доступ к новым моделям первым</li>
            </ul>
            <button data-scroll="register" class="w-full bg-secondary hover:bg-secondary/80 rounded-md px-3 py-2">Начать</button>
          </div>
        </div>
      </div>
    </section>

    <section class="py-20 px-6">
      <div class="container mx-auto max-w-2xl text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Хватит мучаться с VPN</h2>
        <p class="text-muted-foreground mb-8">Присоединяйся к 15,000+ пользователям</p>
        <button data-scroll="register" class="bg-primary hover:bg-primary/90 px-8 text-base glow-purple rounded-md py-3 text-primary-foreground">
          Попробовать бесплатно <i data-lucide="arrow-right" class="w-5 h-5 ml-2 inline-block"></i>
        </button>
      </div>
    </section>

    <footer class="border-t border-border/30 py-8 px-6">
      <div class="container mx-auto text-center text-sm text-muted-foreground">
        <div class="flex items-center justify-center gap-2 mb-2">
          <i data-lucide="shield" class="w-4 h-4"></i> Безопасно и конфиденциально
        </div>
        © <?php echo $year; ?> NeuroBro. Все права защищены.
      </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
    <script type="module" src="assets/app.js"></script>
  </body>
</html>
