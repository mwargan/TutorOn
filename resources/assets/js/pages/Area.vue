<template>
         <v-container fluid fill-height>
          <v-layout align-center justify-center>
            <v-flex xs12>
              <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                  <v-toolbar-title>Vuetify Google Autocomplete Simple Example</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                  <vuetify-google-autocomplete id="example" placeholder="Enter text..." hint="Enter an address" v-on:placechanged="handlePlaceChangedEvent"></vuetify-google-autocomplete>
                </v-card-text>
                <v-card-text>
                  <div><strong>Address:</strong> <small>{{ address }}</small></div>
                  <div><strong>Google Result:</strong> <small>{{ googleResult }}</small></div>
                  <div><strong>ID:</strong> <small>{{ componentId }}</small></div>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
</template>
<script>
export default {
  data () {
    return {
      address: null,
      googleResult: null,
      componentId: null,
    }
  },
  methods: {
    handlePlaceChangedEvent(address, googleResult, componentId) {
      console.log('address: ', address);
      console.log('googleResult: ', googleResult);
      console.log('componentId: ', componentId);
      this.address = address;
      this.googleResult = googleResult;
      this.componentId = componentId;
    }
  }
}
</script>