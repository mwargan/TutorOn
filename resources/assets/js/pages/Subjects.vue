<template>
    <div class="page_wrap_vue subjects_view">
        <v-tabs dark v-model="active">
            <v-tabs-bar class="cyan">
                <v-tabs-item href="#subjects" ripple>
                    Subjects
                </v-tabs-item>
                <v-tabs-slider class="yellow"></v-tabs-slider>
            </v-tabs-bar>
            <v-tabs-items>
                <v-tabs-content id="subjects">
                    <v-card flat>
                        <v-card-text>
                            <subject-lists></subject-lists>
                        </v-card-text>
                    </v-card>
                </v-tabs-content>
            </v-tabs-items>
        </v-tabs>
    </div>
</template>

<script>
    import SubjectLists from '../components/subject/SubjectLists.vue';
    export default {
        components: {
            SubjectLists
        },
        data() {
            return {
                active: ''
            }
        },
        watch: {
            active(v) {
                console.log('active tab: ' + v);
            }
        },
        mounted() {
            const self = this;
        }
    }
</script>