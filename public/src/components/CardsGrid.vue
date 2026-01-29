<template>
  <div class="cards-grid">
    <transition-group name="card" tag="div" class="cards-container">
      <CardItem
        v-for="(card, index) in cards"
        :key="`${card.value}-${card.color}-${index}`"
        :card="card"
        :sorted="sorted"
        :delay="index * 0.03"
      />
    </transition-group>
  </div>
</template>

<script setup>
import CardItem from './CardItem.vue'

defineProps({
  cards: {
    type: Array,
    required: true
  },
  sorted: {
    type: Boolean,
    default: false
  }
})
</script>

<style scoped>
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

/* Transitions pour les cartes */
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
</style>

