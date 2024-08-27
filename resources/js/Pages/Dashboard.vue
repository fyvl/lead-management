<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const leads = ref([]);
const totalLeads = computed(() => leads.value.length);
const statuses = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/leads');
        leads.value = response.data.leads;
        statuses.value = response.data.statuses;
    } catch (error) {
        console.error('Ошибка при загрузке лидов:', error);
    }
});

const updateLeadStatus = async (leadId, newStatus) => {
    try {
        await axios.put(`/leads/${leadId}/status`, { status_id: newStatus });
        const lead = leads.value.find(l => l.id === leadId);
        if (lead) {
        lead.status.id = newStatus;
        }
    } catch (error) {
        console.error('Ошибка при обновлении статуса:', error);
    }
};

const deleteLead = async (leadId) => {
    try {
        await axios.delete(`/leads/${leadId}`);
        leads.value = leads.value.filter(l => l.id !== leadId);
    } catch (error) {
        console.error('Ошибка при удалении лида:', error);
    }
};
</script>

<template>
    <Head title="Лиды" />
    <AuthenticatedLayout>
        <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Лиды</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Общее количество лидов: {{ totalLeads }}</h3>
                        
                        <div class="mb-4">
                        <h4 class="text-md font-semibold">Количество лидов по статусам:</h4>
                        <ul>
                            <li v-for="(count, status) in leadsByStatus" :key="status">
                            {{ status }}: {{ count }}
                            </li>
                        </ul>
                        </div>

                        <table class="min-w-full">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Дата создания</th>
                                <th>Статус</th>
                                <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in leads" :key="lead.id">
                                <td>{{ lead.id }}</td>
                                <td>{{ lead.first_name }}</td>
                                <td>{{ lead.last_name }}</td>
                                <td>{{ lead.email }}</td>
                                <td>{{ lead.phone }}</td>
                                <td>{{ new Date(lead.created_at).toLocaleString() }}</td>
                                <td>
                                    <select 
                                        :value="lead.status.id" 
                                        @change="updateLeadStatus(lead.id, $event.target.value)"
                                    >
                                        <option v-for="status in statuses" :key="status.id" :value="status.id">
                                            {{ status.name }}
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <button @click="deleteLead(lead.id)" class="text-red-600">Удалить</button>
                                </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>