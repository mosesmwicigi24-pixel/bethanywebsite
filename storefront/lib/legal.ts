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
  "user-policy": {
    slug: "user-policy",
    title: "User Policy",
    updated: "August 2026",
    intro: `This policy sets out how our website, our chat and our services may be used, so that ${SITE.name} stays safe and fair for every church and customer we serve. It sits alongside our Terms of Service and Privacy Policy.`,
    sections: [
      {
        h: "1. Who this applies to",
        p: [
          "It applies to everyone who uses our website — browsing the catalogue, chatting with our assistant, submitting a review or placing an order — whether you are a parish, a minister, an institution or an individual.",
          "By using the site, you agree to use it in the way described here.",
        ],
      },
      {
        h: "2. The information you give us",
        p: [
          "Please give accurate contact, delivery and measurement details, and keep them up to date. Made-to-order garments are cut to the measurements you supply and deliveries are made to the address you give us, so mistakes there are difficult and costly to undo.",
          "Please do not use another person's name, phone number or payment details without their permission, or present yourself as acting for a church or organisation that has not authorised you.",
        ],
      },
      {
        h: "3. Using the site fairly",
        p: [
          "You are welcome to browse, search, request quotations and order for yourself, for your parish, or for the institution you represent.",
          "Please do not use the site unlawfully or in a way that harms others. In particular: do not try to reach accounts, data or systems that are not yours; do not interfere with the site's operation or security; do not use automated tools to scrape, copy or overload it; and do not upload anything containing malware.",
        ],
      },
      {
        h: "4. Using our assistant, Neema",
        p: [
          "Neema is here to help you find items, ask questions and prepare an order. Please use it for that purpose and keep the conversation courteous.",
          "Please do not share other people's personal information in the chat, or anything confidential that isn't needed for your order, and do not attempt to misuse the assistant or use it to produce content unrelated to our products.",
          "Neema is an automated assistant and can occasionally be mistaken. Prices, availability, measurements and delivery timelines it gives are guidance until a member of our team confirms them — a confirmed order or invoice from us is what counts.",
        ],
      },
      {
        h: "5. Reviews and anything you post",
        p: [
          "Reviews should reflect genuine experience of a product you bought or used. Please don't post anything false, abusive, obscene or defamatory, anything that infringes someone else's rights, or a review written on behalf of a competitor or in exchange for reward.",
          "Reviews are moderated before they appear, and we may decline or remove one that breaks this policy. By submitting a review you allow us to display it on the site alongside the name you choose to show.",
        ],
      },
      {
        h: "6. Orders placed in good faith",
        p: [
          "Please order only what you intend to buy and receive. Repeatedly placing orders that are abandoned or refused on delivery, or misusing our returns policy, may lead us to ask for payment in advance or to decline future orders.",
          "Where we suspect fraud, or a payment we cannot verify, we may hold or cancel an order and refund any amount already paid.",
        ],
      },
      {
        h: "7. Our catalogue and images",
        p: [
          "The photographs, descriptions and designs on this site belong to us or to our suppliers. You are welcome to share links to our pages, and to use our product images when ordering or recommending an item to your parish.",
          "Please do not copy our catalogue, photographs or descriptions to sell the same goods elsewhere, or present our work as your own, without our written permission.",
        ],
      },
      {
        h: "8. If this policy is broken",
        p: ["Where the site is used in a way that breaks this policy, we may remove the content concerned, restrict or suspend access, decline or cancel orders, and report the matter to the authorities where the law requires it. We will act proportionately, and where it is reasonable to do so we will tell you why."],
      },
      {
        h: "9. How this fits with our other policies",
        p: ["This policy sits alongside our Terms of Service, which govern your purchase, and our Privacy Policy, which explains how we handle your personal information. Where a genuine conflict arises about a purchase, the Terms of Service prevail."],
      },
      {
        h: "10. Changes and contact",
        p: [`We may update this policy as our services change; the version published on this page is the one in force. If anything here is unclear, or you would like to report misuse, please tell us. ${contact}`],
      },
    ],
  },
  "data-deletion": {
    slug: "data-deletion",
    title: "User Data Deletion",
    updated: "August 2026",
    intro: `You can ask ${SITE.name} to delete the personal information we hold about you at any time. This page explains how to request it, what we delete, and how long it takes.`,
    sections: [
      {
        h: "1. How to request deletion",
        p: [
          `Send us a deletion request by any of these routes: email ${SITE.email}; WhatsApp or call ${SITE.phone} or ${SITE.phone2}; or visit us at ${SITE.address}, ${SITE.city}.`,
          'Please write "Data deletion request" in your message, and include the name and the phone or WhatsApp number you used with us, so that we can find your records. If you contacted us through WhatsApp, Messenger, Facebook or Instagram, please send the request from — or tell us — the number or account you used there.',
          "We may ask one question to confirm your identity before we delete anything. This protects you: it stops somebody else from deleting your records.",
        ],
      },
      {
        h: "2. What we delete",
        p: [
          "Your customer profile and contact details; your saved measurements and sizes; your conversations with our assistant across our website, WhatsApp, Messenger, Facebook and Instagram; any saved cart or enquiry; and your marketing preferences.",
          "Reviews you have posted are removed or, where the review must remain for other customers, permanently separated from your name and contact details so that it can no longer identify you.",
        ],
      },
      {
        h: "3. What we must keep, and why",
        p: [
          "Kenyan tax and accounting law requires us to retain records of completed sales — invoices, receipts and payment references — for the period the law prescribes, and we cannot delete those earlier. We keep only what the law requires, we stop using it for anything else, and we delete it when that period ends.",
          "We may also retain the minimum needed to establish or defend a legal claim, or to comply with an order from a court or regulator.",
        ],
      },
      {
        h: "4. How long it takes",
        p: [
          "We act on deletion requests promptly and complete them within 30 days. If a request is complex and we need longer, we will tell you and explain why.",
          "We will confirm to you in writing once the deletion is done.",
        ],
      },
      {
        h: "5. Your other rights",
        p: [
          `Deletion is one of several rights you hold under the Data Protection Act, 2019 (Kenya). You may also ask for a copy of your data, ask us to correct it, object to or restrict a particular use, or withdraw a consent you gave — for example to marketing. Our Privacy Policy explains each of these. ${contact}`,
          "If you are not satisfied with how we handle your request, you may lodge a complaint with the Office of the Data Protection Commissioner (Kenya).",
        ],
      },
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
