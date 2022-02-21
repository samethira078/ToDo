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
            <v-col v-for="list in todos" :key="list.id" sm="12" lg="4" md="4">
                <v-card>
                    <v-card-title>
                       {{list.table_name}}
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
                                    <v-list-item-title>Taken: <span v-for="item in list.task_name" :key="item">| {{item}}</span></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>

                        <v-list-item-group color="green">
                            <v-list-item>
                                <v-list-item-icon>
                                    <v-icon color="green darken-2">mdi-calendar-range</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>Datum: {{list.table_creation}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>

                    </v-list>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn class="red--text" text>
                            Verwijderen
                        </v-btn>
                        <v-btn @click="loadItems(list.table_id)" class="green--text" text>
                            Bekijken
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
       </v-row>
<!--        DIALOG SELECTED ITEMS-->
        <v-dialog dark v-model="selected_dialog" width="1000">
            <v-sheet elevation="6">
                <v-tabs  background-color="teal darken-5" dark
                    next-icon="mdi-arrow-right-bold-box-outline"
                    prev-icon="mdi-arrow-left-bold-box-outline"
                    show-arrows>
                    <v-tabs-slider color="white"></v-tabs-slider>
<!--                    TABS SELECTED ITEMS-->
                    <v-tab v-for="list in selected" :key="list.id" :href="'#tab-'+list.id">
                        {{list.task_name}}
                    </v-tab>
<!--                    ITEMS SELECTED ITEMS-->
                    <v-tab-item class="ma-2" v-for="list in selected" :key="list.id" :value="'tab-'+list.id">
                        <v-card>
                            <v-row class="ma-2 text-center">
                                <v-col cols="4">
<!--                                    name-->
                                </v-col>
                                <v-col cols="4">
<!--                                    status-->
                                </v-col>
                                <v-col cols="4">
<!--                                    labels-->
                                </v-col>
                            </v-row>
                            {{list.items}}
                        </v-card>
                        <v-spacer></v-spacer>
                    </v-tab-item>
                </v-tabs>
            </v-sheet>
        </v-dialog>
    </v-container>
    </v-app>
</template>

<script>
export default {
    name: "User",
    data(){
        return{
            selectedItem: 1,
            todos: [],
            selected: [],
            selected_dialog: false,
        }
    },
    methods: {
        loadItems(id){
           if(this.selected_dialog === false){
               this.selected_dialog = !this.selected_dialog;
           }
           this.$store.dispatch('grab_todos_tasks', id).then(response => {
               this.selected = response;
           })
        },
        loadData(){
            //New request
            this.$store.dispatch('grab_todos_list').then(response => {
                let data = [];
                //Loop through returned data
                response.forEach(function (item){
                    //Search if json object iteration exists
                    let loop = data.find(element => element['table_id'] === item['table_id']);
                    //Add item to existing iteration
                    if(loop){
                        loop.task_name.push(item.task_name);
                    } else {
                        //Create new iteration
                        data.push({id: item['id'], table_id: item['table_id'], table_name: item['table_name'], username: item['username'], table_creation: item['table_creation'], task_name: [item['task_name']]})
                    }
                })
                this.todos = data;
                //  Error
            })
        }
    },
    mounted() {
        this.loadData();
    }
}
</script>

<style scoped>

</style>
