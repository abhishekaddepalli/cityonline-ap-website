import { QueryClient } from "@tanstack/react-query";
import { createRouter, rootRouteId } from "@tanstack/react-router";
import { describe, expect, it } from "vitest";

import { routeTree } from "@/routeTree.gen";

const PATHS = ["/", "/plans", "/coverage", "/tools", "/tools/speed-test", "/tools/ip-lookup", "/tools/dns-lookup", "/tools/ping-test", "/tools/wifi-troubleshooter", "/tools/bandwidth-calculator", "/help", "/about", "/contact"];

describe("App routing", () => {
  it.each(PATHS)("matches a page for %s", (p) => {
    const router = createRouter({ routeTree, context: { queryClient: new QueryClient() } });
    expect(router.matchRoutes(p).at(-1)?.routeId).not.toBe(rootRouteId);
  });
});
