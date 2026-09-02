# Product clips — hover-to-play cards

The storefront plays a short, silent product clip when a shopper hovers a
product card, and leads the product-page gallery with that clip. This is the
pattern jojosfashion.com uses; this page records what we learned from their
site, what we built, and how to make a clip that fits.

## What jojosfashion.com does (measured 2026-09-02)

**Architecture.** A Vite + React 18 single-page app (TanStack Query, Radix UI,
Tailwind, wouter), served as a PWA with a service worker and the View
Transitions API. The catalogue is fetched client-side from `/api/products`
(guarded by an `X-Jojo-Client: web` header). Product cards, the HLS player and
the mobile video feed are lazy chunks, so the initial bundle carries no video
code. 28 of their 58 products have a clip.

**The card.** `onMouseEnter` (and link focus) on the card sets a hovering flag;
only then is a `<video muted loop playsInline autoPlay preload="auto">`
mounted in place of the `<img>`, with the still as its `poster`. The poster is
kept on screen and cross-faded out over 300 ms once `loadeddata`/`playing`
fires. On leave the video unmounts, so re-entering restarts from the top.
There is no hover-to-play on touch devices; mobile gets a separate vertical
video feed.

**Delivery.** Each product carries three URLs:

| Field | What | Typical weight |
|---|---|---|
| `videoUrl` | the raw phone upload (`.MOV`/`.MP4`, HEVC, 60 fps, with audio, 720×1280 or 1080×1920) | 5–105 MB, median 23 MB |
| `hlsVideoUrl` | a single-rendition HLS playlist, 2-second `.ts` segments | 5.8–7.9 MB per clip, ~2.05 Mbps |
| `videoThumbnail` | 640×1138 JPEG poster | ~34 KB |

The browser plays the HLS rendition (hls.js, lazily loaded, `capLevelToPlayerSize`
on, 20 s buffer) and only falls back to the raw file if HLS fails. So a hover
on their site streams ~2 Mbps of 9:16 video, 22–32 s long, and the raw
uploads are effectively an origin archive.

**What we took, and what we changed.**

- Same interaction: still first, clip mounted on first hover/focus, cross-fade
  once the first frame plays, rewind on leave, nothing on touch.
- No HLS. We have no transcoder in the pipeline and our clips are short loops,
  so a single progressive MP4 at 720 px is smaller than one of their HLS
  segments' worth of buffering and starts faster.
- A 120 ms arm delay so sweeping the cursor across a grid doesn't download a
  clip for every card crossed.
- On phones and tablets (no hover) a clip plays while its card is mostly on
  screen and stops when it scrolls away — an Instagram-feed feel, and never
  more than the two or three cards actually in view. jojo skips card video on
  mobile entirely and runs a separate full-screen feed instead.
- Reduced-motion and Save-Data users never load a clip; they see a ▶ chip so
  they know the product page has a video.

## How it works here

- `storefront/components/HoverVideo.tsx` — the client component. Renders the
  still (lazy, indexable) and layers the `<video>` over it. Its hover target is
  the element wrapping it (the card's `.ph` link), so the whole tile triggers;
  on touch devices the same element is watched with an IntersectionObserver
  (60% visible = play, less = pause and rewind).
- `storefront/components/cards.tsx` — `ProductCard` and `MiniCard` pass
  `p.video` to it. Cards without a clip behave exactly as before.
- `storefront/components/pdp.tsx` — `Gallery` takes `video`; the clip becomes
  the first slide (muted, autoplay, loop, with controls), the first still is
  its poster and its thumbnail wears a play badge.
- `storefront/lib/products.ts` — `Product.video?: string`. `lib/catalog.ts`
  takes the hub's `video_url` first (the clip uploaded from the Hub's admin,
  see below) and falls back to a curated local path for the few products
  that have one in the repo.
- `storefront/app/globals.css` — the `.hv` / `.hv-play` / `.vthumb` rules.

The three clips shipped today (`public/products/video/*.mp4`) are motion
loops generated from the existing studio stills so the feature is visible.
Replace them with real footage using the recipe below; the file names are
what `lib/products.ts` points at.

## Uploading from the Hub (the normal way)

The Hub's admin product form has a **Product Video** section under the
Images tab (bethany-house: `POST /api/v1/admin/products/{id}/video`). Upload
the clip straight from the phone — MP4, MOV or WebM, under 20 MB. The Hub
runs the ffmpeg conversion below itself (H.264, 720 px, 30 fps, silent,
first 12 s, faststart), stores the result under `products/{id}/` on its
public disk and writes the URL to `products.video_url`. The public
products API carries that field, the storefront's catalogue sync reads it,
and the change pings the storefront's revalidate hook like any other
product edit — so the clip shows on the card and product page within
moments, no code change. Replacing uploads a new file and removes the old
one; Remove clears it.

## Making a clip by hand

Target: **H.264 (Main), 720 px on the long side, 24–30 fps, 6–10 s, no audio,
≤ 1.5 MB**, `+faststart` so playback begins before the download finishes.
Shoot on a tripod or steady hand, in the same framing as the product still,
and cut it so the last frame lands near the first (it loops).

```bash
ffmpeg -i source.mov -t 8 \
  -vf "scale='if(gt(iw,ih),720,-2)':'if(gt(iw,ih),-2,720)',fps=30,setsar=1,format=yuv420p" \
  -c:v libx264 -profile:v main -level 3.1 -preset slow -crf 26 \
  -movflags +faststart -an \
  storefront/public/products/video/<slug>.mp4
```

Then point the product at it: add `video: "/products/video/<slug>.mp4"` to its
entry in `storefront/lib/products.ts`. This is the fallback route; uploading
in the Hub is simpler and wins over a curated path when both exist.

A quick check of the result:

```bash
ffprobe -v error -show_entries stream=codec_name,width,height,bit_rate,avg_frame_rate \
  -show_entries format=duration,size storefront/public/products/video/<slug>.mp4
```

Anything over ~2 MB or above 720 px is heavier than it needs to be for a
tile that renders at 250–400 px wide.
