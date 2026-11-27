import { createFileRoute } from "@tanstack/react-router";
import { PageHero } from "@/components/site/primitives";
import { ToolsGrid } from "@/components/site/sections";
import { ExternalDiagnostic } from "@/components/tools/NetTools";
import { LINKS } from "@/lib/links";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/")({
  head: () => seo("Free Internet Tools", "Speed test, IP lookup, DNS lookup, ping test, Wi-Fi troubleshooter and bandwidth calculator from City Online AP.", "/tools"),
  component: Tools,
});

function Tools() {
  return (
    <>
      <PageHero eyebrow="Internet Tools" title="Test, check and troubleshoot your connection" sub="Free tools that run in your browser using real public test services. Nothing here is simulated." />
      <section className="container-x py-16"><ToolsGrid /></section>
      <section className="container-x grid gap-4 pb-16 md:grid-cols-2">
        <ExternalDiagnostic title="Traceroute" href={LINKS.traceroute} cta="Open external traceroute">
          A web browser can't send the low-level network packets traceroute needs, so we don't show made-up hop results here. Use a trusted external tool, or run <code className="rounded bg-secondary px-1">tracert example.com</code> (Windows) or <code className="rounded bg-secondary px-1">traceroute example.com</code> (Mac/Linux) on your computer.
        </ExternalDiagnostic>
        <ExternalDiagnostic title="Port Checker" href={LINKS.portChecker} cta="Open external port checker">
          Browsers can't reliably test whether an arbitrary TCP port is open on your connection. An external service can check ports on your public IP from the outside.
        </ExternalDiagnostic>
      </section>
    </>
  );
}
