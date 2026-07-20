import type { Metadata, Viewport } from "next";
import { Plus_Jakarta_Sans, Fraunces } from "next/font/google";
import { UtilityBar, Nav, Footer, ChatFab } from "@/components/chrome";
import { CartProvider } from "@/lib/cart";
import { CurrencyProvider } from "@/lib/currency";
import { CatalogProvider } from "@/lib/catalogClient";
import { getCatalog } from "@/lib/catalog";
import CartDrawer from "@/components/CartDrawer";
import JsonLd from "@/components/JsonLd";
import { SITE } from "@/lib/site";
import { organizationJsonLd } from "@/lib/seo";
import "./globals.css";

const jakarta = Plus_Jakarta_Sans({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700", "800"],
  variable: "--font-jakarta",
});

const fraunces = Fraunces({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
  style: ["normal", "italic"],
  variable: "--font-fraunces",
});

// viewport-fit=cover enables iOS safe-area insets (Dynamic Island +
// home indicator); themeColor matches the navy nav behind the status bar.
export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  viewportFit: "cover",
  themeColor: "#0a1425",
};

export const metadata: Metadata = {
  // Absolute-URL base for canonical/OG links across every route.
  metadataBase: new URL(SITE.url),
  title: {
    default: "Bethany House — Communion Elements, Clergy Apparel & Christian Gifts",
    template: "%s | Bethany House",
  },
  description:
    "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts — serving churches across East Africa from Nairobi.",
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    siteName: SITE.name,
    title: "Bethany House — Communion Elements, Clergy Apparel & Christian Gifts",
    description:
      "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts — worldwide shipping from Nairobi.",
    url: SITE.url,
    locale: "en_KE",
    images: [{ url: "/products/gold-wares.jpg", width: 1200, height: 630, alt: SITE.name }],
  },
  twitter: {
    card: "summary_large_image",
    title: "Bethany House — Communion Elements, Clergy Apparel & Christian Gifts",
    description: "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts.",
    images: ["/products/gold-wares.jpg"],
  },
};

export default async function RootLayout({ children }: { children: React.ReactNode }) {
  const catalog = await getCatalog();
  return (
    <html lang="en" className={`${jakarta.variable} ${fraunces.variable}`}>
      <body>
        <JsonLd data={organizationJsonLd()} />
        <CurrencyProvider>
        <CatalogProvider catalog={catalog}>
        <CartProvider>
          <UtilityBar />
          <Nav />
          {children}
          <Footer />
          <ChatFab />
          <CartDrawer />
        </CartProvider>
        </CatalogProvider>
        </CurrencyProvider>
      </body>
    </html>
  );
}
