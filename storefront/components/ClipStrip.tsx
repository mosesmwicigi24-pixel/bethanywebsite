import Link from "next/link";
import Rail from "./Rail";
import HoverVideo from "./HoverVideo";
import { Price } from "./Money";
import type { Product } from "@/lib/products";

/**
 * Homepage strip of every product that has a clip — portrait tiles that
 * play on hover (desktop) or while in view (phones), each opening the
 * /clips feed on that product. The reel above already leads with clips on
 * phones; this is the entry point for tablet and desktop, where the reel
 * is not shown, and it grows on its own as clips are uploaded in the Hub.
 *
 * Nothing is rendered until there are at least two clips: one tile is
 * not a strip.
 */
export default function ClipStrip({ products }: { products: Product[] }) {
  const clips = products.filter((p) => p.video);
  if (clips.length < 2) return null;
  return (
    <section className="section wrap clipstrip" aria-labelledby="clipstrip-h">
      <div className="section-head">
        <div>
          <span className="section-eyebrow">Watch</span>
          <h2 id="clipstrip-h">See them move.</h2>
        </div>
        <Link href="/clips">All clips →</Link>
      </div>
      <Rail navInWrap>
        {clips.map((p) => (
          <Link
            key={p.slug}
            className="clip-tile"
            href={`/clips#${p.slug}`}
            aria-label={`Watch the ${p.name} clip`}
          >
            <HoverVideo src={p.img} video={p.video} alt={p.name} />
            <span className="clip-tile-txt">
              <b>{p.name}</b>
              {p.price > 0 && <span><Price p={p} /></span>}
            </span>
          </Link>
        ))}
      </Rail>
    </section>
  );
}
