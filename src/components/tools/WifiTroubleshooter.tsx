import { AnimatePresence, motion } from "framer-motion";
import { Check, ChevronDown, MessageCircle, Phone, Power, Radio, Router, Signal, Smartphone, Cable } from "lucide-react";
import { useState } from "react";
import { btnClass } from "@/components/site/primitives";
import { LINKS, ext } from "@/lib/links";
import { cn } from "@/lib/utils";
import { Panel } from "./ToolShell";

const STEPS = [
  { icon: Router, t: "Check the router lights", d: "Power and PON lights should be steady green. A red or blinking LOS light means a fibre signal problem — skip ahead and contact support." },
  { icon: Power, t: "Restart your router", d: "Switch the router off at the wall, wait 30 seconds, then switch it on. Give it 2–3 minutes to fully reconnect before testing." },
  { icon: Cable, t: "Check the fibre and cables", d: "Make sure the thin fibre cable isn't sharply bent, pinched in a door or pulled loose. Check the power adapter is firmly connected." },
  { icon: Radio, t: "Improve router placement", d: "Place the router high, in an open central spot. Keep it away from metal cupboards, mirrors, fish tanks and microwaves." },
  { icon: Signal, t: "Try the 5 GHz or 2.4 GHz network", d: "5 GHz is faster near the router; 2.4 GHz reaches further through walls. Switch bands to see which works better in that room." },
  { icon: Smartphone, t: "Test another device", d: "If only one phone or laptop is slow, restart that device and forget/re-join the Wi-Fi network. Too many devices streaming at once can also slow things down." },
];

export function WifiTroubleshooter() {
  const [done, setDone] = useState<Set<number>>(new Set());
  const [openI, setOpenI] = useState<number | null>(0);
  const toggle = (i: number) => setDone((s) => { const n = new Set(s); n.has(i) ? n.delete(i) : n.add(i); return n; });
  const pct = Math.round((done.size / STEPS.length) * 100);
  return (
    <Panel>
      <div className="mb-6">
        <div className="flex items-center justify-between text-sm font-semibold"><span>Your progress</span><span className="text-accent">{done.size}/{STEPS.length} steps</span></div>
        <div className="mt-2 h-2 overflow-hidden rounded-full bg-secondary"><motion.div className="h-full bg-orange-gradient" animate={{ width: `${pct}%` }} /></div>
      </div>
      <ol className="space-y-3">
        {STEPS.map((s, i) => (
          <li key={s.t} className={cn("rounded-2xl border transition", done.has(i) ? "border-success/40 bg-success/5" : "border-border")}>
            <div className="flex items-center gap-3 p-4">
              <button onClick={() => toggle(i)} aria-pressed={done.has(i)} aria-label={`Mark "${s.t}" as done`} className={cn("grid h-8 w-8 shrink-0 place-items-center rounded-full border-2 transition", done.has(i) ? "border-success bg-success text-primary-foreground" : "border-input")}>
                {done.has(i) && <Check className="h-4 w-4" />}
              </button>
              <button onClick={() => setOpenI(openI === i ? null : i)} aria-expanded={openI === i} className="flex min-w-0 flex-1 items-center gap-3 text-left">
                <s.icon className="h-5 w-5 shrink-0 text-accent" />
                <span className="min-w-0 flex-1 font-bold text-primary">{i + 1}. {s.t}</span>
                <ChevronDown className={cn("h-4 w-4 shrink-0 transition", openI === i && "rotate-180")} />
              </button>
            </div>
            <AnimatePresence initial={false}>
              {openI === i && (
                <motion.div initial={{ height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} exit={{ height: 0, opacity: 0 }} className="overflow-hidden">
                  <p className="px-4 pb-4 pl-[3.75rem] text-sm text-muted-foreground">{s.d}</p>
                </motion.div>
              )}
            </AnimatePresence>
          </li>
        ))}
      </ol>
      <div className="mt-8 rounded-2xl bg-secondary p-5">
        <p className="font-bold text-primary">Still not working?</p>
        <p className="mt-1 text-sm text-secondary-foreground/80">Our Kakinada team attends faults within 24 hours.</p>
        <div className="mt-4 flex flex-wrap gap-2">
          <a href={LINKS.whatsapp} {...ext} className={btnClass("orange")}><MessageCircle className="h-4 w-4" /> WhatsApp us</a>
          <a href={LINKS.tel.support} className={btnClass("outline")}><Phone className="h-4 w-4" /> Call support</a>
        </div>
      </div>
    </Panel>
  );
}
