import Link from "next/link";

export type Crumb = { label: string; href?: string };

/** Breadcrumb trail — last item rendered bold, others as links. */
export default function Crumbs({ items }: { items: Crumb[] }) {
  return (
    <div className="crumbs">
      {items.map((c, i) => (
        <span key={i} style={{ display: "contents" }}>
          {i > 0 && <span className="sep">»</span>}
          {c.href ? <Link href={c.href}>{c.label}</Link> : <b>{c.label}</b>}
        </span>
      ))}
    </div>
  );
}
