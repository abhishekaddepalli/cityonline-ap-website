import { AnimatePresence, motion } from "framer-motion";
import { Globe2, MessageCircle, Phone, Send, X, Bot, Sparkles, Loader2 } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { LINKS, ext } from "@/lib/links";
import { usePrefs, type Lang } from "@/lib/prefs";
import { useCms } from "@/lib/cms";
import { cn } from "@/lib/utils";
import { CONTACT_ANSWER, FAQS, matchFaq, type Faq } from "./chat-faq";

type Msg = { from: "bot" | "user"; text: string; links?: Faq["links"]; source?: string };

function Rich({ text }: { text: string }) {
  return (
    <div className="space-y-1.5 leading-relaxed">
      {text.split("\n").map((line, i) => {
        const trimmed = line.trim();
        if (!trimmed) return <div key={i} className="h-1" />;
        const isBullet = trimmed.startsWith("• ") || trimmed.startsWith("- ") || /^\d+\.\s/.test(trimmed);

        const content = trimmed.replace(/^[•\-]\s|^\d+\.\s/, "");
        const parts = content.split(/(\*\*[^*]+\*\*)/).map((part, j) =>
          part.startsWith("**") && part.endsWith("**") ? (
            <strong key={j} className="font-bold text-foreground">
              {part.slice(2, -2)}
            </strong>
          ) : (
            part
          ),
        );

        return (
          <p key={i} className={cn("text-xs sm:text-sm", isBullet && "flex items-start gap-1.5 pl-1")}>
            {isBullet && <span className="text-accent font-bold">•</span>}
            <span className="flex-1">{parts}</span>
          </p>
        );
      })}
    </div>
  );
}

const defaultGreet: Record<Lang, string> = {
  en: "Namaste! I'm City Online AP's AI Assistant. How can I help you with our fibre plans, bill payments, or support today?",
  te: "నమస్తే! నేను సిటీ ఆన్‌లైన్ సహాయకుడిని. మా ఫైబర్ ప్లాన్లు, బిల్లు చెల్లింపులు లేదా సహాయం కోసం నన్ను అడగండి.",
};

const QUICK_CHIPS = [
  { q: "What are your broadband plans?", te: "బ్రాడ్‌బ్యాండ్ ప్లాన్లు ఏమిటి?" },
  { q: "How can I pay my bill?", te: "బిల్లు ఎలా చెల్లించాలి?" },
  { q: "My internet is not working", te: "ఇంటర్నెట్ పనిచేయడం లేదు" },
  { q: "Check coverage in Peddapuram", te: "పెద్దాపురంలో కవరేజ్ చెక్ చేయండి" },
  { q: "Peddapuram office location", te: "పెద్దాపురం ఆఫీస్ ఎక్కడ ఉంది?" },
];

export function FloatingSupport({ open, onOpenChange }: { open: boolean; onOpenChange: (open: boolean) => void }) {
  const { lang: siteLang } = usePrefs();
  const { settings } = useCms();
  const [lang, setLang] = useState<Lang>(siteLang);
  const [msgs, setMsgs] = useState<Msg[]>([]);
  const [input, setInput] = useState("");
  const [loading, setLoading] = useState(false);
  const messagesRef = useRef<HTMLDivElement>(null);

  const customGreeting = settings?.gemini_greeting_msg;
  const initialGreeting = lang === "en" && customGreeting ? customGreeting : defaultGreet[lang];

  useEffect(() => setLang(siteLang), [siteLang]);

  useEffect(() => {
    const el = messagesRef.current;
    if (el) el.scrollTop = el.scrollHeight;
  }, [msgs, loading]);

  useEffect(() => {
    if (!open) return;
    const close = (e: KeyboardEvent) => {
      if (e.key === "Escape") onOpenChange(false);
    };
    document.addEventListener("keydown", close);
    return () => document.removeEventListener("keydown", close);
  }, [open, onOpenChange]);

  const ask = async (text: string) => {
    if (!text.trim() || loading) return;

    const userText = text.trim();
    const updatedMsgs: Msg[] = [...msgs, { from: "user", text: userText }];
    setMsgs(updatedMsgs);
    setLoading(true);

    try {
      const res = await fetch("/api/v1/chat", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          message: userText,
          lang,
          history: updatedMsgs.slice(-4),
        }),
      });

      if (res.ok) {
        const data = await res.json();
        if (data.reply) {
          setMsgs((m) => [
            ...m,
            {
              from: "bot",
              text: data.reply,
              links: data.links || [
                { label: "WhatsApp Support", href: LINKS.whatsapp },
                { label: "View Plans", href: "/plans" },
              ],
              source: data.source || "gemini",
            },
          ]);
          setLoading(false);
          return;
        }
      }
    } catch {
      // Fallback below
    }

    // Client-side fallback if server fails
    const f = matchFaq(userText);
    setMsgs((m) => [
      ...m,
      f
        ? { from: "bot", text: f.a[lang], links: f.links, source: "faq" }
        : {
            from: "bot",
            text: CONTACT_ANSWER[lang],
            links: [
              { label: "WhatsApp Support", href: LINKS.whatsapp },
              { label: "Call Support", href: LINKS.tel.support },
              { label: "Pay Bill Online", href: LINKS.payBill },
            ],
            source: "faq",
          },
    ]);
    setLoading(false);
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
            aria-label="AI Help Assistant"
            initial={{ opacity: 0, y: 24, scale: 0.96 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 24, scale: 0.96 }}
            transition={{ type: "spring", damping: 26, stiffness: 320 }}
            className="fixed inset-x-3 bottom-[calc(4rem+env(safe-area-inset-bottom))] z-50 flex max-h-[min(580px,calc(100dvh-9rem-env(safe-area-inset-bottom)))] flex-col overflow-hidden rounded-3xl border border-border bg-card shadow-2xl sm:inset-x-auto sm:right-6 sm:w-[400px] xl:bottom-24"
          >
            {/* Header */}
            <header className="flex shrink-0 items-center gap-2 bg-gradient-to-r from-brand-navy via-brand-blue to-blue-900 p-3.5 text-white sm:gap-3 sm:p-4 shadow-sm">
              <span className="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-accent text-accent-foreground shadow-xs">
                <Bot className="h-5 w-5" />
              </span>
              <div className="min-w-0 flex-1">
                <div className="flex items-center gap-1.5">
                  <p className="truncate font-extrabold text-sm">City Online Assistant</p>
                  <span className="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-accent/30 text-amber-200 border border-accent/40 backdrop-blur-xs">
                    <Sparkles className="h-2.5 w-2.5" />
                    AI Powered
                  </span>
                </div>
                <p className="text-[11px] text-white/75 truncate">Instant 24/7 Peddapuram broadband answers</p>
              </div>

              <div className="flex shrink-0 rounded-full bg-white/10 p-0.5">
                {(["en", "te"] as const).map((l) => (
                  <button
                    key={l}
                    onClick={() => setLang(l)}
                    aria-pressed={lang === l}
                    className={cn(
                      "rounded-full px-2 py-0.5 text-xs font-bold transition",
                      lang === l ? "bg-accent text-accent-foreground shadow-xs" : "text-white/80 hover:text-white",
                    )}
                  >
                    {l === "en" ? "EN" : "తె"}
                  </button>
                ))}
              </div>
              <button
                type="button"
                onClick={() => onOpenChange(false)}
                aria-label="Close help assistant"
                className="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 transition"
              >
                <X className="h-4 w-4" />
              </button>
            </header>

            {/* Chat Body */}
            <div
              ref={messagesRef}
              className="min-h-0 flex-1 space-y-3.5 overflow-y-auto overscroll-contain p-4 text-sm [overflow-wrap:anywhere]"
            >
              {/* Bot Greeting */}
              <div className="max-w-[92%] rounded-2xl rounded-tl-xs bg-secondary/80 p-3.5 text-foreground border border-border/50 shadow-xs">
                <Rich text={initialGreeting} />
              </div>

              {/* Chat Thread */}
              {msgs.map((m, i) =>
                m.from === "user" ? (
                  <div
                    key={i}
                    className="ml-auto w-fit max-w-[85%] rounded-2xl rounded-br-xs bg-primary px-3.5 py-2.5 text-xs sm:text-sm text-primary-foreground shadow-xs"
                  >
                    {m.text}
                  </div>
                ) : (
                  <motion.div
                    key={i}
                    initial={{ opacity: 0, y: 6 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="max-w-[92%] space-y-2 rounded-2xl rounded-tl-xs bg-secondary/80 p-3.5 text-foreground border border-border/50 shadow-xs"
                  >
                    <Rich text={m.text} />

                    {m.links && m.links.length > 0 && (
                      <div className="flex flex-wrap gap-1.5 pt-1.5">
                        {m.links.map((l) => (
                          <a
                            key={l.href}
                            href={l.href}
                            {...(l.href.startsWith("http") ? ext : {})}
                            className="inline-flex items-center gap-1 rounded-full border border-accent/40 bg-accent/10 px-2.5 py-1 text-[11px] font-bold text-accent transition hover:bg-accent hover:text-accent-foreground"
                          >
                            <span>{l.label}</span>
                          </a>
                        ))}
                      </div>
                    )}
                  </motion.div>
                ),
              )}

              {/* AI Typing Indicator */}
              {loading && (
                <div className="flex items-center gap-2 text-xs font-semibold text-accent p-2">
                  <Loader2 className="h-4 w-4 animate-spin text-accent" />
                  <span>Assistant is thinking...</span>
                </div>
              )}

              {/* Quick Action Suggestion Chips */}
              {msgs.length === 0 && (
                <div className="pt-2">
                  <p className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground mb-2">
                    Frequently Asked Questions
                  </p>
                  <div className="flex flex-wrap gap-1.5">
                    {QUICK_CHIPS.map((chip) => (
                      <button
                        key={chip.q}
                        onClick={() => ask(lang === "te" ? chip.te : chip.q)}
                        className="rounded-xl border border-border/70 bg-card px-2.5 py-1.5 text-left text-xs font-semibold text-foreground/85 shadow-2xs hover:border-accent hover:bg-accent hover:text-accent-foreground transition active:scale-95"
                      >
                        {lang === "te" ? chip.te : chip.q}
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>

            {/* Chat Input Bar */}
            <div className="flex shrink-0 gap-2 border-t border-border bg-card p-2.5">
              <a
                href={LINKS.tel.support}
                className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-secondary text-primary hover:bg-primary hover:text-primary-foreground transition"
                aria-label="Call support hotline"
                title="Call 24/7 Support"
              >
                <Phone className="h-4 w-4" />
              </a>

              <form
                className="flex min-w-0 flex-1 gap-2"
                onSubmit={(e) => {
                  e.preventDefault();
                  if (input.trim()) {
                    ask(input);
                    setInput("");
                  }
                }}
              >
                <input
                  value={input}
                  onChange={(e) => setInput(e.target.value)}
                  disabled={loading}
                  placeholder={lang === "en" ? "Ask anything about plans, Wi-Fi, bills…" : "ప్రశ్న అడగండి…"}
                  aria-label="Your question"
                  className="min-w-0 flex-1 rounded-xl border border-input bg-background px-3 text-xs sm:text-sm outline-none focus:border-ring disabled:opacity-50"
                />
                <button
                  type="submit"
                  disabled={loading || !input.trim()}
                  aria-label="Send question"
                  className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-accent text-accent-foreground shadow-xs transition hover:bg-accent-hover disabled:opacity-40 active:scale-95"
                >
                  <Send className="h-4 w-4" />
                </button>
              </form>
            </div>
          </motion.section>
        )}
      </AnimatePresence>
    </>
  );
}
