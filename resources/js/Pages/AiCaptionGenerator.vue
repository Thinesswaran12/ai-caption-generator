<script setup>
import { ref, nextTick } from 'vue'
import axios from 'axios'

const topic = ref('')
const platform = ref('Instagram')
const tone = ref('Friendly')
const loading = ref(false)
const captions = ref([])
const error = ref('')
const copiedIndex = ref(null)
const hasGenerated = ref(false)

const generateCaptions = async () => {
    error.value = ''
    copiedIndex.value = null

    if (!topic.value.trim()) {
        error.value = 'Please enter a topic'
        return
    }

    loading.value = true
    hasGenerated.value = false

    try {
        const response = await axios.post('/api/ai-caption-generator/generate', {
            topic: topic.value,
            platform: platform.value,
            tone: tone.value,
        })

        captions.value = response.data.captions ?? []
        await nextTick()
        hasGenerated.value = true
    } catch (err) {
        error.value = 'Something went wrong while generating captions.'
        console.error(err)
    } finally {
        loading.value = false
    }
}

const copyCaption = async (caption, index) => {
    try {
        await navigator.clipboard.writeText(caption)
        copiedIndex.value = index

        setTimeout(() => {
            copiedIndex.value = null
        }, 1400)
    } catch (err) {
        console.error(err)
    }
}
</script>

<template>
    <div class="min-h-screen bg-[#f6f3f1] px-6 py-12 md:px-10 lg:px-16">
        <div class="mx-auto max-w-5xl">
            <!-- HERO -->
            <section class="pt-10 md:pt-16">
                <div class="max-w-3xl">
                    <div class="mb-5 flex items-center gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-[#fff7f3] text-[#d6785e] shadow-sm"
                        >
                            ✦
                        </div>

                        <h1
                            class="font-serif text-5xl tracking-[-0.03em] text-[#1f1b19] md:text-6xl"
                        >
                            AI Caption Generator
                        </h1>
                    </div>

                    <p class="text-xl text-[#6f6a67]">
                        Generate social media captions instantly with the power of AI
                    </p>
                </div>

                <!-- FORM CARD -->
                <div
                    class="mt-12 rounded-[28px] border border-[#e7dfda] bg-white/70 p-7 shadow-[0_10px_30px_rgba(0,0,0,0.03)] backdrop-blur-sm md:p-10"
                >
                    <div class="space-y-8">
                        <div>
                            <label class="mb-4 block font-serif text-2xl text-[#1f1b19]">
                                What's your topic?
                            </label>

                            <input
                                v-model="topic"
                                type="text"
                                placeholder="e.g., coffee promotion, product launch..."
                                class="h-16 w-full rounded-2xl border border-[#ded7d2] bg-white px-6 text-lg text-[#1f1b19] placeholder:text-[#aaa29d] focus:border-[#d6785e] focus:outline-none focus:ring-2 focus:ring-[#f1d7cd]"
                            />

                            <p v-if="error" class="mt-3 text-sm text-red-500">
                                {{ error }}
                            </p>
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="mb-4 block font-serif text-2xl text-[#1f1b19]">
                                    Platform
                                </label>

                                <select
                                    v-model="platform"
                                    class="h-16 w-full rounded-2xl border border-[#ded7d2] bg-white px-5 text-lg text-[#1f1b19] focus:border-[#d6785e] focus:outline-none focus:ring-2 focus:ring-[#f1d7cd]"
                                >
                                    <option>Instagram</option>
                                    <option>Facebook</option>
                                    <option>LinkedIn</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-4 block font-serif text-2xl text-[#1f1b19]">
                                    Tone
                                </label>

                                <select
                                    v-model="tone"
                                    class="h-16 w-full rounded-2xl border border-[#ded7d2] bg-white px-5 text-lg text-[#1f1b19] focus:border-[#d6785e] focus:outline-none focus:ring-2 focus:ring-[#f1d7cd]"
                                >
                                    <option>Friendly</option>
                                    <option>Professional</option>
                                    <option>Casual</option>
                                </select>
                            </div>
                        </div>

                        <button
                            @click="generateCaptions"
                            :disabled="loading"
                            class="flex h-16 w-full items-center justify-center rounded-2xl bg-[#d6785e] px-6 text-xl font-medium text-white shadow-[0_10px_25px_rgba(214,120,94,0.25)] transition duration-300 hover:bg-[#c96c53] hover:shadow-[0_14px_30px_rgba(214,120,94,0.3)] disabled:cursor-not-allowed disabled:opacity-70"
                        >
                            <span
                                class="transition-all duration-300"
                                :class="loading ? 'opacity-80' : 'opacity-100'"
                            >
                                {{ loading ? 'Generating Captions...' : 'Generate Captions' }}
                            </span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- RESULTS -->
            <transition
                enter-active-class="transition-all duration-700 ease-out"
                enter-from-class="translate-y-6 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition-all duration-300 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <section v-if="captions.length" class="pt-20">
                    <div class="max-w-3xl">
                        <h2 class="font-serif text-5xl tracking-[-0.03em] text-[#1f1b19] md:text-6xl">
                            Generated Captions
                        </h2>
                        <p class="mt-3 text-xl text-[#6f6a67]">
                            Choose your favorite and share it with the world
                        </p>
                    </div>

                    <div class="mt-12 space-y-6">
                        <transition-group
                            name="card"
                            tag="div"
                            enter-active-class="transition-all duration-700 ease-out"
                            enter-from-class="translate-y-6 opacity-0"
                            enter-to-class="translate-y-0 opacity-100"
                        >
                            <div
                                v-for="(caption, index) in captions"
                                :key="`${caption}-${index}`"
                                class="rounded-[28px] border border-[#e7dfda] bg-white px-7 py-8 shadow-[0_10px_30px_rgba(0,0,0,0.03)]"
                                :style="{ transitionDelay: `${index * 120}ms` }"
                            >
                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex flex-1 items-start gap-4">
                                        <div
                                            class="pt-1 text-6xl leading-none text-[#efe4de]"
                                        >
                                            ”
                                        </div>

                                        <p class="pt-2 text-[1.95rem] leading-relaxed text-[#1f1b19] md:text-[2rem]">
                                            {{ caption }}
                                        </p>
                                    </div>

                                    <div
                                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#f5efeb] text-lg text-[#8f7b70]"
                                    >
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <div class="mt-7 pl-16">
                                    <button
                                        @click="copyCaption(caption, index)"
                                        class="inline-flex items-center gap-3 rounded-2xl bg-[#f4efeb] px-6 py-4 text-lg text-[#1f1b19] transition duration-200 hover:bg-[#ece4de]"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 16H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2M10 20h8a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z"
                                            />
                                        </svg>

                                        <span>
                                            {{ copiedIndex === index ? 'Copied!' : 'Copy Caption' }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </transition-group>
                    </div>

                    <div class="mt-10 flex justify-center">
                        <button
                            @click="generateCaptions"
                            class="inline-flex items-center gap-3 rounded-2xl bg-[#d6785e] px-8 py-5 text-xl font-medium text-white shadow-[0_10px_25px_rgba(214,120,94,0.25)] transition duration-300 hover:bg-[#c96c53]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 4.5v5h5M19.5 19.5v-5h-5M19 9a7 7 0 0 0-12-3.5M5 15a7 7 0 0 0 12 3.5"
                                />
                            </svg>
                            Regenerate
                        </button>
                    </div>
                </section>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.font-serif {
    font-family: "Cormorant Garamond", "Playfair Display", "Times New Roman", serif;
}

.card-move,
.card-enter-active,
.card-leave-active {
    transition: all 0.6s ease;
}

.card-enter-from,
.card-leave-to {
    opacity: 0;
    transform: translateY(24px);
}
</style>