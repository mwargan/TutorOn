<template>
    <div class="component-wrap">

        <!-- search -->
        <v-card dark>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12 sm12>
                        <v-btn @click="showDialog('subject_add')" class="blue lighten-1" dark>
                            New subject
                            <v-icon right dark>add</v-icon>
                        </v-btn>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <v-text-field prepend-icon="search" box dark label="Filter By Name" v-model="filters.name"></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
        <!-- /search -->

        <!-- data table -->
        <v-data-table
                v-bind:headers="headers"
                v-bind:pagination.sync="pagination"
                :items="items"
                :total-items="totalItems"
                class="elevation-1">
            <template slot="headerCell" slot-scope="props">
                <span v-if="props.header.value=='name'">
                    <v-icon>person</v-icon> {{ props.header.text }}
                </span>
                <span v-else-if="props.header.value=='email'">
                    <v-icon>email</v-icon> {{ props.header.text }}
                </span>
                <span v-else-if="props.header.value=='permissions'">
                    <v-icon>vpn_key</v-icon> {{ props.header.text }}
                </span>
                <span v-else-if="props.header.value=='last_login'">
                    <v-icon>av_timer</v-icon> {{ props.header.text }}
                </span>
                <span v-else>{{ props.header.text }}</span>
            </template>
            <template slot="items" slot-scope="props">
                <td>
                    <v-menu>
                        <v-btn icon slot="activator" dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile @click="showDialog('subject_edit',props.item)">
                                <v-list-tile-title>Edit</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile @click="trash(props.item)">
                                <v-list-tile-title>Delete</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                </td>
                <td>{{ props.item['t-data'] }}</td>
            </template>
        </v-data-table>

        <!-- add subject -->
        <v-dialog v-model="dialogs.add.show" fullscreen transition="dialog-bottom-transition" :overlay=false>
            <v-card>
                <v-toolbar dark class="primary">
                    <v-btn icon @click.native="dialogs.add.show = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Add subject</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialogs.add.show = false">Done</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text>
                    <subject-form-add></subject-form-add>
                </v-card-text>
            </v-card>
        </v-dialog>

        <!-- edit subject -->
        <v-dialog v-model="dialogs.edit.show" fullscreen :laze="false" transition="dialog-bottom-transition" :overlay=false>
            <v-card>
                <v-toolbar dark class="primary">
                    <v-btn icon @click.native="dialogs.edit.show = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Edit subject</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="save(); dialogs.edit.show = false">Done</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text>
                    <subject-form-edit :propSubjectId="dialogs.edit.subject['tag-id']"></subject-form-edit>
                </v-card-text>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import subjectFormAdd from './subjectFormAdd.vue';
    import subjectFormEdit from './subjectFormEdit.vue';
    export default {
        components: {
            subjectFormAdd,
            subjectFormEdit
        },
        data () {
            return {
                headers: [
                    { text: 'Action', value: false, align: 'left', sortable: false },
                    { text: 'Name', value: 'name', align: 'left', sortable: false }
                ],
                items: [],
                totalItems: 0,
                pagination: {
                    rowsPerPage: 10
                },

                filters: {
                    name: '',
                    email: '',
                },

                dialogs: {
                    showPermissions: {
                        items: [],
                        show: false
                    },
                    edit: {
                        subject: {},
                        show: false
                    },
                    add: {
                        show: false
                    }
                }
            }
        },
        mounted() {
            const self = this;

            self.loadsubjects(()=>{});

            self.$eventBus.$on(['SUBJECT_ADDED','SUBJECT_UPDATED','SUBJECT_DELETED','GROUP_ADDED'],()=>{
                self.loadsubjects(()=>{});
            });
        },
        watch: {
            pagination: {
                handler() {
                    this.loadsubjects(()=>{});
                },
                deep: true
            },
            'filters.name':_.debounce(function(){
                const self = this;
                self.loadsubjects(()=>{});
            },700),
            'filters.email':_.debounce(function(){
                const self = this;
                self.loadsubjects(()=>{});
            },700)
        },
        methods: {
            trash(subject) {
                const self = this;

                self.$store.commit('showDialog',{
                    type: "confirm",
                    title: "Confirm Deletion",
                    message: "Are you sure you want to delete this subject?",
                    okCb: ()=>{

                        axios.delete('/admin/subjects/' + subject['tag-id']).then(function(response) {

                            self.$store.commit('showSnackbar',{
                                message: response.data.message,
                                color: 'success',
                                duration: 3000
                            });

                            self.$eventBus.$emit('subject_DELETED');

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
                    cancelCb: ()=>{
                        console.log("CANCEL");
                    }
                });
            },
            showDialog(dialog, data) {

                const self = this;

                switch (dialog){
                    case 'subject_edit':
                        self.dialogs.edit.subject = data;
                        setTimeout(()=>{
                            self.dialogs.edit.show = true;
                        },500);
                    break;
                    case 'subject_add':
                        setTimeout(()=>{
                            self.dialogs.add.show = true;
                        },500);
                        break;
                }
            },
            loadsubjects(cb) {

                const self = this;

                let params = {
                    page: self.pagination.page,
                    per_page: self.pagination.rowsPerPage
                };

                axios.get('/admin/subjects',{params: params}).then(function(response) {
                    self.items = response.data.data.data;
                    self.totalItems = response.data.data.total;
                    self.pagination.totalItems = response.data.data.total;
                    (cb || Function)();
                });
            }
        }
    }
</script>