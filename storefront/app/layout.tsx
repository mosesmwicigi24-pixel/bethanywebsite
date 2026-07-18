import type { Metadata } from "next";
import { Plus_Jakarta_Sans, Fraunces } from "next/font/google";
import { UtilityBar, Nav, Footer, ChatFab } from "@/components/chrome";
import { CartProvider } from "@/lib/cart";
import CartDrawer from "@/components/CartDrawer";
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

export const metadata: Metadata = {
  title: {
    default: "Bethany House — Communion Elements, Clergy Apparel & Christian Gifts",
    template: "%s | Bethany House",
  },
  description:
    "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts — serving churches across East Africa from Nairobi.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`${jakarta.variable} ${fraunces.variable}`}>
      <body>
        <CartProvider>
          <UtilityBar />
          <Nav />
          {children}
          <Footer />
          <ChatFab />
          <CartDrawer />
        </CartProvider>
      </body>
    </html>
  );
}
