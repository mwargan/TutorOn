<template>
    <div>
        <v-card dark>
            <v-form v-model="valid" ref="subjectFormAdd" lazy-validation>
                <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field box dark label="First Name" v-model="name" :rules="nameRules"></v-text-field>
                    </v-flex>
                    <v-flex xs12><v-spacer></v-spacer></v-flex>
                    <v-flex xs12>
                        <v-btn @click="save()" :disabled="!valid" color="primary" dark>Save</v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            </v-form>
        </v-card>
    </div>
</template>

<script>
    export default {
        data() {

            const self = this;

            return {
                valid: false,
                name: '',
                nameRules: [
                    (v) => !!v || 'Name is required',
                ],
                alert: {
                    show: false,
                    icon: '',
                    color: '',
                    message: ''
                }
            }
        },
        mounted() {
            console.log('components.subjectFormAdd.vue');

            const self = this;
        },
        methods: {
            save() {

                const self = this;

                let payload = {
                    't-data': self.name,
                };

                self.$store.commit('index/showLoader');

                axios.post('/admin/subjects',payload).then(function(response) {

                    self.$store.commit('showSnackbar',{
                       message: response.data.message,
                       color: 'success',
                       duration: 3000
                    });

                    self.$eventBus.$emit('subject_ADDED');
                    self.$store.commit('hideLoader');

                    // reset
                    self.$refs.subjectFormAdd.reset();

                }).catch(function (error) {
                    if (error.response) {
                        self.$store.commit('showSnackbar',{
                            message: error.response.data.message,
                            color: 'error',
                            duration: 3000
                        });
                    } else if (error.request) {
                        console.log(error.request);
                    } else {
                        console.log('Error', error.message);
                    }
                });
            }
        }
    }
</script>