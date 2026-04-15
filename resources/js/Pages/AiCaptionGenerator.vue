<script setup>
import { ref } from 'vue'

const topic = ref('')
const platform = ref('Instagram')
const tone = ref('Friendly')
const loading = ref(false)
const captions = ref([])
const error = ref('')

const generateCaptions = async () => {
    error.value = ''

    if (!topic.value.trim()) {
        error.value = 'Please enter a topic'
        return
    }

    loading.value = true

    // fake loading
    setTimeout(() => {
        captions.value = [
            `Start your day with ${topic.value} and a smile.`,
            `${topic.value} made simple, fresh, and exciting.`,
            `Discover why everyone is talking about ${topic.value}.`,
        ]
        loading.value = false
    }, 1000)
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
                <h2 class="mb-4 text-2xl font-semibold text-gray-900">Generated Captions</h2>

                <div class="space-y-4">
                    <div
                        v-for="(caption, index) in captions"
                        :key="index"
                        class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-200"
                    >
                        <p class="text-gray-700">{{ caption }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>