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
    updated: "August 2026",
    intro: `This policy explains what personal information ${SITE.name} collects, why we collect it, who we share it with, and the rights you have over it under the Data Protection Act, 2019 (Kenya).`,
    sections: [
      {
        h: "1. What we collect",
        p: [
          "Details you give us — your name, phone or WhatsApp number, email, church or parish, delivery address, and the items, sizes and measurements needed to prepare your order.",
          "What you send our assistant — the messages you write in our chat, and any photo you choose to upload for a measurement estimate.",
          "Ratings and reviews you submit for a product, together with the name you choose to show alongside them.",
          "Basic technical data such as your device type, your approximate country (used to show you the right currency and delivery information) and the pages you view, to run, secure and improve the store.",
        ],
      },
      {
        h: "2. How we use it",
        p: [
          "To answer your questions, prepare quotations, process and deliver your orders, tailor made-to-order garments to the measurements you give us, provide support, prevent fraud, meet our legal and accounting obligations, and — only where you have opted in — send occasional offers.",
          "We do not sell your personal data, and we do not rent or trade it for advertising.",
        ],
      },
      {
        h: "3. Our assistant, Neema",
        p: [
          "Neema is an AI assistant that helps you find items, answer questions and prepare an order. To produce a reply, the text of your conversation is sent to the AI provider that powers it. Please don't share sensitive personal information in the chat that isn't needed for your order.",
          "If you upload a photo for a measurement estimate, it is sent to the AI provider solely to produce those estimates. It is not stored on our servers and is not added to your profile. The estimates are only a starting point — you confirm the final measurements before anything is cut or sewn.",
          "Contact details you give the assistant, such as your name and phone number, are saved to your customer record so that we can follow up on your enquiry or order.",
        ],
      },
      { h: "4. Payments", p: ["Payments are processed by our payment providers (for example M-Pesa and our card processor). We receive a confirmation and a payment reference — not your full card number, which never reaches our servers."] },
      {
        h: "5. Who we share it with",
        p: [
          "Only with those who help us serve you: delivery and courier partners, payment processors, the AI providers described above, and our hosting and IT suppliers. Each is bound by confidentiality and may use your data only for the service they provide to us.",
          "We also disclose data where the law requires it, or where it is necessary to establish or defend a legal claim.",
        ],
      },
      { h: "6. Transfers outside Kenya", p: ["Some of our providers — including our hosting and AI providers — process data on servers outside Kenya. Where that happens we rely on the safeguards permitted by the Data Protection Act, 2019, including contractual commitments requiring them to protect your data and to process it only on our instructions."] },
      {
        h: "7. Retention & security",
        p: [
          "We keep order and accounting records for as long as the law requires, and other details only for as long as they remain useful to serve you. Photos uploaded for a measurement estimate are not retained on our servers.",
          "We take reasonable technical and organisational measures to protect your data, including encrypted connections and access limited to staff who need it.",
        ],
      },
      {
        h: "8. Your rights",
        p: [
          "Under the Data Protection Act, 2019 you may ask us for a copy of the data we hold about you, ask us to correct or delete it, object to or restrict how we use it, or withdraw a consent you have given — for example, to marketing messages.",
          `${contact} We will respond within the time the law allows.`,
          "If you are not satisfied with our response, you may lodge a complaint with the Office of the Data Protection Commissioner (Kenya).",
        ],
      },
      { h: "9. Cookies", p: ["We use essential cookies to keep your cart, session and preferences working. Any analytics we use are reviewed in aggregate and not used to identify you personally."] },
      { h: "10. Changes to this policy", p: ["We may update this policy as our services change. The version published on this page is the one in force, and the date shown above is when it was last revised."] },
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
