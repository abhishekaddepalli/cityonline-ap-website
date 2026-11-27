import { createFileRoute } from "@tanstack/react-router";
import { Check } from "lucide-react";
import { PageHero, Reveal } from "@/components/site/primitives";
import { PlansGrid } from "@/components/site/sections";
import { PLANS } from "@/lib/links";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/plans")({
  head: () => seo("Broadband Plans & Prices", "Standard 60 Mbps ₹590, Premium 100 Mbps ₹767 and Ultra 150 Mbps ₹1,180 per month, GST included. Unlimited data on every plan.", "/plans"),
  component: Plans,
});

function Plans() {
  return (
    <>
      <PageHero eyebrow="Broadband plans" title="Choose the Right Plan for You" sub="Unlimited data with no FUP on every plan. Same speed up and down. Every price includes 18% GST." />
      <section className="container-x py-16"><PlansGrid /></section>
      <section className="container-x pb-16">
        <Reveal>
          <h2 className="mb-6 text-2xl font-extrabold text-primary">Compare plans</h2>
          <div className="overflow-x-auto rounded-2xl border border-border bg-card shadow-soft">
            <table className="w-full min-w-[520px] text-left text-sm">
              <thead className="bg-secondary text-xs uppercase tracking-wider text-muted-foreground">
                <tr><th className="p-4">Feature</th>{PLANS.map((p) => <th key={p.id} className="p-4 text-primary">{p.name}</th>)}</tr>
              </thead>
              <tbody>
                {[
                  ["Download speed", ...PLANS.map((p) => `${p.speed} Mbps`)],
                  ["Upload speed", ...PLANS.map((p) => `${p.speed} Mbps`)],
                  ["Monthly price (incl. GST)", ...PLANS.map((p) => `₹${p.price}`)],
                  ["Price before GST", ...PLANS.map((p) => p.base.split(" +")[0])],
                  ["Best for", ...PLANS.map((p) => p.note)],
                ].map((row) => (
                  <tr key={row[0]} className="border-t border-border">{row.map((c, i) => <td key={i} className={i === 0 ? "p-4 font-semibold" : "p-4"}>{c}</td>)}</tr>
                ))}
                <tr className="border-t border-border"><td className="p-4 font-semibold">Unlimited data, no FUP</td>{PLANS.map((p) => <td key={p.id} className="p-4"><Check className="h-5 w-5 text-accent" /></td>)}</tr>
              </tbody>
            </table>
          </div>
        </Reveal>
      </section>
    </>
  );
}
