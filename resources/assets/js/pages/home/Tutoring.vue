<template>
    <v-container grid-list-lg grey lighten-4 fluid>
        <v-layout wrap v-if="lessons.length > 0">
            <v-flex xs12 sm6 offset-sm3 v-for="subject in lessons" :key="subject.id">
                <v-card>
                    <v-card-media v-if="subject.subject['t-pic']" :src="subject.subject['t-pic']" height="150px">
                    </v-card-media>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">{{ subject.subject['t-data'] }}</h3>
                            <div>€{{subject.price}}/hr </div>
                        </div>
                        <v-spacer></v-spacer>
                        <v-menu bottom left :close-on-content-click="false" v-model="subject.showMenu">
                            <v-btn icon slot="activator">
                                <v-icon>more_vert</v-icon>
                            </v-btn>
                            <v-card>
                                <v-list>
                                    <v-list-tile href="javascript:;">
                                        <v-list-tile-action>
                                            <v-switch color="success" v-model="subject.active"></v-switch>
                                        </v-list-tile-action>
                                        <v-list-tile-content @click="subject.active = !subject.active">
                                            <v-list-tile-title>Accept new students</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                                <v-divider></v-divider>
                                <v-list>
                                    <v-list-tile @click="dialogs.delete.student.show = true;subject.showMenu = false;">
                                        <v-list-tile-action>
                                            <v-icon color="primary">monetization_on</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Change price</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile @click="dialogs.delete.student.show = true;">
                                        <v-list-tile-action>
                                            <v-icon color="primary">description</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Update description</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile @click="dialogs.delete.student.show = true;">
                                        <v-list-tile-action>
                                            <v-icon color="primary">language</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Edit languages</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile @click="showDialog('stop_tutoring', subject); subject.showMenu = false;">
                                        <v-list-tile-action>
                                            <v-icon color="error">remove_circle</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Stop tutoring</v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-list>
                            </v-card>
                        </v-menu>
                    </v-card-title>
                    <v-list two-line v-if="subject.students">
                        <v-list-tile v-for="student in subject.students" :key="student.id" @click="showDialog('show_student', student, subject)">
                            <v-list-tile-avatar>
                                <img :src="student.profile_picture_url">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ student.name }}</v-list-tile-title>
                                <v-list-tile-sub-title>Your student</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout wrap v-else>
            <v-flex xs12 sm6 offset-sm3>
                <v-card>
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline mb-0">You aren't tutoring any subjects right now</h3>
                            <div>No subject</div>
                        </div>
                    </v-card-title>
                    <v-card-actions>
                        <v-btn color="primary" :to="{name: 'home.explore'}">Explore subjects</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="dialogs.delete.show" max-width="290">
            <v-card>
                <v-card-title class="headline">{{ dialogs.delete.title }}</v-card-title>
                <v-card-text>{{ dialogs.delete.text }}</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat="flat" @click.native="dialogs.delete.show = false">Cancel</v-btn>
                    <v-btn color="red darken-1" flat="flat" @click.native="dialogs.delete.show = false">{{ dialogs.delete.action }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogs.student.show" lazy max-width="500px" transition="dialog-bottom-transition" :overlay="false" scrollable>
            <v-card tile>
                <v-toolbar card dark color="primary">
                    <v-btn icon @click.native="dialogs.student.show = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Your student</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-menu bottom left offset-y>
                        <v-btn slot="activator" dark icon>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile @click="showDialog('remove_student', dialogs.student.user, dialogs.student.subject );">
                                <v-list-tile-title>Remove student</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                </v-toolbar>
                <v-card-text>
                    <v-list two-line>
                        <v-list-tile avatar>
                            <v-list-tile-avatar>
                                <img :src="dialogs.student.user.profile_picture_url">
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ dialogs.student.user.name }}</v-list-tile-title>
                                <v-list-tile-sub-title v-if="dialogs.student.subject.subject">Your student in {{ dialogs.student.subject.subject['t-data'] }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                    <v-list two-line>
                        <v-list-tile avatar :href="'mailto:'+dialogs.student.user.email">
                            <v-list-tile-action>
                                <v-icon color="primary">email</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>{{ dialogs.student.user.email }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-subheader>Class details</v-subheader>
                        <v-list-tile @click="">
                            <v-list-tile-action>
                                <v-icon color="primary">date_range</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>Mondays</v-list-tile-title>
                                <v-list-tile-sub-title>Preferred days</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click="">
                            <v-list-tile-action>
                                <v-icon color="primary">access_time</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>16:30</v-list-tile-title>
                                <v-list-tile-sub-title>Preferred time</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click="">
                            <v-list-tile-action>
                                <v-icon color="primary">location_on</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>International University of Monaco, Monaco</v-list-tile-title>
                                <v-list-tile-sub-title>Meeting location</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <!-- <v-subheader>Settings</v-subheader>
                        <v-list-tile href="javascript:;">
                            <v-list-tile-action>
                                <v-switch color="success" v-model="notifications"></v-switch>
                            </v-list-tile-action>
                            <v-list-tile-content @click="notifications = !notifications">
                                <v-list-tile-title>Economics</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile href="javascript:;">
                            <v-list-tile-action>
                                <v-checkbox v-model="widgets"></v-checkbox>
                            </v-list-tile-action>
                            <v-list-tile-content @click="widgets = !widgets">
                                <v-list-tile-title>Auto-add widgets</v-list-tile-title>
                                <v-list-tile-sub-title>Automatically add home screen widgets</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile> -->
                    </v-list>
                </v-card-text>
                <div style="flex: 1 1 auto;" />
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog2" max-width="500px">
            <v-card>
                <v-card-title>
                    Dialog 2
                </v-card-title>
                <v-card-text>
                    <v-btn color="primary" dark @click.stop="dialog3 = !dialog3">Open Dialog 3</v-btn>
                    <v-select :items="select" label="A Select List" item-value="text"></v-select>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" flat @click.stop="dialog2=false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog3" max-width="500px">
            <v-card>
                <v-card-title>
                    <span>Dialog 3</span>
                    <v-spacer></v-spacer>
                    <v-menu bottom left>
                        <v-btn icon slot="activator">
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile v-for="(item, i) in items" :key="i" @click="">
                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                </v-card-title>
                <v-card-actions>
                    <v-btn color="primary" flat @click.stop="dialog3=false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog4" lazy scrollable max-width="500px">
            <v-card>
                <v-card-title class="primary white--text">
                    <span class="headline">Edit your class in</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field v-model="description" counter="120" label="Why are you qualified to tutor this subject?" type="text" multi-line required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="What's your price?" required type="number" prefix="€" suffix="per hour" v-model="pricing"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-select label="Languages you're comfortable tutoring this subject in" autocomplete v-model="select" :items="items" multiple chips :rules="[v => !!v || 'Item is required']" required :search-input.sync="search"></v-select>
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
    </v-container>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            lessons: [],
            notifications: false,
            totalItems: 0,
            pagination: {
                rowsPerPage: 100
            },
            dialogs: {
                delete: {
                    show: false
                },
                student: {
                    user: [],
                    subject: [],
                    show: false
                }
            },
            filters: {
                name: '',
                email: '',
            }
        }
    },
    created() {

        const self = this;

        self.loadToughtClasses(() => {});

    },
    methods: {
        showDialog(dialog, data, secondary_data) {

            const self = this;

            switch (dialog) {
                case 'show_student':
                    self.dialogs.student.user = data;
                    self.dialogs.student.subject = secondary_data;
                    self.dialogs.student.show = true;
                    break;
                case 'stop_tutoring':
                    self.dialogs.delete.show = true;
                    self.dialogs.delete.title = "Stop tutoring " + data.subject['t-data'] + "?";
                    self.dialogs.delete.text = "You are about to remove " + data.subject['t-data'] + " from the subjects you tutor and un-enroll all your students in this subject.";
                    self.dialogs.delete.action = "Stop tutoring";
                    break;
                case 'remove_student':
                    self.dialogs.delete.show = true;
                    self.dialogs.student.show = false;
                    self.dialogs.student.subject = secondary_data;
                    self.dialogs.delete.title = "Remove " + data.name + " from "+secondary_data.subject['t-data']+"?";
                    self.dialogs.delete.text = "You are about to remove " + data.name + " from your "+secondary_data.subject['t-data']+" class.";
                    self.dialogs.delete.action = "Remove student";
                    break;
                case 'subject_add':
                    setTimeout(() => {
                        self.dialogs.add.show = true;
                    }, 500);
                    break;
            }
        },
        loadToughtClasses(cb) {

            const self = this;

            let params = {
                name: self.filters.name,
                email: self.filters.email,
                page: self.pagination.page,
                per_page: self.pagination.rowsPerPage,
                me: true
            };

            axios.get('/admin/user-subjects', { params: params }).then(function(response) {
                console.log(response.data)
                self.lessons = response.data.data.data;
                (cb || Function)();
            });
        }
    }
}
</script>