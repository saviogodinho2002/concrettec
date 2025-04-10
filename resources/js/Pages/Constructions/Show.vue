<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Detalhes da Obra</h2>
        <div class="tw-flex tw-gap-3">
          <Link
            :href="route('constructions.index')"
            class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
          >
            <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
            <span>Voltar</span>
          </Link>

          <Link
            :href="route('constructions.edit', construction.id)"
            class="!tw-font-medium !tw-flex !tw-items-center !tw-gap-2 !tw-px-4 !tw-py-2 !tw-rounded-md !tw-bg-primary-500 !tw-text-white hover:!tw-bg-primary-600 !tw-transition"
          >
            <Icon icon="solar:pen-bold-duotone" width="22" height="22" />
            <span>Editar</span>
          </Link>
        </div>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 !tw-mb-6">
      <v-card-text>
        <div class="tw-p-2">
          <!-- Cabeçalho com informações principais -->
          <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start md:tw-items-center tw-pb-4 tw-border-b tw-border-gray-200">
            <div>
              <h3 class="tw-text-xl tw-font-medium tw-mb-1">{{ construction.name }}</h3>
              <p class="tw-text-gray-600 tw-flex tw-items-center tw-gap-2">
                <Icon icon="solar:building-bold-duotone" width="18" height="18" class="tw-mb-0.5" />
                <span>{{ construction.enterprise.name }}</span>
              </p>
            </div>
            <div class="tw-mt-3 md:tw-mt-0">
              <v-chip
                color="primary"
                size="small"
                class="!tw-font-medium"
              >
                <Icon icon="solar:buildings-3-bold-duotone" width="18" height="18" class="tw-mr-1" />
                Construção
              </v-chip>
            </div>
          </div>

          <!-- Informações detalhadas em grid -->
          <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-8 tw-mt-6">
            <!-- Coluna de Informações da Obra -->
            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
              <h4 class="tw-text-lg tw-font-medium tw-mb-4 tw-flex tw-items-center tw-gap-2">
                <Icon icon="solar:info-circle-bold-duotone" width="22" height="22" />
                Informações da Obra
              </h4>

              <div class="tw-space-y-4">
                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:tag-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Nome da Obra</p>
                    <p class="tw-font-medium">{{ construction.name }}</p>
                  </div>
                </div>

                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:buildings-3-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Empresa Responsável</p>
                    <p class="tw-font-medium">{{ construction.enterprise.name }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Coluna de Endereço -->
            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
              <h4 class="tw-text-lg tw-font-medium tw-mb-4 tw-flex tw-items-center tw-gap-2">
                <Icon icon="solar:map-point-search-bold-duotone" width="22" height="22" />
                Endereço
              </h4>

              <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-x-4 tw-gap-y-4">
                <div class="tw-col-span-2 tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:square-academic-cap-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Endereço Completo</p>
                    <p class="tw-font-medium">
                      {{ construction.address.street }},
                      {{ construction.address.number || 'S/N' }}
                      {{ construction.address.complement ? '- ' + construction.address.complement : '' }}
                    </p>
                    <p class="tw-text-gray-600">
                      {{ construction.address.neighborhood }},
                      {{ (construction.address.city.name) }} - {{ construction.address.city.uf }}
                    </p>
                    <p class="tw-text-gray-600">CEP: {{ construction.address.cep }}</p>
                  </div>
                </div>

                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:map-arrow-square-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">CEP</p>
                    <p class="tw-font-medium">{{ construction.address.cep || '-' }}</p>
                  </div>
                </div>

                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:map-point-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Cidade/UF</p>
                    <p class="tw-font-medium">{{ (construction.address.city.name) }}/{{ construction.address.city.uf || '-' }}</p>
                  </div>
                </div>

                <div v-if="hasCoordinates" class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:gps-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Coordenadas</p>
                    <p class="tw-font-medium tw-flex tw-items-center tw-gap-1">
                      <span>{{ construction.address.latitude }}, {{ construction.address.longitude }}</span>
                      <a
                        :href="`https://www.google.com/maps/search/?api=1&query=${construction.address.latitude},${construction.address.longitude}`"
                        target="_blank"
                        class="tw-text-primary-500 hover:tw-text-primary-600"
                        title="Abrir no Google Maps"
                      >
                        <Icon icon="solar:square-top-right-bold-duotone" width="16" height="16" />
                      </a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </v-card-text>
    </v-card>

    <!-- Mapa -->
    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-card-text>
        <h4 class="tw-text-lg tw-font-medium tw-mb-4 tw-flex tw-items-center tw-gap-2">
          <Icon icon="solar:map-bold-duotone" width="22" height="22" />
          Localização no Mapa
        </h4>
        
        <div id="map" class="tw-h-[450px] tw-w-full tw-rounded-lg tw-overflow-hidden tw-shadow-sm"></div>
        
        <div v-if="!hasCoordinates" class="tw-mt-4 tw-p-3 tw-bg-yellow-50 tw-border tw-border-yellow-100 tw-rounded-md tw-flex tw-items-center tw-gap-2">
          <Icon icon="solar:info-circle-bold-duotone" width="20" height="20" class="tw-text-yellow-500" />
          <span class="tw-text-yellow-700">
            Sem coordenadas definidas. O mapa está mostrando a localização padrão.
          </span>
        </div>
      </v-card-text>
    </v-card>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { ref, computed, onMounted } from 'vue'
import { SANTAREM_LAT, SANTAREM_LNG, ADDRESS_ZOOM, CITY_ZOOM } from '@/scripts/mapConstants'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
  construction: {
    type: Object,
    required: true
  }
})

// Verifica se temos coordenadas válidas
const hasCoordinates = computed(() => {
  return props.construction.address?.latitude && 
         props.construction.address?.longitude &&
         !isNaN(parseFloat(props.construction.address.latitude)) &&
         !isNaN(parseFloat(props.construction.address.longitude))
})

// Garantir que o mapa seja renderizado após o componente estar montado
onMounted(() => {
  // Importar Leaflet dinamicamente para evitar problemas de SSR
  import('leaflet').then((L) => {
    // Definir o centro do mapa
    let mapCenter = [SANTAREM_LAT, SANTAREM_LNG];
    let zoomLevel = CITY_ZOOM;
    
    // Se tivermos coordenadas, usá-las
    if (hasCoordinates.value) {
      mapCenter = [
        parseFloat(props.construction.address.latitude),
        parseFloat(props.construction.address.longitude)
      ];
      zoomLevel = ADDRESS_ZOOM;
    }
    
    // Inicializar o mapa
    const map = L.map('map').setView(mapCenter, zoomLevel);

    // Adicionar camada de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Adicionar marcador se tivermos coordenadas
    if (hasCoordinates.value) {
      // Criar ícone personalizado para o marcador
      const marker = L.marker(mapCenter).addTo(map);
      
      // Adicionar popup com informações do endereço
      marker.bindPopup(`
        <strong>${props.construction.name}</strong><br>
        ${props.construction.address.street}, ${props.construction.address.number || 'S/N'}<br>
        ${props.construction.address.neighborhood}, 
        ${typeof props.construction.address.city === 'object' ? props.construction.address.city.name : props.construction.address.city} - 
        ${typeof props.construction.address.city === 'object' ? props.construction.address.city.uf : props.construction.address.uf}
      `).openPopup();
    }

    // Forçar a renderização do mapa (necessário em alguns casos)
    setTimeout(() => {
      map.invalidateSize();
    }, 200);
  });
})
</script>

<style>
@import 'leaflet/dist/leaflet.css';

/* Ajustes específicos para o mapa em modo visualização */
.leaflet-control-zoom {
  border: none !important;
  box-shadow: 0 1px 5px rgba(0,0,0,0.2) !important;
}

.leaflet-control-zoom a {
  background-color: white !important;
  color: #444 !important;
}

.leaflet-popup-content-wrapper {
  border-radius: 8px !important;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15) !important;
}

.leaflet-popup-content {
  margin: 12px 16px !important;
  line-height: 1.5 !important;
}
</style>
