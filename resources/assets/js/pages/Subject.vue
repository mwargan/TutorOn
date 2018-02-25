<template>
    <div class="page_wrap_vue subjects_view">
        <v-card :img="subject['t-pic']" height="300px" class="no_border_radius">
            <v-toolbar color='white'>
                <v-btn icon @click.native="this.window.history.back();">
                    <v-icon>arrow_back</v-icon>
                </v-btn>
                <v-toolbar-title>{{ subject['t-data'] }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn class="primary" @click.native="showDialog('subject_add')">
                    Tutor
                </v-btn>
                <v-btn icon @click.native="this.window.history.back();">
                    <v-icon>share</v-icon>
                </v-btn>
            </v-toolbar>
        </v-card>
        <v-container grid-list-lg container grey lighten-4 fluid>
            <v-layout row style="margin-top: -82px;">
                <v-flex xs12 sm6 offset-sm3>
                    <v-card>
                        <v-list three-line>
                            <template>
                                <div class="text-xs-center" v-show="false">
                                    <v-chip color="success" text-color="white" v-if="subject.classes.length > 0">
                                        You are learning this subject
                                    </v-chip>
                                    <v-chip color="blue" text-color="white" v-else>
                                        You tutor this subject
                                    </v-chip>
                                </div>
                                <v-subheader>
                                    <template v-if="subject.classes.length > 0">People tutoring this subject</template>
                                    <template v-else>Nobody is tutoring this subject right now</template>
                                </v-subheader>
                                <template v-for="(item, index) in subject.classes" v-if="item.active">
                                    <v-divider></v-divider>
                                    <v-list-tile avatar :key="item.title" @click="showDialog('subject_edit', item)">
                                        <v-list-tile-avatar class="">
                                            <img v-if="item.user.profile_picture" :src="item.user.profile_picture.path"> <span v-else class="hiteheadline">{{ item.user.name[0] }}</span>
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title v-html="item.user.name"></v-list-tile-title>
                                            <v-list-tile-sub-title>
                                                <template v-if="item.price > 0">€{{item.price}}/hr</template>
                                                <template v-else>Free</template> - {{ item.description }}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </template>
                            </template>
                        </v-list>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout row>
                <v-flex xs12 sm6 offset-sm3>
                    <v-card>
                        <v-list three-line>
                            <v-subheader>
                                <template>Learning materials for this subject</template>
                            </v-subheader>
                            <template v-for="(item, index) in 1">
                                <v-divider></v-divider>
                                <v-list-tile avatar :href="'https://justbookr.com/find/?q='+subject['t-data']">
                                    <v-avatar tile>
                                        <img src="https://justbookr.com/images/logoDark.svg">
                                    </v-avatar>
                                    <v-list-tile-content class="ml-2">
                                        <v-list-tile-title>Textbooks</v-list-tile-title>
                                        <v-list-tile-sub-title>
                                            See if there are people selling textbooks for {{ subject['t-data'] }} on JustBookr.
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </template>
                        </v-list>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
        <v-dialog v-model="dialogs.edit.show" lazy fullscreen scrollable transition="dialog-bottom-transition">
            <v-card>
                <v-card-title class="primary white--text">
                    <span class="headline" v-if="dialogs.edit.subject.user">Learn {{ subject['t-data'] }} With {{ dialogs.edit.subject.user.name }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-list three-line>
                                    <v-list-tile avatar>
                                        <v-list-tile-avatar>
                                            <img src="https://randomuser.me/api/portraits/men/85.jpg">
                                        </v-list-tile-avatar>
                                        <v-list-tile-content>
                                            <v-list-tile-title>MBA in Marketing</v-list-tile-title>
                                            <v-list-tile-sub-title>I teach Lamda in Monaco (Public Speaking) , have taught Marketing at IUM, and write CV’s and Cover Letters. I hold an MBA in Marketing and an undergraduate degree in business from the USA. </v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                                    <v-divider></v-divider>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-select label="Best days to meet" multiple autocomplete chips prepend-icon="date_range" :items="[
                                {text: 'Mondays', value: 2},{text: 'Tuesdays', value: 3},{text: 'Wednesdays', value: 4},{text: 'Thursdays', value: 5},{text: 'Fridays', value: 6},{text: 'Saturdays', value: 7},{text: 'Sundays', value: 1}
                                ]" v-model="days"></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-dialog ref="dialog" persistent lazy :close-on-content-click="true" v-model="dialogs.edit.menu2" transition="scale-transition" offset-y full-width :nudge-right="40" max-width="290px" min-width="290px" :return-value.sync="dialogs.edit.time">
                                    <v-text-field slot="activator" label="Easiest meeting time" v-model="dialogs.edit.time" prepend-icon="access_time" readonly></v-text-field>
                                    <v-time-picker required v-model="dialogs.edit.time" actions>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="dialogs.edit.menu2 = false">Cancel</v-btn>
                                        <v-btn flat color="primary" @click="$refs.dialog.save(dialogs.edit.time)">OK</v-btn>
                                    </v-time-picker>
                                </v-dialog>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-select label="Study hours per lesson" autocomplete prepend-icon="timelapse" :items="['1', '2', '3', '4', '5']" v-model="hours"></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <vuetify-google-autocomplete id="map" prepend-icon="location_on" label="Meeting location" types="['address', 'establishment']" :enable-geolocation="true" v-on:placechanged="getAddressData">
                                </vuetify-google-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Your message (optional)" type="text" v-model="message" multi-line></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click.native="dialogs.edit.show = false">Close</v-btn>
                    <v-btn color="primary darken-1" @click.native="enroll()">Send message</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogs.add.show" lazy fullscreen scrollable transition="dialog-bottom-transition">
            <v-card>
                <v-card-title class="primary white--text">
                    <span class="headline">Tutor people in {{ subject['t-data'] }}</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <vuetify-google-autocomplete id="mapp" prepend-icon="location_on" label="Where are you based?" hint="Pick your favorite states" types="address" :enable-geolocation="true" v-on:placechanged="getAddressData">
                                </vuetify-google-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field v-model="description" counter="120" label="Why are you qualified to tutor this subject?" type="text" multi-line required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="What's your price?" required type="number" prefix="€" suffix="per hour" v-model="pricing"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-select label="Languages you can tutor this subject in" autocomplete v-model="languages" :items="items" multiple chips :rules="[v => !!v || 'Item is required']" required :search-input.sync="search"></v-select>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click.native="dialogs.add.show = false">Close</v-btn>
                    <v-btn color="primary darken-1" @click.native="save()">Post</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
import SubjectLists from '../components/subject/SubjectLists.vue';
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            address: '',
            active: '',
            name: this.$route.params.name,
            subject: null,
            description: null,
            pricing: null,
            languages: [],
            dialogs: {
                showPermissions: {
                    items: [],
                    show: false
                },
                edit: {
                    subject: {},
                    show: false,
                    time: "13:00",
                    menu2: false,
                    modal2: false,
                    message: null
                },
                add: {
                    show: false
                }
            }
        }
    },
    watch: {
        active(v) {
            console.log('active tab: ' + v);
        },
        '$route' (to, from) {
            // react to route changes...
            this.name = to.params.name
            this.subject = this.thisSubject(this.name)
        }
    },
    computed: mapGetters({
        thisSubject: 'subject/getSubjectByName',
        allSubjects: 'subject/subjects'
    }),
    created() {
        const self = this;
        console.log(self.allSubjects)
        self.subject = self.thisSubject(self.name);

        self.$eventBus.$on(['subject_ADDED', 'SUBJECT_UPDATED', 'SUBJECT_DELETED', 'GROUP_ADDED'], () => {
            self.loadSubject(() => {});
        });
        this.getLanguages();
    },
    methods: {
        loadSubject(cb) {

            const self = this;

            axios.get('/admin/subjects?limit=1&name=' + self.name).then(function(response) {
                console.log(response.data.data.data[0])
                self.subject = response.data.data.data[0];
                (cb || Function)();
            });
        },
        showDialog(dialog, data) {

            const self = this;

            switch (dialog) {
                case 'subject_permissions':
                    self.dialogs.showPermissions.items = data;
                    self.dialogs.showPermissions.show = true;
                    break;
                case 'subject_edit':
                    self.dialogs.edit.subject = data;
                    self.dialogs.edit.show = true;
                    break;
                case 'subject_add':
                    self.dialogs.add.show = true;
                    break;
            }
        },
        save() {

            const self = this;

            let payload = {
                'description': self.description,
                'price': self.pricing,
                'subject_id': self.subject['tag-id'],
                'languages': self.languages
            };
            console.log(payload.languages);
            //self.$store.commit('index/showLoader');

            axios.post('/admin/user-subjects', payload).then(function(response) {

                self.$store.commit('index/showSnackbar', {
                    message: "You're now offering lessons in this subject!",
                    color: 'success',
                    duration: 3000
                });

                self.$eventBus.$emit('subject_ADDED');
                self.$store.commit('index/hideLoader');

                // reset
                self.dialogs.add.show = false;

            }).catch(function(error) {
                if (error.response) {
                    self.$store.commit('index/showSnackbar', {
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
        enroll() {

            const self = this;
            var address = self.address;

            let payload = {
                'class_id': self.dialogs.edit.subject.id,
                'hours-per-lesson': '0' + (self.hours) + ':00:00',
                'meeting-time': self.dialogs.edit.time,
                'days': self.days,
                'location': {
                    'lat': address.latitude,
                    'lng': address.longitude,
                    'locality': address.locality,
                    'postal_code': address.postal_code
                },
                'meta': {
                    'message': self.message
                }
            };
            // self.$store.commit('index/showLoader');
            axios.post('/admin/subject-users', payload).then(function(response) {

                self.$store.commit('index/showSnackbar', {
                    message: "We've sent your tutor a message! They'll reply to your email.",
                    color: 'success',
                    duration: 5000
                });

                //self.$eventBus.$emit('subject_ADDED');
                self.$store.commit('index/hideLoader');

                // reset
                self.dialogs.edit.show = false;

            }).catch(function(error) {
                if (error.response) {
                    self.$store.commit('index/showSnackbar', {
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
        getAddressData(addressData, placeResultData, id) {
            const self = this;
            self.address = addressData;
            console.log(self.address)
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
                        data => ({ text: data.nativeName, value: data })
                    ))
                );
                result = [].concat.apply([], result)

                result = result.filter((lang, index, self) =>
                    index === self.findIndex((t) => (
                        t.text === lang.text
                    ))
                )
                console.log(result)
                self.items = result
            });
        }
    }
}
</script>