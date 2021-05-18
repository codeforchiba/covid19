import { NuxtI18nHead } from 'nuxt-i18n/types/nuxt-i18n'

declare module 'vue/types/vue' {
  interface Vue {
    $nuxtI18nHead(): NuxtI18nHead
  }
}
