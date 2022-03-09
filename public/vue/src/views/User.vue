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
            <v-col v-for="list in todos" :key="list.id" sm="12" lg="4" md="6">
                <v-card>
                    <v-card-title>
                       {{list.table_name}}
                        <v-spacer></v-spacer>

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
                        <v-btn text @click="add_row(list.id, 'open_menu')" color="primary">
                            Nieuw taak
                        </v-btn>
                        <v-btn v-if="list.task_name[0] !== null" @click="loadItems(list.table_id)" class="green--text" text>
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
                    <v-tab-item  :change="selectedItem" class="ma-2" v-for="(list) in selected"  :key="list.id" :value="'tab-'+list.id">
                        <v-card flat v-for="(item, key) in list.items" :key="key">
                            <v-row id="properties" @click="run_Click(item.id)" class="ma-2 text--black">
                                <v-col v-if="item.status && item.status !== 'archived'" class="green lighten-4" cols="12">
                                    <!--                                    name-->
                                    lol
                                </v-col>
                                <v-col v-if="!item.status && item.status !== 'archived'" class="red lighten-4" cols="12">
                                    <!--                                    name-->
                                    <h4>{{item.name}}</h4>
                                </v-col>
                                <v-col v-if="item.status === 'archived'" class="grey lighten-2" cols="12">
                                    <!--                                    name-->
                                    <h4 class="text-decoration-line-through font-weight-light">{{item.name}}</h4>
                                </v-col>
                            </v-row>
                        </v-card>
                        <v-spacer></v-spacer>

                        <v-btn text @click="add_item = !add_item" class="mt-2 float-end" color="primary">
                            Nieuw item toevoegen {{selectedItem}}
                        </v-btn>
                    </v-tab-item>
                </v-tabs>
            </v-sheet>
        </v-dialog>


        <v-dialog width="400" v-model="add_item">
            <v-card>
                <v-form ref="add_new_task">
                    <v-card-title>
                        Nieuwe item toevoegen
                    </v-card-title>
                    <v-card-text>
                        <v-text-field
                            v-model="create_option"
                            :rules="addOptionInput"
                            label="Name"
                            required
                        ></v-text-field>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="red"
                            text
                            @click="add_item = false"
                        >
                            Sluiten
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn v-on:keyup.enter="add_row()" @click="add_row()"
                               color="primary"
                               text
                        >Toevoegen
                        </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>

    </v-container>
    </v-app>
</template>

<script>
export default {
    name: "User",
    data(){
        return{
            selectedItem: '',
            todos: [],
            selected_item: [],
            selected: [],
            selected_dialog: false,
            add_item: false,
            create_option: '',
            addOptionInput: [
                v => !!v || 'Veld is leeg',
                v => v && v.length >= 3 || 'Minimaal 3 letters of cijfers',
                v => /^[a-zA-Z0-9]+$/.test(v)||'Alleen letters of cijfers toegestaan',
            ],
        }
    },
    methods: {
        loadItems(id){
           if(this.selected_dialog === false){
               this.selected_dialog = !this.selected_dialog;
           }
           this.$store.dispatch('grab_todos_tasks', id).then(response => {
               response.forEach( item => item.items = JSON.parse(item.items))
               this.selected = response;
               console.log(response);
           })
           // console.log(this.selected);
        },
        loadData(){
            //New request
            this.$store.dispatch('grab_todos_list').then(response => {
                console.log(response);
                let data = [];
                //Loop through returned data
                response.forEach(function (item){
                    //Search if json object iteration exists
                    let loop = data.find(element => element['table_name'] === item['table_name']);
                    //Add item to existing iteration
                    if(loop){
                        loop.task_name.push(item.task_name);
                    } else {
                        //Create new iteration
                        data.push({id: item['todo_id'], table_id: item['table_id'], table_name: item['table_name'], username: item['username'], table_creation: item['table_creation'], task_name: [item['task_name']]})
                    }
                })
                this.todos = data;
                console.log(data);
                //  Error
            })
        },
        add_row(index, action){
            if(action){
                this.selected = index;
                return this.add_item = !this.add_item;
            }
            if(this.$refs.add_new_task.validate()) {
               this.$store.dispatch('add_new_task', [this.selected, this.create_option]).then(() => {
                    this.add_item = false;
                    this.loadData();
               })
           }
        },
        add_new_item(){

        },
        run_Click(index){
            console.log(index)

        }
    },
    mounted() {
        this.loadData();
    }
}
</script>

<style scoped>
#properties:hover{
    cursor: pointer;
}
</style>
