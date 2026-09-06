import { cn } from "@/lib/utils";
import * as React from "react";

const Input = React.forwardRef<HTMLInputElement, React.ComponentProps<"input">>(
  ({ className, type, ...props }, ref) => {
    return (
      <input
        type={type}
        className={cn(
          "flex h-10 w-full rounded-full border border-neutral-300 bg-white px-4 py-2 text-sm text-black shadow-sm transition-colors placeholder:text-neutral-400 focus-visible:border-black focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50",
          type === "search" &&
            "[&::-webkit-search-cancel-button]:appearance-none [&::-webkit-search-decoration]:appearance-none [&::-webkit-search-results-button]:appearance-none [&::-webkit-search-results-decoration]:appearance-none",
          type === "file" &&
            "p-0 pr-3 italic text-neutral-500 file:me-3 file:h-full file:border-0 file:border-r file:border-solid file:border-neutral-300 file:bg-transparent file:px-3 file:text-sm file:font-medium file:not-italic file:text-black",
          className,
        )}
        ref={ref}
        {...props}
      />
    );
  },
);
Input.displayName = "Input";

export { Input };
