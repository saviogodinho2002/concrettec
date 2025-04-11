<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import DonutChart from '@/Components/DonutChart.vue';
import { Icon } from '@iconify/vue';


const props = defineProps({
    indicator:Object
})


const agendamentosData = {
  aprovados: 20,
  pendentes: 54,
  cancelados: 26
};

const eventos = ref([
  {
    title: 'Meeting w/ Client',
    start: '2024-04-03',
    backgroundColor: '#ffcdd2'
  },
  {
    title: 'Financial Advisor Meeting',
    start: '2024-04-06',
    backgroundColor: '#fff3e0'
  },
  {
    title: "St. Patrick's Day",
    start: '2024-04-17',
    backgroundColor: '#e8f5e9'
  }
]);

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: eventos.value,
  headerToolbar: false,
  height: 'auto',
  dayMaxEvents: true,
  contentHeight: 600
});

const ultimosAgendamentos = [
  { data: '18/04 - 16h', descricao: '4 Caminhos', empresa: 'R Branco', situacao: 'Pendente' },
  { data: '18/04 - 16h', descricao: '4 Caminhos', empresa: 'R Branco', situacao: 'Aprovado' },
  { data: '18/04 - 16h', descricao: '4 Caminhos', empresa: 'R Branco', situacao: 'Aprovado' },
  { data: '18/04 - 16h', descricao: '4 Caminhos', empresa: 'R Branco', situacao: 'Aprovado' }
];

const ultimosEnsaios = [
  { data: '18/04 - 16h', carro: 'Caminhão 01', obra: 'R Branco', responsavel: 'Jorge LM', lote: '0001' },
  { data: '18/04 - 16h', carro: 'Caminhão 01', obra: 'R Branco', responsavel: 'Jorge LM', lote: '0001' },
  { data: '18/04 - 16h', carro: 'Caminhão 01', obra: 'R Branco', responsavel: 'Jorge LM', lote: '0001' },
  { data: '18/04 - 16h', carro: 'Caminhão 01', obra: 'R Branco', responsavel: 'Jorge LM', lote: '0001' }
];

const headers = {
  agendamentos: [
    { title: 'Data', key: 'data', align: 'start' },
    { title: 'Descrição', key: 'descricao', align: 'start' },
    { title: 'Empresa', key: 'empresa', align: 'start' },
    { title: 'Situação', key: 'situacao', align: 'start' },
  ],
  ensaios: [
    { title: 'Data', key: 'data', align: 'start' },
    { title: 'Carro', key: 'carro', align: 'start' },
    { title: 'Obra', key: 'obra', align: 'start' },
    { title: 'Responsável', key: 'responsavel', align: 'start' },
    { title: 'Lote', key: 'lote', align: 'start' },
  ]
};

const cardData = [
    {
        value: props.indicator.total_enterprises,
        label: 'Total Empresas',
        icon: 'solar:buildings-2-bold-duotone',
        bgColor: 'tw-bg-pink-100',
        iconColor: 'tw-bg-[#ec4899]'
    },
    {
        value: props.indicator.total_constructions,
        label: 'Obras ativas',
        icon: 'emojione-monotone:building-construction',
        bgColor: 'tw-bg-orange-50',
        iconColor: 'tw-bg-[#f97316]'
    }
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <span class="tw-text-[25px] tw-font-bold tw-ml-8">Dashboard</span>
        </template>

        <v-container fluid class="tw-bg-gray-50 tw-py-3">
            <!-- Primeira Row: Informações Gerais e Calendário -->
            <v-row>
                <!-- Informações Gerais e Gráfico -->
                <v-col cols="12" md="4" class="">
                    <v-card variant="flat" class="h-100 px-5 py-2 rounded-xl">
                        <!-- Informações Gerais -->
                        <h2 class="text-h6 font-weight-bold mb-4 ">Informações gerais</h2>

                        <!-- Cards em Grid -->
                        <div class="tw-flex tw-gap-4 mb-4">
                            <div v-for="(card, index) in cardData" :key="index"
                                 :class="['flex-1 rounded-lg pa-4', card.bgColor]">
                                <div class="d-flex flex-column h-100 " >
                                    <Icon
                                        :class="card.iconColor"
                                        :icon="card.icon"
                                        color="white"
                                        height="45"
                                        width="45"
                                        class="mb-2 tw-rounded-full tw-p-2 "
                                    />
                                    <span class="text-h3 font-weight-bold mb-1">{{ card.value }}</span>
                                    <span class="text-subtitle-1 text-medium-emphasis">{{ card.label }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Agendamentos -->
                        <div class="tw-bg-[#EBF5FF] rounded-lg pa-4 mt-4">
                            <div class="d-flex justify-space-between align-center mb-2">
                                <span class="text-subtitle-1">Agendamentos</span>
                                <span class="text-caption text-grey">Today</span>
                            </div>
                            <div class="d-flex flex-column align-center">
                                <div style="width: 160px; height: 220px;" class="mb-4">
                                    <DonutChart :data="agendamentosData" />
                                </div>
                                <div class="d-flex tw-gap-4">
                                    <div class="d-flex align-center">
                                        <div class="rounded-circle mr-2" style="width: 6px; height: 6px; background-color: #22c55e;"></div>
                                        <span class="text-caption">Aprovados - </span>
                                        <span class="text-caption ml-1">{{ agendamentosData.aprovados }}</span>
                                    </div>
                                    <div class="d-flex align-center">
                                        <div class="rounded-circle mr-2" style="width: 6px; height: 6px; background-color: #3b82f6;"></div>
                                        <span class="text-caption">Pendentes - </span>
                                        <span class="text-caption ml-1">{{ agendamentosData.pendentes }}</span>
                                    </div>
                                    <div class="d-flex align-center">
                                        <div class="rounded-circle mr-2" style="width: 6px; height: 6px; background-color: #ef4444;"></div>
                                        <span class="text-caption">Cancelados - </span>
                                        <span class="text-caption ml-1">{{ agendamentosData.cancelados }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card>
                </v-col>

                <!-- Calendário -->
                <v-col cols="12" md="8">
                    <v-card variant="flat" class="h-100 px-5 py-2 rounded-xl">
                        <v-card-title class="d-flex justify-space-between align-center pa-4">
                            <span class="text-h6 font-weight-bold">Calendário de Agendamento</span>
                            <div>
                                <v-btn icon variant="text" size="small" class="mr-2">
                                    <v-icon>mdi-chevron-left</v-icon>
                                </v-btn>
                                <v-btn icon variant="text" size="small">
                                    <v-icon>mdi-chevron-right</v-icon>
                                </v-btn>
                            </div>
                        </v-card-title>
                        <v-card-text class="pa-0">
                            <div class="tw-calendar-container">
                                <FullCalendar :options="calendarOptions" />
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Segunda Row: Tabelas -->
            <v-row class="mt-6">
                <!-- Últimos Agendamentos -->
                <v-col cols="12" md="4">
                    <v-card variant="flat" class="h-100 px-5 py-2 rounded-xl">
                        <v-card-title class="text-h6 font-weight-bold mb-4">
                            Últimos agendamentos
                        </v-card-title>
                        <v-card-text>
                            <v-table>
                                <thead>
                                    <tr>
                                        <th v-for="header in headers.agendamentos" :key="header.key" class="text-left">
                                            {{ header.title }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in ultimosAgendamentos" :key="index">
                                        <td>{{ item.data }}</td>
                                        <td>{{ item.descricao }}</td>
                                        <td>{{ item.empresa }}</td>
                                        <td>
                                            <v-chip
                                                :color="item.situacao === 'Aprovado' ? 'success' : item.situacao === 'Pendente' ? 'info' : 'error'"
                                                size="small"
                                                variant="tonal"
                                            >
                                                {{ item.situacao }}
                                            </v-chip>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- Últimos Ensaios -->
                <v-col cols="12" md="8">
                    <v-card variant="flat" class="h-100 px-5 py-2 rounded-xl">
                        <v-card-title class="text-h6 font-weight-bold mb-4">
                            Últimos ensaios
                        </v-card-title>
                        <v-card-text>
                            <v-table>
                                <thead>
                                    <tr>
                                        <th v-for="header in headers.ensaios" :key="header.key" class="text-left">
                                            {{ header.title }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in ultimosEnsaios" :key="index">
                                        <td>{{ item.data }}</td>
                                        <td>{{ item.carro }}</td>
                                        <td>{{ item.obra }}</td>
                                        <td>{{ item.responsavel }}</td>
                                        <td>{{ item.lote }}</td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>

<style>
.tw-calendar-container {

    width: 100%;
}

.fc-day-today {
    background-color: transparent !important;
}

.fc-event {
    border: none;
    padding: 2px 4px;
    font-size: 0.875rem;
}

.fc-h-event {
    background-color: transparent;
    border: none;
}

.fc td {
    border: 1px solid #e5e7eb;
}

.fc-daygrid-day-number {
    font-size: 0.875rem;
    color: #4b5563;
}

.fc-col-header-cell {
    padding: 8px;
    font-weight: 500;
    color: #4b5563;
}

.fc {
    width: 100%;
    height: 100%;
}

.h-100 {
    height: 100%;
}

.flex-1 {
    flex: 1;
}

/* Ajustes para o FullCalendar */
.fc {
    background: white;
    padding: 1rem;
}

.fc-header-toolbar {
    display: none !important;
}

.fc-view-harness {
    background: white;
}
</style>
