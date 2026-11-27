import { Link } from "@tanstack/react-router";
import { ArrowLeft } from "lucide-react";
import type { ReactNode } from "react";
import { PageHero } from "@/components/site/primitives";
import { TOOLS } from "@/components/site/nav-data";

export function ToolPage({ title, sub, children, current }: { title: string; sub: string; children: ReactNode; current: string }) {
  return (
    <>
      <PageHero eyebrow="Internet Tools" title={title} sub={sub}>
        <Link to="/tools" className="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-navy-foreground/80 hover:text-accent"><ArrowLeft className="h-4 w-4" /> All tools</Link>
      </PageHero>
      <div className="container-x relative py-8 sm:py-10">{children}</div>
      <div className="container-x pb-16">
        <p className="mb-3 text-sm font-bold text-muted-foreground">More tools</p>
        <div className="flex flex-wrap gap-2">
          {TOOLS.filter((t) => t.to !== current).map((t) => (
            <Link key={t.to} to={t.to} className="inline-flex items-center gap-2 rounded-full border border-border bg-card px-4 py-2 text-sm font-semibold hover:border-accent hover:text-accent"><t.icon className="h-4 w-4 text-accent" />{t.label}</Link>
          ))}
        </div>
      </div>
    </>
  );
}
