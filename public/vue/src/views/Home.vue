<template>
   <v-container fluid>
       <v-flex>
           <v-row justify="end">
               <v-col class="d-flex justify-end">
                   <v-btn text class="mr-5 black white--text" @click.stop="dialog_login = true">
                       Inloggen
                   </v-btn>
                   <v-btn text class="mr-5 black white--text" @click.stop="dialog_register = true">
                       Registeren
                   </v-btn>
               </v-col>
           </v-row>
       </v-flex>
       <v-dialog v-model="dialog_register" max-width="290">
           <v-card>
               <v-card-title class="text-h5 orange lighten-1">
                  <h4 class="text-center white--text">Account creëren</h4>
               </v-card-title>
               <v-form>
               <v-card-text>
                       <v-row>
                           <v-col cols="12" class="pa-5">
                               <v-text-field name="name" :rules="userInput" label="Gebruikersnaam" v-model="details.name"></v-text-field>
                               <v-text-field name="password" :rules="passwordInput" label="Wachtwoord" type="password" v-model="details.password"></v-text-field>
                               <v-text-field name="password_confirm" :rules="confirmPasswordRules" type="password" label="Wachtwoord bevestigen" v-model="details.password_confirm"></v-text-field>
                           </v-col>
                       </v-row>
               </v-card-text>
               <v-divider></v-divider>
               <v-card-actions>
                   <v-spacer></v-spacer>
                   <v-btn color="success" text @click="register_user">
                       Voltooien
                   </v-btn>
               </v-card-actions>
               </v-form>
           </v-card>
       </v-dialog>
       <v-dialog v-model="dialog_login" max-width="290">
           <v-card>
               <v-card-title class="text-h5 orange lighten-1">
                   <h4 class="text-center white--text">Inloggen</h4>
               </v-card-title>
               <v-form>
                   <v-card-text>
                       <v-row>
                           <v-col cols="12" class="pa-5">
                               <v-text-field name="name" :rules="userInput" label="Gebruikersnaam" v-model="details.name"></v-text-field>
                               <v-text-field name="password" :rules="passwordInput" label="Wachtwoord" type="password" v-model="details.password"></v-text-field>
                           </v-col>
                       </v-row>
                   </v-card-text>
                   <v-divider></v-divider>
                   <v-card-actions>
                       <v-spacer></v-spacer>
                       <v-btn color="success" text @click="login_user">
                           Inloggen
                       </v-btn>
                   </v-card-actions>
               </v-form>
           </v-card>
       </v-dialog>
   </v-container>
</template>

<script>

  export default {
    name: 'Home',
     data(){
        return{
            dialog_register: false,
            dialog_login: false,
            details: [
                {
                    name: '',
                },
                {
                    password: '',
                },
                {
                    password_confirm: '',
                }
            ],
            userInput: [
                v => !!v || 'Veld is leeg',
                v => v && v.length >= 3 || 'Minimaal 3 letters of cijfers',
                v => /^[a-zA-Z0-9]+$/.test(v)||'Alleen letters of cijfers toegestaan',
            ],
            passwordInput: [
                v => !!v || 'Veld is leeg',
                v => v && v.length >= 8 || 'Minimaal 8 letters of cijfers',
            ],
            confirmPasswordRules: [
                (value) => !!value || 'Wachtwoord bevestigen',
                (value) =>
                    value === this.details.password || 'Wachtwoord komt niet overeen',
            ],
        }
     },
      watch: {
          dialog_register(new_i) {
             if(new_i){
                 this.details.password = '';
                 this.details.password_confirm = '';
             }
          },
          dialog_login(new_i) {
              if(new_i){
                  this.details.password = '';
              }
          }
      },

      methods: {
        register_user(){
            this.$store.dispatch('register', this.details).then(response => {
                console.log(response);
                //  Error
            }).catch(response => {
               console.log(response)
            })
        },
          login_user(){
              this.$store.dispatch('login', this.details).then(response => {
                  console.log(response);
                  this.$router.push('/User');
                  //  Error
              }).catch(response => {
                  console.log(response)
              })
          }
      },
  }
</script>
