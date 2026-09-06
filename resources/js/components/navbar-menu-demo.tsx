"use client";
import React, { useState } from "react";
import { HoveredLink, Menu, MenuItem, ProductItem } from "@/components/ui/navbar-menu";
import { cn } from "@/lib/utils";

// Versi Stekpro: menu dikembalikan ke awal
// (Beranda, Equipment, Harga, Tentang, Kontak)
// + dropdown Katalog untuk Studio & Layanan.
export default function NavbarDemo() {
  return (
    <div className="relative flex min-h-120 w-full translate-z-0 items-center justify-center">
      <Navbar className="top-2" />
      <p className="text-black dark:text-white">
        Hover over the navbar to see the menu
      </p>
    </div>
  );
}

function Navbar({ className }: { className?: string }) {
  const [active, setActive] = useState<string | null>(null);
  return (
    <div
      className={cn("fixed inset-x-0 top-10 z-50 mx-auto max-w-2xl", className)}
    >
      <Menu setActive={setActive}>
        <MenuItem setActive={setActive} active={active} item="Beranda">
          <div className="flex flex-col space-y-4 text-sm">
            <HoveredLink href="/">Beranda</HoveredLink>
            <HoveredLink href="/#cara-sewa">Cara Sewa</HoveredLink>
            <HoveredLink href="/#katalog">Katalog Utama</HoveredLink>
          </div>
        </MenuItem>
        <MenuItem setActive={setActive} active={active} item="Katalog">
          <div className="grid grid-cols-2 gap-10 p-4 text-sm">
            <ProductItem
              title="Equipment"
              href="/products"
              src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=400&auto=format&fit=crop"
              description="Kamera, lensa, lighting, dan audio terawat."
            />
            <ProductItem
              title="Studio"
              href="/customer/studio"
              src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?q=80&w=400&auto=format&fit=crop"
              description="Cyclorama, podcast room, hingga rooftop."
            />
            <ProductItem
              title="Layanan Kreatif"
              href="/customer/layanan"
              src="https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?q=80&w=400&auto=format&fit=crop"
              description="Videografer, fotografer, dan crew profesional."
            />
            <ProductItem
              title="Harga"
              href="/pricing"
              src="https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?q=80&w=400&auto=format&fit=crop"
              description="Harga transparan per jam dan per hari."
            />
          </div>
        </MenuItem>
        <MenuItem setActive={setActive} active={active} item="Informasi">
          <div className="flex flex-col space-y-4 text-sm">
            <HoveredLink href="/pricing">Harga</HoveredLink>
            <HoveredLink href="/about">Tentang</HoveredLink>
            <HoveredLink href="/contact">Kontak</HoveredLink>
            <HoveredLink href="/faq">FAQ</HoveredLink>
          </div>
        </MenuItem>
      </Menu>
    </div>
  );
}
