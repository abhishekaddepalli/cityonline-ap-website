// Browser-side network measurement helpers. Uses public CORS-enabled services only.
export const SPEED_BASE: string = import.meta.env["VITE_SPEEDTEST_BASE"] || "https://speed.cloudflare.com";

const now = () => performance.now();

export async function timedFetch(url: string, signal?: AbortSignal) {
  const t0 = now();
  const r = await fetch(url, { cache: "no-store", ...(signal ? { signal } : {}) });
  await r.arrayBuffer();
  return now() - t0;
}

export function stats(samples: number[]) {
  const sorted = [...samples].sort((a, b) => a - b);
  const avg = samples.reduce((a, b) => a + b, 0) / samples.length;
  const median = sorted[Math.floor(sorted.length / 2)] ?? 0;
  let jitter = 0;
  for (let i = 1; i < samples.length; i++) jitter += Math.abs(samples[i]! - samples[i - 1]!);
  jitter = samples.length > 1 ? jitter / (samples.length - 1) : 0;
  return { avg, median, min: sorted[0] ?? 0, max: sorted[sorted.length - 1] ?? 0, jitter };
}

export const mbps = (bytes: number, ms: number) => (bytes * 8) / (ms / 1000) / 1e6;

export async function measureLatency(n: number, signal: AbortSignal, onSample?: (ms: number) => void) {
  const out: number[] = [];
  for (let i = 0; i < n; i++) {
    const ms = await timedFetch(`${SPEED_BASE}/__down?bytes=0&r=${Math.random()}`, signal);
    if (i > 0) { out.push(ms); onSample?.(ms); } // first request includes connection setup
  }
  return stats(out);
}

export async function measureDownload(signal: AbortSignal, onProgress: (mbps: number) => void, maxMs = 8000) {
  const sizes = [1e6, 5e6, 10e6, 25e6, 25e6, 50e6];
  let total = 0;
  const start = now();
  for (const size of sizes) {
    if (now() - start > maxMs) break;
    const r = await fetch(`${SPEED_BASE}/__down?bytes=${size}&r=${Math.random()}`, { cache: "no-store", signal });
    if (!r.ok || !r.body) throw new Error("Download test server unavailable");
    const reader = r.body.getReader();
    for (;;) {
      const { done, value } = await reader.read();
      if (done) break;
      total += value.length;
      onProgress(mbps(total, now() - start));
      if (now() - start > maxMs + 2000) { await reader.cancel(); break; }
    }
  }
  return mbps(total, now() - start);
}

export async function measureUpload(signal: AbortSignal, onProgress: (mbps: number) => void, maxMs = 7000) {
  const sizes = [5e5, 1e6, 2e6, 5e6, 10e6, 10e6, 10e6];
  let total = 0;
  const start = now();
  for (const size of sizes) {
    if (now() - start > maxMs) break;
    const body = new Uint8Array(size);
    const r = await fetch(`${SPEED_BASE}/__up?r=${Math.random()}`, { method: "POST", body, signal, cache: "no-store", headers: { "content-type": "text/plain" } });
    if (!r.ok) throw new Error("Upload test server unavailable");
    await r.text();
    total += size;
    onProgress(mbps(total, now() - start));
  }
  return mbps(total, now() - start);
}

export const UNITS = { MB: 1e6, GB: 1e9, TB: 1e12 } as const;
/** Seconds to transfer `size` (decimal MB/GB/TB) at `speedMbps` megabits per second. */
export function downloadSeconds(size: number, unit: keyof typeof UNITS, speedMbps: number) {
  if (!(size > 0) || !(speedMbps > 0)) return NaN;
  return (size * UNITS[unit] * 8) / (speedMbps * 1e6);
}
export function formatDuration(s: number) {
  if (!Number.isFinite(s)) return "—";
  if (s < 1) return "under 1 second";
  const d = Math.floor(s / 86400), h = Math.floor((s % 86400) / 3600), m = Math.floor((s % 3600) / 60), sec = Math.round(s % 60);
  return [d && `${d}d`, h && `${h}h`, m && `${m}m`, (sec || (!d && !h && !m)) && `${sec}s`].filter(Boolean).join(" ");
}
