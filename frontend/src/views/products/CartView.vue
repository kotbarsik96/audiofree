<template>
    <div class="cart-page">
        <div class="container">
            <div class="cart-page__page-heading page-heading">
                <div class="breadcrumbs">
                    <RouterLink class="breadcrumbs__link link" :to="{ name: 'Home' }">
                        Главная
                    </RouterLink>
                    <RouterLink class="breadcrumbs__link link" :to="{ name: 'Cart' }">
                        Корзина
                    </RouterLink>
                </div>
                <h1 class="page-title">
                    Корзина
                </h1>
            </div>
            <div class="cart-page__body" v-if="cartList.length > 0">
                <LoadingScreen v-if="isLoading"></LoadingScreen>
                <div class="cart-page__list-container">
                    <ul class="cart-page__list-heading cart-list__heading">
                        <li class="cart-page__list-heading-item cart-list__column-image"></li>
                        <li class="cart-page__list-heading-item cart-list__column-name">
                            Название товара:
                        </li>
                        <li class="cart-page__list-heading-item cart-list__column-price">
                            Цена за штуку:
                        </li>
                        <li class="cart-page__list-heading-item cart-list__column-quantity">
                            Количество:
                        </li>
                        <li class="cart-page__list-heading-item cart-list__column-sum">
                            Сумма:
                        </li>
                        <li class="cart-page__list-heading-item cart-list__column-cancel"></li>
                    </ul>
                    <TransitionGroup tag="ul" class="cart-page__list cart-list"
                        v-if="cartList.length > 0 && cartList[0].productData" :css="false" @before-enter="onItemBeforeEnter"
                        @enter="onItemEnter" @leave="onItemLeave">
                        <li class="cart-list__item" v-for="(item, index) in cartList" :key="item.id">
                            <template v-if="item.productData">
                                <div class="cart-list__item-row cart-list__item-image cart-list__column-image">
                                    <RouterLink :to="{ name: 'Product', params: { productId: item.product_id } }">
                                        <ImagePicture :obj="item.productData"></ImagePicture>
                                        <!-- <img :src="getImagePath(item.productData.image_path)" :alt="item.productData.name"> -->
                                    </RouterLink>
                                </div>
                                <div class="cart-list__item-row cart-list__item-name cart-list__column-name">
                                    <RouterLink class="link"
                                        :to="{ name: 'Product', params: { productId: item.product_id } }">
                                        {{ item.productData.name }}
                                    </RouterLink>
                                </div>
                                <div class="cart-list__item-row cart-list__item-price cart-list__column-price">
                                    <span>Цена за штуку:</span>
                                    <span>
                                        {{ item.productData.current_price }} ₽
                                    </span>
                                </div>
                                <div class="cart-list__item-row cart-list__item-quantity cart-list__column-quantity">
                                    <QuantityInput v-model="cartList[index].quantity" :name="`product-${item.id}-quantity`"
                                        :id="`product-${item.id}-quantity`" :min="1"
                                        :max="cartList[index].productData.quantity" @update:modelValue="onItemChange(item)">
                                    </QuantityInput>
                                </div>
                                <div class="cart-list__item-row cart-list__item-sum cart-list__column-sum">
                                    <span>Итого:</span>
                                    <span>
                                        {{ getSum(item) }} ₽
                                    </span>
                                </div>
                                <div class="cart-list__item-row cart-list__item-cancel cart-list__column-cancel">
                                    <button type="button" @click="removeFromCart(item)">
                                        <CrossIcon></CrossIcon>
                                    </button>
                                </div>
                            </template>
                        </li>
                    </TransitionGroup>
                </div>
                <div class="cart-page__meta">
                    <div class="cart-page__code">
                        <CodeInput id="cart-code" name="cart-code" button="Активировать" placeholder="Введите код">
                            <template v-slot:label>
                                Введите Ваш код купона, если он у вас есть:
                            </template>
                        </CodeInput>
                    </div>
                    <div class="cart-page__total">
                        <div>
                            Сумма заказа:
                        </div>
                        <div>
                            {{ totalPrice }} ₽
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart-page__body" v-else>
                <div class="cart-page__empty">
                    <EmptyCartIcon></EmptyCartIcon>
                    <span>Ваша корзина пуста</span>
                    <RouterLink class="link" :to="{ name: 'Catalog' }">
                        За покупками!
                    </RouterLink>
                </div>
            </div>
            <div class="cart-page__bottom">
                <button class="checkout-button button button--colored" type="submit" :disabled="isCheckoutDisabled"
                    @click.prevent="createOrder">
                    Оформить заказ
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingScreen from '@/components/page/LoadingScreen.vue'
import QuantityInput from '@/components/inputs/QuantityInput.vue'
import CodeInput from '@/components/inputs/CodeInput.vue'
import { getHeight } from '@/assets/js/scripts.js'
import { mapState } from 'pinia'
import { useIndexStore } from '@/stores'
import { useNotificationsStore } from '@/stores/notifications.js'
import axios from 'axios'
import { gsap } from 'gsap'
import { nextTick } from 'vue'

export default {
    name: 'CartView',
    components: {
        LoadingScreen,
        QuantityInput,
        CodeInput
    },
    data() {
        return {
            isLoading: false,
            unsavedItems: [],
            updateCartTimeout: null,
            isLoaded: false,
            isCheckoutDisabled: true
        }
    },
    computed: {
        ...mapState(useIndexStore, ['cart', 'cartOneClick']),
        totalPrice() {
            return this.cartList.reduce((accumulator, currentValueObj) => {
                return accumulator + this.getSum(currentValueObj)
            }, 0)
        },
        isOneClick() {
            return Boolean(this.$route.name.match(/oneclick/i))
        },
        cartName() {
            return this.isOneClick ? 'cartOneClick' : 'cart'
        },
        cartList() {
            return this.isOneClick
                ? this.cartOneClick
                : this.cart
        }
    },
    methods: {
        getSum(item) {
            if (!item.productData)
                return 0
            return item.quantity * item.productData.current_price
        },
        async loadCart() {
            const store = useIndexStore()
            this.isLoaded = false
            await store.loadEntity(this.cartName, { allData: true })
            this.isLoaded = true
            this.isCheckoutDisabled = false
        },
        async removeFromCart(item) {
            const link = `${import.meta.env.VITE_USER_CART}${item.id}`
            this.isLoading = true

            try {
                const res = await axios.delete(link, {
                    data: {
                        isOneClick: this.isOneClick
                    }
                })
                if (Array.isArray(res.data.cart)) {
                    const store = useIndexStore()
                    store[this.cartName] = res.data.cart
                } else if (!res.data.success)
                    throw new Error()
            } catch (err) {
                useNotificationsStore().addNotification({
                    message: `Произошла ошибка при попытке удалить товар ${item.productData.name} из корзины`,
                    timeout: 5000
                })
            }

            this.isLoading = false
            this.loadCart()
        },
        onItemChange(item) {
            if (!this.isLoaded)
                return

            this.isCheckoutDisabled = true
            // вычислить максимальное количество товаров с этим id, которое может быть добавлено в корзину
            const maxQuantity = item.productData.quantity - this.cartList.reduce((acc, otherItem) => {
                if (otherItem.productData.id === item.productData.id && otherItem.id !== item.id)
                    return acc + otherItem.quantity
                return acc
            }, 0)

            if (!this.unsavedItems.find(o => o.id === item.id))
                this.unsavedItems.push(item)
            else
                this.unsavedItems = [...this.unsavedItems]

            nextTick().then(() => {
                // не дать возможность выбрать количество товаров с этим id больше, чем есть в наличии
                if (item.quantity > maxQuantity)
                    item.quantity = maxQuantity

                this.updateCart()
            })
        },
        /* загрузит новые данные в БД корзины */
        updateCart() {
            if (this.updateCartTimeout)
                clearTimeout(this.updateCartTimeout)

            this.updateCartTimeout = setTimeout(async () => {
                const link = `${import.meta.env.VITE_USER_CART}update`
                const store = useIndexStore()
                store.toggleLoading('updateCart', true)
                const isOneClick = this.$route.name.match(/oneclick/i)

                try {
                    const res = await axios.post(link, {
                        cart: this.cartList,
                        allData: true,
                        isOneClick
                    })
                    if (Array.isArray(res.data.cart))
                        store[this.cartName] = res.data.cart
                } catch (err) {
                    useNotificationsStore().addNotification({
                        message: 'Произошла ошибка при попытке обновления корзины'
                    })
                }

                this.unsavedItems = []
                store.toggleLoading('updateCart', false)
                this.isCheckoutDisabled = false
            }, 1000);
        },
        async createOrder() {
            const link = import.meta.env.VITE_ORDER_NEW_LINK

            try {
                const res = await axios.post(link, {
                    isOneClick: this.isOneClick
                })
                if (res.data) {
                    this.$router.push({ name: 'Order', params: { id: res.data } })
                } else {
                    throw new Error()
                }
            } catch (err) {
                let message = 'Произошла ошибка. Попробуйте оформить заказ позднее'
                if (err.response.data.error)
                    message = err && err.response && err.response.data.error

                useNotificationsStore().addNotification({
                    message,
                    timeout: 5000
                })
            }
        },
        // list animation - start
        onItemBeforeEnter(el) {
            const height = getHeight(el)
            gsap.set(el, {
                opacity: 0,
                marginBottom: height * (-1)
            })
        },
        onItemEnter(el, done) {
            gsap.to(el, {
                opacity: 1,
                marginBottom: 0,
                duration: 0.3,
                onComplete: done
            })
        },
        onItemLeave(el, done) {
            const height = getHeight(el)
            gsap.to(el, {
                opacity: 0,
                duration: 0.3,
                marginBottom: height * (-1),
                onComplete: done
            })
        }
        // list animation - end
    },
    watch: {
        async '$route.name'() {
            await this.$nextTick()
            this.loadCart()
        }
    },
    async mounted() {
        this.loadCart()
    },
}
</script>

<style lang="scss">
.cart-page {
    padding: 70px 0 50px 0;

    &__body {
        margin-top: 50px;
        border-bottom: 1px solid #dedede;
        padding-bottom: 50px;
        position: relative;
    }

    &__list-heading {
        display: flex;
    }

    &__meta {
        margin-top: 35px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    &__empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;

        svg {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
            color: var(--theme_color);
        }

        span,
        .link {
            font-size: 28px;
            line-height: 40px;
        }

        .link:not(:hover) {
            color: var(--theme_color);
        }
    }

    &__code {
        margin-right: 30px;

        .code-input {
            max-width: 360px;
        }
    }

    &__total {
        font-weight: 700;
        display: flex;
        align-items: center;
        position: relative;
        top: 12px;

        div:first-child {
            font-size: 18px;
            line-height: 21px;
            margin-right: 50px;
        }

        div:last-child {
            font-size: 24px;
            line-height: 28px;
        }
    }

    &__bottom {
        display: flex;
        justify-content: flex-end;
    }
}

.cart-list {

    &__heading {
        margin-bottom: 13px;
        font-size: 13px;
        line-height: 15px;
        color: #b9b9b9;
    }

    &__item {
        position: relative;
        display: flex;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
        border-radius: var(--border_radius);
        margin-bottom: 12px;
        padding-top: 5px;
        padding-bottom: 5px;
        transform-origin: top center;

        &:last-child {
            margin-bottom: 0;
        }
    }

    &__item-row {
        padding: 0 11px;
        position: relative;
        display: flex;
        align-items: center;
        color: #444;

        &:not(:last-child)::after {
            content: "";
            background-color: #eee;
            height: 40px;
            width: 1px;
            position: absolute;
            right: 0;
        }
    }

    &__item-image {
        padding-left: 0;

        picture,
        img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
    }

    &__item-name {
        font-size: 14px;
        line-height: 16px;
        font-weight: 500;
        padding-left: 30px;
    }

    &__item-price,
    &__item-sum {
        font-size: 18px;
        line-height: 21px;
        font-weight: 700;
        text-align: center;
        justify-content: center;

        span:first-child {
            display: none;
        }
    }

    &__item-quantity {
        justify-content: center;
        color: #737373;
    }

    &__item-cancel {
        justify-content: center;
        transform: translateY(3px);

        svg {
            color: #FF2222;
            width: 32px;
            height: 32px;
        }
    }
}

// сами ячейки и их названия
.cart-list {
    &__column-image {
        flex: 0 0 80px;
    }

    &__column-name {
        flex: 1 1 auto;
    }

    &__column-price {
        flex: 0 0 151px;
    }

    &__column-quantity {
        flex: 0 0 197px;
    }

    &__column-sum {
        flex: 0 0 155px;
    }

    &__column-cancel {
        flex: 0 0 73px;
    }
}

.cart-list__item,
.cart-list__heading {
    padding-left: 13px;
    padding-right: 13px;
}

@media (max-width: 949px) {
    .cart-page {
        padding: 25px 0 50px 0;

        &__total {
            div:last-child {
                font-size: 21px;
                line-height: 24px;
            }
        }
    }

    .cart-list {
        &__heading {
            display: none;
        }

        &__item {
            margin-bottom: 6px;
            padding: 13px;
            position: relative;
            display: grid;
            grid-template-columns: 80px 140px 1fr;
            grid-gap: 10px 15px;
        }

        &__item-row {
            &::after {
                display: none;
            }
        }

        &__item-image {
            grid-column: 1 / 2;
            grid-row: 1 / 3;
        }

        &__item-name {
            grid-column: 2 / -1;
            padding-left: 11px;
        }

        &__item-price {
            grid-column: 2 / -1;
        }

        &__item-quantity {
            grid-column: 2 / 3;
            justify-content: flex-start;
        }

        &__item-sum {
            grid-column: 3 / -1;

            span:last-child {
                color: var(--theme_color);
            }
        }

        &__item-price,
        &__item-sum {
            flex-direction: column;
            align-items: flex-start;

            span:first-child {
                display: block;
                color: #b9b9b9;
                font-size: 13px;
                line-height: 15px;
                font-weight: 400;
            }
        }

        &__item-cancel {
            position: absolute;
            top: 9px;
            right: 9px;
        }
    }
}

@media (max-width: 669px) {
    .cart-page {
        &__body {
            border-bottom: 0;
        }

        &__code {
            flex: 0 0 100%;
            margin-bottom: 20px;
            margin-right: 0;
        }

        &__total {
            flex: 0 0 100%;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
            padding: 20px 0;
        }
    }
}

@media (max-width: 399px) {
    .cart-page {
        &__total {
            justify-content: space-between;

            div:first-child {
                margin-right: 15px;
            }
        }
    }

    .cart-list {
        &__item {
            grid-template-columns: 80px 1fr;
        }

        &__item-sum {
            grid-column: 2 / 3;
        }
    }
}
</style>