<script setup>
import { ref } from 'vue'
import axios from 'axios'

const topic = ref('')
const platform = ref('Instagram')
const tone = ref('Friendly')
const loading = ref(false)
const captions = ref([])
const error = ref('')
const copiedIndex = ref(null)

const generateCaptions = async () => {
    error.value = ''
    captions.value = []
    copiedIndex.value = null

    if (!topic.value.trim()) {
        error.value = 'Please enter a topic'
        return
    }

    loading.value = true

    try {
        const response = await axios.post('/api/ai-caption-generator/generate', {
            topic: topic.value,
            platform: platform.value,
            tone: tone.value,
        })

        captions.value = response.data.captions ?? []
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
        }, 1500)
    } catch (err) {
        console.error('Copy failed', err)
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 px-4 py-10">
        <div class="mx-auto max-w-2xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">AI Caption Generator</h1>
                <p class="mt-2 text-gray-600">Generate social media captions instantly</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Topic or Product</label>
                        <input
                            v-model="topic"
                            type="text"
                            placeholder="Enter topic or product..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none"
                        />
                        <p v-if="error" class="mt-2 text-sm text-red-500">{{ error }}</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Platform</label>
                        <select
                            v-model="platform"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none"
                        >
                            <option>Instagram</option>
                            <option>Facebook</option>
                            <option>LinkedIn</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Tone</label>
                        <select
                            v-model="tone"
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none"
                        >
                            <option>Friendly</option>
                            <option>Professional</option>
                            <option>Casual</option>
                        </select>
                    </div>

                    <button
                        @click="generateCaptions"
                        :disabled="loading"
                        class="w-full rounded-lg bg-blue-600 px-4 py-3 font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-70"
                    >
                        {{ loading ? 'Generating...' : 'Generate Captions' }}
                    </button>
                </div>
            </div>

            <div v-if="captions.length" class="mt-8">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-900">Generated Captions</h2>

                    <button
                        @click="generateCaptions"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Regenerate
                    </button>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="(caption, index) in captions"
                        :key="index"
                        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200"
                    >
                        <p class="text-gray-700">{{ caption }}</p>

                        <div class="mt-4 flex justify-end">
                            <button
                                @click="copyCaption(caption, index)"
                                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                            >
                                {{ copiedIndex === index ? 'Copied!' : 'Copy' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>