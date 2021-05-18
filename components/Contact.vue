<template>
  <v-card>
    <v-card-title>
      <v-icon size="24" left>mdi-access-point</v-icon>
      相談窓口について
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="12">
          <p>
            発熱などの症状がある場合は、かかりつけ医、またはお近くの医療機関に事前に電話で連絡しましょう。
          </p>
          <p>受診先がわからない場合は、以下の窓口を活用しましょう。</p>
        </v-col>
        <v-col cols="12" md="6">
          <v-card outlined>
            <v-card-title>
              千葉市新型コロナウィルス感染症相談センター
            </v-card-title>
            <v-list three-line>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-phone</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-h5 text-md-h4">
                    043-238-9966
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    受付時間 9:00 〜 19:00(土・日・祝日、年末年始は 17:00 まで)
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-fax</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-h5">
                    043-307-7274
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    聴覚に障害のある方や、電話での相談が難しい方
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-at</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-subtitle-2 text-md-h5">
                    chibashicorona@city.chiba.lg.jp
                  </v-list-item-title>
                  <v-list-item-subtitle>メールはこちら</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
        <v-col cols="12" md="6">
          <v-card outlined>
            <v-card-title>千葉市コロナワクチン接種コールセンター</v-card-title>
            <v-list three-line>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-phone</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-h5 text-md-h4">
                    0120-57-8970
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    受付時間 8:30 〜 18:00(土・日・祝日含む、年末年始は除く)
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-fax</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-h5">
                    043-245-5128
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    聴覚に障害のある方や、電話での相談が難しい方
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-at</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-subtitle-2 text-md-h5">
                    cv-call@city.chiba.lg.jp
                  </v-list-item-title>
                  <v-list-item-subtitle>メールはこちら</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
        <v-col cols="12" md="6">
          <v-card outlined>
            <v-card-title>千葉県発熱相談コールセンター</v-card-title>
            <v-list three-line>
              <v-list-item>
                <v-list-item-icon>
                  <v-icon>mdi-phone</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="wrap text-h5 text-md-h4">
                    03-6747-8414
                  </v-list-item-title>
                  <v-list-item-subtitle>
                    24時間対応(無休)
                  </v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<style scoped>
.wrap {
  white-space: normal;
}
</style>
