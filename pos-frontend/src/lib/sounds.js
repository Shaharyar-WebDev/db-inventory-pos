// src/lib/sounds-xbox360.js

const ctx = new (window.AudioContext || window.webkitAudioContext)()

function beep({ frequency = 440, type = 'sine', duration = 0.1, volume = 0.3, delay = 0 } = {}) {
    const oscillator = ctx.createOscillator()
    const gainNode = ctx.createGain()

    oscillator.connect(gainNode)
    gainNode.connect(ctx.destination)

    oscillator.type = type
    oscillator.frequency.setValueAtTime(frequency, ctx.currentTime + delay)

    gainNode.gain.setValueAtTime(0, ctx.currentTime + delay)
    gainNode.gain.linearRampToValueAtTime(volume, ctx.currentTime + delay + 0.01)
    gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + duration)

    oscillator.start(ctx.currentTime + delay)
    oscillator.stop(ctx.currentTime + delay + duration + 0.05)
}

export const sounds = {
    addItem() {
        // Xbox 360 blade select sound
        beep({ frequency: 880, type: 'sine', duration: 0.05, volume: 0.2 })
        beep({ frequency: 1320, type: 'sine', duration: 0.03, volume: 0.15, delay: 0.05 })
    },

    removeItem() {
        // Xbox 360 back/cancel sound
        beep({ frequency: 660, type: 'sine', duration: 0.08, volume: 0.18 })
        beep({ frequency: 440, type: 'sine', duration: 0.06, volume: 0.15, delay: 0.08 })
    },

    updateItem() {
        // Xbox 360 navigation tick
        beep({ frequency: 1000, type: 'sine', duration: 0.03, volume: 0.12 })
    },

    clearCart() {
        // Xbox 360 menu close sound
        beep({ frequency: 600, type: 'square', duration: 0.1, volume: 0.15 })
        beep({ frequency: 400, type: 'square', duration: 0.12, volume: 0.12, delay: 0.1 })
        beep({ frequency: 200, type: 'square', duration: 0.15, volume: 0.1, delay: 0.22 })
    },

    submitSuccess() {
        // Xbox 360 achievement unlocked!
        beep({ frequency: 523, type: 'sine', duration: 0.1, volume: 0.2 })
        beep({ frequency: 659, type: 'sine', duration: 0.1, volume: 0.2, delay: 0.12 })
        beep({ frequency: 784, type: 'sine', duration: 0.15, volume: 0.25, delay: 0.24 })
        beep({ frequency: 1047, type: 'sine', duration: 0.3, volume: 0.3, delay: 0.4 })
        beep({ frequency: 0, type: 'sine', duration: 0.05, volume: 0, delay: 0.7 })
    },

    submitError() {
        // Xbox 360 error buzz
        beep({ frequency: 150, type: 'sawtooth', duration: 0.4, volume: 0.25 })
        beep({ frequency: 120, type: 'sawtooth', duration: 0.3, volume: 0.2, delay: 0.4 })
    },

    submitOffline() {
        // Xbox 360 disconnect sound
        beep({ frequency: 440, type: 'triangle', duration: 0.15, volume: 0.18 })
        beep({ frequency: 330, type: 'triangle', duration: 0.2, volume: 0.15, delay: 0.18 })
    }
}