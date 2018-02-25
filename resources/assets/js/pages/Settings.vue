<template>
    <div class="page_wrap_vue subjects_view">
        <v-container container grey lighten-4 fluid>
            <v-form v-model="valid" ref="form" lazy-validation>
                <v-text-field label="Full Name" v-model="name" :rules="nameRules" :counter="10" required></v-text-field>
                <v-text-field label="E-mail" v-model="email" :rules="emailRules" required></v-text-field>
                <v-select label="Languages you speak" autocomplete v-model="select" :items="items" multiple chips :rules="[v => !!v || 'Item is required']" required :search-input.sync="search"></v-select>
                <v-btn @click="submit" :disabled="!valid">
                    submit
                </v-btn>
            </v-form>
        </v-container>
    </div>
</template>
<script>
export default {
    data: () => ({
        valid: true,
        search: null,
        name: '',
        nameRules: [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters'
        ],
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
        ],
        select: null,
        items: [
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4'
        ],
        checkbox: false
    }),
    mounted() {
        console.log('pages.Settings.vue')

        const self = this;
        this.getLanguages()
    },
    watch: {
        search(val) {
            // val && this.getLanguages(val)
        }
    },
    methods: {
        submit() {
            if (this.$refs.form.validate()) {
                // Native form submission is not yet supported
                axios.post('/api/submit', {
                    name: this.name,
                    email: this.email,
                    select: this.select,
                    checkbox: this.checkbox
                })
            }
        },
        loadUser() {
            this.$refs.form.reset()
        },
        getLanguages() {
            const self = this;

            axios.get('https://restcountries.eu/rest/v2/all?fields=languages', {
                transformRequest: [(data, headers) => {
                    delete headers.common['X-CSRF-TOKEN']
                    return data
                }]
            }).then(function(response) {
                var results = [].concat.apply([], response.data);
                var result = results.map(
                    person => (person.languages.map(
                        data => (data.nativeName)
                    ))
                );
                result = [].concat.apply([], result)

                self.items = result
            });
        }
    }
}
</script>