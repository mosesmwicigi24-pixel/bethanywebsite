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
      // Known legacy category slugs map to the real /category pages; any OTHER
      // unknown /category/* address is caught by app/category/[slug]/page.tsx,
      // which permanentRedirects it to /shop. (No catch-all here — config
      // redirects run before routes and would shadow the live category pages.)
      {
        source: "/category/clergy-vestments-fofem6",
        destination: "/category/clergy-vestments",
        permanent: true,
      },

      // Dead legacy product slugs verified in Google's index (404 today).
      // Extend this list from Search Console's "Not found (404)" report.
      { source: "/product/alb-letmgph3a", destination: "/product/alb", permanent: true },
      { source: "/product/altar-wine-2o92iheke", destination: "/product/altar-wine", permanent: true },

      // Legacy listing page still in Google's index (was 404ing away its equity).
      { source: "/new-arrivals", destination: "/shop", permanent: true },

      // Old-site static pages (Search Console 404 report, 2026-08-23) →
      // their new-site equivalents.
      { source: "/about-us", destination: "/about", permanent: true },
      { source: "/contact-us", destination: "/contact", permanent: true },
      { source: "/faqs", destination: "/faq", permanent: true },
      { source: "/how-to-shop", destination: "/faq", permanent: true },
      { source: "/privacy-policy", destination: "/policies/privacy", permanent: true },
      { source: "/terms-and-conditions", destination: "/policies/terms", permanent: true },
      { source: "/special-offers", destination: "/shop", permanent: true },
      { source: "/featured", destination: "/shop", permanent: true },
      { source: "/home", destination: "/", permanent: true },
      { source: "/index.html", destination: "/", permanent: true },
      { source: "/shops", destination: "/shop", permanent: true },
      { source: "/shop.I", destination: "/shop", permanent: true },
      { source: "/account/track", destination: "/orders", permanent: true },
      { source: "/brand/:slug", destination: "/shop", permanent: true },
      // Natural guess-URL (people type it; /categories/ covered via slash strip).
      { source: "/categories", destination: "/shop", permanent: true },

      // Overrides for hub slug-redirects that point at the wrong product —
      // their hub records were reused for unrelated items, so the old slug
      // now follows the record to an absurd destination (a Bible landing on
      // a collar). Config redirects win over the dynamic resolver.
      { source: "/product/preaching-gown-1", destination: "/product/preaching-gown-843glv9rc", permanent: true },
      { source: "/product/anointing-oil-eliad-olive-oil-750ml", destination: "/product/eliad-anointing-oil", permanent: true },
      { source: "/product/the-carry-along-bible", destination: "/category/bibles-devotionals", permanent: true },
      { source: "/product/the-carry-along-bible-9co393v3r", destination: "/category/bibles-devotionals", permanent: true },
      { source: "/product/the-carry-along-bible-9co393v3r--v:id", destination: "/category/bibles-devotionals", permanent: true },
    ];
  },
};

export default nextConfig;
