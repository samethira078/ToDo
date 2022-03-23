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
      create_todos_table(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/create/list', {
                  table_name: data.name,
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      grab_todos_list(){
          return new Promise((resolve, reject) => {
              axios.get('user/grab/list').
              then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      create_todos_field(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/create/list', {
                  table_name: data,
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      grab_todos_tasks(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/grab/tasks', {
                  id: data,
              }).
              then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      add_new_task(_, data){
          return new Promise((resolve, reject) => {
              console.log(data);
              axios.post('user/add/task', {
                  id: data[0],
                  list: data[1],
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      add_new_item(_, data){
          return new Promise((resolve, reject) => {
              console.log(data);
              axios.post('user/add/item', {
                  id: data[0],
                  list: data[1],
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },
      grab_single_item(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/grab/single/item', {
                  id: data,
              }).then(response => {
                  resolve(response.data)
              }).catch(() =>{
                  reject(reject)
              })
          })
      },

      update_options(_, data){
          return new Promise((resolve, reject) => {
              axios.post('user/update/options', {
                  data: data,
              }).then(response => {
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
