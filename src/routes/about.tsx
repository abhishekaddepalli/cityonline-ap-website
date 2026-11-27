import { createFileRoute } from "@tanstack/react-router";
import { ArrowRight } from "lucide-react";
import { btnClass, PageHero, SectionHeading } from "@/components/site/primitives";
import { AboutBlock, Benefits } from "@/components/site/sections";
import { LINKS, ext } from "@/lib/links";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/about")({
  head: () => seo("About City Online AP", "Kakinada's own fibre broadband for 10+ years. Our own network and our own local team — no franchise in between.", "/about"),
  component: () => (
    <>
      <PageHero eyebrow="About us" title="Kakinada's own fibre broadband" sub="City Online AP Pvt Ltd has served homes and businesses in Kakinada for over 10 years, on a network we built and look after ourselves." />
      <AboutBlock withLink={false} />
      <section className="bg-secondary/50 py-4"><Benefits /></section>
      <section className="container-x py-16 text-center">
        <SectionHeading title="Ready to join?" sub="Book your connection online and our team will be in touch." />
        <a href={LINKS.newConnection} {...ext} className={btnClass("orange", "h-12 px-8")}>Get New Connection <ArrowRight className="h-4 w-4" /></a>
      </section>
    </>
  ),
});
