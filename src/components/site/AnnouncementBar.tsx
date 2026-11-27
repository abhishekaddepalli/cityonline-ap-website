import { useState, useEffect } from "react";
import { ArrowRight, Sparkles, X } from "lucide-react";
import { useCms } from "@/lib/cms";
import { cn } from "@/lib/utils";

export function AnnouncementBar() {
  const { settings } = useCms();
  const [dismissed, setDismissed] = useState(true);

  const isEnabled = settings?.announcement_enabled === "1" || settings?.announcement_enabled === "true";
  const badge = settings?.announcement_badge || "SPECIAL OFFER";
  const text = settings?.announcement_text || "Get FREE dual-band Wi-Fi router + Zero installation fee on all 6-month plans in Peddapuram & surroundings!";
  const ctaLabel = settings?.announcement_cta_label || "Claim Now";
  const ctaUrl = settings?.announcement_cta_url || "https://customer.cityonlineap.com/book-connection";
  const style = settings?.announcement_style || "gradient-orange";

  useEffect(() => {
    if (typeof window !== "undefined") {
      const isDismissed = sessionStorage.getItem("city_announcement_dismissed");
      if (!isDismissed && isEnabled) {
        setDismissed(false);
      }
    }
  }, [isEnabled]);

  if (dismissed || !isEnabled || !text) return null;

  const handleDismiss = () => {
    setDismissed(true);
    if (typeof window !== "undefined") {
      sessionStorage.setItem("city_announcement_dismissed", "true");
    }
  };

  const bgCls =
    style === "navy-blue"
      ? "bg-gradient-to-r from-brand-navy via-brand-blue to-blue-900 text-white"
      : style === "festive-glow"
        ? "bg-gradient-to-r from-red-600 via-rose-600 to-amber-600 text-white"
        : "bg-gradient-to-r from-orange-600 via-brand-orange to-amber-500 text-white";

  return (
    <div className={cn("relative z-50 text-xs py-2 px-4 shadow-xs transition-all", bgCls)}>
      <div className="container-x mx-auto flex items-center justify-between gap-3">
        <div className="flex-1 flex flex-wrap items-center justify-center sm:justify-start gap-2">
          {badge && (
            <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-white border border-white/30 backdrop-blur-xs shadow-xs">
              <Sparkles className="h-3 w-3 text-amber-300" />
              {badge}
            </span>
          )}
          <span className="font-semibold text-center sm:text-left">{text}</span>
          {ctaLabel && (
            <a
              href={ctaUrl}
              className="inline-flex items-center gap-1 font-bold text-white underline underline-offset-4 hover:text-amber-200 transition-colors ml-1"
            >
              <span>{ctaLabel}</span>
              <ArrowRight className="h-3 w-3" />
            </a>
          )}
        </div>

        <button
          onClick={handleDismiss}
          aria-label="Dismiss announcement"
          className="p-1 rounded-lg hover:bg-white/20 text-white/80 hover:text-white transition-colors"
        >
          <X className="h-3.5 w-3.5" />
        </button>
      </div>
    </div>
  );
}
