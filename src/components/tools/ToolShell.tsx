import { AlertTriangle, Info } from "lucide-react";
import type { ReactNode } from "react";
import { cn } from "@/lib/utils";

export function Panel({ children, className }: { children: ReactNode; className?: string }) {
  return <div className={cn("min-w-0 rounded-[22px] border border-border bg-card p-4 shadow-soft sm:p-8", className)}>{children}</div>;
}
export function Note({ children, tone = "info" }: { children: ReactNode; tone?: "info" | "warn" }) {
  const Icon = tone === "warn" ? AlertTriangle : Info;
  return (
    <div className={cn("flex gap-3 rounded-xl p-4 text-sm", tone === "warn" ? "bg-destructive/10 text-foreground" : "bg-secondary text-secondary-foreground")}>
      <Icon className={cn("mt-0.5 h-4 w-4 shrink-0", tone === "warn" ? "text-destructive" : "text-primary")} />
      <div className="min-w-0">{children}</div>
    </div>
  );
}
export function Stat({ label, value, unit }: { label: string; value: string; unit?: string }) {
  return (
    <div className="min-w-0 rounded-2xl bg-secondary p-3 sm:p-4">
      <p className="break-words text-xs font-semibold uppercase text-muted-foreground">{label}</p>
      <p className="mt-1 break-all text-xl font-extrabold text-primary sm:text-2xl">{value}{unit && <span className="ml-1 inline-block text-sm font-semibold text-muted-foreground">{unit}</span>}</p>
    </div>
  );
}
export const inputCls = "h-12 w-full min-w-0 rounded-xl border border-input bg-background px-4 text-base outline-none focus:border-ring focus:ring-2 focus:ring-ring/30";
