<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Detalhes da Obra</h2>
        <Link
          :href="route('constructions.index')"
          class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
        >
          <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
          <span>Voltar</span>
        </Link>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-card-text>
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
          <div>
            <h3 class="tw-text-lg tw-font-medium tw-mb-4">Informações da Obra</h3>
            <div class="tw-space-y-4">
              <div>
                <span class="tw-text-gray-600">Nome:</span>
                <p class="tw-font-medium">{{ construction.name }}</p>
              </div>
              <div>
                <span class="tw-text-gray-600">Empresa:</span>
                <p class="tw-font-medium">{{ construction.enterprise.name }}</p>
              </div>
            </div>
          </div>

          <div>
            <h3 class="tw-text-lg tw-font-medium tw-mb-4">Endereço</h3>
            <div class="tw-space-y-4">
              <div>
                <span class="tw-text-gray-600">Rua:</span>
                <p class="tw-font-medium">{{ construction.address.street }}</p>
              </div>
              <div>
                <span class="tw-text-gray-600">Número:</span>
                <p class="tw-font-medium">{{ construction.address.number || '-' }}</p>
              </div>
              <div>
                <span class="tw-text-gray-600">Bairro:</span>
                <p class="tw-font-medium">{{ construction.address.neighborhood || '-' }}</p>
              </div>
              <div>
                <span class="tw-text-gray-600">Complemento:</span>
                <p class="tw-font-medium">{{ construction.address.complement || '-' }}</p>
              </div>
              <div>
                <span class="tw-text-gray-600">CEP:</span>
                <p class="tw-font-medium">{{ construction.address.cep || '-' }}</p>
              </div>
              <div v-if="construction.address.latitude && construction.address.longitude">
                <span class="tw-text-gray-600">Coordenadas:</span>
                <p class="tw-font-medium">
                  {{ construction.address.latitude }}, {{ construction.address.longitude }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="tw-mt-6">
          <h3 class="tw-text-lg tw-font-medium tw-mb-4">Localização no Mapa</h3>
          <div id="map" class="tw-h-[400px] tw-w-full tw-rounded-lg tw-border tw-border-gray-200"></div>
        </div>
      </v-card-text>
    </v-card>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { onMounted } from 'vue'
import 'leaflet/dist/leaflet.css'

// Coordenadas de Santarém-PA
const SANTAREM_LAT = -2.4329
const SANTAREM_LNG = -54.7031

const props = defineProps({
  construction: {
    type: Object,
    required: true
  }
})

onMounted(() => {
  // Importar Leaflet dinamicamente para evitar problemas de SSR
  import('leaflet').then((L) => {
    const hasCoordinates = props.construction.address?.latitude && props.construction.address?.longitude
    
    if (hasCoordinates) {
      const lat = parseFloat(props.construction.address.latitude)
      const lng = parseFloat(props.construction.address.longitude)
      
      if (!isNaN(lat) && !isNaN(lng)) {
        // Inicializar o mapa
        const map = L.map('map').setView([lat, lng], 16)
        
        // Adicionar camada de mapa OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map)
        
        // Adicionar marcador na localização
        L.marker([lat, lng]).addTo(map)
      }
    } else {
      // Se não tiver coordenadas, mostrar mapa de Santarém
      const map = L.map('map').setView([SANTAREM_LAT, SANTAREM_LNG], 13)
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map)
    }
  })
})
</script>

<style>
@import 'leaflet/dist/leaflet.css';
</style> 