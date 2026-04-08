import { ref, watch } from 'vue'
import { gsap } from 'gsap'

export function useAnimatedNumber(source) {
    const displayed = ref(source.value)

    watch(source, (newVal, oldVal) => {
        gsap.to(displayed, {
            value: newVal,
            duration: 0.4,
            ease: 'power2.out',
        })
    })

    return displayed
}
