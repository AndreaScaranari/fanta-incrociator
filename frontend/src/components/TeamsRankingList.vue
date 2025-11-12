<script setup>
defineProps({
    teams: {
        required: true,
        type: Object
    }
})
</script>

<template>
    <div class="row row-tierlist">
        <div v-for="(tierTeams, tier) in teams" :key="tier" class="col">
            <div class="card">
                <div class="card-header">
                    <h5>
                        Tier {{ tier }}
                    </h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li v-for="team in tierTeams" :key="team.id" class="list-group-item">
                        {{ team.nome }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
@use "@/assets/style/colors" as *;

.row-tierlist {
    display: flex;
    justify-content: center;
    row-gap: 1rem;

    .col {
        width: 20%;

        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

            &:hover {
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            .card-header {
                color: $white;
                background-color: $green;

                h5 {
                    margin-bottom: 0;
                }
            }

            .list-group-item {
                border-left: 4px solid transparent;
                transition: all 0.2s;
                font-weight: bold;


                &:hover {
                    border-left-color: $blue;
                    background-color: #f8f9fa;
                }
            }
        }
    }
}



@media (max-width: 991px) {
    .row-tierlist {
        margin: 0 -0.5rem;

        .col {
            padding: 0 0.5rem;
        }
    }
}

@media (max-width: 767px) {
    .row-tierlist {
        .col {
            width: 100%;
            flex: auto;
        }
    }
}
</style>