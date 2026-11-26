// Generated from registry/react/components/marquee-3d.tsx by scripts/write-vue-registry.mts.
import { defineComponent, h, type IntrinsicElementAttributes } from "vue"
import { cn } from "@/lib/utils"
import type { AsChildComponent, PolymorphicProps } from "@ark-ui/vue/factory"

// React's `React.ComponentProps<T>`: the props an element or a Vue component
// takes, writable as in React so interfaces can extend several. `class` and
// `style` typed alike whatever declares them (an element `any`, a component
// `unknown`), so those interfaces agree; and without the `[key: string]:
// unknown` vue-tsc gives a component's props, where `Omit` keeps nothing else.
type ComponentProps<T> = {
  -readonly [K in keyof DeclaredProps<T> as K extends "class" | "style"
    ? never
    : string extends K
      ? never
      : number extends K
        ? never
        : K]: DeclaredProps<T>[K]
} & { class?: any; style?: any }

// The props an element or a Vue component declares.
type DeclaredProps<T> = T extends keyof IntrinsicElementAttributes
  ? IntrinsicElementAttributes[T]
  : T extends AsChildComponent<infer E extends keyof IntrinsicElementAttributes>
    ? IntrinsicElementAttributes[E] & PolymorphicProps
    : T extends new (...args: any) => { $props: infer P }
      ? P
      : T extends (props: infer P, ...args: any) => any
        ? P
        : never

export interface Marquee3DProps extends ComponentProps<"div"> {
  /**
   * Tiles painted in a colour each instead of images: any CSS colour, such
   * as a theme token (`var(--chart-1)`). Used when given.
   */
  colors?: string[];
  /**
   * How many columns the tiles are dealt into, in order: the first tile to
   * the first column, the second to the second, and so on.
   *
   * @default 4
   */
  columns?: number;
  /**
   * The time one column takes to scroll by its own length, in seconds;
   * every other column takes a quarter longer, the other way.
   *
   * @default 20
   */
  duration?: number;
  /** The images' URLs, one tile each. 8 to 16 fill the grid well. */
  images?: string[];
  /**
   * Whether the columns hold still where they are: for a pause button of
   * yours, since motion that starts on its own needs a way to stop it
   * without a mouse.
   *
   * @default false
   */
  paused?: boolean;
  /**
   * Whether the columns stop while the pointer is over the grid.
   *
   * @default false
   */
  pauseOnHover?: boolean;
  /**
   * Whether the grid leans the other way: to the start instead of the end.
   *
   * @default false
   */
  reverse?: boolean;
}

const dealt = (items: string[], columns: number) =>
  Array.from({ length: columns }, (_, column) =>
    items.filter((_item, index) => index % columns === column)
  );

const LEAST = 4;

const runOf = (column: string[]) => {
  const set = Array.from(
    { length: column.length > 0 ? Math.ceil(LEAST / column.length) : 0 },
    () => column
  ).flat();
  return [...set, ...set, ...set];
};

export const Marquee3D = defineComponent(
  (props: Marquee3DProps, { attrs }) => {
    return () => {
      const {
        images,
        colors,
        columns = 4,
        duration = 20,
        pauseOnHover = false,
        paused = false,
        reverse = false,
      } = props;
      const {
        class: className,
        style,
        ...rest
      } = attrs;
      const painted = colors !== undefined;
      const tiles = dealt(colors ?? images ?? [], columns);
      return (
        h("div" as never, { class: cn(
            "group/marquee-3d @container-size relative isolate size-full overflow-hidden",
            "mask-x-from-80% mask-y-from-80%",
            "[--marquee-3d-turn:-45deg] rtl:[--marquee-3d-turn:45deg]",
            "data-reverse:[--marquee-3d-turn:45deg] rtl:data-reverse:[--marquee-3d-turn:-45deg]",
            className
          ), "data-pause-on-hover": pauseOnHover ? "" : undefined, "data-paused": paused ? "" : undefined, "data-reverse": reverse ? "" : undefined, "data-slot": "marquee-3d", style: [{ "--marquee-3d-duration": `${duration}s` }, style], ...rest }, h("div" as never, { class: "absolute inset-0 flex items-center justify-center", "data-slot": "marquee-3d-scene" }, h("div" as never, { class: "grid w-[160cqmax] shrink-0 gap-[2cqmax] [transform:rotateX(55deg)_rotateZ(var(--marquee-3d-turn))]", "data-slot": "marquee-3d-grid", style: {
                gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))`,
              } }, [tiles.map((column, index) => (
                h("div" as never, { class: cn(
                    "flex flex-col gap-[2cqmax] pb-[2cqmax]",
                    "motion-safe:animate-marquee-3d",
                    "even:[animation-direction:reverse] even:[animation-duration:calc(var(--marquee-3d-duration)*1.25)]",
                    "group-data-pause-on-hover/marquee-3d:group-hover/marquee-3d:[animation-play-state:paused]",
                    "group-data-paused/marquee-3d:[animation-play-state:paused]"
                  ), "data-slot": "marquee-3d-column", key: index }, [runOf(column).map((tile, place) => (
                    h("div" as never, { "aria-hidden": place >= column.length ? "true" : undefined, class: cn(
                        "aspect-4/3 w-full shrink-0 overflow-hidden rounded-[1.5cqmax] bg-muted shadow-xl ring-1 ring-foreground/10",
                        "transition-[translate,scale] duration-200 ease-out motion-safe:hover:-translate-y-[1.5cqmax] motion-safe:hover:scale-105"
                      ), "data-slot": "marquee-3d-tile", key: place, style: painted ? { background: tile } : undefined }, [painted ? null : (
                        h("img" as never, { alt: "", class: "size-full object-cover", draggable: false, height: 300, src: tile, width: 400 })
                      )])
                  ))])
              ))])))
      );
    }
  },
  { name: "Marquee3D", inheritAttrs: false, props: ["images", "colors", "columns", "duration", "pauseOnHover", "paused", "reverse"] }
)
