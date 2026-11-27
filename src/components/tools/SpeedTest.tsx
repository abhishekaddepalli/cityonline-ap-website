import { motion } from "framer-motion";
import { Play, Square, RotateCcw } from "lucide-react";
import { useRef, useState } from "react";
import { measureDownload, measureLatency, measureUpload, SPEED_BASE } from "@/lib/net";
import { btnClass } from "@/components/site/primitives";
import { Note, Panel, Stat } from "./ToolShell";

type Phase = "idle" | "latency" | "download" | "upload" | "done" | "error" | "stopped";
const MAX = 300;

function Gauge({ value, label }: { value: number; label: string }) {
  const frac = Math.min(1, Math.sqrt(Math.max(0, value) / MAX));
  const R = 120, C = Math.PI * R;
  return (
    <div className="relative mx-auto w-full max-w-sm">
      <svg viewBox="0 0 280 160" className="w-full" role="img" aria-label={`${label}: ${value.toFixed(1)} Mbps`}>
        <path d="M20 145 A120 120 0 0 1 260 145" stroke="var(--secondary)" strokeWidth="18" fill="none" strokeLinecap="round" />
        <motion.path d="M20 145 A120 120 0 0 1 260 145" stroke="var(--accent)" strokeWidth="18" fill="none" strokeLinecap="round" strokeDasharray={C} animate={{ strokeDashoffset: C * (1 - frac) }} transition={{ type: "spring", damping: 20 }} />
        {[0, 10, 50, 100, 200, 300].map((t) => {
          const a = Math.PI * (1 - Math.sqrt(t / MAX));
          return <text key={t} x={140 + 92 * Math.cos(a)} y={148 - 92 * Math.sin(a)} textAnchor="middle" className="fill-muted-foreground text-[10px] font-semibold">{t}</text>;
        })}
      </svg>
      <div className="absolute inset-x-0 bottom-2 text-center">
        <p className="text-5xl font-extrabold tabular-nums text-primary">{value.toFixed(value < 10 ? 1 : 0)}</p>
        <p className="text-sm font-semibold text-muted-foreground">Mbps · {label}</p>
      </div>
    </div>
  );
}

export function SpeedTest() {
  const [phase, setPhase] = useState<Phase>("idle");
  const [live, setLive] = useState(0);
  const [res, setRes] = useState<{ ping?: number | undefined; jitter?: number | undefined; down?: number | undefined; up?: number | undefined }>({});
  const [err, setErr] = useState("");
  const ctrl = useRef<AbortController | null>(null);

  const start = async () => {
    const c = new AbortController();
    ctrl.current = c;
    setRes({}); setErr(""); setLive(0);
    try {
      setPhase("latency");
      const l = await measureLatency(12, c.signal);
      setRes({ ping: l.median, jitter: l.jitter });
      setPhase("download");
      const down = await measureDownload(c.signal, setLive);
      setRes((r) => ({ ...r, down }));
      setLive(0);
      setPhase("upload");
      const up = await measureUpload(c.signal, setLive);
      setRes((r) => ({ ...r, up }));
      setPhase("done");
    } catch (e) {
      if (c.signal.aborted) setPhase("stopped");
      else { setErr(e instanceof Error ? e.message : "The test server could not be reached."); setPhase("error"); }
    }
  };
  const stop = () => ctrl.current?.abort();
  const running = phase === "latency" || phase === "download" || phase === "upload";
  const gaugeVal = running ? live : phase === "done" ? res.down ?? 0 : 0;
  const gaugeLabel = phase === "upload" ? "Upload" : phase === "latency" ? "Measuring latency" : "Download";

  return (
    <Panel>
      <div className="grid gap-8 lg:grid-cols-[1.2fr_1fr] lg:items-center">
        <div>
          <Gauge value={gaugeVal} label={gaugeLabel} />
          <div className="mt-6 flex justify-center gap-3">
            {running ? (
              <button onClick={stop} className={btnClass("outline")}><Square className="h-4 w-4" /> Stop</button>
            ) : (
              <button onClick={start} className={btnClass("orange", "px-8")}>{phase === "idle" ? <Play className="h-4 w-4" /> : <RotateCcw className="h-4 w-4" />} {phase === "idle" ? "Start test" : "Run again"}</button>
            )}
          </div>
          <p className="mt-3 text-center text-sm text-muted-foreground" aria-live="polite">
            {phase === "idle" && "Press start to measure your connection."}
            {phase === "latency" && "Measuring latency…"}
            {phase === "download" && "Testing download speed…"}
            {phase === "upload" && "Testing upload speed…"}
            {phase === "done" && "Test complete."}
            {phase === "stopped" && "Test stopped."}
          </p>
        </div>
        <div className="space-y-4">
          <div className="grid grid-cols-2 gap-3">
            <Stat label="Download" value={res.down ? res.down.toFixed(1) : "—"} unit="Mbps" />
            <Stat label="Upload" value={res.up ? res.up.toFixed(1) : "—"} unit="Mbps" />
            <Stat label="Latency" value={res.ping ? res.ping.toFixed(0) : "—"} unit="ms" />
            <Stat label="Jitter" value={res.jitter !== undefined ? res.jitter.toFixed(1) : "—"} unit="ms" />
          </div>
          {phase === "error" && <Note tone="warn">{err} The speed test needs an external test server, which isn't responding right now. Please try again later — no results are shown rather than made-up numbers.</Note>}
          <Note>
            Measured live from your browser against <strong>{new URL(SPEED_BASE).host}</strong>. Results depend on your device, Wi-Fi and other usage at home — for the most accurate figure, use a cable connected to the router.
          </Note>
        </div>
      </div>
    </Panel>
  );
}
