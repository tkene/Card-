<template>
  <div class="modern-card home-card">
    <div class="card-header">
      <div class="header-content">
        <div class="home-icon">🃏</div>
        <h1 class="card-title">Jeu de cartes</h1>
        <p class="card-subtitle">Créez et triez vos mains selon vos règles</p>
      </div>
    </div>

    <div class="card-actions">
      <button
        @click="startGame"
        class="action-btn primary-btn start-btn"
      >
        Commencer
      </button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { gameService } from '../services/gameService'

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
  padding: 3rem 2rem;
  max-width: 700px;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.home-card {
  text-align: center;
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

.card-header {
  margin-bottom: 2rem;
}

.card-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1a1a2e;
  letter-spacing: -0.02em;
  margin-bottom: 0.75rem;
}

.card-subtitle {
  color: #6b7280;
  font-size: 1.1rem;
  font-weight: 400;
  margin: 0;
}

.card-actions {
  margin-top: 1rem;
}

.action-btn {
  padding: 1rem 3rem;
  border-radius: 12px;
  border: none;
  font-size: 1.1rem;
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

.start-btn {
  padding: 1.25rem 4rem;
  font-size: 1.2rem;
}

@media (max-width: 640px) {
  .modern-card {
    padding: 2rem 1.5rem;
  }
  
  .home-icon {
    font-size: 4rem;
  }
  
  .card-title {
    font-size: 2rem;
  }
}
</style>
