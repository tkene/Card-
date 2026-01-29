<script setup>
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'
import ModernCard from '../components/ModernCard.vue'
import ActionButton from '../components/ActionButton.vue'

const router = useRouter()

const startGame = async () => {
  try {
    await gameService.initializeGame()
    router.push('/choose-colors')
  } catch (error) {
    console.error('Erreur lors de l\'initialisation:', error)
  }
}
</script>

<template>
  <ModernCard card-class="home-card">
    <template #header>
      <div class="home-icon">🃏</div>
      <h1 class="home-title">Jeu de cartes</h1>
      <p class="home-subtitle">Créez et triez vos mains selon vos règles</p>
    </template>

    <template #actions>
      <ActionButton
        label="Commencer"
        button-class="primary-btn start-btn"
        @click="startGame"
      />
    </template>
  </ModernCard>
</template>

<style scoped>
.home-card {
  text-align: center;
  padding: 3rem 2rem;
  align-items: center;
}

.home-icon {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  animation: float 3s ease-in-out infinite;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.home-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1a1a2e;
  letter-spacing: -0.02em;
  margin-bottom: 0.75rem;
}

.home-subtitle {
  color: #6b7280;
  font-size: 1.1rem;
  font-weight: 400;
  margin: 0;
}

.start-btn {
  padding: 1.25rem 4rem;
  font-size: 1.2rem;
}

@media (max-width: 640px) {
  .home-card {
    padding: 2rem 1.5rem;
  }
  
  .home-icon {
    font-size: 4rem;
  }
  
  .home-title {
    font-size: 2rem;
  }
}
</style>
