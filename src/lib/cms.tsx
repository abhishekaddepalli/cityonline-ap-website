import React, { createContext, useContext, useEffect, useState } from "react";
import { PLANS, CONTACT, LINKS } from "./links";
import { AREAS } from "@/components/site/sections";

export interface PlanItem {
  id: string;
  name: string;
  speed: number;
  price: string;
  base: string;
  note: string;
  href: string;
  popular: boolean;
  features?: string[];
}

export interface CmsState {
  plans: PlanItem[];
  localities: string[];
  settings: Record<string, string>;
  links: Record<string, string>;
  loaded: boolean;
  submitEnquiry: (data: EnquiryData) => Promise<{ success: boolean; message: string; enquiry_id?: number }>;
  submitSupportTicket: (data: SupportTicketData) => Promise<{ success: boolean; message: string; ticket_number?: string }>;
}

export interface EnquiryData {
  customer_name: string;
  mobile_number: string;
  email_address?: string;
  locality?: string;
  address?: string;
  preferred_plan?: string;
  enquiry_type?: string;
  notes?: string;
}

export interface SupportTicketData {
  customer_name: string;
  phone: string;
  email?: string;
  customer_id?: string;
  issue_category: string;
  subject: string;
  description: string;
  priority?: string;
}

const defaultPlans: PlanItem[] = PLANS.map((p) => ({
  id: p.id,
  name: p.name,
  speed: p.speed,
  price: p.price,
  base: p.base,
  note: p.note,
  href: p.href,
  popular: p.popular,
  features: ["Unlimited data, no FUP", "Same upload & download speed", "Faults attended in 24 hours"],
}));

const CmsContext = createContext<CmsState>({
  plans: defaultPlans,
  localities: AREAS,
  settings: {},
  links: {},
  loaded: false,
  submitEnquiry: async () => ({ success: false, message: "CMS not initialized" }),
  submitSupportTicket: async () => ({ success: false, message: "CMS not initialized" }),
});

export function CmsProvider({ children }: { children: React.ReactNode }) {
  const [plans, setPlans] = useState<PlanItem[]>(defaultPlans);
  const [localities, setLocalities] = useState<string[]>(AREAS);
  const [settings, setSettings] = useState<Record<string, string>>({});
  const [links, setLinks] = useState<Record<string, string>>({});
  const [loaded, setLoaded] = useState(false);

  useEffect(() => {
    let isMounted = true;

    async function fetchCmsData() {
      try {
        const res = await fetch("/api/v1/init");
        if (!res.ok) return;
        const data = await res.json();
        if (!isMounted) return;

        if (Array.isArray(data.plans) && data.plans.length > 0) {
          const mappedPlans: PlanItem[] = data.plans.map((p: any) => {
            const rawPrice = p.price_monthly ?? p.price_inr ?? p.price ?? 0;
            const numericPrice = Math.round(Number(rawPrice)) || 0;
            const formattedPrice = numericPrice > 0 ? numericPrice.toLocaleString("en-IN") : String(p.price || "");
            const baseText = p.base_price || (p.tax_inclusive ? `₹${formattedPrice} incl. GST` : `₹${formattedPrice} + GST`);
            return {
              id: p.slug || String(p.id),
              name: p.name,
              speed: Number(p.speed_mbps) || 0,
              price: formattedPrice,
              base: baseText,
              note: p.note || p.description || (p.is_popular ? "Most popular choice in Kakinada" : "Reliable fibre broadband"),
              href: p.cta_url || p.portal_url || (p.slug && (LINKS.plan as any)[p.slug]) || LINKS.newConnection,
              popular: Boolean(p.is_popular),
              features: Array.isArray(p.features) && p.features.length > 0 ? p.features : ["Unlimited data, no FUP", "Same upload & download speed", "Faults attended in 24 hours"],
            };
          });
          setPlans(mappedPlans);
        }

        if (Array.isArray(data.localities) && data.localities.length > 0) {
          setLocalities(data.localities);
        }

        if (data.settings && typeof data.settings === "object") {
          setSettings(data.settings);
        }

        if (data.links && typeof data.links === "object") {
          setLinks(data.links);
        }

        setLoaded(true);
      } catch {
        // Graceful fallback to static defaults already in state
      }
    }

    fetchCmsData();

    return () => {
      isMounted = false;
    };
  }, []);

  const submitEnquiry = async (data: EnquiryData) => {
    try {
      const res = await fetch("/api/v1/enquiries", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(data),
      });
      return await res.json();
    } catch (err: any) {
      return { success: false, message: err.message || "Failed to submit enquiry. Please try again or call us." };
    }
  };

  const submitSupportTicket = async (data: SupportTicketData) => {
    try {
      const res = await fetch("/api/v1/support", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(data),
      });
      return await res.json();
    } catch (err: any) {
      return { success: false, message: err.message || "Failed to submit support ticket. Please call support." };
    }
  };

  return (
    <CmsContext.Provider value={{ plans, localities, settings, links, loaded, submitEnquiry, submitSupportTicket }}>
      {children}
    </CmsContext.Provider>
  );
}

export function useCms() {
  return useContext(CmsContext);
}
