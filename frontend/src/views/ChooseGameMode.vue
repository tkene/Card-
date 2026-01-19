<template>
  <div class="modern-card">
    <div class="card-header">
      <div class="header-content">
        <h1 class="card-title">
          <span class="title-text">Étape 3 : Choisir le nombre de cartes</span>
          <span class="title-emoji">🃏</span>
        </h1>
        <p class="card-subtitle">
          Combien de cartes veux-tu tirer ?
        </p>
      </div>
    </div>

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

      <div class="card-actions">
        <button
          type="submit"
          class="action-btn primary-btn"
        >
          Confirmer
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import { useQuasar } from 'quasar'

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

<style scoped>
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

.card-actions {
  display: flex;
  gap: 1rem;
  justify-content: center;
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

.primary-btn:active {
  transform: translateY(0);
}

@media (max-width: 640px) {
  .modern-card {
    padding: 1.5rem;
  }
  
  .card-title {
    font-size: 1.5rem;
  }
  
  .input-card {
    padding: 1.5rem;
    flex-direction: column;
  }
  
  .number-input {
    width: 100%;
  }
}
</style>
