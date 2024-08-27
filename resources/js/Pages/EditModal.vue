<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    editingLead: Object
});

const emit = defineEmits(['save', 'cancel']);

const saveTask = () => {
    emit('save', props.editingLead);
};

const cancel = () => {
    emit('cancel');
};
</script>

<template>
    <div v-if="editingLead" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
        <div class="bg-white p-5 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Редактировать лид</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Имя</label>
                <input v-model="editingLead.first_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Фамилия</label>
                <input v-model="editingLead.last_name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input v-model="editingLead.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Телефон</label>
                <input v-model="editingLead.phone" type="tel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex justify-end">
                <button @click="saveTask" class="bg-blue-500 text-white px-4 py-2 rounded mr-2 hover:bg-blue-600">Сохранить</button>
                <button @click="cancel" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Отмена</button>
            </div>
        </div>
    </div>
</template>
