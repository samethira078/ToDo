<template>
    <v-app id="app">
    <v-container fluid>
       <v-row class="indigo darken-4">
         <v-col cols="12">
             <v-app-bar outlined dense flat class="indigo darken-4">
                 <v-app-bar-title class="hidden-xs-only">
                    <h2 class="font-weight-black white--text">To-do-List</h2>
                 </v-app-bar-title>
                 <v-spacer></v-spacer>
                 <v-btn class="mr-4" depressed>
                     creëren
                 </v-btn>
                 <v-btn depressed>
                     UITLOGGEN
                 </v-btn>
             </v-app-bar>
         </v-col>
       </v-row>
       <v-row>
            <v-col sm="12" lg="4" md="4">
                <v-card>
                    <v-card-title>
                       TITEL VAN DE TITEL
                        <v-spacer></v-spacer>
                        <v-avatar tile size="50">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                        </v-avatar>
                    </v-card-title>

                    <v-list dense flat>
                        <v-subheader class="ml-3">INFORMATIE</v-subheader>

                        <v-list-item-group color="blue-grey darken-5">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="blue-grey darken-5">mdi-file-tree</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Taken: ...</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>

                        <v-list-item-group color="green">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="green darken-2">mdi-calendar-range</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Datum: 04-03-2020</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn class="red--text" text>
                            Verwijderen
                        </v-btn>
                        <v-btn class="green--text" text>
                            Bewerken
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
       </v-row>
    </v-container>
    </v-app>
</template>

<script>
export default {
    name: "User",
    data(){
        return{
            selectedItem: 1,
        }
    }
}
</script>

<style scoped>

</style>
