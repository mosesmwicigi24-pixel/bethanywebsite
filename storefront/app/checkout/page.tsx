import type { Metadata } from "next";
import CheckoutClient from "@/components/CheckoutClient";
import WhyBuy from "@/components/WhyBuy";

export const metadata: Metadata = { title: "Checkout" };

export default function CheckoutPage() {
  return (
    <>
      <CheckoutClient />
      <WhyBuy />
    </>
  );
}
