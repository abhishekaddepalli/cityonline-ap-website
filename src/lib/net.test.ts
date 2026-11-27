import { describe, expect, it } from "vitest";
import { downloadSeconds, formatDuration, stats } from "./net";

describe("bandwidth calculator", () => {
  it("1 GB at 100 Mbps takes 80 seconds", () => expect(downloadSeconds(1, "GB", 100)).toBe(80));
  it("1 TB at 150 Mbps", () => expect(formatDuration(downloadSeconds(1, "TB", 150))).toBe("14h 48m 53s"));
  it("rejects zero speed", () => expect(downloadSeconds(1, "GB", 0)).toBeNaN());
});
describe("latency stats", () => {
  it("computes min/max/jitter", () => expect(stats([10, 20, 10])).toMatchObject({ min: 10, max: 20, jitter: 10 }));
});
