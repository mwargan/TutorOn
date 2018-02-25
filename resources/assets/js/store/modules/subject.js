import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  subjects: []
}

// getters
export const getters = {
  subjects: state => state.subjects,
  getSubjectByName: (state) => (name) => {
    return state.subjects.find(subject => subject['t-data'] === name)
  }
}

// mutations
export const mutations = {
  [types.ADD_SUBJECT] (state, { subject }) {
    state.subjects.push(subject)
  }
}

// actions
export const actions = {
  async fetchSubject ({ commit }, input ) {
    if(state.subjects.filter(function(e) { return e['t-data'] === input; }).length === 0) {
      console.log('fetch')
      try {
        const { data } = await axios.get('/admin/subjects?limit=1&name='+input)
        commit(types.ADD_SUBJECT, { subject: data } )
      } catch (e) {
            console.log(e)
 }
    }
  },
  addSubject ({ commit }, data ) {
    if(state.subjects.filter(function(e) { return e['tag-id'] === data.id; }).length === 0) {
      try {
        commit(types.ADD_SUBJECT, { subject: data } )
      } catch (e) { }
    }
  }
}
