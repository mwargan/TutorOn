
import * as types from '../mutation-types'

export const state = {

        // loader
        showLoader: false,

        // snackbar
        showSnackbar: false,
        snackbarMessage: '',
        snackbarColor: '',
        snackbarDuration: 3000,

        // dialog
        dialogShow: false,
        dialogType: '',
        dialogTitle: '',
        dialogMessage: '',
        dialogOkCb: ()=>{},
        dialogCancelCb: ()=>{},
    }
  export const mutations = {

        // loader
        [types.showLoader] (state) {
            state.showLoader = true
        },
        [types.hideLoader] (state) {
            state.showLoader = false
        },

        // snackbar
        [types.showSnackbar](state, data) {
            state.snackbarDuration = data.duration || 3000;
            state.snackbarMessage = data.message || 'No message.';
            state.snackbarColor = data.color || 'info';
            state.showSnackbar = true;
        },
        [types.hideSnackbar](state) {
            state.showSnackbar = false;
        },

        // dialog
        [types.showDialog](state, data) {
            state.dialogType = data.type || 'confirm';
            state.dialogTitle = data.title;
            state.dialogMessage = data.message;
            state.dialogOkCb = data.okCb || function(){};
            state.dialogCancelCb = data.cancelCb || function(){};
            state.dialogShow = true;
        },
        [types.hideDialog](state) {
            state.dialogShow = false;
        },
        [types.dialogOk](state) {
            state.dialogOkCb();
            state.dialogShow = false;
        },
        [types.dialogCancel](state) {
            state.dialogCancelCb();
            state.dialogShow = false;
        }
    }
    export const getters = {

        // loader
        showLoader: state => {
            return state.showLoader
        },

        // snackbar
        showSnackbar: state => {
            return state.showSnackbar
        },
        snackbarMessage: state => {
            return state.snackbarMessage
        },
        snackbarColor: state => {
            return state.snackbarColor
        },
        snackbarDuration: state => {
            return state.snackbarDuration
        },

        // dialog
        showDialog: state => {
            return state.dialogShow
        },
        dialogType: state => {
            return state.dialogType
        },
        dialogTitle: state => {
            return state.dialogTitle
        },
        dialogMessage: state => {
            return state.dialogMessage
        }
    }
