import { createContext, useContext, useEffect, useState, type ReactNode } from "react";

export type Lang = "en" | "te";
type Theme = "light" | "dark";

const dict = {
  home: { en: "Home", te: "హోమ్" },
  plans: { en: "Broadband Plans", te: "బ్రాడ్‌బ్యాండ్ ప్లాన్లు" },
  coverage: { en: "Coverage", te: "కవరేజ్" },
  tools: { en: "Internet Tools", te: "ఇంటర్నెట్ టూల్స్" },
  support: { en: "Support", te: "సహాయం" },
  about: { en: "About Us", te: "మా గురించి" },
  contact: { en: "Contact Us", te: "సంప్రదించండి" },
  payBill: { en: "Pay Bill", te: "బిల్ చెల్లించండి" },
  newConnection: { en: "Get New Connection", te: "కొత్త కనెక్షన్ పొందండి" },
  checkArea: { en: "Check My Area", te: "నా ప్రాంతం చెక్ చేయండి" },
  heroBadge: { en: "Kakinada's own fibre broadband", te: "కాకినాడ సొంత ఫైబర్ బ్రాడ్‌బ్యాండ్" },
  heroTitleA: { en: "One connection,", te: "ఒక్క కనెక్షన్," },
  heroTitleB: { en: "the whole house.", te: "ఇల్లంతా ఇంటర్నెట్." },
  heroSub: {
    en: "Unlimited, reliable and high-speed fibre broadband for your home and business in Kakinada.",
    te: "కాకినాడలో మీ ఇంటికి మరియు వ్యాపారానికి అపరిమిత, నమ్మకమైన, హై-స్పీడ్ ఫైబర్ బ్రాడ్‌బ్యాండ్.",
  },
  t1: { en: "Unlimited data", te: "అపరిమిత డేటా" },
  t2: { en: "Same upload & download speeds", te: "ఒకే అప్‌లోడ్ & డౌన్‌లోడ్ వేగం" },
  t3: { en: "Faults attended in 24 hours", te: "24 గంటల్లో సమస్య పరిష్కారం" },
  plansTitle: { en: "Choose the Right Plan for You", te: "మీకు సరైన ప్లాన్ ఎంచుకోండి" },
  getPlan: { en: "Get This Plan", te: "ఈ ప్లాన్ పొందండి" },
  coverageTitle: { en: "Is City Online available in your area?", te: "మీ ప్రాంతంలో సిటీ ఆన్‌లైన్ అందుబాటులో ఉందా?" },
  menu: { en: "Menu", te: "మెనూ" },
} as const;
export type Key = keyof typeof dict;

const Ctx = createContext<{ lang: Lang; setLang: (l: Lang) => void; t: (k: Key) => string; theme: Theme; toggleTheme: () => void }>(
  null as never,
);

export function PrefsProvider({ children }: { children: ReactNode }) {
  const [lang, setLangState] = useState<Lang>("en");
  const [theme, setTheme] = useState<Theme>("light");
  useEffect(() => {
    const l = localStorage.getItem("co-lang");
    if (l === "te" || l === "en") setLangState(l);
    if (localStorage.getItem("co-theme") === "dark") setTheme("dark");
  }, []);
  useEffect(() => {
    document.documentElement.classList.toggle("dark", theme === "dark");
  }, [theme]);
  useEffect(() => {
    document.documentElement.lang = lang;
  }, [lang]);
  const setLang = (l: Lang) => {
    setLangState(l);
    localStorage.setItem("co-lang", l);
  };
  const toggleTheme = () =>
    setTheme((p) => {
      const n = p === "dark" ? "light" : "dark";
      localStorage.setItem("co-theme", n);
      return n;
    });
  return (
    <Ctx.Provider value={{ lang, setLang, t: (k) => dict[k][lang], theme, toggleTheme }}>{children}</Ctx.Provider>
  );
}
export const usePrefs = () => useContext(Ctx);
