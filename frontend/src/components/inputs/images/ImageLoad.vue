<template>
    <div class="image-load">
        <h4 v-if="$slots.title" class="image-load__title">
            <slot name="title"></slot>
        </h4>
        <Transition name="grow">
            <div v-if="error" class="error image-load__error--top">
                {{ error }}
            </div>
        </Transition>
        <div class="image-load__wrapper" @click="onAddClick">
            <LoadingScreen v-if="isLoading"></LoadingScreen>
            <Transition name="scale-up" mode="out-in">
                <div v-if="modelValue.image_path" class="image-load__container">
                    <button class="image-load__remove" type="button" @click.stop="onRemoveClick">
                        <TrashCanCircleIcon></TrashCanCircleIcon>
                    </button>
                    <ImagePicture class="image-load__image" :obj="this.modelValue" :alt="alt"></ImagePicture>
                </div>
                <div v-else class="image-load__icon">
                    <PlusCircleIcon></PlusCircleIcon>
                </div>
            </Transition>
            <input type="file" accept="image/png, image/jpeg" ref="input" @change="onChange">
        </div>
        <Transition name="grow">
            <div v-if="errors.image" class="error">
                {{ errors.image[0] }}
            </div>
        </Transition>
    </div>
</template>

<script>
import axios from 'axios'
import LoadingScreen from '@/components/page/LoadingScreen.vue'
import { useModalsStore } from '@/stores/modals.js'
import ConfirmModal from '@/components/modals/ConfirmModal.vue'
import { h } from 'vue'

export default {
    name: 'ImageLoad',
    emits: ['update:modelValue', 'update:id'],
    props: {
        modelValue: {
            type: Object,
            required: true
        },
        id: {
            type: Number,
            required: true
        },
        alt: {
            type: String,
            default: 'Загруженное изображение'
        },
    },
    components: {
        LoadingScreen
    },
    data() {
        return {
            error: '',
            errors: {
                image: ''
            },
            isLoading: false,
            addClicked: false
        }
    },
    methods: {
        openExplorer() {
            this.$refs.input.click()
        },
        nullifyErrors() {
            this.errors = []
            this.error = ''
        },
        onAddClick() {
            if (this.addClicked)
                return

            this.addClicked = true
            const component = h(ConfirmModal, {
                onlyConfirm: true,
                confirmProps: {
                    text: 'Загрузить из галереи на сайте',
                    callback: () => {
                        this.createModalGallery()
                        this.addClicked = false
                    }
                },
                confirmButtons: [
                    {
                        text: 'Загрузить с устройства',
                        callback: () => {
                            this.openExplorer()
                            this.addClicked = false
                        }
                    }
                ],
            })
            useModalsStore().addModal({ component })
        },
        async createModalGallery() {
            const callback = (modalCtx, selectedIds, selectedGallery) => {
                if (selectedGallery.length < 1)
                    return

                this.$emit('update:modelValue', selectedGallery[0])
                this.$emit('update:id', selectedGallery[0].id)
            }

            useModalsStore().addModal({
                component: 'GalleryModal',
                props: {
                    title: 'Изображение для товара',
                    confirmData: { callback },
                    singleSelect: true,
                    withPagination: true
                }
            })
        },
        async onChange() {
            this.isLoading = true

            this.nullifyErrors()
            const file = this.$refs.input.files[0]
            if (!file)
                return

            const data = new FormData()
            data.append('image', file)
            try {
                const res = await axios.post(import.meta.env.VITE_IMAGE_LOAD_LINK, data)
                if (res.data.id) {
                    this.$emit('update:modelValue', res.data)
                    this.$emit('update:id', res.data.id)
                }
            } catch (err) {
                const errorsList = err.response.data.errors
                if (errorsList && typeof errorsList === 'object')
                    this.errors = errorsList
                else
                    this.error = 'Произошла ошибка'
            }

            this.isLoading = false
            this.nullifyFileList()
        },
        onRemoveClick() {
            const component = h(ConfirmModal, {
                title: 'Удалить из галереи или только открепить от товара?',
                declineProps: {
                    text: 'Отменить'
                },
                confirmProps: {
                    text: 'Удалить из галереи',
                    callback: this.removeImage
                },
                confirmButtons: [
                    {
                        text: 'Открепить от товара',
                        callback: () => {
                            this.$emit('update:modelValue', {})
                            this.$emit('update:id', 0)
                        }
                    }
                ]
            })

            useModalsStore().addModal({ component })
        },
        async removeImage() {
            this.isLoading = true
            this.nullifyErrors()

            const link = `${import.meta.env.VITE_IMAGE_DELETE_LINK}${this.id}`
            try {
                const res = await axios.delete(link)
                if (res.data.success) {
                    this.$emit('update:modelValue', {})
                    this.$emit('update:id', 0)
                }
            } catch (err) {
                this.error = err.response.data.error
            }

            this.nullifyFileList()
            this.isLoading = false
        },
        nullifyFileList() {
            const dt = new DataTransfer()
            this.$refs.input.files = dt.files
        },
    }
}
</script>

<style lang="scss">
.image-load {
    position: relative;
    width: 400px;

    &__title {
        font-size: 21px;
        line-height: 24px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    &__error--top {
        margin-bottom: 5px;
    }

    &__wrapper {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #F4F4F4;
        position: relative;
        width: 100%;
        height: 227px;
    }

    &__container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    &__remove {
        position: absolute;
        right: -45px;
        top: 0px;
        width: 35px;
        height: 35px;
        color: var(--error_color);

        svg {
            width: 100%;
            height: 100%;
        }
    }

    &__icon {
        width: 51px;
        height: 51px;
        color: #000;

        svg {
            width: 100%;
            height: 100%;
        }
    }

    &__image {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    input {
        display: none;
    }

    @media (max-width: 767px) {
        width: 100%;

        &__remove {
            right: 5px;
            top: 5px;
        }
    }
}
</style>