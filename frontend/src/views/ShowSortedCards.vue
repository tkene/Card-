<template>
  <div class="cards-page">
    <div class="modern-card cards-card">
      <div class="card-header">
        <div class="header-content">
          <h1 class="card-title">
            <span class="title-text">Étape 5 : Main triée</span>
            <span class="title-emoji">📏</span>
          </h1>
          <p class="card-subtitle">
            Les cartes sont maintenant triées selon les règles :
          </p>
          <div class="rules-box">
            <p class="rule-item">
              <strong>1.</strong> D'abord par couleur, selon l'ordre choisi à l'étape 1
            </p>
            <p class="rule-item">
              <strong>2.</strong> Ensuite par valeur, selon l'ordre choisi à l'étape 2
            </p>
          </div>
        </div>
      </div>

      <q-inner-loading :showing="loading" />

      <div v-if="!loading" class="cards-grid">
        <transition-group name="card" tag="div" class="cards-container">
          <div
            v-for="(card, index) in sortedCards"
            :key="`${card.value}-${card.color}-${index}`"
            class="card-item sorted"
            :style="{ '--delay': index * 0.03 + 's' }"
          >
            <div class="card-symbol">{{ card.symbol }}</div>
            <div class="card-value">{{ card.value }}</div>
            <div class="card-color">{{ card.color }}</div>
          </div>
        </transition-group>
      </div>

      <div class="card-actions">
        <button
          @click="goToUnsortedCards"
          class="action-btn secondary-btn"
        >
          Retour vers la main non triée
        </button>
        <button
          @click="resetGame"
          class="action-btn primary-btn"
        >
          Reseter la partie
        </button>
      </div>
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
const sortedCards = ref([])
const loading = ref(true)

const loadSortedCards = async () => {
  try {
    loading.value = true
    const data = await gameService.getSortedHand()
    if (data.sortedCards) {
      sortedCards.value = data.sortedCards
    }
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors du chargement des cartes triées'
    })
  } finally {
    loading.value = false
  }
}

const goToUnsortedCards = () => {
  router.push('/show-cards')
}

const resetGame = async () => {
  try {
    await gameService.resetGame()
    router.push('/')
  } catch (error) {
    console.error('Erreur lors de la réinitialisation:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors de la réinitialisation'
    })
  }
}

onMounted(() => {
  loadSortedCards()
})
</script>

<style scoped>
.cards-page {
  width: 100%;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.cards-card {
  max-width: 95vw;
  max-height: 95vh;
  overflow-y: auto;
}

.cards-card::-webkit-scrollbar {
  width: 6px;
}

.cards-card::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.cards-card::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 10px;
}

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
  width: 100%;
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
  margin: 0 0 1rem 0;
}

.rules-box {
  background: rgba(102, 126, 234, 0.05);
  border: 2px solid rgba(102, 126, 234, 0.15);
  border-radius: 16px;
  padding: 1.25rem;
  margin-top: 1rem;
  text-align: left;
}

.rule-item {
  color: #1a1a2e;
  font-size: 0.95rem;
  margin: 0.5rem 0;
  line-height: 1.6;
}

.rule-item strong {
  color: #667eea;
  font-weight: 700;
}

.cards-grid {
  flex: 1;
  min-height: 0;
  margin: 1.5rem 0;
}

.cards-container {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: center;
}

.card-item {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 16px;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.06),
    0 1px 3px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  animation: cardAppear 0.4s ease-out backwards;
  animation-delay: var(--delay);
  width: calc(20% - 0.8rem);
  min-width: 120px;
  aspect-ratio: 2/3;
}

.card-item.sorted {
  border-color: rgba(102, 126, 234, 0.2);
  background: linear-gradient(135deg, #ffffff 0%, #f0f4ff 100%);
}

@keyframes cardAppear {
  from {
    opacity: 0;
    transform: scale(0.8) rotateY(-10deg);
  }
  to {
    opacity: 1;
    transform: scale(1) rotateY(0deg);
  }
}

.card-item:hover {
  transform: translateY(-4px) scale(1.05);
  box-shadow: 
    0 8px 24px rgba(102, 126, 234, 0.2),
    0 4px 12px rgba(0, 0, 0, 0.1);
  border-color: rgba(102, 126, 234, 0.3);
}

.card-symbol {
  font-size: 3rem;
  line-height: 1;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.card-value {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1a1a2e;
  text-align: center;
}

.card-color {
  font-size: 0.95rem;
  color: #6b7280;
  text-align: center;
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

.secondary-btn:hover {
  background: rgba(102, 126, 234, 0.15);
  border-color: rgba(102, 126, 234, 0.3);
  transform: translateY(-2px);
}

.card-enter-active {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-enter-from {
  opacity: 0;
  transform: scale(0.8) rotateY(-20deg);
}

.card-leave-to {
  opacity: 0;
  transform: scale(0.8) rotateY(20deg);
}

.card-move {
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (max-width: 1024px) {
  .card-item {
    width: calc(25% - 0.75rem);
  }
}

@media (max-width: 768px) {
  .card-item {
    width: calc(33.333% - 0.67rem);
    min-width: 100px;
  }
}

@media (max-width: 640px) {
  .modern-card {
    padding: 1.5rem;
  }
  
  .card-item {
    width: calc(50% - 0.5rem);
  }
  
  .card-title {
    font-size: 1.5rem;
  }
  
  .card-actions {
    flex-direction: column;
  }
  
  .action-btn {
    width: 100%;
  }
}
</style>
