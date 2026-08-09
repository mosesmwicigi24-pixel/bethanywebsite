import Reveal from "@/components/Reveal";

export interface Chapter {
  eyebrow?: string;
  title: string;
  copy?: string;
  img?: string;
  theme?: string; // "dark" (default) | "light"
}

/** Apple-style editorial "chapter" — a cinematic image block with an eyebrow,
    a large headline and a line of copy, revealed on scroll. Product pages can
    stack several (Health / Fitness / Craft…). Content is CMS-managed
    (product_chapter blocks); this renders nothing when there are none. */
export default function EditorialChapter({ c }: { c: Chapter }) {
  const light = c.theme === "light";
  return (
    <Reveal as="section" className={`chapter ${light ? "light" : "dark"}`}>
      {c.img ? <img className="chapter-bg" src={c.img} alt="" /> : null}
      <span className="chapter-scrim" aria-hidden />
      <div className="chapter-inner">
        {c.eyebrow ? <span className="chapter-eyebrow">{c.eyebrow}</span> : null}
        <h2 className="chapter-title">{c.title}</h2>
        {c.copy ? <p className="chapter-copy">{c.copy}</p> : null}
      </div>
    </Reveal>
  );
}
