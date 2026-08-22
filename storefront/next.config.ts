import path from "node:path";
import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Self-contained server bundle for the Docker image (node server.js)
  output: "standalone",
  // Two lockfiles exist up-tree (legacy CI3 app) — pin the workspace root
  // so the standalone bundle isn't nested under storefront/.
  turbopack: { root: path.join(__dirname) },
  outputFileTracingRoot: path.join(__dirname),

  async redirects() {
    return [
      // www → apex. The canonical origin is bethanyhouse.co.ke; www currently
      // serves the site as a duplicate host. 308 = permanent.
      {
        source: "/:path*",
        has: [{ type: "host", value: "www.bethanyhouse.co.ke" }],
        destination: "https://bethanyhouse.co.ke/:path*",
        permanent: true,
      },

      // Legacy-site URLs still in Google's index hard-404 today, discarding
      // rankings the old site earned. Forward them to their nearest live page.
      //
      // ⚠ TEMPORARY TARGET: /category/* currently forwards to /shop because no
      // category landing pages exist yet. When real /category/<slug> pages ship,
      // REPLACE this catch-all with per-slug redirects (redirects run before
      // routes, so leaving it in place would shadow the new pages).
      {
        source: "/category/:path*",
        destination: "/shop",
        permanent: true,
      },

      // Dead legacy product slugs verified in Google's index (404 today).
      // Extend this list from Search Console's "Not found (404)" report.
      { source: "/product/alb-letmgph3a", destination: "/product/alb", permanent: true },
      { source: "/product/altar-wine-2o92iheke", destination: "/product/altar-wine", permanent: true },
    ];
  },
};

export default nextConfig;
