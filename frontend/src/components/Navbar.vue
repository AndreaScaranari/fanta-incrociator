<script>
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <router-link to="/" class="navbar-brand">
                Fanta Incrociator ⚽
            </router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link" active-class="active">
                            Dashboard
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/easyscore" class="nav-link" active-class="active">
                            Incrociator
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
</template>

<style lang="scss" scoped>
@use "@/assets/style/colors" as *;

nav {
    height: 80px;
    font-size: 1.25rem;
    background-color: $black;
    color: $white;

    .navbar-brand {
        font-weight: 700;
        font-size: 1.75rem;
        color: $white;
    }
}
</style>