<template>
    <div class="page_wrap_vue subjects_view">
        <v-tabs v-model="activeTab" icons-and-text show-arrows color="primary" dark slider-color="secondary">
            <v-tab v-for="tab in tabs" :to="{name: tab.route}" :ripple="false" :key="tab.name">
                {{ tab.name }}
            </v-tab>
        </v-tabs>
        <transition name="fade">
            <keep-alive>
                <router-view></router-view>
            </keep-alive>
        </transition>
    </div>
</template>
<script>

export default {
    data() {
        return {
            activeTab: null
        }
    },
    computed: {
        tabs() {
            return [{
                    name: 'Explore',
                    route: 'home.explore'
                },
                {
                    name: 'Subjects you tutor',
                    route: 'home.tutoring'
                },
                {
                    name: 'Subjects you are learning',
                    route: 'home.learning'
                }
            ]
        }
    }
}
</script>