<template>
  <div id="app" class="min-h-screen bg-gradient-primary flex justify-center items-center p-5 font-sans">
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  // Initialisation si nécessaire
})
</script>

<style scoped>
#app {
  width: 100%;
  min-height: 100vh;
}
</style>

