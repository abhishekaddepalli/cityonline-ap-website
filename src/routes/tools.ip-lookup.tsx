import { createFileRoute } from "@tanstack/react-router";
import { ToolPage } from "@/components/tools/ToolPage";
import { IpLookup } from "@/components/tools/NetTools";
import { seo } from "@/lib/seo";

export const Route = createFileRoute("/tools/ip-lookup")({
  head: () => seo("My IP Address", "See the public IP address your connection is using, and copy it in one tap.", "/tools/ip-lookup"),
  component: () => (
    <ToolPage current="/tools/ip-lookup" title="My IP Address" sub="See the public IP address your connection is using, and copy it in one tap.">
      <IpLookup />
    </ToolPage>
  ),
});
