<script setup>
import Footer from './components/Footer.vue';
import Navbar from './components/Navbar.vue';
</script>

<template>
  <div id="app">
    <!-- Navbar -->
    <Navbar />

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<style scoped lang="scss">
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
}
</style>