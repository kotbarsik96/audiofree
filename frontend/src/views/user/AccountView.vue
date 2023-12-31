<template>
    <div class="container">
        <div class="account">
            <nav class="tile-nav card">
                <ul class="tile-nav__list card__container">
                    <li class="tile-nav__item">
                        <RouterLink :to="{ name: 'AccountInfo' }" class="link">
                            Общая информация
                        </RouterLink>
                    </li>
                    <li class="tile-nav__item">
                        <RouterLink :to="{ name: 'ChangePassword' }" class="link">
                            Смена пароля
                        </RouterLink>
                    </li>
                    <li class="tile-nav__item" v-if="!emailVerified">
                        <RouterLink :to="{ name: 'EmailVerification' }" class="link">
                            Подтверждение email
                        </RouterLink>
                    </li>
                </ul>
            </nav>
            <div class="account__body card">
                <div class="card__container">
                    <RouterView v-slot="{ Component }">
                        <Transition name="account-body" mode="out-in">
                            <component :is="Component" :user="user"></component>
                        </Transition>
                    </RouterView>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useIndexStore } from '@/stores'
import { mapState } from 'pinia'
import axios from 'axios'

export default {
    name: 'AccountView',
    computed: {
        ...mapState(useIndexStore, ['emailVerified'])
    },
    data() {
        return {
            user: {}
        }
    },
    methods: {
        async loadUser() {
            const store = useIndexStore()
            store.toggleLoading('loadAccountUser', true)

            try {
                const res = await axios.get(`${import.meta.env.VITE_USER_GET_LINK}current`)
                if (res.data.id)
                    this.user = res.data
            } catch (err) {
                this.$router.push({ name: 'Home' })
            }

            store.toggleLoading('loadAccountUser', false)
        }
    },
    mounted() {
        this.loadUser()
    }
}
</script>

<style lang="scss">
.account {
    display: flex;
    align-items: flex-start;
    min-height: 50vh;
    padding: 70px 0;

    &__body {
        flex: 1 1 auto;

        .card {
            &__container {
                padding: 15px;
            }
        }

        &::before {
            transform: translateY(12px) scale(0.96);
        }
    }

    &__container {
        &--min-height {
            min-height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    }

    &__buttons {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        margin-top: -10px;

        .button {
            font-size: 16px;
            min-width: 150px;
            margin: 10px 5px 0 10px;
        }
    }

    .tile-nav {
        margin-right: 20px;
        flex-shrink: 0;

        &::before {
            transform: translateY(8px) scale(0.96);
        }
    }

    @media (max-width: 992px) {
        flex-wrap: wrap;

        &__body {
            flex: 0 0 100%;
        }

        .tile-nav {
            margin-right: 0;
            margin-bottom: 20px;
        }
    }
}

.account-body {

    &-enter-active,
    &-leave-active {
        transition: all .25s;
    }

    &-leave-to {
        opacity: 0;
    }

    &-enter-from {
        transform: scale(0);
    }

    &-enter-to {
        transform: scale(1);
    }
}
</style>