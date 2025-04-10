<script setup>
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

const props = defineProps({
  data: {
    type: Object,
    required: true
  }
});

const chartData = {
  labels: ['Aprovados', 'Pendentes', 'Cancelados'],
  datasets: [
    {
      data: [props.data.aprovados, props.data.pendentes, props.data.cancelados],
      backgroundColor: ['#22c55e', '#3b82f6', '#ef4444'],
      borderWidth: 0
    }
  ]
};

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '70%',
  plugins: {
    legend: {
      display: false
    }
  }
};
</script>

<template>
  <div class="tw-w-full tw-h-full">
    <Doughnut :data="chartData" :options="chartOptions" />
  </div>
</template> 