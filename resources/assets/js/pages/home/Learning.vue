<template>
        <v-container grid-list-lg grey lighten-4 fluid>
            <v-layout wrap v-if="classes.length > 0">
                <v-flex xs12 sm6 offset-sm3 v-for="subject in classes" :key="subject.id">
                    <v-card>
                        <v-card-media v-if="subject.class.subject['t-pic']" :src="subject.class.subject['t-pic']" height="150px">
                        </v-card-media>
                        <v-card-title primary-title>
                            <div>
                                <h3 class="headline mb-0">{{ subject.class.subject['t-data'] }}</h3>
                            </div>
                        </v-card-title>
                        <v-list two-line>
                            <v-list-tile>
                                <v-list-tile-avatar>
                                    <img src="https://randomuser.me/api/portraits/men/85.jpg">
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ subject.class.user.name }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Your tutor</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile :href="'mailto:'+subject.class.user.email">
                                <v-list-tile-action>
                                    <v-icon color="indigo">email</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>{{ subject.class.user.email }}</v-list-tile-title>
                                    <v-list-tile-sub-title>Tutors email</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider inset></v-divider>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon color="indigo">date_range</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>Mondays</v-list-tile-title>
                                    <v-list-tile-sub-title>Preferred days</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon color="indigo">date_range</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>16:30</v-list-tile-title>
                                    <v-list-tile-sub-title>Preferred time</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                        <v-card-actions>
                            <v-btn flat color="warning" class="ml-2" @click.native="showDialog('subject_add')">Stop learning</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout wrap v-else>
                <v-flex xs12 sm6 offset-sm3>
                    <v-card>
                        <v-card-title primary-title>
                            <div>
                                <h3 class="headline mb-0">You aren't learning any subjects right now</h3>
                                <div>No subject</div>
                            </div>
                        </v-card-title>
                        <v-card-actions>
                            <v-btn color="primary" :to="{name: 'home.explore'}">Explore subjects</v-btn>
                        </v-card-actions>
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
            classes: [],
            totalItems: 0,
            pagination: {
                rowsPerPage: 100
            },
            dialogs: {
                delete: {
                    student: {
                        show: false
                    }
                },
                student: {
                    show: false
                }
            }
        }
    },
    computed: { ...mapGetters({
            fetchedSubjects: 'subject/subjects'
        })
    },
    created() {

        const self = this;
        self.loadClasses(() => {});

    },
    methods: {
        loadClasses(cb) {

            const self = this;

            let params = {
                me: true
            };

            axios.get('/admin/subject-users', { params: params }).then(function(response) {
                console.log(response.data)
                self.classes = response.data.data.data;
                (cb || Function)();
            });
        }
    }
}
</script>