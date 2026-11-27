import { Check, Copy, Loader2, Search, Activity, ExternalLink } from "lucide-react";
import { useEffect, useState } from "react";
import { btnClass } from "@/components/site/primitives";
import { downloadSeconds, formatDuration, SPEED_BASE, stats, timedFetch, UNITS } from "@/lib/net";
import { LINKS, PLANS, ext } from "@/lib/links";
import { cn } from "@/lib/utils";
import { inputCls, Note, Panel, Stat } from "./ToolShell";

export function IpLookup() {
  const [ip, setIp] = useState<{ v4?: string; v6?: string; loc?: string } | null>(null);
  const [err, setErr] = useState("");
  const [copied, setCopied] = useState(false);
  const [loading, setLoading] = useState(true);
  const load = async () => {
    setLoading(true); setErr("");
    try {
      const [v4, trace] = await Promise.allSettled([
        fetch("https://api.ipify.org?format=json", { cache: "no-store" }).then((r) => r.json()),
        fetch("https://1.1.1.1/cdn-cgi/trace", { cache: "no-store" }).then((r) => r.text()),
      ]);
      const out: { v4?: string; v6?: string; loc?: string } = {};
      if (v4.status === "fulfilled") out.v4 = v4.value.ip;
      if (trace.status === "fulfilled") {
        const m: Record<string, string> = Object.fromEntries(trace.value.trim().split("\n").map((l) => l.split("=")));
        const tip = m["ip"], loc = m["loc"];
        if (tip && tip.includes(":")) out.v6 = tip; else if (tip && !out.v4) out.v4 = tip;
        if (loc) out.loc = loc;
      }
      if (!out.v4 && !out.v6) throw new Error();
      setIp(out);
    } catch { setErr("We couldn't reach the IP lookup service. Check your connection or ad blocker and try again."); }
    setLoading(false);
  };
  useEffect(() => { load(); }, []);
  const main = ip?.v4 ?? ip?.v6 ?? "";
  return (
    <Panel>
      <p className="text-sm font-semibold text-muted-foreground">Your public IP address</p>
      {loading ? <Loader2 className="mt-4 h-8 w-8 animate-spin text-accent" /> : err ? <div className="mt-4"><Note tone="warn">{err}</Note></div> : (
        <>
          <p className="mt-2 break-all text-3xl font-extrabold text-primary sm:text-5xl">{main}</p>
          <div className="mt-6 flex flex-wrap gap-3">
            <button onClick={async () => { await navigator.clipboard.writeText(main); setCopied(true); setTimeout(() => setCopied(false), 1800); }} className={btnClass("orange")}>
              {copied ? <Check className="h-4 w-4" /> : <Copy className="h-4 w-4" />} {copied ? "Copied" : "Copy IP"}
            </button>
            <button onClick={load} className={btnClass("outline")}>Refresh</button>
          </div>
          <div className="mt-6 grid gap-3 sm:grid-cols-3">
            <Stat label="IPv4" value={ip?.v4 ?? "—"} />
            <Stat label="IPv6" value={ip?.v6 ? "Yes" : "Not detected"} />
            <Stat label="Country" value={ip?.loc ?? "—"} />
          </div>
        </>
      )}
      <div className="mt-6"><Note>Looked up using public services (ipify and Cloudflare). City Online does not store this information.</Note></div>
    </Panel>
  );
}

const TYPES = ["A", "AAAA", "CNAME", "MX", "TXT", "NS", "SOA"] as const;
const TYPE_NUM: Record<number, string> = { 1: "A", 28: "AAAA", 5: "CNAME", 15: "MX", 16: "TXT", 2: "NS", 6: "SOA" };
const RCODE: Record<number, string> = { 2: "Server failure", 3: "Domain does not exist (NXDOMAIN)", 5: "Query refused" };

export function DnsLookup() {
  const [domain, setDomain] = useState("cityonlineap.com");
  const [type, setType] = useState<(typeof TYPES)[number]>("A");
  const [rows, setRows] = useState<{ name: string; type: number; TTL: number; data: string }[] | null>(null);
  const [err, setErr] = useState("");
  const [loading, setLoading] = useState(false);
  const run = async (e?: React.FormEvent) => {
    e?.preventDefault();
    const d = domain.trim().replace(/^https?:\/\//, "").split("/")[0] ?? "";
    if (!/^[a-z0-9.-]+\.[a-z]{2,}$/i.test(d)) { setErr("Please enter a valid domain, like example.com"); setRows(null); return; }
    setLoading(true); setErr(""); setRows(null);
    const q = `name=${encodeURIComponent(d)}&type=${type}`;
    try {
      let j;
      try { j = await (await fetch(`https://dns.google/resolve?${q}`)).json(); }
      catch { j = await (await fetch(`https://cloudflare-dns.com/dns-query?${q}`, { headers: { accept: "application/dns-json" } })).json(); }
      if (j.Status !== 0) setErr(RCODE[j.Status] ?? `DNS error code ${j.Status}`);
      else setRows(j.Answer ?? []);
    } catch { setErr("The DNS service could not be reached. Please try again."); }
    setLoading(false);
  };
  return (
    <Panel>
      <form onSubmit={run} className="flex flex-col gap-3 sm:flex-row">
        <label htmlFor="dns" className="sr-only">Domain</label>
        <input id="dns" value={domain} onChange={(e) => setDomain(e.target.value)} placeholder="example.com" className={cn(inputCls, "flex-1")} />
        <select aria-label="Record type" value={type} onChange={(e) => setType(e.target.value as typeof type)} className={cn(inputCls, "sm:w-32")}>
          {TYPES.map((t) => <option key={t}>{t}</option>)}
        </select>
        <button className={btnClass("orange", "h-12")} disabled={loading}>{loading ? <Loader2 className="h-4 w-4 animate-spin" /> : <Search className="h-4 w-4" />} Look up</button>
      </form>
      <div className="mt-6 space-y-3">
        {err && <Note tone="warn">{err}</Note>}
        {rows && rows.length === 0 && <Note>No {type} records found for this domain.</Note>}
        {rows && rows.length > 0 && (
          <div className="overflow-x-auto rounded-xl border border-border">
            <table className="w-full table-fixed text-left text-sm">
              <thead className="bg-secondary text-xs uppercase tracking-wider text-muted-foreground"><tr><th className="w-16 p-3">Type</th><th className="p-3">Value</th><th className="w-20 p-3">TTL</th></tr></thead>
              <tbody>{rows.map((r, i) => (
                <tr key={i} className="border-t border-border"><td className="p-3 font-bold text-accent">{TYPE_NUM[r.type] ?? r.type}</td><td className="break-all p-3 font-mono text-xs sm:text-sm">{r.data}</td><td className="p-3 text-muted-foreground">{r.TTL}s</td></tr>
              ))}</tbody>
            </table>
          </div>
        )}
        <Note>Queried over HTTPS through Google Public DNS, with Cloudflare as a fallback.</Note>
      </div>
    </Panel>
  );
}

const TARGETS = [
  { label: "Cloudflare", url: `${SPEED_BASE}/__down?bytes=0` },
  { label: "Google", url: "https://dns.google/resolve?name=a.root-servers.net&type=A" },
];
export function PingTest() {
  const [target, setTarget] = useState(0);
  const [samples, setSamples] = useState<number[]>([]);
  const [running, setRunning] = useState(false);
  const [err, setErr] = useState("");
  const run = async () => {
    const selected = TARGETS[target];
    if (!selected) return;
    setRunning(true); setSamples([]); setErr("");
    const c = new AbortController();
    try {
      await timedFetch(`${selected.url}&w=${Math.random()}`, c.signal); // warm-up connection
      for (let i = 0; i < 10; i++) {
        const ms = await timedFetch(`${selected.url}&r=${Math.random()}`, c.signal);
        setSamples((s) => [...s, ms]);
      }
    } catch { setErr("The test server couldn't be reached, so no latency could be measured."); }
    setRunning(false);
  };
  const s = samples.length ? stats(samples) : null;
  return (
    <Panel>
      <div className="flex flex-wrap items-center gap-3">
        <div role="group" aria-label="Target" className="inline-flex rounded-xl bg-secondary p-1">
          {TARGETS.map((t, i) => (
            <button key={t.label} onClick={() => setTarget(i)} aria-pressed={target === i} className={cn("rounded-lg px-4 py-2 text-sm font-bold", target === i ? "bg-primary text-primary-foreground" : "text-secondary-foreground")}>{t.label}</button>
          ))}
        </div>
        <button onClick={run} disabled={running} className={btnClass("orange")}>{running ? <Loader2 className="h-4 w-4 animate-spin" /> : <Activity className="h-4 w-4" />} {running ? "Testing…" : "Start ping test"}</button>
      </div>
      <div className="mt-6 grid gap-3 min-[360px]:grid-cols-3">
        <Stat label="Average" value={s ? s.avg.toFixed(0) : "—"} unit="ms" />
        <Stat label="Minimum" value={s ? s.min.toFixed(0) : "—"} unit="ms" />
        <Stat label="Maximum" value={s ? s.max.toFixed(0) : "—"} unit="ms" />
      </div>
      <div className="mt-4 flex h-24 items-end gap-1.5" aria-hidden>
        {samples.map((v, i) => <div key={i} className="flex-1 rounded-t bg-accent" style={{ height: `${Math.min(100, (v / Math.max(...samples)) * 100)}%` }} />)}
      </div>
      <div className="mt-4 space-y-3">
        {err && <Note tone="warn">{err}</Note>}
        <Note>This measures the time for small HTTPS requests from your browser. It is <strong>not an ICMP ping</strong> and usually reads a little higher than a real ping.</Note>
      </div>
    </Panel>
  );
}

export function BandwidthCalculator() {
  const [size, setSize] = useState("5");
  const [unit, setUnit] = useState<keyof typeof UNITS>("GB");
  const [speed, setSpeed] = useState("100");
  const secs = downloadSeconds(parseFloat(size), unit, parseFloat(speed));
  return (
    <Panel>
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label htmlFor="fs" className="text-sm font-semibold">File size</label>
          <div className="mt-2 flex gap-2">
            <input id="fs" type="number" min="0" step="any" inputMode="decimal" value={size} onChange={(e) => setSize(e.target.value)} className={cn(inputCls, "flex-1")} />
            <select aria-label="Size unit" value={unit} onChange={(e) => setUnit(e.target.value as keyof typeof UNITS)} className={cn(inputCls, "w-24")}>
              {Object.keys(UNITS).map((u) => <option key={u}>{u}</option>)}
            </select>
          </div>
        </div>
        <div>
          <label htmlFor="sp" className="text-sm font-semibold">Internet speed (Mbps)</label>
          <input id="sp" type="number" min="0" step="any" inputMode="decimal" value={speed} onChange={(e) => setSpeed(e.target.value)} className={cn(inputCls, "mt-2")} />
          <div className="mt-2 flex flex-wrap gap-2">
            {PLANS.map((p) => <button key={p.id} onClick={() => setSpeed(String(p.speed))} className="rounded-full border border-border px-3 py-1 text-xs font-semibold hover:border-accent hover:text-accent">{p.name} · {p.speed} Mbps</button>)}
          </div>
        </div>
      </div>
      <div className="mt-8 rounded-2xl bg-hero p-6 text-center text-navy-foreground">
        <p className="text-sm text-navy-foreground/70">Estimated download time</p>
        <p className="mt-1 break-words text-3xl font-extrabold text-accent sm:text-4xl">{formatDuration(secs)}</p>
      </div>
      <div className="mt-4"><Note>Ideal-case estimate using decimal units (1 GB = 1000 MB) and 8 bits per byte. Real downloads are often slower due to Wi-Fi and server limits.</Note></div>
    </Panel>
  );
}

export function ExternalDiagnostic({ title, children, href, cta }: { title: string; children: React.ReactNode; href: string; cta: string }) {
  return (
    <Panel>
      <h2 className="text-xl font-bold text-primary">{title}</h2>
      <div className="mt-3 text-sm text-muted-foreground">{children}</div>
      <a href={href} {...ext} className={btnClass("outline", "mt-5")}>{cta} <ExternalLink className="h-4 w-4" /></a>
    </Panel>
  );
}
export { LINKS };
