<template>
  <header class="header" :class="headerClassName">
    <template v-if="platform === 'desktop'">
      <div class="header__top">
        <div class="header__container container">
          <LogoText link />
          <ul class="header__top-links">
            <li v-for="obj in topLinks">
              <RouterLink
                class="header__top-link link link--white"
                :to="obj.to"
              >
                {{ obj.title }}
              </RouterLink>
            </li>
          </ul>
        </div>
      </div>
      <div class="header__main">
        <div class="header__container container">
          <FreeCall />
          <InputWrapper
            class="header__search"
            id="header-search"
            rounded
            icon="search"
            iconPos="left"
            placeholder="Поиск товара"
          />
          <ul class="header__main-icon-links">
            <li v-for="item in iconLinks" :key="item.icon">
              <ButtonIcon
                :icon="item.icon"
                type="router-link"
                :to="item.to"
                badge="2"
                shadow
              />
            </li>
          </ul>
        </div>
      </div>
      <div class="header__bottom">
        <div class="header__container container">
          <div class="header__bottom-catalog">
            <RouterLink class="link" :to="{ name: 'Catalog' }">
              <Icon type="menu" />
              <span> Каталог товаров</span>
            </RouterLink>
          </div>
          <ul class="header__bottom-links">
            <li v-for="link in bottomLinks">
              <RouterLink
                :to="link.to"
                class="header__bottom-link"
                :class="{ bold: link.bold }"
              >
                {{ link.title }}
              </RouterLink>
            </li>
          </ul>
          <HeaderAuthBlock class="header__auth" />
        </div>
      </div>
    </template>
    <template v-if="platform === 'mobile'" class="header__container">
      <div class="header-mobile__search" :class="mobileSearchClassName">
        <button
          class="header-mobile__search-button"
          type="button"
          aria-label="Открыть поиск"
          @click="toggleMobileSearch"
        >
          <Icon type="search" />
        </button>
        <div class="header-mobile__search-input-wrapper">
          <InputWrapper
            class="header-mobile__search-input"
            id="search"
            placeholder="Поиск товара"
          />
        </div>
      </div>
      <div class="header-mobile">
        <button
          class="header-mobile__menu-btn"
          type="button"
          aria-label="Открыть меню"
          ref="headerMobileMenuBtnEl"
          @click="toggleMenu"
        >
          <Icon type="menu" />
        </button>
        <div class="header-mobile__logo-wrapper">
          <LogoText class="header-mobile__logo" hideIcon hideText link />
        </div>
        <ul class="header-mobile__icons">
          <li v-for="item in iconLinks" :key="item.icon">
            <ButtonIcon
              :icon="item.icon"
              type="router-link"
              :to="item.to"
              badge="2"
            />
          </li>
        </ul>
      </div>
      <div class="header-mobile__menu-wrapper">
        <nav class="header-mobile__menu" v-click-away="closeMenu">
          <ul class="header-mobile__menu-list">
            <li class="header-mobile__menu-item">
              <button
                class="header-mobile__menu-item-inner --iconed --close"
                type="button"
              >
                <Icon type="chevron-right" rotate="180deg" />
                <span>Меню</span>
              </button>
            </li>
            <li class="header-mobile__menu-item">
              <RouterLink
                class="header-mobile__menu-item-inner --iconed"
                :to="{ name: 'Home' }"
              >
                <Icon type="headphones" />
                <span>Главная</span>
              </RouterLink>
            </li>
            <li class="header-mobile__menu-item">
              <RouterLink
                class="header-mobile__menu-item-inner --iconed"
                :to="{ name: 'Catalog' }"
              >
                <Icon type="menu" />
                <span>Каталог</span>
              </RouterLink>
            </li>
            <li class="header-mobile__menu-item">
              <HeaderAuthBlock class="header-mobile__auth-item --iconed" />
            </li>
            <li
              class="header-mobile__menu-item"
              v-for="item in topLinks"
              :key="item.title"
            >
              <RouterLink class="header-mobile__menu-item-inner" :to="item.to">
                {{ item.title }}
              </RouterLink>
            </li>
            <li
              v-for="item in bottomLinksMobile"
              :key="item.title"
              class="header-mobile__menu-item"
            >
              <RouterLink class="header-mobile__menu-item-inner" :to="item.to">
                {{ item.title }}
              </RouterLink>
            </li>
          </ul>
          <div class="header-mobile__free-call">
            <FreeCall />
          </div>
        </nav>
      </div>
    </template>
  </header>
</template>

<script setup lang="ts">
import Icon from "@/components/Blocks/Icon.vue"
import LogoText from "@/components/Blocks/LogoText.vue"
import topLinks from "@/enums/header/top-links"
import FreeCall from "@/components/Blocks/FreeCall.vue"
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import iconLinks from "@/enums/header/icon-links"
import bottomLinks from "@/enums/header/bottom-links"
import { computed, ref } from "vue"
import { useMediaQueries } from "@/utils/useMediaQueries"
import ButtonIcon from "@/components/Blocks/ButtonIcon.vue"
import HeaderAuthBlock from "@/components/Blocks/Header/HeaderAuthBlock.vue"
import vClickAway from "@/directives/vClickAway"

const mobileMedia = 991
const mediaQueries = useMediaQueries({
  media: mobileMedia,
})

const platform = computed<"mobile" | "desktop">(() =>
  mediaQueries.matches[mobileMedia] ? "mobile" : "desktop"
)

const bottomLinksMobile = computed(() =>
  bottomLinks.filter((item) => item.to.name !== "Home")
)

const isMobileShown = ref(false)
const headerClassName = computed(() => {
  return {
    shown: isMobileShown.value,
  }
})

const mobileSearchShown = ref(false)
const mobileSearchClassName = computed(() => {
  return { shown: mobileSearchShown.value }
})

const headerMobileMenuBtnEl = ref<HTMLElement>()

function toggleMobileSearch() {
  mobileSearchShown.value = !mobileSearchShown.value
}
function toggleMenu() {
  isMobileShown.value = !isMobileShown.value
}
function closeMenu(e: Event) {
  if (headerMobileMenuBtnEl.value?.contains(e.target as HTMLElement)) return
  isMobileShown.value = false
}
</script>

<style lang="scss">
.header {
  --header-height: 165px;

  box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.12);
  position: relative;
  background-color: var(--white);

  &__container {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__top {
    background-color: var(--purple-dark);
    padding: 5px 0;
  }
  &__top &__container {
    gap: 30px;
    color: var(--white);
  }

  &__top-links {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1.875rem;
    flex: 1 1 auto;
  }

  &__main {
    border-bottom: 1px solid var(--stroke);
    padding: 1.5rem 0;
  }
  &__main &__container {
    gap: 60px;
  }

  &__main-icon-links {
    display: flex;
    align-items: center;
    gap: 33px;
  }

  &__search {
    min-width: 19rem;
  }

  &__bottom &__container {
    align-items: stretch;
  }

  &__bottom-catalog,
  &__auth {
    border-right: 1px solid var(--stroke);
    border-left: 1px solid var(--stroke);
    padding: 0.75rem 1rem;
  }

  &__bottom-catalog {
    min-width: 16.875rem;

    a {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 1rem;
      @include fMedium(18);
    }
  }

  &__auth {
    min-width: 16.875rem;
  }

  &__bottom-links {
    display: flex;
    gap: 2.5rem;
    justify-content: center;
    padding: 0.75rem 3rem;
    flex: 1 1 auto;
  }

  &__bottom-link {
    position: relative;
    display: inline-block;
    transition: var(--general-transition);

    &:first-child {
      font-weight: 700;
    }

    &::after {
      content: "";
      opacity: 0;
      bottom: 0;
      width: 100%;
      height: 0.625rem;
      transition: var(--general-transition);
    }

    &.active,
    &.router-link-exact-active {
      color: var(--green);

      &::after {
        opacity: 1;
      }
    }

    &:hover {
      color: var(--green);
    }
  }

  @include adaptive(tablet-big) {
    --header-height: 53px;
    --search-btn-size: 22px;

    box-shadow: none;
    background: transparent;

    &.shown .header-mobile {
      border-bottom-left-radius: 0;
    }
  }
}

.header-mobile {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: var(--white);
  border-radius: 0 0 9px 9px;
  padding: 15px;
  transition: var(--general-transition);

  &__search {
    height: 100%;
    position: absolute;
    left: 45px;
    right: 0;
    top: 0;
    z-index: 100;
    display: flex;
    align-items: center;
  }

  &__search-button {
    position: relative;
    top: -3px;
    z-index: 50;
    font-size: var(--search-btn-size);
    color: var(--purple-dark);
  }

  &__search-input-wrapper {
    position: absolute;
    z-index: 20;
    left: -5px;
    right: 110%;
    background-color: var(--white);
    overflow: hidden;
    top: 50%;
    border: none;
    border-radius: 0;
    transform: translateY(-50%);
    transition: var(--general-transition);
  }
  &__search.shown &__search-input-wrapper {
    right: 15px;
  }
  &__search.shown + .header-mobile > .header-mobile {
    &__logo-wrapper,
    &__icons {
      opacity: 0;
    }
  }

  &__search-input {
    width: 100%;

    .input {
      padding-left: calc(var(--search-btn-size) + 15px);
    }
  }

  &__menu-btn {
    font-size: 16px;
    color: var(--purple-dark);
  }

  &__logo-wrapper,
  &__icons {
    transition: var(--general-transition);
  }

  &__logo {
    @include fBold(18);
    color: var(--purple-dark);
  }

  &__icons {
    display: flex;
    align-items: center;
    gap: 15px;
  }

  &__menu-wrapper {
    position: fixed;
    left: 0;
    top: var(--header-height);
    width: 100%;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 50;
    opacity: 0;
    transition: var(--general-transition);
  }
  .header.shown &__menu-wrapper {
    opacity: 1;
  }

  &__menu {
    height: 100%;
    transform: translateX(-100%);
    max-width: 300px;
    background-color: var(--white);
    overflow: auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--general-transition);
  }
  .header.shown &__menu {
    transform: translateX(0);
  }

  &__menu-item {
    border-bottom: 1px solid #ededed;
    padding: 16px 22px;
  }

  &__menu-item-inner {
    @include fRegular(12);

    &.--iconed {
      display: flex;
      align-items: center;
      gap: 22px;
      @include fMedium(14);

      .icon {
        color: var(--purple);
        opacity: 0.3;
        font-size: 21px;
      }
    }
    &.--close {
      @include fBold(18);

      .icon {
        opacity: 1;
      }
    }
  }

  &__auth-item {
    justify-content: flex-start;
  }

  &__free-call {
    padding: 15px;

    .btn-icon {
      display: none;
    }
  }
}
</style>
