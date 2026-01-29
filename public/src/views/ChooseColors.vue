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
const colorOrder = ref([])
const loading = ref(true)
const isMoving = ref(false)
const isGenerating = ref(false)

const loadColorOrder = async () => {
  try {
    loading.value = true
    const data = await gameService.getColorOrder()
    if (data.colorOrder) {
      colorOrder.value = data.colorOrder
    } else {
      // Générer un nouvel ordre si aucun n'existe
      await generateNewOrder()
    }
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors du chargement des couleurs'
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
    const data = await gameService.generateNewColorOrder()
    colorOrder.value = data.colorOrder
    
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
  } finally {
    isGenerating.value = false
  }
}

const moveUp = async (index) => {
  if (index === 0) return
  try {
    isMoving.value = true
    
    // Animation de transition (logique UI dans le composant)
    await new Promise(resolve => setTimeout(resolve, 200))
    
    // La réorganisation est faite côté backend
    const data = await gameService.reorderColors(index, 'up')
    colorOrder.value = data.colorOrder
    
    // Retour à l'état normal
    await new Promise(resolve => setTimeout(resolve, 100))
    isMoving.value = false
  } catch (error) {
    console.error('Erreur lors du déplacement:', error)
    isMoving.value = false
  }
}

const moveDown = async (index) => {
  if (index === colorOrder.value.length - 1) return
  try {
    isMoving.value = true
    
    // Animation de transition (logique UI dans le composant)
    await new Promise(resolve => setTimeout(resolve, 200))
    
    // La réorganisation est faite côté backend
    const data = await gameService.reorderColors(index, 'down')
    colorOrder.value = data.colorOrder
    
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
    await gameService.confirmColorOrder()
    router.push('/choose-values')
  } catch (error) {
    console.error('Erreur lors de la confirmation:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors de la confirmation'
    })
  }
}

onMounted(() => {
  loadColorOrder()
})
</script>

<template>
  <ModernCard
    title="Étape 1 : Choisir un ordre des couleurs"
    subtitle="Réorganisez les couleurs selon votre préférence"
    emoji="🎨"
    :is-moving="isMoving"
  >
    <q-inner-loading :showing="loading" />

    <div 
      v-if="!loading" 
      class="colors-list-container custom-scrollbar"
    >
      <transition-group name="list" tag="div" class="colors-list">
        <ReorderableItem
          v-for="(color, index) in colorOrder"
          :key="color.name"
          :position="index + 1"
          :can-move-up="index !== 0"
          :can-move-down="index !== colorOrder.length - 1"
          :delay="index * 0.05"
          @move-up="moveUp(index)"
          @move-down="moveDown(index)"
        >
          <div class="color-icon">
            {{ color.symbol }}
          </div>
          
          <div class="color-name">
            {{ color.name }}
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
.colors-list-container {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
  margin: 1.5rem 0;
  padding-right: 0.5rem;
  max-height: calc(95vh - 350px);
}

.colors-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

/* Icône de couleur */
.color-icon {
  font-size: 1.75rem;
  line-height: 1;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

/* Nom de couleur */
.color-name {
  flex: 1;
  font-size: 1rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: -0.01em;
}
</style>
