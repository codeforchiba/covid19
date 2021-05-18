<template>
  <div class="SideNavigation">
    <div class="SideNavigation-HeadingContainer sp-flex">
      <v-icon
        class="SideNavigation-HeadingIcon pc-none"
        :aria-label="$t('Navi Open')"
        @click="openNavi"
      >
        mdi-menu
      </v-icon>
      <nuxt-link to="/" class="SideNavigation-HeadingLink">
        <div class="SideNavigation-Logo">
          <img src="/logo.svg" :alt="$t('Chiba')" />
        </div>
        <h1 class="SideNavigation-Heading">
          {{ $t('COVID-19') }}<br />{{ $t('Measures site') }}
        </h1>
      </nuxt-link>
    </div>
    <v-divider class="SideNavigation-HeadingDivider" />
    <div class="sp-none" :class="{ open: isNaviOpen }">
      <v-icon
        class="SideNavigation-ListContainerIcon pc-none"
        :aria-label="$t('Navi Close')"
        @click="closeNavi"
      >
        mdi-close
      </v-icon>
      <v-list :flat="true" class="mx-4">
        <template
          v-for="(item, i) in items"
          class="SideNavigation-ListItemContainer"
        >
          <ListItem
            :key="i"
            :link="item.link"
            :icon="item.icon"
            :title="item.title"
          />
          <v-divider
            v-show="item.divider"
            :key="i"
            class="SideNavigation-Divider"
          />
        </template>
        <FeedbackListItem />
      </v-list>
      <div class="SideNavigation-Footer">
        <div class="SideNavigation-SocialLinkContainer">
          <a
            href="https://twitter.com/code4chiba"
            target="_blank"
            rel="noopener"
          >
            <img src="/twitter.png" alt="Twitter" />
          </a>
          <a
            href="https://www.facebook.com/codeforchiba"
            target="_blank"
            rel="noopener"
          >
            <img src="/facebook.png" alt="Facebook" />
          </a>
        </div>
        <small class="SideNavigation-Copyright" lang="en">
          Powered by Code for Chiba
        </small>
      </div>
    </div>
  </div>
</template>

<script>
import FeedbackListItem from '@/components/FeedbackListItem'
import ListItem from '@/components/ListItem'

export default {
  components: {
    ListItem,
    FeedbackListItem,
  },
  props: {
    isNaviOpen: {
      type: Boolean,
      required: true,
    },
  },
  computed: {
    items() {
      return [
        {
          icon: 'mdi-chart-timeline-variant',
          title: this.$t('The latest updates'),
          link: '/',
        },
        {
          icon: 'mdi-shield-half-full',
          title: this.$t('All we have to do to prevent infection is simple.'),
          link: 'https://www.bowlgraphics.net/covid19',
        },
        {
          icon: 'mdi-silverware-variant',
          title: this.$t('Support with takeout'),
          link: 'https://togo.code4chiba.org/',
          divider: true,
        },
        {
          icon: 'mdi-web',
          title: this.$t('Government special website'),
          link: 'https://www.city.chiba.jp/hokenfukushi/iryoeisei/seisaku/kansensyoujyouhou.html',
        },
        {
          icon: 'mdi-twitter',
          title: this.$t('Twitter by Chiba PR'),
          link: 'https://twitter.com/Chiba_city_PR',
        },
        {
          icon: 'mdi-needle',
          title: this.$t('V-sys MHLW'),
          link: 'https://v-sys.mhlw.go.jp/search/list.html?id=121002',
        },
        {
          icon: 'mdi-information-outline',
          title: this.$t('About us'),
          link: '/about',
          divider: true,
        },
      ]
    },
  },
  methods: {
    openNavi() {
      this.$emit('openNavi')
    },
    closeNavi() {
      this.$emit('closeNavi')
    },
  },
}
</script>

<style lang="scss" scoped>
.SideNavigation {
  position: relative;
  height: 100%;
  background: $white;
  box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.15);
  &-HeadingContainer {
    padding: 1.25em 0 1.25em 1.25em;
    align-items: center;
    @include lessThan($small) {
      padding: 7px 0 7px 20px;
    }
  }
  &-HeadingIcon {
    margin-right: 16px;
  }
  &-HeadingLink {
    @include lessThan($small) {
      display: flex;
      align-items: center;
    }
    text-decoration: none;
  }
  &-ListContainerIcon {
    margin: 24px 16px 0;
  }
  &-ListItemContainer {
    padding: 2px 20px;
  }
  &-Logo {
    margin: 20px 16px 0 0;
    width: 110px;
    @include lessThan($small) {
      margin-top: 0;
    }
  }
  &-Heading {
    margin-top: 8px;
    font-size: 13px;
    color: #898989;
    padding: 0.5em 0;
    text-decoration: none;
    @include lessThan($small) {
      margin-top: 0;
    }
  }
  &-HeadingDivider {
    margin: 0px 20px 4px;
    @include lessThan($small) {
      display: none;
    }
  }
  &-Divider {
    margin: 12px 0;
  }
  &-Footer {
    padding: 20px;
    background-color: $white;
  }
  &-SocialLinkContainer {
    display: flex;
    & img {
      width: 30px;
      &:first-of-type {
        margin-right: 10px;
      }
    }
  }
  &-Copyright {
    display: block;
    margin-top: 10px;
    font-size: 8px;
    line-height: 1.2;
    color: $gray-1;
    font-weight: bold;
  }
}
.open {
  @include lessThan($small) {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    display: block !important;
    width: 100%;
    z-index: z-index-of(opened-side-navigation);
    background-color: $white;
  }
}
@include largerThan($small) {
  .pc-none {
    display: none;
  }
}
@include lessThan($small) {
  .sp-flex {
    display: flex;
  }
  .sp-none {
    display: none;
  }
}
</style>
