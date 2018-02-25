<template>
<v-container grid-list-lg grey lighten-4 fluid>
    <v-layout wrap>
        <v-flex xs12 sm6 offset-sm3>
            <v-text-field solo label="Search" v-model="query" prepend-icon="search">
            </v-text-field>
        </v-flex>
        <v-flex xs12 sm4 v-for="subject in tableFilter" :key="subject.id">
            <v-card :to="{name: 'subject', params: {name:subject['t-data']}}">
                <v-card-media v-if="subject['t-pic']" :src="subject['t-pic']" height="150px">
                </v-card-media>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">{{ subject['t-data'] }}</h3>
                    </div>
                </v-card-title>
            </v-card>
        </v-flex>
    </v-layout>
</v-container>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            subjects: [],
            query: '',
        }
    },
    computed: {
        ...mapGetters({
        fetchedSubjects: 'subject/subjects'
    }),
        tableFilter: function () {
                    return this.findBy(this.subjects, this.query)
                  }
},
    created() {

        const self = this;
        self.subjects = self.fetchedSubjects;

    },
    methods: {
        findBy: function (list, value) {
          return list.filter(function (item) {
            console.log(item['t-data'].toUpperCase().includes(value.toUpperCase()))
              return item['t-data'].toUpperCase().includes(value.toUpperCase())
          })
        }
    }
}
</script>