
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
const drawnCards = ref([])
const loading = ref(true)

const loadCards = async () => {
  try {
    loading.value = true
    const data = await gameService.getUnsortedHand()
    if (data.drawnCards) {
      drawnCards.value = data.drawnCards
    }
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
    $q.notify({
      type: 'negative',
      message: 'Erreur lors du chargement des cartes'
    })
  } finally {
    loading.value = false
  }
}

const goToSortedCards = () => {
  router.push('/show-sorted-cards')
}

onMounted(() => {
  loadCards()
})
</script>

<template>
  <div class="cards-page">
    <ModernCard
      title="Main non triée"
      subtitle="Les cartes tirées arrivent dans le désordre, comme quand on pioche."
      emoji="🙃"
      fullscreen
      card-class="custom-scrollbar"
    >
      <q-inner-loading :showing="loading" />
      <div 
        v-if="!loading" 
        class="cards-grid-container custom-scrollbar"
      >
      <CardsGrid :cards="drawnCards" />
      </div>
      <template #actions>
        <ActionButton
          label="Continuer vers la main triée"
          button-class="primary-btn"
          @click="goToSortedCards"
        />
      </template>
    </ModernCard>
  </div>
</template>
