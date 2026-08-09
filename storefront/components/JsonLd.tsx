/** Renders a JSON-LD structured-data block. `<` is escaped to < to
    neutralise XSS via injected strings (per the Next.js JSON-LD guide).
    Server component — no client JS is shipped. */
export default function JsonLd({ data }: { data: object }) {
  return (
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(data).replace(/</g, "\\u003c") }}
    />
  );
}
