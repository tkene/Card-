<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import { useQuasar } from 'quasar'
import ModernCard from '../components/ModernCard.vue'
import ActionButton from '../components/ActionButton.vue'
import CardsGrid from '../components/CardsGrid.vue'
import '../styles/common.css'

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

<template>
  <div class="cards-page">
    <ModernCard
      title="Main triée"
      subtitle="Les cartes sont maintenant triées selon les règles :"
      emoji="📏"
      fullscreen
      card-class="custom-scrollbar"
    >
      <q-inner-loading :showing="loading" />

      <div 
        v-if="!loading" 
        class="cards-grid-container custom-scrollbar"
      >
      <CardsGrid :cards="sortedCards" :sorted="true" />
      </div>

      <template #actions>
        <ActionButton
          label="Retour vers la main non triée"
          button-class="secondary-btn"
          @click="goToUnsortedCards"
        />
        <ActionButton
          label="Reseter la partie"
          button-class="primary-btn"
          @click="resetGame"
        />
      </template>
    </ModernCard>
  </div>
</template>
