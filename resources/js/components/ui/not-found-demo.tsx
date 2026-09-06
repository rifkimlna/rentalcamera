import { NotFound, Illustration } from "@/components/ui/not-found"

function NotFoundDemo() {
  return (
    <div className="relative flex flex-col w-full justify-center min-h-svh bg-white p-6 md:p-10">
      <div className="relative max-w-5xl mx-auto w-full">
        <Illustration className="absolute inset-0 w-full h-[50vh] opacity-[0.04] text-black" />
        <NotFound
          title="Halaman tidak ditemukan"
          description="Halaman yang Anda cari mungkin telah dipindahkan atau tidak tersedia."
        />
      </div>
    </div>
  )
}

export { NotFoundDemo }
