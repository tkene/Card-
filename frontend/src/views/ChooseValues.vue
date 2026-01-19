<template>
  <div class="modern-card">
    <div class="card-header">
      <div class="header-content">
        <h1 class="card-title">
          <span class="title-text">Étape 2 : Choisir un ordre des valeurs</span>
          <span class="title-emoji">🎨</span>
        </h1>
        <p class="card-subtitle">
          Réorganisez les valeurs selon votre préférence
        </p>
      </div>
    </div>

    <q-inner-loading :showing="loading" />

    <div 
      v-if="!loading" 
      class="values-list-container"
      :class="{ 'is-moving': isMoving }"
    >
      <transition-group name="list" tag="div" class="values-list">
        <div
          v-for="(value, index) in valuesOrder"
          :key="value"
          class="value-card"
          :style="{ '--delay': index * 0.05 + 's' }"
        >
          <div class="value-controls">
            <button
              @click="moveUp(index)"
              :disabled="index === 0"
              class="control-btn up-btn"
              :class="{ 'disabled': index === 0 }"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M18 15l-6-6-6 6"/>
              </svg>
            </button>
            <div class="position-badge">
              {{ index + 1 }}
            </div>
            <button
              @click="moveDown(index)"
              :disabled="index === valuesOrder.length - 1"
              class="control-btn down-btn"
              :class="{ 'disabled': index === valuesOrder.length - 1 }"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </button>
          </div>
          
          <div class="value-text">
            {{ value }}
          </div>
        </div>
      </transition-group>
    </div>

    <div class="card-actions">
      <button
        @click="generateNewOrder"
        :disabled="isGenerating"
        class="action-btn secondary-btn"
        :class="{ 'loading': isGenerating }"
      >
        <span v-if="!isGenerating">Nouveau ordre</span>
        <span v-else class="loading-text">Génération...</span>
      </button>
      <button
        @click="confirmOrder"
        class="action-btn primary-btn"
      >
        Confirmer cet ordre
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import { useQuasar } from 'quasar'

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
    
    await new Promise(resolve => setTimeout(resolve, 300))
    
    const data = await gameService.generateNewValuesOrder()
    valuesOrder.value = data.valuesOrder
    
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
    
    await new Promise(resolve => setTimeout(resolve, 200))
    
    const data = await gameService.reorderValues(index, 'up')
    if (data.valuesOrder) {
      valuesOrder.value = data.valuesOrder
    }
    
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
    
    await new Promise(resolve => setTimeout(resolve, 200))
    
    const data = await gameService.reorderValues(index, 'down')
    if (data.valuesOrder) {
      valuesOrder.value = data.valuesOrder
    }
    
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

<style scoped>
/* Import des styles communs depuis ChooseColors */
.modern-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-radius: 24px;
  box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.1),
    0 2px 8px rgba(0, 0, 0, 0.05),
    inset 0 1px 0 rgba(255, 255, 255, 0.8);
  padding: 2rem;
  max-width: 700px;
  width: 100%;
  max-height: 95vh;
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-header {
  margin-bottom: 2rem;
}

.header-content {
  text-align: center;
}

.card-title {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  font-size: 1.75rem;
  font-weight: 700;
  color: #1a1a2e;
  letter-spacing: -0.02em;
}

.title-emoji {
  font-size: 1.5rem;
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
}

.card-subtitle {
  color: #6b7280;
  font-size: 1rem;
  font-weight: 400;
  margin: 0;
}

.values-list-container {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
  margin: 1.5rem 0;
  padding-right: 0.5rem;
  max-height: calc(95vh - 350px);
  transition: opacity 0.3s ease;
}

.values-list-container.is-moving {
  opacity: 0.7;
}

.values-list-container::-webkit-scrollbar {
  width: 6px;
}

.values-list-container::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.values-list-container::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 10px;
}

.values-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.value-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 16px;
  padding: 1.25rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.06),
    0 1px 3px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  animation: slideIn 0.4s ease-out backwards;
  animation-delay: var(--delay);
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.value-card:hover {
  transform: translateY(-2px);
  box-shadow: 
    0 8px 24px rgba(102, 126, 234, 0.15),
    0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: rgba(102, 126, 234, 0.2);
}

.value-controls {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.control-btn {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  border: none;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.control-btn:hover:not(.disabled) {
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.control-btn:active:not(.disabled) {
  transform: scale(0.95);
}

.control-btn.disabled {
  opacity: 0.4;
  cursor: not-allowed;
  box-shadow: none;
}

.position-badge {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.9rem;
  box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
}

.value-text {
  flex: 1;
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: -0.01em;
}

.card-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-top: 1.5rem;
  flex-shrink: 0;
}

.action-btn {
  padding: 0.875rem 2rem;
  border-radius: 12px;
  border: none;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  letter-spacing: -0.01em;
}

.action-btn::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.action-btn:hover::before {
  width: 300px;
  height: 300px;
}

.primary-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 
    0 4px 16px rgba(102, 126, 234, 0.4),
    0 2px 8px rgba(102, 126, 234, 0.2);
}

.primary-btn:hover {
  transform: translateY(-2px);
  box-shadow: 
    0 8px 24px rgba(102, 126, 234, 0.5),
    0 4px 12px rgba(102, 126, 234, 0.3);
}

.secondary-btn {
  background: rgba(102, 126, 234, 0.1);
  color: #667eea;
  border: 2px solid rgba(102, 126, 234, 0.2);
}

.secondary-btn:hover:not(:disabled) {
  background: rgba(102, 126, 234, 0.15);
  border-color: rgba(102, 126, 234, 0.3);
  transform: translateY(-2px);
}

.secondary-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.loading-text {
  display: inline-block;
  animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.list-enter-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.list-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.list-enter-from {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}

.list-leave-to {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}

.list-move {
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (max-width: 640px) {
  .modern-card {
    padding: 1.5rem;
  }
  
  .card-title {
    font-size: 1.5rem;
  }
  
  .value-card {
    padding: 1rem;
    gap: 1rem;
  }
  
  .card-actions {
    flex-direction: column;
  }
  
  .action-btn {
    width: 100%;
  }
}
</style>
