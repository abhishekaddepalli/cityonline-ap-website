import { Link } from "@tanstack/react-router";
import { AnimatePresence, motion } from "framer-motion";
import { ChevronDown, CreditCard, Menu, Moon, Sun, X, Home, Layers, Wrench, Headphones, MapPin } from "lucide-react";
import { useEffect, useRef, useState, type ReactNode } from "react";
import { LINKS, ext } from "@/lib/links";
import { usePrefs } from "@/lib/prefs";
import { useCms } from "@/lib/cms";
import { cn } from "@/lib/utils";
import { Logo } from "./primitives";
import { SUPPORT_EXTERNAL, SUPPORT_INTERNAL, TOOLS } from "./nav-data";

const navLink =
  "relative rounded-lg px-3 py-2 text-sm font-semibold text-foreground/80 transition-colors hover:text-primary data-[status=active]:text-primary";

function LangSwitch({ compact }: { compact?: boolean }) {
  const { lang, setLang } = usePrefs();
  return (
    <div role="group" aria-label="Language" className="inline-flex rounded-full bg-secondary p-1">
      {(["en", "te"] as const).map((l) => (
        <button
          key={l}
          onClick={() => setLang(l)}
          aria-pressed={lang === l}
          className={cn(
            "rounded-full px-2.5 py-1 text-xs font-bold transition-colors",
            lang === l ? "bg-primary text-primary-foreground" : "text-secondary-foreground",
          )}
        >
          {l === "en" ? "EN" : compact ? "తె" : "తెలుగు"}
        </button>
      ))}
    </div>
  );
}

function ThemeToggle() {
  const { theme, toggleTheme } = usePrefs();
  return (
    <button
      onClick={toggleTheme}
      aria-label={theme === "dark" ? "Switch to light theme" : "Switch to dark theme"}
      className="grid h-9 w-9 place-items-center rounded-full bg-secondary text-secondary-foreground transition-transform hover:scale-105"
    >
      {theme === "dark" ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
    </button>
  );
}

function Dropdown({ label, children }: { label: string; children: ReactNode }) {
  const [open, setOpen] = useState(false);
  const ref = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const h = (e: MouseEvent) => ref.current && !ref.current.contains(e.target as Node) && setOpen(false);
    document.addEventListener("mousedown", h);
    return () => document.removeEventListener("mousedown", h);
  }, []);
  return (
    <div ref={ref} className="relative" onMouseEnter={() => setOpen(true)} onMouseLeave={() => setOpen(false)}>
      <button className={cn(navLink, "inline-flex items-center gap-1")} aria-expanded={open} onClick={() => setOpen((o) => !o)}>
        {label} <ChevronDown className={cn("h-4 w-4 transition-transform", open && "rotate-180")} />
      </button>
      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 8 }}
            transition={{ duration: 0.18 }}
            className="absolute left-1/2 top-full z-50 w-[22rem] -translate-x-1/2 pt-2"
            onClick={() => setOpen(false)}
          >
            <div className="rounded-2xl border border-border bg-popover p-2 shadow-lift">{children}</div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}

function MenuItem({ icon: Icon, label, desc }: { icon: React.ComponentType<{ className?: string }>; label: string; desc?: string }) {
  return (
    <span className="flex items-start gap-3 rounded-xl p-3 transition-colors hover:bg-secondary">
      <span className="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-secondary text-accent">
        <Icon className="h-5 w-5" />
      </span>
      <span className="min-w-0">
        <span className="block text-sm font-bold text-foreground">{label}</span>
        {desc && <span className="block text-xs text-muted-foreground">{desc}</span>}
      </span>
    </span>
  );
}

export function Header() {
  const { t } = usePrefs();
  const { settings } = useCms();
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  const template = settings?.header_template || "modern-glass";
  const glow = settings?.enable_header_glow === "1" || settings?.enable_header_glow === "true";

  useEffect(() => {
    const f = () => setScrolled(window.scrollY > 8);
    f();
    window.addEventListener("scroll", f, { passive: true });
    return () => window.removeEventListener("scroll", f);
  }, []);
  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    return () => { document.body.style.overflow = ""; };
  }, [open]);

  const isIsland = template === "floating-island";
  const isSolid = template === "classic-solid";

  return (
    <>
      <header
        className={cn(
          "z-40 transition-all duration-300",
          isIsland
            ? "sticky top-2 sm:top-3 px-3 sm:px-6"
            : isSolid
              ? "sticky top-0 border-b border-border bg-background shadow-xs"
              : cn(
                  "sticky top-0 border-b backdrop-blur-md transition-all",
                  scrolled
                    ? "border-border/80 bg-background/90 shadow-soft"
                    : "border-border/30 bg-background/70",
                ),
          glow && "hover:border-accent/40",
        )}
      >
        <div
          className={cn(
            "grid h-16 grid-cols-[minmax(0,1fr)_auto] items-center gap-3 xl:flex xl:h-20 xl:justify-between transition-all",
            isIsland
              ? "container-x mx-auto rounded-2xl border border-border/70 bg-background/95 px-4 shadow-lift backdrop-blur-lg sm:px-6"
              : "container-x",
          )}
        >
          <Link to="/" className="min-w-0" aria-label="City Online AP home">
            <span className="inline-flex max-w-full rounded-lg dark:bg-logo-surface dark:px-2 dark:py-1">
              <Logo type="header" className="h-auto w-auto max-h-7 max-w-full sm:max-h-9 sm:max-w-none xl:max-h-10" />
            </span>
          </Link>
          <nav aria-label="Main" className="hidden items-center gap-1 xl:flex">
            <Link to="/" className={navLink} activeOptions={{ exact: true }}>{t("home")}</Link>
            <Link to="/plans" className={navLink}>{t("plans")}</Link>
            <Link to="/coverage" className={navLink}>{t("coverage")}</Link>
            <Dropdown label={t("tools")}>
              {TOOLS.map((x) => (
                <Link key={x.to} to={x.to}><MenuItem icon={x.icon} label={x.label} desc={x.desc} /></Link>
              ))}
              <Link to="/tools" className="mt-1 block rounded-xl bg-secondary px-3 py-2 text-center text-sm font-bold text-primary">View all tools</Link>
            </Dropdown>
            <Dropdown label={t("support")}>
              {SUPPORT_INTERNAL.map((x) => (
                <Link key={x.to} to={x.to}><MenuItem icon={x.icon} label={x.label} desc={x.desc} /></Link>
              ))}
              {SUPPORT_EXTERNAL.map((x) => (
                <a key={x.href} href={x.href} {...ext}><MenuItem icon={x.icon} label={x.label} /></a>
              ))}
            </Dropdown>
          </nav>
          <div className="flex shrink-0 items-center gap-2">
            <div className="hidden sm:block"><LangSwitch /></div>
            <div className="sm:hidden"><LangSwitch compact /></div>
            <div className="hidden sm:block"><ThemeToggle /></div>
            <a href={LINKS.payBill} {...ext} className="hidden items-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-bold text-accent-foreground shadow-orange transition hover:bg-accent-hover active:scale-95 md:inline-flex">
              <CreditCard className="h-4 w-4" /> {t("payBill")}
            </a>
            <button onClick={() => setOpen(true)} aria-label="Open menu" aria-expanded={open} className="grid h-10 w-10 place-items-center rounded-xl bg-secondary text-primary xl:hidden">
              <Menu className="h-5 w-5" />
            </button>
          </div>
        </div>
      </header>

      <AnimatePresence>
        {open && (
          <>
            <motion.div className="fixed inset-0 z-50 bg-navy/60 backdrop-blur-sm" initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }} onClick={() => setOpen(false)} />
            <motion.aside
              role="dialog"
              aria-modal="true"
              aria-label={t("menu")}
              className="fixed inset-y-0 right-0 z-50 flex w-[88%] max-w-sm flex-col overflow-y-auto bg-background shadow-lift"
              initial={{ x: "100%" }}
              animate={{ x: 0 }}
              exit={{ x: "100%" }}
              transition={{ type: "spring", damping: 30, stiffness: 300 }}
            >
              <div className="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 border-b border-border p-4">
                <span className="inline-flex max-w-full rounded-lg dark:bg-logo-surface dark:px-2 dark:py-1"><Logo className="max-h-7 max-w-[190px]" /></span>
                <button onClick={() => setOpen(false)} aria-label="Close menu" className="grid h-10 w-10 place-items-center rounded-xl bg-secondary text-primary"><X className="h-5 w-5" /></button>
              </div>
              <nav aria-label="Mobile" className="flex flex-col gap-1 p-4 pb-[calc(1rem+env(safe-area-inset-bottom))]" onClick={(e) => (e.target as HTMLElement).closest("a") && setOpen(false)}>
                {[
                  { to: "/", l: t("home") },
                  { to: "/plans", l: t("plans") },
                  { to: "/coverage", l: t("coverage") },
                  { to: "/tools", l: t("tools") },
                  { to: "/help", l: t("support") },
                  { to: "/about", l: t("about") },
                  { to: "/contact", l: t("contact") },
                ].map((x) => (
                  <Link key={x.to} to={x.to} activeOptions={{ exact: true }} className="rounded-xl px-4 py-3 text-base font-semibold text-foreground hover:bg-secondary data-[status=active]:bg-secondary data-[status=active]:text-primary">
                    {x.l}
                  </Link>
                ))}
                <p className="mt-4 px-4 text-xs font-bold uppercase tracking-widest text-muted-foreground">Tools</p>
                <div className="grid grid-cols-2 gap-2">
                  {TOOLS.map((x) => (
                    <Link key={x.to} to={x.to} className="flex items-center gap-2 rounded-xl border border-border p-3 text-sm font-semibold">
                      <x.icon className="h-4 w-4 shrink-0 text-accent" /> <span className="min-w-0 truncate">{x.label}</span>
                    </Link>
                  ))}
                </div>
                <div className="mt-6 flex items-center justify-between px-1"><span className="text-sm font-semibold text-muted-foreground">Theme</span><ThemeToggle /></div>
                <a href={LINKS.payBill} {...ext} className="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-accent py-3 font-bold text-accent-foreground">
                  <CreditCard className="h-4 w-4" /> {t("payBill")}
                </a>
                <a href={LINKS.newConnection} {...ext} className="mt-2 inline-flex items-center justify-center rounded-xl border-2 border-primary/25 py-3 font-bold text-primary">
                  {t("newConnection")}
                </a>
              </nav>
            </motion.aside>
          </>
        )}
      </AnimatePresence>
    </>
  );
}

export function MobileBottomNav({ onHelp }: { onHelp: () => void }) {
  const { t } = usePrefs();
  const item = "flex min-w-0 flex-1 flex-col items-center gap-0.5 py-2 text-[11px] font-semibold text-muted-foreground data-[status=active]:text-accent";
  return (
    <nav aria-label="Quick" className="fixed inset-x-0 bottom-0 z-40 border-t border-border bg-background/95 pb-[env(safe-area-inset-bottom)] backdrop-blur-lg xl:hidden">
      <div className="mx-auto flex max-w-lg">
        <Link to="/" activeOptions={{ exact: true }} className={item}><Home className="h-5 w-5" />{t("home").split(" ")[0]}</Link>
        <Link to="/plans" className={item}><Layers className="h-5 w-5" />Plans</Link>
        <Link to="/coverage" className={item}><MapPin className="h-5 w-5" />Coverage</Link>
        <Link to="/tools" className={item}><Wrench className="h-5 w-5" />Tools</Link>
        <button type="button" onClick={onHelp} className={item} aria-label="Open help assistant"><Headphones className="h-5 w-5" />Support</button>
        <a href={LINKS.payBill} {...ext} className={cn(item, "text-accent")}><CreditCard className="h-5 w-5" />Pay Bill</a>
      </div>
    </nav>
  );
}
