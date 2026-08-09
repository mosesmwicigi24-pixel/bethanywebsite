import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { LEGAL, LEGAL_SLUGS } from "@/lib/legal";

export function generateStaticParams() {
  return LEGAL_SLUGS.map((slug) => ({ slug }));
}

export async function generateMetadata(
  { params }: { params: Promise<{ slug: string }> },
): Promise<Metadata> {
  const { slug } = await params;
  const doc = LEGAL[slug];
  if (!doc) return { title: "Policy" };
  return {
    title: doc.title,
    description: doc.intro,
    alternates: { canonical: `/policies/${slug}` },
  };
}

export default async function PolicyPage(
  { params }: { params: Promise<{ slug: string }> },
) {
  const { slug } = await params;
  const doc = LEGAL[slug];
  if (!doc) notFound();

  return (
    <main className="wrap legal">
      <nav className="crumbs">
        <Link href="/">Home</Link>
        <span className="sep">›</span>
        <b>{doc.title}</b>
      </nav>
      <h1>{doc.title}</h1>
      <p className="legal-updated">Last updated: {doc.updated}</p>
      <p className="legal-intro">{doc.intro}</p>

      {doc.sections.map((s) => (
        <section key={s.h} className="legal-section">
          <h2>{s.h}</h2>
          {s.p.map((para, i) => (
            <p key={i}>{para}</p>
          ))}
        </section>
      ))}

      <p className="legal-more">
        See also:{" "}
        {LEGAL_SLUGS.filter((s) => s !== slug).map((s, i) => (
          <span key={s}>
            {i > 0 ? " · " : ""}
            <Link href={`/policies/${s}`}>{LEGAL[s].title}</Link>
          </span>
        ))}
      </p>
    </main>
  );
}
