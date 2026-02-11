import { createClient } from "https://cdn.jsdelivr.net/npm/@supabase/supabase-js/+esm";

const SUPABASE_URL = "https://qgnolzkwjanymtyirvbv.supabase.co";
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InFnbm9semt3amFueW10eWlydmJ2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzA3NTAwMTEsImV4cCI6MjA4NjMyNjAxMX0.v4xfcnLmHIxI9NGFbPXyOv2t-aiKMceN-ewD-sd9UeA";

const supabase = createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

const toastViewport = $("#toast-viewport");
const refreshIcons = () => {
  if (window.lucide && typeof window.lucide.createIcons === "function") {
    window.lucide.createIcons();
  }
};

function showToast({ title, description, variant = "default", timeout = 3500 }) {
  if (!toastViewport) return;
  const toast = document.createElement("div");
  const variantClass = variant === "destructive" ? "destructive group border-destructive bg-destructive text-destructive-foreground" : "border bg-background text-foreground";
  toast.className = `group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all ${variantClass}`;

  const content = document.createElement("div");
  content.className = "grid gap-1";
  if (title) {
    const titleEl = document.createElement("div");
    titleEl.className = "text-sm font-semibold";
    titleEl.textContent = title;
    content.appendChild(titleEl);
  }
  if (description) {
    const descEl = document.createElement("div");
    descEl.className = "text-sm opacity-90";
    descEl.textContent = description;
    content.appendChild(descEl);
  }

  const closeBtn = document.createElement("button");
  closeBtn.className = "absolute right-2 top-2 rounded-md p-1 text-foreground/50 opacity-70 hover:opacity-100";
  closeBtn.innerHTML = "×";
  closeBtn.addEventListener("click", () => toast.remove());

  toast.appendChild(content);
  toast.appendChild(closeBtn);
  toastViewport.appendChild(toast);

  if (timeout) {
    setTimeout(() => toast.remove(), timeout);
  }
}

function formatDateRu(dateStr) {
  if (!dateStr) return "—";
  try {
    return new Date(dateStr).toLocaleDateString("ru-RU", { day: "numeric", month: "long", year: "numeric" });
  } catch {
    return "—";
  }
}

function generatePassword(length = 12) {
  const chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$";
  const random = crypto.getRandomValues(new Uint8Array(length));
  return Array.from(random).map((b) => chars[b % chars.length]).join("");
}

async function getSession() {
  const { data: { session } } = await supabase.auth.getSession();
  return session || null;
}

async function fetchProfile(userId) {
  const { data } = await supabase.from("profiles").select("*").eq("id", userId).maybeSingle();
  return data || null;
}

async function checkAdmin(userId) {
  const { data } = await supabase.rpc("has_role", { _user_id: userId, _role: "admin" });
  return data === true;
}

function initScrollButtons() {
  $$('[data-scroll]').forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-scroll");
      const el = document.getElementById(id);
      if (el) el.scrollIntoView({ behavior: "smooth" });
    });
  });
}

async function initLanding() {
  initScrollButtons();

  const modeRegister = $("#mode-register");
  const modeLogin = $("#mode-login");
  const passwordWrap = $("#password-wrap");
  const authHint = $("#auth-hint");
  const submitBtn = $("#auth-submit");
  const emailInput = $("#auth-email");
  const passwordInput = $("#auth-password");
  const form = $("#auth-form");
  const generatedBlock = $("#generated-password");
  const generatedValue = $("#generated-password-value");
  const copyGenerated = $("#copy-generated");
  const goDashboard = $("#go-dashboard");
  const togglePassword = $("#toggle-password");

  let mode = "register";
  let loading = false;

  function updateMode(next) {
    mode = next;
    if (mode === "register") {
      modeRegister.className = "flex-1 py-2 rounded-lg text-sm font-medium transition-colors bg-primary text-primary-foreground";
      modeLogin.className = "flex-1 py-2 rounded-lg text-sm font-medium transition-colors text-muted-foreground hover:text-foreground";
      passwordWrap.classList.add("hidden");
      authHint.textContent = "Бесплатно. Без карты. Без VPN.";
      submitBtn.textContent = "Создать аккаунт";
    } else {
      modeLogin.className = "flex-1 py-2 rounded-lg text-sm font-medium transition-colors bg-primary text-primary-foreground";
      modeRegister.className = "flex-1 py-2 rounded-lg text-sm font-medium transition-colors text-muted-foreground hover:text-foreground";
      passwordWrap.classList.remove("hidden");
      authHint.textContent = "Введите email и пароль";
      submitBtn.textContent = "Войти";
    }
  }

  modeRegister.addEventListener("click", () => updateMode("register"));
  modeLogin.addEventListener("click", () => updateMode("login"));

  togglePassword.addEventListener("click", () => {
    if (!passwordInput) return;
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  });

  copyGenerated?.addEventListener("click", async () => {
    if (!generatedValue?.textContent) return;
    await navigator.clipboard.writeText(generatedValue.textContent);
    showToast({ title: "Скопировано!", description: "Пароль в буфере обмена" });
  });

  goDashboard?.addEventListener("click", () => {
    sessionStorage.removeItem("just_registered");
    window.location.href = "dashboard.php";
  });

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (loading) return;

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    if (!email) return;

    loading = true;
    submitBtn.textContent = "...";

    if (mode === "register") {
      const generated = generatePassword();
      const { error } = await supabase.auth.signUp({
        email,
        password: generated,
        options: { emailRedirectTo: window.location.origin },
      });
      if (error) {
        showToast({ title: "Ошибка", description: error.message, variant: "destructive" });
      } else {
        sessionStorage.setItem("just_registered", "1");
        generatedValue.textContent = generated;
        generatedBlock.classList.remove("hidden");
        showToast({ title: "Аккаунт создан!", description: `Ваш пароль: ${generated}` });
      }
    } else {
      const { error } = await supabase.auth.signInWithPassword({ email, password });
      if (error) {
        showToast({ title: "Ошибка", description: error.message, variant: "destructive" });
      } else {
        window.location.href = "dashboard.php";
      }
    }

    loading = false;
    submitBtn.textContent = mode === "register" ? "Создать аккаунт" : "Войти";
  });

  updateMode("register");
  refreshIcons();

  const session = await getSession();
  const justRegistered = sessionStorage.getItem("just_registered");
  if (session && !justRegistered) {
    window.location.href = "dashboard.php";
  }
}

function setActivePanel(panelId) {
  $$('[data-panel]').forEach((panel) => panel.classList.add("hidden"));
  const active = $("#panel-" + panelId);
  if (active) active.classList.remove("hidden");

  $$('[data-nav]').forEach((btn) => {
    const isActive = btn.getAttribute("data-nav") === panelId;
    btn.className = isActive
      ? "flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-primary/50 bg-primary/10 text-primary glow-purple"
      : "flex items-center justify-center gap-2 px-4 py-3 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/50 text-foreground hover:border-primary/30 hover:bg-secondary";
    const icon = btn.querySelector("i");
    if (icon) {
      if (panelId === "chat" && isActive) icon.className = "w-4 h-4 text-primary";
    }
  });
}

async function initDashboard() {
  const pageLoading = $("#page-loading");
  const pageContent = $("#page-content");
  const session = await getSession();
  if (!session) {
    window.location.href = "index.php";
    return;
  }

  const user = session.user;
  let profile = await fetchProfile(user.id);
  let tokenBalance = profile?.tokens_balance ?? 0;

  const balanceValue = $("#balance-value");
  const tokensBalance = $("#tokens-balance");
  const profileBalance = $("#profile-balance");

  function updateBalances() {
    balanceValue.textContent = tokenBalance.toLocaleString();
    tokensBalance.textContent = tokenBalance.toLocaleString();
    profileBalance.textContent = tokenBalance.toLocaleString();
  }

  async function refreshProfile() {
    profile = await fetchProfile(user.id);
    tokenBalance = profile?.tokens_balance ?? 0;
    updateBalances();
    renderProfile();
  }

  async function spendTokens(cost, description) {
    if (tokenBalance < cost) {
      openTopUp();
      return false;
    }
    const { data, error } = await supabase.rpc("spend_tokens", {
      _user_id: user.id,
      _amount: cost,
      _description: description,
    });
    if (error || data === false) {
      openTopUp();
      return false;
    }
    await refreshProfile();
    return true;
  }

  const isAdmin = await checkAdmin(user.id);
  const adminBtn = $("#admin-btn");
  if (isAdmin) adminBtn.classList.remove("hidden");
  adminBtn.addEventListener("click", () => { window.location.href = "admin.php"; });

  $("#logout-btn").addEventListener("click", async () => {
    await supabase.auth.signOut();
    window.location.href = "index.php";
  });

  updateBalances();
  refreshIcons();

  $$('[data-nav]').forEach((btn) => {
    btn.addEventListener("click", () => setActivePanel(btn.getAttribute("data-nav")));
  });

  $("#balance-btn").addEventListener("click", () => setActivePanel("tokens"));

  const topupModal = $("#topup-modal");
  const openTopUp = () => topupModal.classList.remove("hidden");
  const closeTopUp = () => topupModal.classList.add("hidden");

  $("#topup-close").addEventListener("click", closeTopUp);
  topupModal.addEventListener("click", (event) => {
    if (event.target === topupModal) closeTopUp();
  });
  $$('[data-action="topup"]').forEach((btn) => btn.addEventListener("click", openTopUp));

  $$('[data-topup]').forEach((btn) => {
    btn.addEventListener("click", async () => {
      const tokens = Number(btn.getAttribute("data-topup"));
      await supabase.rpc("add_tokens", {
        _user_id: user.id,
        _amount: tokens,
        _type: "purchase",
        _description: `Покупка ${tokens} токенов`,
      });
      await refreshProfile();
      closeTopUp();
      showToast({ title: "Баланс пополнен!", description: `+${tokens} токенов` });
    });
  });

  function renderProfile() {
    $("#profile-email").textContent = user.email || "—";
    $("#profile-created").textContent = `Аккаунт создан: ${formatDateRu(profile?.created_at)}`;
    $("#profile-refcode").textContent = profile?.referral_code || "—";

    const subscription = profile?.subscription;
    const expiresAt = profile?.subscription_expires_at;

    const noneEl = $("#profile-subscription-none");
    const activeEl = $("#profile-subscription-active");
    const upsell = $("#profile-upsell");
    const expiring = $("#profile-expiring");
    const expiringText = $("#profile-expiring-text");
    const expiresWrap = $("#profile-expires-wrap");
    const expiresEl = $("#profile-expires");

    if (!subscription) {
      noneEl.classList.remove("hidden");
      activeEl.classList.add("hidden");
      upsell.classList.remove("hidden");
      expiring.classList.add("hidden");
      expiresWrap.classList.add("hidden");
    } else {
      noneEl.classList.add("hidden");
      activeEl.classList.remove("hidden");
      upsell.classList.add("hidden");
      $("#profile-subscription-name").textContent = subscription.charAt(0).toUpperCase() + subscription.slice(1);

      if (expiresAt) {
        const expires = new Date(expiresAt);
        const now = new Date();
        const daysLeft = Math.ceil((expires.getTime() - now.getTime()) / (1000 * 60 * 60 * 24));
        $("#profile-subscription-days").textContent = daysLeft > 0 ? `осталось ${daysLeft} дн.` : "истекла";
        if (daysLeft <= 3) {
          expiring.classList.remove("hidden");
          expiringText.textContent = daysLeft === 0 ? "⚠️ Подписка истекла!" : `⚠️ Подписка истекает через ${daysLeft} дн.`;
        } else {
          expiring.classList.add("hidden");
        }
        expiresWrap.classList.remove("hidden");
        expiresEl.textContent = `Подписка до: ${formatDateRu(expiresAt)}`;
      }
    }

    const referralLink = `${window.location.origin}/?ref=${profile?.referral_code || "..."}`;
    const refBtn = $("#ref-link");
    refBtn.textContent = referralLink;
    refBtn.addEventListener("click", async () => {
      await navigator.clipboard.writeText(referralLink);
      showToast({ title: "Ссылка скопирована!" });
    });
  }

  $("#daily-bonus").addEventListener("click", async () => {
    const { data, error } = await supabase.rpc("claim_daily_bonus", { _user_id: user.id });
    if (error) {
      showToast({ title: "Ошибка", description: error.message, variant: "destructive" });
    } else if (data === false) {
      showToast({ title: "Бонус уже получен", description: "Приходите завтра! ⏰" });
    } else {
      showToast({ title: "🎁 Бонус получен!", description: "+10 токенов" });
      await refreshProfile();
    }
  });

  $("#ref-copy").addEventListener("click", async () => {
    const referralLink = $("#ref-link").textContent || "";
    await navigator.clipboard.writeText(referralLink);
    showToast({ title: "Ссылка скопирована!" });
  });

  $("#profile-logout").addEventListener("click", async () => {
    await supabase.auth.signOut();
    window.location.href = "index.php";
  });

  let currentRole = "universal";
  const roles = [
    { id: "programmer", label: "Программист", icon: "👨‍💻", desc: "Помогает с кодом, алгоритмами, дебагом" },
    { id: "copywriter", label: "Копирайтер", icon: "✍️", desc: "Пишет тексты, посты, рекламу" },
    { id: "english_tutor", label: "English Репетитор", icon: "🇬🇧", desc: "Обучает английскому, исправляет ошибки" },
    { id: "tarot", label: "Таролог", icon: "🔮", desc: "Гадания на таро, мистика" },
    { id: "universal", label: "Универсальный ассистент", icon: "🤖", desc: "Помощник на все случаи жизни" },
  ];

  const roleOptions = $("#role-options");
  function renderRoles() {
    roleOptions.innerHTML = "";
    roles.forEach((role) => {
      const btn = document.createElement("button");
      const isActive = role.id === currentRole;
      btn.className = isActive
        ? "w-full flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl border transition-all text-sm font-medium border-primary/50 bg-primary/10 text-primary glow-purple"
        : "w-full flex items-center justify-center gap-3 px-4 py-3.5 rounded-xl border transition-all text-sm font-medium border-border/50 bg-secondary/40 hover:bg-secondary/70 hover:border-primary/30";
      btn.textContent = `${role.icon} ${role.label}`;
      btn.addEventListener("click", () => {
        currentRole = role.id;
        renderRoles();
        renderRoleHeader();
      });
      roleOptions.appendChild(btn);
    });
  }

  function renderRoleHeader() {
    const current = roles.find((r) => r.id === currentRole) || roles[roles.length - 1];
    $("#role-current").textContent = `${current.icon} ${current.label}`;
    $("#role-desc").textContent = current.desc;
  }

  $("#role-open").addEventListener("click", () => setActivePanel("role"));
  $("#role-back").addEventListener("click", () => setActivePanel("profile"));
  $("#role-clear").addEventListener("click", () => {
    currentRole = "";
    renderRoleHeader();
  });

  renderRoles();
  renderRoleHeader();

  const chatMessages = $("#chat-messages");
  function addChatMessage(role, content) {
    const wrap = document.createElement("div");
    wrap.className = `flex ${role === "user" ? "justify-end" : "justify-start"}`;
    const bubble = document.createElement("div");
    bubble.className = role === "user"
      ? "max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed whitespace-pre-wrap bg-primary text-primary-foreground rounded-br-md"
      : "max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed whitespace-pre-wrap glass rounded-bl-md";
    bubble.textContent = content;
    wrap.appendChild(bubble);
    chatMessages.appendChild(wrap);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  addChatMessage("assistant", "Привет! Я NeuroBro 🤖 Задай мне любой вопрос.\n\n⚠️ Сейчас работает демо-режим.");

  const chatInput = $("#chat-input");
  const chatSend = $("#chat-send");
  const chatVoice = $("#chat-voice");
  let chatLoading = false;
  let recording = false;
  let recognition;

  function setChatLoading(state) {
    chatLoading = state;
    if (state) {
      addChatMessage("assistant", "Думаю...");
    }
  }

  async function sendChat() {
    if (chatLoading) return;
    const text = chatInput.value.trim();
    if (!text) return;
    if (!(await spendTokens(1, "chat request"))) return;

    addChatMessage("user", text);
    chatInput.value = "";

    setChatLoading(true);
    setTimeout(() => {
      addChatMessage("assistant", "Это демо-ответ. После подключения AI здесь будут реальные ответы. Токен списан ✅");
      chatLoading = false;
    }, 1000);
  }

  chatSend.addEventListener("click", sendChat);
  chatInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter") sendChat();
  });

  chatVoice.addEventListener("click", () => {
    if (recording) {
      recognition?.stop();
      recording = false;
    chatVoice.className = "shrink-0 transition-all border border-border/50 text-muted-foreground hover:text-foreground rounded-md px-3 py-2";
    chatVoice.innerHTML = '<i data-lucide="mic" class="w-4 h-4"></i>';
    refreshIcons();
    return;
  }
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      showToast({ title: "❌ Браузер не поддерживает голосовой ввод", variant: "destructive" });
      return;
    }
    recognition = new SpeechRecognition();
    recognition.lang = "ru-RU";
    recognition.interimResults = true;
    recognition.continuous = false;
    recognition.onresult = (event) => {
      const transcript = Array.from(event.results).map((r) => r[0].transcript).join("");
      chatInput.value = transcript;
    };
    recognition.onend = () => {
      recording = false;
      chatVoice.className = "shrink-0 transition-all border border-border/50 text-muted-foreground hover:text-foreground rounded-md px-3 py-2";
      chatVoice.innerHTML = '<i data-lucide="mic" class="w-4 h-4"></i>';
      refreshIcons();
    };
    recognition.onerror = (event) => {
      recording = false;
      if (event.error === "not-allowed") {
        showToast({ title: "🎤 Разрешите доступ к микрофону", variant: "destructive" });
      }
    };
    recognition.start();
    recording = true;
    chatVoice.className = "shrink-0 transition-all bg-destructive hover:bg-destructive/90 animate-pulse rounded-md px-3 py-2 text-destructive-foreground";
    chatVoice.innerHTML = '<i data-lucide="mic-off" class="w-4 h-4"></i>';
    refreshIcons();
  });

  const imageAspects = [
    { value: "1:1", label: "1:1", icon: "⬜", desc: "Пост" },
    { value: "9:16", label: "9:16", icon: "📱", desc: "Stories" },
    { value: "16:9", label: "16:9", icon: "🖼", desc: "Обложка" },
    { value: "21:9", label: "21:9", icon: "🎬", desc: "Кино" },
  ];
  const imageQualities = [
    { value: "standard", label: "Стандарт", icon: "⭐", cost: 5 },
    { value: "high", label: "Высокое", icon: "💎", cost: 8 },
    { value: "ultra", label: "Ультра", icon: "👑", cost: 12 },
  ];
  const imageStyles = [
    { value: "photo", label: "Фото", icon: "📷" },
    { value: "art", label: "Арт", icon: "🎨" },
    { value: "painting", label: "Живопись", icon: "🖼" },
    { value: "sketch", label: "Скетч", icon: "✏️" },
    { value: "cinema", label: "Кино", icon: "🎬" },
    { value: "anime", label: "Аниме", icon: "🌸" },
  ];

  let imageAspect = "1:1";
  let imageQuality = "standard";
  let imageStyle = "photo";

  function renderImageSelectors() {
    const aspectWrap = $("#image-aspect-options");
    const qualityWrap = $("#image-quality-options");
    const styleWrap = $("#image-style-options");

    aspectWrap.innerHTML = "";
    imageAspects.forEach((a) => {
      const btn = document.createElement("button");
      const active = a.value === imageAspect;
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${a.icon} ${a.label}`;
      btn.addEventListener("click", () => { imageAspect = a.value; renderImageSelectors(); renderImageSummary(); });
      aspectWrap.appendChild(btn);
    });

    qualityWrap.innerHTML = "";
    imageQualities.forEach((q) => {
      const active = q.value === imageQuality;
      const btn = document.createElement("button");
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${q.icon} ${q.label}`;
      btn.addEventListener("click", () => { imageQuality = q.value; renderImageSelectors(); renderImageSummary(); });
      qualityWrap.appendChild(btn);
    });

    styleWrap.innerHTML = "";
    imageStyles.forEach((s) => {
      const active = s.value === imageStyle;
      const btn = document.createElement("button");
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${s.icon} ${s.label}`;
      btn.addEventListener("click", () => { imageStyle = s.value; renderImageSelectors(); renderImageSummary(); });
      styleWrap.appendChild(btn);
    });
  }

  function renderImageSummary() {
    const aspect = imageAspects.find((a) => a.value === imageAspect);
    const quality = imageQualities.find((q) => q.value === imageQuality);
    const style = imageStyles.find((s) => s.value === imageStyle);
    $("#image-aspect-value").textContent = aspect.value;
    $("#image-aspect-desc").textContent = aspect.desc;
    $("#image-quality-value").textContent = `${quality.icon} ${quality.label}`;
    $("#image-style-value").textContent = `${style.icon} ${style.label}`;
    $("#image-cost").textContent = `${quality.cost} токенов`;
  }

  $("#image-tips").addEventListener("click", () => {
    $("#image-tips-more").classList.toggle("hidden");
  });

  $("#image-generate").addEventListener("click", async () => {
    const prompt = $("#image-prompt").value.trim();
    if (!prompt) return;
    const quality = imageQualities.find((q) => q.value === imageQuality);
    if (!(await spendTokens(quality.cost, "image request"))) return;

    $("#image-loading").classList.remove("hidden");
    $("#image-result").classList.add("hidden");

    setTimeout(() => {
      const url = `https://placehold.co/512x512/1a1a2e/7c3aed?text=${encodeURIComponent(prompt.slice(0, 20))}`;
      $("#image-output").src = url;
      $("#image-loading").classList.add("hidden");
      $("#image-result").classList.remove("hidden");
    }, 1500);
  });

  const videoDurations = [
    { value: "5", label: "5 сек" },
    { value: "10", label: "10 сек" },
  ];
  const videoQualities = [
    { value: "720", label: "720p" },
    { value: "1080", label: "1080p" },
  ];
  const videoAspects = [
    { value: "16:9", label: "16:9" },
    { value: "9:16", label: "9:16" },
    { value: "1:1", label: "1:1" },
  ];

  let videoDuration = "5";
  let videoQuality = "720";
  let videoAspect = "16:9";

  function renderVideoSelectors() {
    const durationWrap = $("#video-duration-options");
    const qualityWrap = $("#video-quality-options");
    const aspectWrap = $("#video-aspect-options");

    durationWrap.innerHTML = "";
    videoDurations.forEach((d) => {
      const active = d.value === videoDuration;
      const btn = document.createElement("button");
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${d.label}`;
      btn.addEventListener("click", () => { videoDuration = d.value; renderVideoSelectors(); renderVideoSummary(); });
      durationWrap.appendChild(btn);
    });

    qualityWrap.innerHTML = "";
    videoQualities.forEach((q) => {
      const active = q.value === videoQuality;
      const btn = document.createElement("button");
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${q.label}`;
      btn.addEventListener("click", () => { videoQuality = q.value; renderVideoSelectors(); renderVideoSummary(); });
      qualityWrap.appendChild(btn);
    });

    aspectWrap.innerHTML = "";
    videoAspects.forEach((a) => {
      const active = a.value === videoAspect;
      const btn = document.createElement("button");
      btn.className = active
        ? "py-3 rounded-xl border text-sm font-medium transition-all border-primary/50 bg-primary/10 text-primary"
        : "py-3 rounded-xl border text-sm font-medium transition-all border-border/50 bg-secondary/40 text-foreground hover:border-primary/30";
      btn.textContent = `${active ? "✓ " : ""}${a.label}`;
      btn.addEventListener("click", () => { videoAspect = a.value; renderVideoSelectors(); renderVideoSummary(); });
      aspectWrap.appendChild(btn);
    });
  }

  function renderVideoSummary() {
    $("#video-duration").textContent = `${videoDuration} сек`;
    $("#video-quality").textContent = `HD ${videoQuality}p`;
    $("#video-aspect").textContent = videoAspect;
    const cost = videoQuality === "1080" ? 30 : 20;
    $("#video-cost").textContent = `${cost} токенов`;
  }

  $("#video-tips").addEventListener("click", () => {
    $("#video-tips-more").classList.toggle("hidden");
  });

  $("#video-generate").addEventListener("click", async () => {
    const prompt = $("#video-prompt").value.trim();
    if (!prompt) return;
    const cost = videoQuality === "1080" ? 30 : 20;
    if (!(await spendTokens(cost, "video request"))) return;
    $("#video-loading").classList.remove("hidden");
    setTimeout(() => {
      $("#video-loading").classList.add("hidden");
      showToast({ title: "Видео в обработке", description: "Демо-режим: результат появится позже." });
    }, 2000);
  });

  renderImageSelectors();
  renderImageSummary();
  renderVideoSelectors();
  renderVideoSummary();
  renderProfile();
  setActivePanel("chat");
  refreshIcons();

  pageLoading.classList.add("hidden");
  pageContent.classList.remove("hidden");
}

async function initAdmin() {
  const pageLoading = $("#page-loading");
  const pageContent = $("#page-content");

  const session = await getSession();
  if (!session) {
    window.location.href = "index.php";
    return;
  }

  const user = session.user;
  const isAdmin = await checkAdmin(user.id);
  if (!isAdmin) {
    window.location.href = "dashboard.php";
    return;
  }

  let users = [];

  async function fetchUsers() {
    const { data, error } = await supabase.from("profiles").select("*").order("tokens_balance", { ascending: false });
    if (error) {
      showToast({ title: "Ошибка загрузки", description: error.message, variant: "destructive" });
      return;
    }
    users = data || [];
    renderStats();
    renderUsers();
  }

  function renderStats() {
    $("#stat-total").textContent = users.length.toLocaleString();
    $("#stat-with-sub").textContent = users.filter((u) => u.subscription).length.toLocaleString();
    const totalTokens = users.reduce((sum, u) => sum + (u.tokens_balance || 0), 0);
    $("#stat-total-tokens").textContent = totalTokens.toLocaleString();

    const list = $("#overview-users");
    list.innerHTML = "";
    users.slice(0, 5).forEach((u) => {
      const row = document.createElement("div");
      row.className = "flex items-center justify-between text-sm py-2 border-b border-border/20 last:border-0";
      row.innerHTML = `
        <span class="truncate max-w-[200px]">${u.email || "—"}</span>
        <div class="flex items-center gap-3">
          <span class="font-mono text-muted-foreground">${(u.tokens_balance || 0).toLocaleString()}</span>
          ${u.subscription ? `<span class="px-2 py-0.5 rounded-full text-xs bg-neon-green/10 text-neon-green">${u.subscription}</span>` : ""}
        </div>
      `;
      list.appendChild(row);
    });
  }

  function renderUsers() {
    const list = $("#users-list");
    list.innerHTML = "";
    $("#users-count").textContent = users.length.toLocaleString();
    const query = $("#users-search").value.trim().toLowerCase();
    const filtered = users.filter((u) =>
      (u.email || "").toLowerCase().includes(query) ||
      (u.referral_code || "").toLowerCase().includes(query)
    );

    filtered.forEach((u) => {
      const card = document.createElement("div");
      card.className = "glass rounded-xl p-4 space-y-3";
      card.innerHTML = `
        <div class="flex items-center justify-between">
          <div>
            <p class="font-medium text-sm truncate max-w-[250px]">${u.email || "—"}</p>
            <p class="text-xs text-muted-foreground">Реф: ${u.referral_code || "—"}</p>
          </div>
          <div class="text-right">
            <p class="font-mono text-sm">${(u.tokens_balance || 0).toLocaleString()} 💎</p>
            <p class="text-xs text-muted-foreground">${u.subscription ? `📋 ${u.subscription}` : "Нет подписки"}</p>
          </div>
        </div>
        <div class="flex gap-2 flex-wrap">
          <button data-add="100" class="text-xs h-8 border border-border/50 rounded-md px-3 py-1">+100</button>
          <button data-add="1000" class="text-xs h-8 border border-border/50 rounded-md px-3 py-1">+1000</button>
          ${u.subscription
            ? `<button data-sub="" class="text-xs h-8 border border-destructive/30 text-destructive rounded-md px-3 py-1">Снять подписку</button>`
            : `<button data-sub="lite" class="text-xs h-8 border border-border/50 rounded-md px-3 py-1">⭐ Lite</button>
               <button data-sub="pro" class="text-xs h-8 border border-border/50 rounded-md px-3 py-1">👑 Pro</button>
               <button data-sub="ultra" class="text-xs h-8 border border-border/50 rounded-md px-3 py-1">💎 Ultra</button>`
          }
        </div>
      `;

      card.querySelectorAll("[data-add]").forEach((btn) => {
        btn.addEventListener("click", async () => {
          const amount = Number(btn.getAttribute("data-add"));
          const { error } = await supabase.rpc("add_tokens", {
            _user_id: u.id,
            _amount: amount,
            _type: "admin_grant",
            _description: `Выдано админом: +${amount}`,
          });
          if (error) {
            showToast({ title: "Ошибка", description: error.message, variant: "destructive" });
          } else {
            showToast({ title: `+${amount} токенов выдано` });
            await fetchUsers();
          }
        });
      });

      card.querySelectorAll("[data-sub]").forEach((btn) => {
        btn.addEventListener("click", async () => {
          const sub = btn.getAttribute("data-sub") || null;
          const { error } = await supabase.from("profiles").update({ subscription: sub }).eq("id", u.id);
          if (error) {
            showToast({ title: "Ошибка", description: error.message, variant: "destructive" });
          } else {
            showToast({ title: sub ? `Подписка ${sub} установлена` : "Подписка снята" });
            await fetchUsers();
          }
        });
      });

      list.appendChild(card);
    });
  }

  function setAdminTab(tab) {
    $$('[data-admin-section]').forEach((section) => section.classList.add("hidden"));
    $("#admin-" + tab).classList.remove("hidden");

    $$('[data-admin-tab]').forEach((btn) => {
      const active = btn.getAttribute("data-admin-tab") === tab;
      if (btn.closest("nav")) {
        btn.className = active
          ? "w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors bg-primary/10 text-primary border border-primary/20"
          : "w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors text-muted-foreground hover:text-foreground hover:bg-secondary/50";
      } else {
        btn.className = active
          ? "flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-primary bg-primary/10"
          : "flex flex-col items-center gap-1 py-2 rounded-lg text-xs text-muted-foreground";
      }
    });
  }

  $$('[data-admin-tab]').forEach((btn) => {
    btn.addEventListener("click", () => setAdminTab(btn.getAttribute("data-admin-tab")));
  });

  $("#admin-back").addEventListener("click", () => { window.location.href = "dashboard.php"; });
  $("#admin-back-mobile").addEventListener("click", () => { window.location.href = "dashboard.php"; });
  $("#admin-refresh").addEventListener("click", fetchUsers);
  $("#users-refresh").addEventListener("click", fetchUsers);
  $("#users-search").addEventListener("input", renderUsers);

  await fetchUsers();
  setAdminTab("overview");
  refreshIcons();

  pageLoading.classList.add("hidden");
  pageContent.classList.remove("hidden");
}

const page = document.body?.dataset?.page;
if (page === "landing") initLanding();
if (page === "dashboard") initDashboard();
if (page === "admin") initAdmin();
