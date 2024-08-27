<script setup>
import { ref } from 'vue';

const props = defineProps({
    lead: Object
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <div v-if="lead" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Текст лида</h3>
            <p class="mb-4 text-wrap">{{ lead.message }}</p>
            <div class="flex justify-end">
                <button @click="closeModal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Закрыть</button>
            </div>
        </div>
    </div>
</template>