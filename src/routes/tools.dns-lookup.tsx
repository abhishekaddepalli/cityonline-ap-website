import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { DnsLookup } from "@/components/tools/NetTools";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/dns-lookup")({
  head: () => seo("DNS Lookup", "Look up A, AAAA, MX, TXT, NS and other DNS records for any domain.", "/tools/dns-lookup"),
  component: () => (
    <ToolPage current="/tools/dns-lookup" title="DNS Lookup" sub="Look up A, AAAA, MX, TXT, NS and other DNS records for any domain.">
      <DnsLookup />
    </ToolPage>
  ),
});
