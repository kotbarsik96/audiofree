import DynamicAdaptive from '@/components/misc/DynamicAdaptive.vue'
import { checkIfFavorite, toggleFavorite } from '@/assets/js/methods.js'
import ConfirmModal from '@/components/modals/ConfirmModal.vue'
import QuantityInput from '@/components/inputs/QuantityInput.vue'
import SelectProductVariations from '@/components/page/sections/SelectProductVariations.vue'
import StarRating from '@/components/misc/StarRating.vue'
import { useModalsStore } from '@/stores/modals.js'
import { useNotificationsStore } from '@/stores/notifications.js'
import { useIndexStore } from '@/stores/'
import { h } from 'vue'
import { addToCart } from '@/assets/js/methods.js'
import axios from 'axios'
import { mapState } from 'pinia'

export default {
    components: {
        DynamicAdaptive,
        ConfirmModal,
        SelectProductVariations,
        QuantityInput,
        StarRating
    },
    props: {
        productData: {
            type: Object,
            required: true
        }
    },
    data: {
        isTopExpanded: false,
        isInFavorites: null,
        product: null,
        rating: 0
    },
    methods: {
        async updateProductData() {
            const res = await axios.get(`${import.meta.env.VITE_PRODUCT_GET_LINK}${this.product.id}`)
            if (res.data.id)
                this.product = res.data
        },
        checkIfFavorite,
        toggleFavorite,
        expandTop() {
            this.isTopExpanded = !this.isTopExpanded
        },
        async onAddToCartClick(isOneClick = false) {
            const store = useIndexStore()
            const loadVariations = async () => {
                const link = `${import.meta.env.VITE_PRODUCT_GET_LINK}${this.product.id}`
                const res = await axios.get(link, {
                    params: {
                        onlyOuterData: true
                    }
                })
                if (!Array.isArray(res.data.variations))
                    throw new Error()

                return res.data.variations
            }
            // выполнится при нажатии "в корзину" или "купить" (в случае "в 1 клик") в модальном окне
            const confirmCallback = async (modalWindowCtx) => {
                const cart = {
                    quantity: modalWindowCtx.$refs.nestedComponentRef[0].value,
                    variations: modalWindowCtx.$refs.nestedComponentRef[1].values,
                    productName: this.product.name,
                    isOneClick
                }
                await addToCart(this.product.id, cart)
                this.updateProductData()

                if (isOneClick)
                    this.$router.push({ name: 'CartOneClick' })
            }
            const confirmButtonText = isOneClick
                ? 'Перейти к оформлению' : 'Добавить в корзину';

            try {
                store.toggleLoading('addToCartCard', true)

                const variations = await loadVariations()
                // создаются компоненты QuantityInput и SelectProductVariations, которые помещаются в компонент ConfirmModal. При нажатии на "В корзину" внутри модального окна, что вызовет confirmProps.callback(), запрос будет отправлен на сервер
                const quantityComp = h(QuantityInput, {
                    class: 'mb-20',
                    name: 'product-popup-quantity',
                    id: 'product-popup-quantity',
                    min: 1,
                    max: this.product.available_quantity
                }, () => 'Количество')
                const variationsComp = h(SelectProductVariations, { variations })
                const component = h(ConfirmModal, {
                    title: 'Выберите параметры',
                    nestedComponents: [quantityComp, variationsComp],
                    confirmProps: {
                        text: confirmButtonText,
                        callback: confirmCallback
                    }
                })
                useModalsStore().addModal({ component })
            } catch (err) {
                useNotificationsStore().addNotification({
                    message: 'Произошла ошибка при попытке добавить товар в корзину',
                    timeout: 3500
                })
            }

            store.toggleLoading('addToCartCard', false)
        },
        getDescription(product) {
            if (!product.description)
                return ''

            const json = JSON.parse(product.description)
            if (!json.blocks)
                return ''
            const firstParagraph = json.blocks[0]

            console.log(firstParagraph);
            return firstParagraph ? firstParagraph.data.text : '' || ''
        }
    },
    computed: {
        ...mapState(useIndexStore, ['favoritesCount']),
        isSmallQuantity() {
            return this.product.available_quantity < 9 && this.product.available_quantity > 0
        },
        isOutOfStock() {
            return this.product.available_quantity < 1
        }
    },
    watch: {
        favoritesCount() {
            this.checkIfFavorite()
        }
    },
    mounted() {
        this.product = this.productData
        this.checkIfFavorite()
    }
}