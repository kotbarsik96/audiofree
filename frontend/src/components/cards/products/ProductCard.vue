<template>
    <div class="card product-card">
        <div class="card__container product-card__container" v-if="product">
            <button class="product-card__expand-top circle-wrapper" :class="{ '__active': isTopExpanded }" aria-label="Показать дополнительную информацию и кнопки" type="button"
                @click="expandTop">
                <PlusIcon></PlusIcon>
            </button>
            <div class="product-card__top" :class="{ '__expanded': isTopExpanded }">
                <InStockPlaceholder class="product-card__stock" type="in-stock"></InStockPlaceholder>
                <div class="product-card__circle-buttons">
                    <button class="circle-wrapper circle-wrapper--shadow circle-wrapper--gray"
                        :class="{ '__active': isInFavorites }" aria-label="Добавить в избранное" type="button" :disabled="isInFavorites === null"
                        @click="toggleFavorite">
                        <HeartIcon></HeartIcon>
                    </button>
                </div>
                <div data-prodcard-mobile-buttons></div>
            </div>
            <div class="product-card__image-container">
                <RouterLink class="link" :to="{ name: 'Product', params: { productId: product.id } }" :aria-label="product.name">
                    <ImagePicture class="product-card__image" :obj="product" :alt="product.name" suffix="195x195"></ImagePicture>
                </RouterLink>
            </div>
            <div class="product-card__title">
                <RouterLink class="link" :to="{ name: 'Product', params: { productId: product.id } }">
                    {{ product.name }}
                </RouterLink>
            </div>
            <div class="product-card__flex">
                <div class="product-card__flex-item">
                    <StarRating :stars="5" :rating="product.rating_value || 0" v-model="rating"></StarRating>
                </div>
                <div class="product-card__flex-item product-card__price">
                    {{ product.current_price ? product.current_price.toLocaleString() : '' }} ₽
                </div>
            </div>
            <DynamicAdaptive query="max-width: 599px" destinationSelector="[data-prodcard-mobile-buttons]"
                :maxParentsCount="1">
                <div class="product-card__buttons">
                    <button class="button button--colored" v-if="!isOutOfStock" type="button" @click="onAddToCartClick(true)">
                        Купить в 1 клик
                    </button>
                    <button class="button" v-if="!isOutOfStock" type="button" @click="onAddToCartClick(false)">
                        В корзину
                    </button>
                    <div class="product-card__out-of-stock" v-if="isOutOfStock">
                        Товар закончился
                    </div>
                </div>
            </DynamicAdaptive>
        </div>
    </div>
</template>

<script>
import injectShared from '@/components/inject-shared.js'
import shared from './shared.js'
import InStockPlaceholder from '@/components/misc/InStockPlaceholder.vue'


const componentData = injectShared(shared, {
    name: 'ProductCard',
    components: {
        InStockPlaceholder,
    },
    data() {
        return Object.assign({}, shared.data)
    },
})
export default componentData
</script>