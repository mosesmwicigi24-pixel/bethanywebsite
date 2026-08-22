import type { Metadata } from "next";

/* /orders is a customer utility (order history on this device) with no search
   value; keep it out of the index. Metadata lives in this layout because the
   page itself is a client component and can't export it. */
export const metadata: Metadata = {
  title: "My Orders",
  robots: { index: false, follow: true },
};

export default function OrdersLayout({ children }: { children: React.ReactNode }) {
  return children;
}
