import type { Directive, DirectiveBinding } from 'vue'

interface DoubleTapElement extends HTMLElement {
  _doubleTapCleanup?: () => void
}

export default {
  mounted(el: DoubleTapElement, binding: DirectiveBinding<(e: Event) => void>) {
    let lastTap = 0

    const handleTouchStart = (event: Event) => {
      const currentTime = new Date().getTime()
      const tapLength = currentTime - lastTap

      if (tapLength < 500 && tapLength > 0) {
        binding.value(event)
      }
      lastTap = currentTime
    }

    const handleDoubleClick = (event: Event) => {
      binding.value(event)
    }

    el.addEventListener('dblclick', handleDoubleClick)
    el.addEventListener('touchstart', handleTouchStart)

    el._doubleTapCleanup = () => {
      el.removeEventListener('dblclick', handleDoubleClick)
      el.removeEventListener('touchstart', handleTouchStart)
    }
  },
  unmounted(el: DoubleTapElement) {
    el._doubleTapCleanup?.()
  },
} satisfies Directive
