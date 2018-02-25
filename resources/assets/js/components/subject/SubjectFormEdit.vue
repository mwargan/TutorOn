<template>
    <div>
        <v-card dark>
            <v-form v-model="valid" ref="subjectFormEdit" @submit="save()" lazy-validation>
                <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12 sm6>
                        <v-text-field box dark label="First Name" v-model="name" :rules="nameRules"></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-btn type="submit" :disabled="!valid" color="primary" dark>Update</v-btn>
                    </v-flex>
                </v-layout>
            </v-container>
            </v-form>
        </v-card>
    </div>
</template>

<script>
    export default {
        props: {
            propSubjectId: {
                required: true
            }
        },
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
            console.log('components.SubjectFormAdd.vue');

        },
        watch: {
            propSubjectId(val) {
                if(val) this.loadSubject(()=>{});
                console.log("hello");
            }
        },
        methods: {

            save() {

                const self = this;

                let payload = {
                    't-data': self.name
                };

                self.$store.commit('index/showLoader');

                axios.put('/admin/subjects/'+self.propSubjectId,payload).then(function(response) {

                    self.$store.commit('showSnackbar',{
                       message: response.data.message,
                       color: 'success',
                       duration: 3000
                    });

                    self.$eventBus.$emit('SUBJECT_UPDATED');
                    self.$store.commit('hideLoader');

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
            },
            loadSubject(cb) {

                const self = this;

                // reset first
                axios.get('/admin/subjects/' + self.propSubjectId).then(function(response) {
                    let Subject = response.data.data;

                    self.name = Subject['t-data'];

                    console.log(Subject);
                    cb();
                });
            }
        }
    }
</script>

<style scoped="">
    .permissions_container {
        padding: 10px;
        background: hsla(0,0%,100%,.1);
    }
</style>