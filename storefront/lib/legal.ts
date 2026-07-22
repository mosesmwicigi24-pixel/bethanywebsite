/* Legal / policy documents rendered at /policies/[slug].
   Sensible defaults for a Kenyan church-supplies store — review with counsel
   and edit as your terms evolve. Company details come from lib/site.ts. */

import { SITE } from "@/lib/site";

export interface LegalSection {
  h: string;
  p: string[];
}
export interface LegalDoc {
  slug: string;
  title: string;
  updated: string;
  intro: string;
  sections: LegalSection[];
}

const contact = `You can reach us at ${SITE.email}${SITE.phone ? ` or ${SITE.phone}` : ""}, or visit ${SITE.address}, ${SITE.city}.`;

export const LEGAL: Record<string, LegalDoc> = {
  terms: {
    slug: "terms",
    title: "Terms of Service",
    updated: "July 2026",
    intro: `These Terms govern your use of ${SITE.name} and any purchase you make from us. By placing an order you agree to them.`,
    sections: [
      { h: "1. Who we are", p: [`${SITE.name} supplies Holy Communion elements, clergy apparel and Christian gifts, serving churches across East Africa from Nairobi, Kenya. ${contact}`] },
      { h: "2. Orders & acceptance", p: ["An order is an offer to buy. We accept it when we confirm it and, where required, receive payment. We may decline or cancel an order — for example if an item is unavailable, mispriced, or an address cannot be verified — and will refund any amount already paid."] },
      { h: "3. Pricing & payment", p: ["Prices are shown in Kenyan Shillings (KES); USD and other currencies are indicative conversions for reference. The KES amount confirmed at checkout is the amount charged. We accept M-Pesa, card and, where offered, cash on delivery. Card details are handled by our payment processor and never stored on our servers."] },
      { h: "4. Made-to-order items", p: ["Vestments, gowns and other tailored items are produced to the measurements you supply. Because they are made specifically for you, please provide measurements carefully — see our Returns & Refunds policy for what applies to custom items."] },
      { h: "5. Delivery", p: ["Delivery times and fees are described in our Shipping & Delivery policy. Risk in the goods passes to you on delivery."] },
      { h: "6. Your responsibilities", p: ["You agree to give accurate account, contact and delivery information, and not to use the site unlawfully or to interfere with its operation."] },
      { h: "7. Liability", p: ["Nothing in these Terms excludes liability that cannot be excluded under Kenyan law. Otherwise, our liability for any order is limited to the amount you paid for it. We are not liable for indirect or consequential loss."] },
      { h: "8. Governing law", p: ["These Terms are governed by the laws of Kenya, and the courts of Kenya have jurisdiction over any dispute."] },
      { h: "9. Changes", p: ["We may update these Terms from time to time; the version in force is the one published here when you order."] },
    ],
  },
  privacy: {
    slug: "privacy",
    title: "Privacy Policy",
    updated: "July 2026",
    intro: `This policy explains what personal information ${SITE.name} collects, why, and your rights over it.`,
    sections: [
      { h: "1. What we collect", p: ["Details you give us — name, phone, email, church/parish, delivery address, order and measurement details — and basic technical data such as your device and pages viewed, to run and improve the store."] },
      { h: "2. How we use it", p: ["To process and deliver your orders, provide support, prevent fraud, meet legal obligations, and — only where you have opted in — send occasional offers. We do not sell your personal data."] },
      { h: "3. Payments", p: ["Payments are processed by our payment providers (e.g. M-Pesa and card processors). We receive confirmation and a reference, not your full card number."] },
      { h: "4. Sharing", p: ["We share data only with providers who help us operate — delivery partners, payment processors and IT services — under confidentiality, and with authorities where the law requires."] },
      { h: "5. Retention & security", p: ["We keep order records as long as needed for accounting, warranty and legal purposes, and take reasonable technical and organisational measures to protect your data."] },
      { h: "6. Your rights", p: [`Under the Data Protection Act, 2019 (Kenya) you may request access to, correction or deletion of your data, or object to certain uses. ${contact}`] },
      { h: "7. Cookies", p: ["We use essential cookies to keep the cart and session working. Any non-essential analytics are used only in aggregate."] },
    ],
  },
  returns: {
    slug: "returns",
    title: "Returns & Refunds",
    updated: "July 2026",
    intro: `We want every parish to be glad of its order. This policy explains when and how you can return an item to ${SITE.name}.`,
    sections: [
      { h: "1. Ready-made items", p: ["Unused ready-made goods in their original condition and packaging may be returned within 7 days of delivery for an exchange or refund of the item price. Please keep your order number."] },
      { h: "2. Made-to-order items", p: ["Vestments, gowns and other items tailored to your measurements cannot be returned for a change of mind, as they are made specifically for you. If a made-to-order item is faulty or not made to the measurements supplied, we will repair, remake or refund it."] },
      { h: "3. Faulty or incorrect items", p: ["If an item arrives damaged, faulty or not what you ordered, tell us within 48 hours with a photo and we will arrange a replacement, repair or full refund including return delivery."] },
      { h: "4. How to start a return", p: [`Contact us at ${SITE.email}${SITE.phone ? ` or ${SITE.phone}` : ""} with your order number and reason. We will confirm the return address and next steps.`] },
      { h: "5. Refunds", p: ["Approved refunds are made to your original payment method (M-Pesa or card) once we receive and inspect the item, normally within 7–14 days."] },
    ],
  },
  shipping: {
    slug: "shipping",
    title: "Shipping & Delivery",
    updated: "July 2026",
    intro: `How and when ${SITE.name} delivers your order.`,
    sections: [
      { h: "1. Nairobi", p: ["Same-day or next-day delivery within Nairobi for orders placed before 2 PM, subject to stock. Free delivery in Nairobi CBD for orders above KES 10,000; a small fee applies below that or outside the CBD, confirmed at dispatch."] },
      { h: "2. Across Kenya & East Africa", p: ["We ship countrywide and across East Africa via trusted couriers, typically 2–4 working days depending on destination. Fees are quoted before dispatch."] },
      { h: "3. Made-to-order lead time", p: ["Tailored vestments and gowns are usually ready in 5–7 working days from confirmed measurements, then delivered as above. We will tell you the expected date when you order."] },
      { h: "4. International", p: ["We ship worldwide; freight and any duties are quoted before dispatch. Import taxes in the destination country are the recipient's responsibility."] },
      { h: "5. Tracking", p: ["You will receive updates by SMS/WhatsApp, and you can check your order status any time from the Track Your Order page."] },
    ],
  },
};

export const LEGAL_SLUGS = Object.keys(LEGAL);
