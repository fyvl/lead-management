<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import EditModal from '@/Pages/EditModal.vue';

const leads = ref([]);
const totalLeads = computed(() => leads.value.length);

const statuses = ref([]);
const leadsByStatus = ref({});
const statusColors = {
    'Новый': 'bg-blue-100 text-blue-800',
    'В работе': 'bg-yellow-100 text-yellow-800',
    'Завершен': 'bg-green-100 text-green-800',
};

const editingLead = ref(null);
const isModalOpen = ref(false);

onMounted(async () => {
    try {
        const response = await axios.get('/leads');
        leads.value = response.data.leads;
        statuses.value = response.data.statuses;
        leadsByStatus.value = response.data.statusCounts;
    } catch (error) {
        console.error('Ошибка при загрузке лидов:', error);
    }
});

const editLead = (lead) => {
    editingLead.value = { ...lead };
    isModalOpen.value = true;
};

const closeModal = () => {
    editingLead.value = null;
    isModalOpen.value = false;
};

const saveEditedLead = async () => {
    try {
        const response = await axios.put(`/leads/${editingLead.value.id}`, editingLead.value);
        const updatedLead = response.data.lead;
        const index = leads.value.findIndex(l => l.id === updatedLead.id);
        if (index !== -1) {
            leads.value[index] = updatedLead;
        }
        closeModal();
    } catch (error) {
        console.error('Ошибка при обновлении лида:', error);
    }
};


const updateLeadStatus = async (leadId, newStatusId) => {
    try {
        await axios.put(`/leads/${leadId}/status`, { status_id: newStatusId });
        const lead = leads.value.find(l => l.id === leadId);
        if (lead) {
            lead.status.id = newStatusId;
        }
        window.location.reload();
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
                        <h3 class="text-2xl font-semibold mb-6">Общее количество лидов: {{ totalLeads }}</h3>
                        
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-3">Количество лидов по статусам:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <div
                                    v-for="(count, status) in leadsByStatus"
                                    :key="status"
                                    :class="[statusColors[status], 'p-3 rounded-lg']"
                                >
                                    <span class="font-medium">{{ status }}:</span> {{ count }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-3 px-4 border-b text-left">ID</th>
                                        <th class="py-3 px-4 border-b text-left">Имя</th>
                                        <th class="py-3 px-4 border-b text-left">Фамилия</th>
                                        <th class="py-3 px-4 border-b text-left">Email</th>
                                        <th class="py-3 px-4 border-b text-left">Телефон</th>
                                        <th class="py-3 px-4 border-b text-left">Дата создания</th>
                                        <th class="py-3 px-4 border-b text-left">Статус</th>
                                        <th class="py-3 px-4 border-b text-left">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lead in leads" :key="lead.id" class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">{{ lead.id }}</td>
                                        <td class="py-2 px-4 border-b">{{ lead.first_name }}</td>
                                        <td class="py-2 px-4 border-b">{{ lead.last_name }}</td>
                                        <td class="py-2 px-4 border-b">{{ lead.email }}</td>
                                        <td class="py-2 px-4 border-b">{{ lead.phone }}</td>
                                        <td class="py-2 px-4 border-b">{{ new Date(lead.created_at).toLocaleString() }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <select
                                                :value="lead.status.id"
                                                @change="updateLeadStatus(lead.id, $event.target.value)"
                                                class="border rounded px-3 py-1 w-28"
                                            >
                                                <option v-for="status in statuses" :key="status.id" :value="status.id">
                                                    {{ status.name }}
                                                </option>
                                            </select>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <button @click="editLead(lead)" class="bg-blue-500 text-white px-3 py-1 rounded mr-2 hover:bg-blue-600">
                                                <PencilIcon class="w-5 h-5"/>
                                            </button>
                                            <button @click="deleteLead(lead.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                <TrashIcon class="w-5 h-5"/>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <EditModal v-if="isModalOpen" :editingLead="editingLead" @save="saveEditedLead" @cancel="closeModal" />
    </AuthenticatedLayout>
</template>