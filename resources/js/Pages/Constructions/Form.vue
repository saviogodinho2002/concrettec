<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">{{ construction ? 'Editar' : 'Nova' }} Obra</h2>
        <Link
          :href="route('constructions.index')"
          class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
        >
          <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
          <span>Voltar</span>
        </Link>
      </div>
    </template>

    <form autocomplete="off" data-form-type="other">
      <v-form @submit.prevent="submit" autocomplete="off">
        <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="form.name"
                  label="Nome da Obra"
                  :error-messages="form.errors.name"
                  variant="outlined"
                  density="comfortable"
                  required
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="6">
                <v-select
                  v-model="form.enterprise_id"
                  label="Empresa"
                  :items="enterprises"
                  item-title="name"
                  item-value="id"
                  :error-messages="form.errors.enterprise_id"
                  variant="outlined"
                  density="comfortable"
                  required
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12">
                <h3 class="tw-text-lg tw-font-medium tw-mb-4">Endereço</h3>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.address.cep"
                  label="CEP"
                  :error-messages="form.errors['address.cep']"
                  variant="outlined"
                  density="comfortable"
                  v-maska:[cepOptions]
                  @update:model-value="checkRequiredFields"
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="2">
                <v-btn
                  color="primary"
                  @click="searchByCep"
                  :loading="isSearchingCep"
                  :disabled="!canSearch"
                  class="!tw-font-medium tw-flex tw-items-center tw-gap-2 tw-h-[56px] tw-w-full"
                >
                  <Icon icon="solar:search-bold-duotone" width="22" height="22" />
                  Buscar CEP
                </v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="form.address.street"
                  label="Rua"
                  :error-messages="form.errors['address.street']"
                  variant="outlined"
                  density="comfortable"
                  required
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="2">
                <v-text-field
                  v-model="form.address.number"
                  label="Número"
                  :error-messages="form.errors['address.number']"
                  variant="outlined"
                  density="comfortable"
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.address.neighborhood"
                  label="Bairro"
                  :error-messages="form.errors['address.neighborhood']"
                  variant="outlined"
                  density="comfortable"
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="6">
                <!-- Campos ocultos para evitar autopreenchimento -->
                <input type="text" style="display:none" autocomplete="username" />
                <input type="password" style="display:none" autocomplete="current-password" />
                
                <v-autocomplete
                  v-model="selectedCity"
                  label="Cidade"
                  :error-messages="form.errors['address.city']"
                  variant="outlined"
                  density="comfortable"
                  :items="cityOptions"
                  item-title="display_name"
                  item-value="id"
                  :loading="isLoadingCities"
                  :search-input.sync="citySearch"
                  @update:search="searchCities"
                  @update:model-value="handleCitySelection"
                  @blur="onCityFieldBlur"
                  return-object
                  autocomplete="new-password"
                  clearable
                  required
                  name="cidader"
                  no-filter
                >
                  <template v-slot:selection="{ item }">
                    {{ item.raw.display_name }}
                  </template>
                  <template v-slot:item="{ props, item }">
                    <v-list-item v-bind="props">
                      <v-list-item-title> </v-list-item-title>
                    </v-list-item>
                  </template>
                  <template v-slot:no-data>
                    <v-list-item>
                      <v-list-item-title>
                       Digite o nome da cidade.
                      </v-list-item-title>
                    </v-list-item>
                  </template>
                </v-autocomplete>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="form.address.uf"
                  label="Estado (UF)"
                  :error-messages="form.errors['address.uf']"
                  variant="outlined"
                  density="comfortable"
                  maxlength="2"
                  required
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12" md="12">
                <v-text-field
                  v-model="form.address.complement"
                  label="Complemento"
                  :error-messages="form.errors['address.complement']"
                  variant="outlined"
                  density="comfortable"
                  autocomplete="new-password"
                />
              </v-col>

              <v-col cols="12">
                <div class="tw-mb-4">
                  <v-col cols="12" md="6">
                <v-btn
                  color="secondary"
                  @click="searchCoordinates"
                  :loading="isSearchingCoords"
                  :disabled="!hasAddressForCoords"
                  class="!tw-font-medium tw-flex tw-items-center tw-gap-2 tw-h-[56px] tw-w-full !text-white "
                >
                  <Icon icon="solar:map-point-search-bold-duotone" width="22" height="22" />
                  Buscar no Mapa
                </v-btn>
              </v-col>
                  <h3 class="tw-text-lg tw-font-medium">Localização no Mapa</h3>
                  <p class="tw-text-gray-600 tw-text-sm tw-mt-1">
                    Para ajustar a localização, clique diretamente no mapa para posicionar o marcador no local correto.
                  </p>
                  <div class="tw-bg-yellow-50 tw-p-3 tw-border tw-border-yellow-200 tw-rounded-md tw-mt-2">
                    <p class="tw-text-yellow-800 tw-text-sm tw-flex tw-items-center">
                      <Icon icon="solar:info-circle-bold-duotone" class="tw-mr-2" width="20" height="20" />
                      <span><b>Atenção:</b> A localização obtida através da busca automática pode não ser precisa. 
                      Após a busca, verifique e ajuste manualmente a posição do marcador no mapa para garantir a precisão do endereço.</span>
                    </p>
                  </div>
                </div>
                
                <!-- Campos de latitude e longitude ocultos -->
                <input type="hidden" v-model="form.address.latitude">
                <input type="hidden" v-model="form.address.longitude">

                <div id="map" class="tw-h-[400px] tw-w-full tw-rounded-lg tw-border tw-border-gray-200"></div>
                
                <div v-if="searchError" class="tw-mt-2 tw-text-red-500">
                  {{ searchError }}
                </div>
              </v-col>
            </v-row>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions class="tw-p-4">
            <v-spacer />
            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              class="!tw-font-medium tw-flex tw-items-center tw-gap-2"
            >
              <Icon icon="solar:disk-bold-duotone" width="22" height="22" />
              {{ construction ? 'Atualizar' : 'Criar' }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </form>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { ref, onMounted, watch, computed } from 'vue'
import axios from 'axios'
import 'leaflet/dist/leaflet.css'
import { SANTAREM_LAT, SANTAREM_LNG, CITY_ZOOM, ADDRESS_ZOOM } from '@/scripts/mapConstants'
import { useToast } from 'vue-toastification'

let map = null
let marker = null
const isSearchingCep = ref(false)
const isSearchingCoords = ref(false)
const searchError = ref(null)
const canSearch = ref(false)
const hasAddressForCoords = ref(false)

// Variáveis para autocomplete de cidade
const citySearch = ref('')
const cityOptions = ref([])
const isLoadingCities = ref(false)
const selectedCity = ref(null)

const props = defineProps({
  construction: {
    type: Object,
    default: null
  },
  enterprises: {
    type: Array,
    required: true
  }
})

const cepOptions = { mask: '#####-###' }

const form = useForm({
  name: props.construction?.name ?? '',
  enterprise_id: props.construction?.enterprise_id ?? '',
  address: {
    street: props.construction?.address?.street ?? '',
    number: props.construction?.address?.number ?? '',
    neighborhood: props.construction?.address?.neighborhood ?? '',
    complement: props.construction?.address?.complement ?? '',
    cep: props.construction?.address?.cep ?? '',
    city: props.construction?.address?.city_name ?? '',
    uf: props.construction?.address?.uf ?? '',
    latitude: props.construction?.address?.latitude ?? '',
    longitude: props.construction?.address?.longitude ?? '',
  }
})

// Verifica se o CEP está preenchido corretamente para habilitar a busca
const checkRequiredFields = () => {
  canSearch.value = form.address.cep && form.address.cep.replace(/\D/g, '').length === 8
  
  // Verifica se temos dados suficientes para buscar coordenadas
  hasAddressForCoords.value = form.address.street && form.address.city && form.address.uf
}

// Verificar campos na inicialização
onMounted(() => {
  checkRequiredFields()
  
  // Se estiver editando, configurar a cidade selecionada
  if (props.construction?.address?.city_name && props.construction?.address?.uf) {
    selectedCity.value = {
      id: props.construction?.address?.city_id || null,
      name: props.construction.address.city_name,
      uf: props.construction.address.uf,
      display_name: `${props.construction.address.city_name} - ${props.construction.address.uf}`
    }
    
    // Inicializa as opções de cidade com a cidade atual
    cityOptions.value = [selectedCity.value]
    
    // Garante que os campos do formulário estejam preenchidos
    form.address.city = props.construction.address.city_name
    form.address.uf = props.construction.address.uf
  }

  // Importar Leaflet dinamicamente para evitar problemas de SSR
  import('leaflet').then((L) => {
    // Inicializar o mapa em Santarém-PA
    map = L.map('map').setView([SANTAREM_LAT, SANTAREM_LNG], CITY_ZOOM);

    // Adicionar camada de mapa OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Se tivermos coordenadas, posicionar o mapa nelas
    if (form.address.latitude && form.address.longitude) {
      const lat = parseFloat(form.address.latitude)
      const lng = parseFloat(form.address.longitude)
      
      if (!isNaN(lat) && !isNaN(lng)) {
        map.setView([lat, lng], ADDRESS_ZOOM)
        marker = L.marker([lat, lng]).addTo(map)
      }
    }

    // Adicionar evento de clique no mapa para atualizar as coordenadas
    map.on('click', function(e) {
      updateMarker(e.latlng.lat, e.latlng.lng)
    })

    // Forçar a renderização do mapa (necessário em alguns casos)
    setTimeout(() => {
      map.invalidateSize()
    }, 200)
  })
})

// Observar mudanças no campo CEP e em outros campos relevantes
watch(() => form.address.cep, () => {
  checkRequiredFields()
})

watch(() => [form.address.street, form.address.city, form.address.uf], () => {
  checkRequiredFields()
}, { deep: true })

// Função para buscar cidades quando o usuário digita
const searchCities = async (searchTerm) => {
  if (!searchTerm || searchTerm.length < 2) {
    cityOptions.value = []
    return
  }
  
  try {
    isLoadingCities.value = true
    const response = await axios.get(route('cities.search'), {
      params: { 
        search: searchTerm,
        limit: 20 // Aumenta o limite para ter mais resultados
      }
    })
    
    // Processar cidades para exibição
    const processedCities = response.data.map(city => ({
      ...city,
      display_name: `${city.name} - ${city.uf}`
    }))
    
    // Filtrar cidades duplicadas, mantendo apenas a versão com UF
    const uniqueCities = []
    const cityNames = new Set()
    
    for (const city of processedCities) {
      const key = `${city.name}:${city.uf}`
      if (!cityNames.has(key) && city.uf) {
        cityNames.add(key)
        uniqueCities.push(city)
      }
    }
    
    cityOptions.value = uniqueCities
  } catch (error) {
    console.error('Erro ao buscar cidades:', error)
    const toast = useToast()
    toast.error('Erro ao buscar cidades. Tente novamente.', {
      position: 'top-right',
      timeout: 3000,
    })
  } finally {
    isLoadingCities.value = false
  }
}

// Função chamada quando o usuário seleciona uma cidade
const handleCitySelection = (city) => {
  if (city) {
    // Atualiza os campos de cidade e UF no formulário
    form.address.city = city.name
    form.address.uf = city.uf
  } else {
    // Se o usuário limpar a seleção
    form.address.city = ''
    form.address.uf = ''
  }
  
  checkRequiredFields()
}

// Função para lidar com a perda de foco no campo de cidade
const onCityFieldBlur = () => {
  // Se não houver cidade selecionada mas houver texto digitado
  if (!selectedCity.value && citySearch.value && citySearch.value.length > 0) {
    // Atualiza apenas o campo city no formulário, mantendo o UF
    form.address.city = citySearch.value
  }
  
  // Se não houver nada digitado, limpa os campos
  if (!selectedCity.value && (!citySearch.value || citySearch.value.length === 0)) {
    form.address.city = ''
    form.address.uf = ''
  }
  
  checkRequiredFields()
}

// Função para buscar apenas os dados do endereço pelo CEP sem buscar coordenadas
const searchByCep = async () => {
  if (!canSearch.value) return

  isSearchingCep.value = true
  searchError.value = null

  try {
    const toast = useToast()
    toast.info('Buscando endereço pelo CEP...', {
      position: 'top-right',
      timeout: 3000,
    })
    
    const cepDigitsOnly = form.address.cep.replace(/\D/g, '')
    await fetchAddressByCep(cepDigitsOnly)
    
    if (!searchError.value) {
      toast.success('Endereço encontrado!', {
        position: 'top-right',
        timeout: 3000,
      })
    }
  } catch (error) {
    console.error('Erro ao buscar endereço por CEP:', error)
    searchError.value = 'Erro ao buscar CEP. Verifique se está correto e tente novamente.'
    
    const toast = useToast()
    toast.error('Erro ao buscar CEP. Verifique se está correto.', {
      position: 'top-right',
      timeout: 5000,
    })
  } finally {
    isSearchingCep.value = false
  }
}

// Função para buscar coordenadas no mapa usando o endereço completo
const searchCoordinates = async () => {
  if (!hasAddressForCoords.value) return

  isSearchingCoords.value = true
  searchError.value = null

  try {
    const toast = useToast()
    toast.info('Buscando coordenadas do endereço...', {
      position: 'top-right',
      timeout: 3000,
    })
    
    await fetchCoordinates()
    
    if (!searchError.value) {
      toast.success('Localização encontrada! Verifique e ajuste a posição se necessário.', {
        position: 'top-right',
        timeout: 5000,
      })
    }
  } catch (error) {
    console.error('Erro ao buscar coordenadas:', error)
    searchError.value = 'Erro ao buscar coordenadas. Tente novamente ou posicione manualmente no mapa.'
    
    const toast = useToast()
    toast.error('Erro ao buscar coordenadas no mapa.', {
      position: 'top-right',
      timeout: 5000,
    })
  } finally {
    isSearchingCoords.value = false
  }
}

// Busca apenas os dados de endereço pelo CEP
const fetchAddressByCep = async (cep) => {
  try {
    // Usar a API do ViaCEP para obter os detalhes do endereço
    const viaCepResponse = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)
    
    if (viaCepResponse.data && !viaCepResponse.data.erro) {
      // Preenche os campos de endereço automaticamente
      form.address.street = viaCepResponse.data.logradouro || form.address.street
      form.address.neighborhood = viaCepResponse.data.bairro || form.address.neighborhood
      
      // Salvar cidade e estado diretamente no formulário
      form.address.city = viaCepResponse.data.localidade || ''
      form.address.uf = viaCepResponse.data.uf || ''
      
      // Atualizar estado do autocomplete de cidade
      if (viaCepResponse.data.localidade && viaCepResponse.data.uf) {
        selectedCity.value = {
          id: null, // Sem ID real, mas não é necessário para o funcionamento
          name: viaCepResponse.data.localidade,
          uf: viaCepResponse.data.uf,
          display_name: `${viaCepResponse.data.localidade} - ${viaCepResponse.data.uf}`
        }
        
        // Força a limpeza das opções para evitar seleções incorretas
        cityOptions.value = [selectedCity.value]
      } else {
        selectedCity.value = null
        cityOptions.value = []
      }
      
      // Verificar se podemos buscar coordenadas
      checkRequiredFields()
    } else {
      searchError.value = 'CEP não encontrado. Verifique se está correto.'
      
      const toast = useToast()
      toast.error('CEP não encontrado. Verifique se está correto.', {
        position: 'top-right',
        timeout: 5000,
      })
    }
  } catch (error) {
    console.error('Erro ao buscar CEP:', error)
    throw error
  }
}

// Busca coordenadas usando o Google Maps Geocoding
const fetchCoordinates = async () => {
  try {
    // Construir o endereço sem incluir o bairro
    const addressQuery = `${form.address.street}, ${form.address.number || ''}, ${form.address.city}, ${form.address.uf}`
    
    const nominatimResponse = await axios.get('https://nominatim.openstreetmap.org/search', {
      params: {
        q: addressQuery,
        format: 'json',
        limit: 1
      },
      headers: {
        'User-Agent': 'Concrettec App'
      }
    })
    
    if (nominatimResponse.data && nominatimResponse.data.length > 0) {
      const result = nominatimResponse.data[0]
      const lat = parseFloat(result.lat)
      const lng = parseFloat(result.lon)

      updateMarker(lat, lng)
      map.setView([lat, lng], ADDRESS_ZOOM)
    } else {
      searchError.value = 'Endereço não encontrado no mapa. Tente adicionar mais detalhes ou clique na posição correta no mapa.'
      
      const toast = useToast()
      toast.error('Endereço não encontrado. Tente adicionar mais detalhes.', {
        position: 'top-right',
        timeout: 5000,
      })
    }
  } catch (error) {
    console.error('Erro ao buscar coordenadas:', error)
    throw error
  }
}

const updateMarker = (lat, lng) => {
  // Atualizar form
  form.address.latitude = lat.toFixed(8)
  form.address.longitude = lng.toFixed(8)

  // Atualizar marcador no mapa
  if (marker) {
    marker.setLatLng([lat, lng])
  } else if (map) {
    marker = L.marker([lat, lng]).addTo(map)
  }
}

const submit = () => {
  if (props.construction) {
    form.put(route('constructions.update', props.construction.id))
  } else {
    form.post(route('constructions.store'))
  }
}
</script>

<style>
@import 'leaflet/dist/leaflet.css';
</style> 