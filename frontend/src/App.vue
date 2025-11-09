<template>
  <div id="app">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <router-link to="/" class="navbar-brand">
          ⚽ Fanta Incrociator
        </router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link to="/" class="nav-link" active-class="active">
                Home
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/ranking" class="nav-link" active-class="active">
                Modifica Ranking
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5 border-top">
      <div class="container-fluid">
        <div class="row">
          <div class="col text-center text-muted">
            <small>
              Fanta Incrociator v1.0 | Made with ❤️ by Andrea Scaranari
            </small>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
</script>

<style scoped lang="scss">
#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
}

.navbar-brand {
  font-weight: 700;
  font-size: 1.25rem;
}

footer {
  margin-top: auto;
}
</style>