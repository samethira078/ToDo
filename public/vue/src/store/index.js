import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

axios.defaults.baseURL = 'http://samet.nl/api/'
axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("token");

export default new Vuex.Store({
  state: {
        token: localStorage.getItem('token'),
  },
  mutations: {
      saveToken(state, token){
          localStorage.setItem('token', token.token);
      }
  },
  actions: {
      register(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/register', {
                  name: data.name,
                  password: data.password
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      login(context, data){
          return new Promise((resolve, reject) => {
              axios.post('user/validate', {
                  name: data.name,
                  password: data.password
              }).then(response => {
                  context.commit('saveToken', response.data);
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
  },
  modules: {
  }
})
