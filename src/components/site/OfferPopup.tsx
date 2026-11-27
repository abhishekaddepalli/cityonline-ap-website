import { useState, useEffect } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { Check, Copy, MessageCircle, Sparkles, Tag, X, Zap } from "lucide-react";
import { useCms } from "@/lib/cms";
import { ext, LINKS } from "@/lib/links";

export function OfferPopup() {
  const { settings } = useCms();
  const [open, setOpen] = useState(false);
  const [copied, setCopied] = useState(false);

  const isEnabled = settings?.popup_enabled === "1" || settings?.popup_enabled === "true";
  const badge = settings?.popup_badge || "EXCLUSIVE DEAL";
  const title = settings?.popup_title || "⚡ Upgrade to Peddapuram's Own Fibre";
  const subtitle = settings?.popup_subtitle || "Get 100 Mbps True Symmetric Internet with Unlimited Data, No FUP, and same-day installation across Peddapuram & surroundings!";
  const discountCode = settings?.popup_discount_code || "CITYFEST2026";
  const imageUrl = settings?.popup_image_url || "/images/fibre-technician-kakinada.jpg";
  const ctaLabel = settings?.popup_cta_label || "Get New Connection";
  const ctaUrl = settings?.popup_cta_url || "https://customer.cityonlineap.com/book-connection";
  const delaySec = Number(settings?.popup_delay_sec) || 3;
  const displayMode = settings?.popup_display_mode || "once_per_session";

  useEffect(() => {
    if (typeof window === "undefined" || !isEnabled) return;

    if (displayMode === "once_per_session") {
      const alreadySeen = sessionStorage.getItem("city_offer_popup_seen");
      if (alreadySeen) return;
    }

    const timer = setTimeout(() => {
      setOpen(true);
      if (displayMode === "once_per_session") {
        sessionStorage.setItem("city_offer_popup_seen", "true");
      }
    }, delaySec * 1000);

    return () => clearTimeout(timer);
  }, [isEnabled, delaySec, displayMode]);

  const handleCopy = () => {
    if (discountCode) {
      navigator.clipboard.writeText(discountCode);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    }
  };

  if (!open || !isEnabled) return null;

  return (
    <AnimatePresence>
      <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy/60 backdrop-blur-xs">
        <motion.div
          initial={{ opacity: 0, scale: 0.9, y: 20 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          exit={{ opacity: 0, scale: 0.9, y: 20 }}
          transition={{ type: "spring", damping: 25, stiffness: 300 }}
          className="relative w-full max-w-md overflow-hidden rounded-3xl border border-border/80 bg-card shadow-2xl"
        >
          {/* Top Banner Image with Badge */}
          <div className="relative h-44 w-full overflow-hidden bg-navy">
            <img
              src={imageUrl}
              alt="Special Promotion"
              className="h-full w-full object-cover object-center opacity-85"
              onError={(e) => {
                e.currentTarget.src = "/images/fibre-technician-kakinada.jpg";
              }}
            />
            <div className="absolute inset-0 bg-gradient-to-t from-card via-card/30 to-transparent" />

            <button
              onClick={() => setOpen(false)}
              aria-label="Close special offer"
              className="absolute right-3.5 top-3.5 grid h-8 w-8 place-items-center rounded-full bg-navy/70 text-white backdrop-blur-xs transition hover:bg-navy hover:scale-105"
            >
              <X className="h-4 w-4" />
            </button>

            {badge && (
              <span className="absolute left-4 top-4 inline-flex items-center gap-1 rounded-full bg-accent px-3 py-1 text-xs font-black uppercase text-accent-foreground shadow-orange">
                <Sparkles className="h-3.5 w-3.5" />
                {badge}
              </span>
            )}
          </div>

          {/* Offer Content */}
          <div className="p-6 pt-2 space-y-4">
            <div>
              <h3 className="text-xl font-extrabold text-primary leading-tight sm:text-2xl">{title}</h3>
              <p className="mt-2 text-xs sm:text-sm text-muted-foreground leading-relaxed">{subtitle}</p>
            </div>

            {/* Discount Code Box if provided */}
            {discountCode && (
              <div className="flex items-center justify-between rounded-2xl border-2 border-dashed border-accent/40 bg-accent/5 p-3">
                <div className="flex items-center gap-2">
                  <Tag className="h-4 w-4 text-accent" />
                  <div>
                    <span className="text-[10px] font-bold uppercase text-muted-foreground block">Special Promo Code</span>
                    <span className="font-mono text-sm font-black text-accent tracking-wider">{discountCode}</span>
                  </div>
                </div>
                <button
                  type="button"
                  onClick={handleCopy}
                  className="inline-flex items-center gap-1 rounded-xl bg-accent px-3 py-1.5 text-xs font-bold text-accent-foreground shadow-xs transition hover:bg-accent-hover active:scale-95"
                >
                  {copied ? (
                    <>
                      <Check className="h-3.5 w-3.5" />
                      <span>Copied!</span>
                    </>
                  ) : (
                    <>
                      <Copy className="h-3.5 w-3.5" />
                      <span>Copy</span>
                    </>
                  )}
                </button>
              </div>
            )}

            {/* Buttons */}
            <div className="space-y-2 pt-2">
              <a
                href={ctaUrl}
                {...(ctaUrl.startsWith("http") ? ext : {})}
                onClick={() => setOpen(false)}
                className="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-accent text-sm font-bold text-accent-foreground shadow-orange hover:bg-accent-hover transition-transform active:scale-[0.98]"
              >
                <Zap className="h-4 w-4" />
                <span>{ctaLabel}</span>
              </a>

              <a
                href={LINKS.whatsapp}
                {...ext}
                onClick={() => setOpen(false)}
                className="flex h-10 w-full items-center justify-center gap-1.5 rounded-xl border border-border bg-secondary/60 text-xs font-bold text-primary hover:bg-secondary transition"
              >
                <MessageCircle className="h-4 w-4 text-emerald-600" />
                <span>Enquire via WhatsApp</span>
              </a>
            </div>
          </div>
        </motion.div>
      </div>
    </AnimatePresence>
  );
}
