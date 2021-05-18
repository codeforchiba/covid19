import type { NuxtVueI18n } from 'nuxt-i18n'

const options: NuxtVueI18n.Options.AllOptionsInterface = {
  strategy: 'no_prefix',
  defaultLocale: 'ja',
  vueI18n: {
    fallbackLocale: 'ja',
    formatFallbackMessages: true,
  },
  lazy: true,
  langDir: 'assets/locales/',
  locales: [
    {
      code: 'ja',
      name: '日本語',
      iso: 'ja-JP',
      file: 'ja.json',
      description: 'Japanese',
    },
  ],
}

export default options
