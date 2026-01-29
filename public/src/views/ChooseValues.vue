<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import { useQuasar } from 'quasar'
import ModernCard from '../components/ModernCard.vue'
import ActionButton from '../components/ActionButton.vue'
import ReorderableItem from '../components/ReorderableItem.vue'
import '../styles/common.css'

const router = useRouter()
const $q = useQuasar()
const valuesOrder = ref([])
const loading = ref(true)
const isMoving = ref(false)
const isGenerating = ref(false)

const loadValuesOrder = async () => {
  try {
    loading.value = true
    const data = await gameService.getValuesOrder()
    if (data.valuesOrder) {
      valuesOrder.value = data.valuesOrder
    } else {
      await generateNewOrder()
    }
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors du chargement des valeurs'
    })
  } finally {
    loading.value = false
  }
}

const generateNewOrder = async () => {
  try {
    isGenerating.value = true
    isMoving.value = true
    
    // Animation de fade out (logique UI dans le composant)
    await new Promise(resolve => setTimeout(resolve, 300))
    
    // Le mélange est fait côté backend, on appelle juste l'API
    const data = await gameService.generateNewValuesOrder()
    valuesOrder.value = data.valuesOrder
    
    // Animation de fade in
    await new Promise(resolve => setTimeout(resolve, 50))
    isMoving.value = false
  } catch (error) {
    console.error('Erreur lors de la génération:', error)
    isMoving.value = false
    $q.notify({
      type: 'negative',
      message: 'Erreur lors de la génération d\'un nouvel ordre'
    })
  }
}

const moveUp = async (index) => {
  if (index === 0) return
  try {
    isMoving.value = true
    
    // Animation de transition (logique UI dans le composant)
    await new Promise(resolve => setTimeout(resolve, 200))
    
    // La réorganisation est faite côté backend
    const data = await gameService.reorderValues(index, 'up')
    valuesOrder.value = data.valuesOrder
    
    // Retour à l'état normal
    await new Promise(resolve => setTimeout(resolve, 100))
    isMoving.value = false
  } catch (error) {
    console.error('Erreur lors du déplacement:', error)
    isMoving.value = false
  }
}

const moveDown = async (index) => {
  if (index === valuesOrder.value.length - 1) return
  try {
    isMoving.value = true
    
    // Animation de transition (logique UI dans le composant)
    await new Promise(resolve => setTimeout(resolve, 200))
    
    // La réorganisation est faite côté backend
    const data = await gameService.reorderValues(index, 'down')
    valuesOrder.value = data.valuesOrder
    
    // Retour à l'état normal
    await new Promise(resolve => setTimeout(resolve, 100))
    isMoving.value = false
  } catch (error) {
    console.error('Erreur lors du déplacement:', error)
    isMoving.value = false
  }
}

const confirmOrder = async () => {
  try {
    await gameService.confirmValuesOrder()
    router.push('/choose-game-mode')
  } catch (error) {
    console.error('Erreur lors de la confirmation:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors de la confirmation'
    })
  }
}

onMounted(() => {
  loadValuesOrder()
})
</script>

<template>
  <ModernCard
    title="Étape 2 : Choisir un ordre des valeurs"
    subtitle="Réorganisez les valeurs selon votre préférence"
    emoji="🎨"
    :is-moving="isMoving"
  >
    <q-inner-loading :showing="loading" />

    <div 
      v-if="!loading" 
      class="values-list-container custom-scrollbar"
    >
      <transition-group name="list" tag="div" class="values-list">
        <ReorderableItem
          v-for="(value, index) in valuesOrder"
          :key="value"
          :position="index + 1"
          :can-move-up="index !== 0"
          :can-move-down="index !== valuesOrder.length - 1"
          :delay="index * 0.05"
          @move-up="moveUp(index)"
          @move-down="moveDown(index)"
        >
          <div class="value-text">
            {{ value }}
          </div>
        </ReorderableItem>
      </transition-group>
    </div>

    <template #actions>
      <ActionButton
        label="Nouveau ordre"
        button-class="secondary-btn"
        :loading="isGenerating"
        loading-text="Génération..."
        @click="generateNewOrder"
      />
      <ActionButton
        label="Confirmer cet ordre"
        button-class="primary-btn"
        @click="confirmOrder"
      />
    </template>
  </ModernCard>
</template>

<style scoped>
/* Container de la liste */
.values-list-container {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
  margin: 1.5rem 0;
  padding-right: 0.5rem;
  max-height: calc(95vh - 350px);
}

.values-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.value-text {
  flex: 1;
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: -0.01em;
}
</style>
