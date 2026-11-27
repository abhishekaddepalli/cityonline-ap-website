import { AnimatePresence, motion } from "framer-motion";
import { Globe2, MessageCircle, Phone, Send, X, Bot } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { LINKS, ext } from "@/lib/links";
import { usePrefs, type Lang } from "@/lib/prefs";
import { cn } from "@/lib/utils";
import { CONTACT_ANSWER, FAQS, matchFaq, type Faq } from "./chat-faq";

type Msg = { from: "bot" | "user"; text: string; links?: Faq["links"] };

function Rich({ text }: { text: string }) {
  return (
    <>
      {text.split("\n").map((line, i) => (
        <p key={i} className={line === "" ? "h-2" : ""}>
          {line.split(/(\*\*[^*]+\*\*)/).map((part, j) =>
            part.startsWith("**") ? <strong key={j}>{part.slice(2, -2)}</strong> : part,
          )}
        </p>
      ))}
    </>
  );
}

const greet: Record<Lang, string> = {
  en: "Namaste! I'm the City Online help assistant. Pick a question below or type your own.",
  te: "నమస్తే! నేను సిటీ ఆన్‌లైన్ సహాయకుడిని. క్రింది ప్రశ్న ఎంచుకోండి లేదా టైప్ చేయండి.",
};

export function FloatingSupport({ open, onOpenChange }: { open: boolean; onOpenChange: (open: boolean) => void }) {
  const { lang: siteLang } = usePrefs();
  const [lang, setLang] = useState<Lang>(siteLang);
  const [msgs, setMsgs] = useState<Msg[]>([]);
  const [input, setInput] = useState("");
  const messagesRef = useRef<HTMLDivElement>(null);
  useEffect(() => setLang(siteLang), [siteLang]);
  useEffect(() => {
    const el = messagesRef.current;
    if (el) el.scrollTop = el.scrollHeight;
  }, [msgs]);
  useEffect(() => {
    if (!open) return;
    const close = (e: KeyboardEvent) => { if (e.key === "Escape") onOpenChange(false); };
    document.addEventListener("keydown", close);
    return () => document.removeEventListener("keydown", close);
  }, [open, onOpenChange]);

  const ask = (text: string, faq?: Faq) => {
    const f = faq ?? matchFaq(text);
    setMsgs((m) => [
      ...m,
      { from: "user", text },
      f
        ? { from: "bot", text: f.a[lang], links: f.links }
        : {
            from: "bot",
            text: CONTACT_ANSWER[lang],
            links: [
              { label: "WhatsApp", href: LINKS.whatsapp },
              { label: "Call support", href: LINKS.tel.support },
              { label: "Email us", href: LINKS.email },
            ],
          },
    ]);
  };

  return (
    <>
      <div className="fixed bottom-6 right-6 z-40 hidden flex-col items-end gap-3 xl:flex">
        <motion.a
          href={LINKS.whatsapp}
          {...ext}
          aria-label="WhatsApp support"
          whileHover={{ scale: 1.08 }}
          whileTap={{ scale: 0.94 }}
          className="grid h-12 w-12 place-items-center rounded-full bg-success text-primary-foreground shadow-lift dark:text-navy"
        >
          <MessageCircle className="h-6 w-6" />
        </motion.a>
        <motion.button
          onClick={() => onOpenChange(!open)}
          aria-label={open ? "Close help assistant" : "Open help assistant"}
          aria-expanded={open}
          initial={{ scale: 0 }}
          animate={{ scale: 1 }}
          whileHover={{ scale: 1.06 }}
          whileTap={{ scale: 0.94 }}
          className="relative grid h-14 w-14 place-items-center rounded-full bg-primary text-primary-foreground shadow-lift"
        >
          {open ? <X className="h-6 w-6" /> : <Bot className="h-6 w-6" />}
        </motion.button>
      </div>

      <AnimatePresence>
        {open && (
          <motion.section
            role="dialog"
            aria-label="Help assistant"
            initial={{ opacity: 0, y: 24, scale: 0.96 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 24, scale: 0.96 }}
            transition={{ type: "spring", damping: 26, stiffness: 320 }}
            className="fixed inset-x-3 bottom-[calc(4rem+env(safe-area-inset-bottom))] z-50 flex max-h-[min(560px,calc(100dvh-9rem-env(safe-area-inset-bottom)))] flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-lift sm:inset-x-auto sm:right-6 sm:w-[380px] xl:bottom-24"
          >
            <header className="flex shrink-0 items-center gap-2 bg-hero p-3 text-navy-foreground sm:gap-3 sm:p-4">
              <span className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-accent text-accent-foreground"><Globe2 className="h-5 w-5" /></span>
              <div className="min-w-0 flex-1">
                <p className="truncate font-bold">City Online Help</p>
                <p className="text-xs text-navy-foreground/70">FAQ assistant · pre-set answers</p>
              </div>
              <div className="flex shrink-0 rounded-full bg-navy-foreground/10 p-0.5">
                {(["en", "te"] as const).map((l) => (
                  <button key={l} onClick={() => setLang(l)} aria-pressed={lang === l} className={cn("rounded-full px-2 py-0.5 text-xs font-bold", lang === l ? "bg-accent text-accent-foreground" : "text-navy-foreground")}>
                    {l === "en" ? "EN" : "తె"}
                  </button>
                ))}
              </div>
              <button type="button" onClick={() => onOpenChange(false)} aria-label="Close help assistant" className="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-navy-foreground/10"><X className="h-4 w-4" /></button>
            </header>
            <div ref={messagesRef} className="min-h-0 flex-1 space-y-3 overflow-y-auto overscroll-contain p-4 text-sm [overflow-wrap:anywhere]">
              <div className="text-foreground"><Rich text={greet[lang]} /></div>
              {msgs.map((m, i) =>
                m.from === "user" ? (
                  <div key={i} className="ml-auto w-fit max-w-[85%] rounded-2xl rounded-br-sm bg-primary px-3 py-2 text-primary-foreground">{m.text}</div>
                ) : (
                  <motion.div key={i} initial={{ opacity: 0, y: 6 }} animate={{ opacity: 1, y: 0 }} className="max-w-[92%] space-y-1 text-foreground">
                    <Rich text={m.text} />
                    {m.links && (
                      <div className="flex flex-wrap gap-2 pt-2">
                        {m.links.map((l) => (
                          <a key={l.href} href={l.href} {...(l.href.startsWith("http") ? ext : {})} className="rounded-full border border-accent/40 px-3 py-1 text-xs font-bold text-accent hover:bg-accent hover:text-accent-foreground">
                            {l.label}
                          </a>
                        ))}
                      </div>
                    )}
                  </motion.div>
                ),
              )}
              <div className="flex flex-wrap gap-2 pt-1">
                {FAQS.map((f) => (
                  <button key={f.id} onClick={() => ask(f.q[lang], f)} className="rounded-full bg-secondary px-3 py-1.5 text-left text-xs font-semibold text-secondary-foreground hover:bg-primary hover:text-primary-foreground">
                    {f.q[lang]}
                  </button>
                ))}
              </div>
            </div>
            <div className="flex shrink-0 gap-2 border-t border-border p-2">
              <a href={LINKS.tel.support} className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-secondary text-primary" aria-label="Call support"><Phone className="h-4 w-4" /></a>
              <form className="flex min-w-0 flex-1 gap-2" onSubmit={(e) => { e.preventDefault(); if (input.trim()) { ask(input.trim()); setInput(""); } }}>
                <input value={input} onChange={(e) => setInput(e.target.value)} placeholder={lang === "en" ? "Type a question…" : "ప్రశ్న టైప్ చేయండి…"} aria-label="Your question" className="min-w-0 flex-1 rounded-xl border border-input bg-background px-3 text-base outline-none focus:border-ring" />
                <button type="submit" aria-label="Send" className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-accent text-accent-foreground"><Send className="h-4 w-4" /></button>
              </form>
            </div>
          </motion.section>
        )}
      </AnimatePresence>
    </>
  );
}
