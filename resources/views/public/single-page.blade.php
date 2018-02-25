@extends('layouts.public') @section('content')
<template>
  <v-app id="inspire">
    <v-navigation-drawer
                         fixed
                         clipped
                         class="grey lighten-4"
                         app
                         floating
                         hide-overlay
                         v-model="drawer"
                         >
    <v-list
              dense
              class="grey lighten-4"
              >
        <v-toolbar flat class="transparent">
        <v-list class="pa-0">
          <v-list-tile avatar @click='menuClick("home.explore", "vue")'>
            <v-list-tile-avatar>
              <img src="https://randomuser.me/api/portraits/men/85.jpg" >
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title v-if="user" v-html="user.name"></v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-toolbar>

        <v-list-tile @click='menuClick("home.learning", "vue")'>
           <v-list-tile-action>
            <v-icon>settings
            </v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Subjects you are learning
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click='menuClick("home.tutoring", "vue")'>
           <v-list-tile-action>
            <v-icon>settings
            </v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Subjects you tutor
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click='menuClick("settings", "vue")'>
           <v-list-tile-action>
            <v-icon>settings
            </v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Settings
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-layout
                  row
                  align-center
                  v-if="subjects.length > 0"
                  >
          <v-flex xs8>
            <v-subheader>
              Subjects being tutored
            </v-subheader>
          </v-flex>
        </v-layout>
        <v-list-tile avatar spacer row v-if="subject.classes.length > 0" :key="subject.id" v-for="subject in subjects" :to="{name: 'subject', params: {name:subject['t-data']}}">
          <v-list-tile-action>
            <v-avatar tile size="38" v-if="subject['t-pic']">
                <img :src="subject['t-pic']">
            </v-avatar>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title v-html="subject['t-data']">
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>


        <v-list-group v-model="true" v-if="subjects.length > 0">
                    <v-layout
                  row
                  align-center
                  slot="activator"
                  >
          <v-flex xs8>
            <v-subheader>
              Explore subjects
            </v-subheader>
          </v-flex>
        </v-layout>
            <v-list-tile avatar spacer :key="subject.id" v-for="subject in subjects" :to="{name: 'subject', params: {name:subject['t-data']}}">
          <v-list-tile-action>
            <v-avatar tile size="38" v-if="subject['t-pic']">
                <img :src="subject['t-pic']">
            </v-avatar>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title v-html="subject['t-data']">
            </v-list-tile-title>
          </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
          <v-divider></v-divider>
        <v-list-tile @click="clickLogout('{{route('logout')}}','{{route('login')}}')">
          <v-list-tile-action>
            <v-icon>directions_walk
            </v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Logout
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
</v-list>
    </v-navigation-drawer>
    <v-toolbar color="primary" dark app flat clipped-left>
      <v-toolbar-side-icon @click.native="drawer = !drawer">
      </v-toolbar-side-icon>
      <span class="title ml-3 mr-5">Tutor&nbsp;
        <span class="text">On
        </span>
      </span>
      <v-spacer>
      </v-spacer>
      <v-menu bottom offset-y max-width="350px" fixed allow-overflow max-height="500px">
        <v-btn slot="activator" icon><v-badge overlap color="secondary" v-model="unread_count"><span slot="badge" v-html="unread_count"></span><v-icon v-if="unread_count">notifications</v-icon><v-icon v-else>notifications_none</v-icon></v-badge></v-btn>

        <v-list three-line  v-if="user.notifications.length > 0">
            <v-subheader>Notifications</v-subheader>
            <template v-for="notification in user.notifications">
          <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title v-html="notification.data.title"></v-list-tile-title>
              <v-list-tile-sub-title v-html="notification.data.text"></v-list-tile-sub-title>
            </v-list-tile-content>
             <v-list-tile-action v-if="!notification.read_at">
                <v-list-tile-action-text class="primary--text text--lighten-1">New</v-list-tile-action-text>
              </v-list-tile-action>
          </v-list-tile>
    </template>
        </v-list>

        <v-list two-line v-else>
                    <v-list-tile>
            <v-list-tile-content>
              <v-list-tile-title>New notifications will show up here!</v-list-tile-title>
              <v-list-tile-sub-title>You haven't got any notifications right now.</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
    </v-toolbar>
    <v-content>
              <!-- loader -->
        <v-container v-if="showLoader" fluid fill-height>
            <v-layout justify-center align-center>
              <v-flex shrink>
                <v-progress-circular :indeterminate="true" slot="progress"></v-progress-circular>
              </v-flex>
            </v-layout>
        </v-container>
    <transition name="fade">
        <keep-alive>
      <router-view>
      </router-view>
  </keep-alive>
  </transition>
    </v-content>
  </v-app>

  <!-- snackbar -->
  <v-snackbar
              :timeout="snackbarDuration"
              :color="snackbarColor"
              top
              v-model="showSnackbar">
    @{{ snackbarMessage }}
  </v-snackbar>
  <!-- dialog confirm -->
  <v-dialog v-show="showDialog" v-model="showDialog" lazy absolute max-width="450px">
    <v-btn color="primary" dark slot="activator">Open Dialog
    </v-btn>
    <v-card>
      <v-card-title>
        <div class="headline">@{{ dialogTitle }}
        </div>
      </v-card-title>
      <v-card-text>@{{ dialogMessage }}
      </v-card-text>
      <v-card-actions v-if="dialogType=='confirm'">
        <v-spacer>
        </v-spacer>
        <v-btn color="green darken-1" flat="flat" @click.native="dialogCancel">Cancel
        </v-btn>
        <v-btn color="green darken-1" flat="flat" @click.native="dialogOk">Ok
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template> @endsection