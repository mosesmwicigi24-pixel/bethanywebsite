/** Shared SVG icon set — one path definition per icon, used by every surface. */

type P = { className?: string };

export const CartIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><path d="M6 7h12l-1 13H7L6 7zm3 0a3 3 0 0 1 6 0" /></svg>
);

export const SearchIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
);

export const UserIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><circle cx="12" cy="8" r="4" /><path d="M4 21c1.5-3.6 4.5-5 8-5s6.5 1.4 8 5" /></svg>
);

export const TruckIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
);

export const CardIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>
);

export const ShieldIcon = ({ className }: P) => (
  <svg viewBox="0 0 24 24" className={className}><path d="M12 3 4 6v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V6l-8-3z" /><path d="m9 12 2 2 4-4" /></svg>
);
