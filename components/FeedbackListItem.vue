<template>
  <v-list-item v-ripple="false" class="ListItem-Container" @click="openForm()">
    <v-list-item-action class="ListItem-IconContainer">
      <v-icon class="ListItem-Icon" size="20">
        mdi-comment-text-outline
      </v-icon>
    </v-list-item-action>
    <v-list-item-content class="ListItem-TextContainer">
      <v-list-item-title class="ListItem-Text"
        >フィードバックする</v-list-item-title
      >
    </v-list-item-content>
  </v-list-item>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { createPopup } from '@typeform/embed'

@Component
export default class FeedbackListItem extends Vue {
  openForm() {
    const { open } = createPopup('REcpVjo4', { size: 100 })
    open()
  }
}
</script>
