
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import { useQuasar } from 'quasar'
import ModernCard from '../components/ModernCard.vue'
import ActionButton from '../components/ActionButton.vue'

const router = useRouter()
const $q = useQuasar()
const numberOfCards = ref(null)
const error = ref(null)

const confirmCardsNumber = async () => {
  error.value = null
  
  if (!numberOfCards.value || numberOfCards.value < 1 || numberOfCards.value > 52) {
    error.value = 'Le nombre de cartes doit être entre 1 et 52.'
    return
  }

  try {
    await gameService.confirmCardsNumber(numberOfCards.value)
    router.push('/show-cards')
  } catch (err) {
    const errorMessage = err.response?.data?.error || 'Erreur lors de la confirmation'
    error.value = errorMessage
    $q.notify({
      type: 'negative',
      message: errorMessage
    })
  }
}
</script>

<template>
  <ModernCard
    title="Étape 3 : Choisir le nombre de cartes"
    subtitle="Combien de cartes veux-tu tirer ?"
    emoji="🃏"
  >

    <div v-if="error" class="error-banner">
      {{ error }}
    </div>

    <form @submit.prevent="confirmCardsNumber" class="card-form">
      <div class="input-container">
        <div class="input-card">
          <div class="card-icon">🃏</div>
          <input
            v-model.number="numberOfCards"
            type="number"
            min="1"
            max="52"
            placeholder="7"
            class="number-input"
            required
          />
          <span class="input-label">cartes</span>
        </div>
      </div>
    </form>

    <template #actions>
      <ActionButton
        label="Confirmer"
        button-class="primary-btn"
        @click="confirmCardsNumber"
      />
    </template>
  </ModernCard>
</template>

<style scoped>

.error-banner {
  background: rgba(239, 68, 68, 0.1);
  border: 2px solid rgba(239, 68, 68, 0.3);
  color: #dc2626;
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
  text-align: center;
}

.card-form {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.input-container {
  display: flex;
  justify-content: center;
}

.input-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 16px;
  padding: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 
    0 2px 8px rgba(0, 0, 0, 0.06),
    0 1px 3px rgba(0, 0, 0, 0.04);
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.card-icon {
  font-size: 3rem;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

.number-input {
  width: 80px;
  text-align: center;
  font-size: 2rem;
  font-weight: 700;
  color: #1a1a2e;
  background: transparent;
  border: 2px solid rgba(102, 126, 234, 0.2);
  border-radius: 12px;
  padding: 0.5rem;
  outline: none;
  transition: all 0.3s ease;
}

.number-input:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.number-input::placeholder {
  color: #9ca3af;
}

.input-label {
  color: #6b7280;
  font-size: 1.1rem;
  font-weight: 500;
}


@media (max-width: 640px) {
  .input-card {
    padding: 1.5rem;
    flex-direction: column;
  }
  
  .number-input {
    width: 100%;
  }
}
</style>
