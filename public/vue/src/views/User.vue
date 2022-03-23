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
                 <v-btn @click="create_table" class="mr-4" depressed>
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
                <v-tabs v-model="active_tab" background-color="teal darken-5" dark
                         next-icon="mdi-arrow-right-bold-box-outline"
                         prev-icon="mdi-arrow-left-bold-box-outline"
                         show-arrows>
                    <v-tabs-slider color="white"></v-tabs-slider>
                    <!--                    TABS SELECTED ITEMS-->
                    <v-tab v-for="list in selected" :key="list.id" :href="'#tab-'+list.id">
                        {{list.task_name}}
                    </v-tab>
                    <!--                    ITEMS SELECTED ITEMS-->
                    <v-tab-item :change="selectedItem" class="ma-2" v-for="(list) in selected"  :key="list.id" :value="'tab-'+list.id">
                        <v-data-table
                            dense
                            :headers="headers"
                            :items="list.options"
                            v-model="selected_rows"
                            item-key="name"
                            class="elevation-1"
                        >
                            <template v-slot:item="{item}">
                                <tr @click="run_Click(item.id)">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.status }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                        <v-spacer></v-spacer>

                        <v-btn text @click="add_item = !add_item" class="mt-2 float-end" color="primary">
                            Nieuw kaart toevoegen {{active_tab}}
                        </v-btn>

                        <v-dialog width="400" v-model="add_item">
                            <v-card>
                                <v-form ref="add_new_item">
                                    <v-card-title>
                                        Nieuwe kaart toevoegen
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
                                        <v-btn @click="add_new_item(list.id)"
                                               color="primary"
                                               text
                                        >Toevoegen
                                        </v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-card>
                        </v-dialog>
                    </v-tab-item>
                </v-tabs>
            </v-sheet>
        </v-dialog>
        <v-dialog width="400" v-model="add_task">
            <v-card>
                <v-form ref="add_new_task">
                    <v-card-title>
                        Nieuwe taak toevoegen
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



        <v-dialog width="450" v-model="edit_task">
            <v-card v-if="selected_item[0]">
                <v-card-title v-if="!change_title">
                    {{selected_item[0].name}}
                    <v-btn @click="change_title = !change_title" text class="ml-1">
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-title v-else>
                    <v-text-field
                        v-model="selected_item[0].name"
                        label="Titel"
                        :value="selected_item[0].name"
                        required
                    ></v-text-field>
                    <v-btn @click="change_title = !change_title" text class="ml-1">
                        <v-icon>mdi-check-bold</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6">
                            <v-select
                                v-model="selected_item[0].status"
                                :items="status_option"
                                label="Status"
                            ></v-select>
                            {{status_option.status}}
                        </v-col>
                        <v-col cols="6">
                            <v-btn @click="updateOption()" color="success" class="ml-1 d-block">Opslaan</v-btn>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn color="primary" @click="add_new_label = !add_new_label" text class="ml-1 d-block">Label toevoegen<v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-col cols="12">
                            <div>
                                <v-chip v-for="(label, index) in selected_item[0].label" :key="index"
                                        @click="remove_label(index)"
                                        class="ma-2"
                                    :color="label.color"
                                >
                                    {{label.name}}
                                </v-chip>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn color="warning" @click="add_new_task = !add_new_task" text class="ml-1 d-block">Taak toevoegen<v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-col cols="12" class=" lighten-3" >
                            <div v-for="(label, index) in selected_item[0].tasks" :key="index">
                                <v-checkbox class="d-inline-block" @click="toggle_checkbox(index)"
                                            :v-model="label.active"
                                            :input-value="label.active"
                                ></v-checkbox>
                                <v-btn @click="remove_checkbox(index)" text class="d-inline-block text-capitalize label_selected mb-2">{{label.name}}</v-btn>
                            </div>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog width="300" v-model="create_table_dialog">
            <v-card>
                <v-card-title>
                    Nieuwe veld creeeren.
                </v-card-title>
                <v-card-text>
                    <v-form
                        ref="new_veld"
                        lazy-validation
                    >
                        <v-text-field
                            :rules="addOptionInput"
                            v-model="add_new_task_item_to_field"
                            label="Omschrijving"
                            required
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="add_to_task_field()"
                           color="primary"
                           text
                    >Toevoegen
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog width="300" v-model="add_new_task">
            <v-card>
                <v-card-title>
                    Nieuwe taak toevoegen
                </v-card-title>
                <v-card-text>
                    <v-form
                        ref="new_item"
                        lazy-validation
                    >
                        <v-text-field
                            :rules="addOptionInput"
                            v-model="add_new_task_item_to_field"
                            label="Omschrijving"
                            required
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="add_to_task_field()"
                           color="primary"
                           text
                    >Toevoegen
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog width="300" v-model="add_new_label">
            <v-card>
                <v-card-title>
                    Nieuwe label toevoegen
                </v-card-title>
                <v-card-text>
                    <v-form
                        ref="new_label"
                        lazy-validation
                    >
                        <v-text-field
                            :rules="addOptionInput"
                            v-model="add_new_task_item_to_field"
                            label="Omschrijving"
                            required
                        ></v-text-field>
                        <v-select
                            :items="label_option"
                            :rules="labelInput"
                            label="Label"
                            v-model="label_selected"
                            required
                        ></v-select>
                    </v-form>
                    <div class="text-center">
                        <v-chip
                            class="ma-2"
                            color="primary"
                        >
                            Primary
                        </v-chip>

                        <v-chip
                            class="ma-2"
                            color="secondary"
                        >
                            Secondary
                        </v-chip>

                        <v-chip
                            class="ma-2"
                            color="error"
                            text-color="white"
                        >
                            Error
                        </v-chip>

                        <v-chip
                            class="ma-2"
                            color="success"
                            text-color="white"
                        >
                            Success
                        </v-chip>
                        <v-chip
                            class="ma-2"
                            color="info"
                            text-color="white"
                        >
                            Info
                        </v-chip>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="add_new_label_to_field()"
                           color="primary"
                           text
                    >Toevoegen
                    </v-btn>
                </v-card-actions>
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
            search: '',
            selected_item_index: null,
            selected_item: [],
            active_tab: 1,
            change_title: false,
            label_selected: '',
            selected_rows: [],
            selected: [],
            headers: [
                { text: 'ID', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Status', value: 'status' },
            ],
            create_table_dialog: false,
            status_option: ['Actief', 'Gesloten', 'Gearchiveerd'],
            edit_task: false,
            add_new_task: false,
            add_new_label: false,
            label_option: ['primary', 'secondary', 'error', 'success', 'info'],
            selected_dialog: false,
            add_new_task_item_to_field: '',
            add_task: false,
            add_item: false,
            create_option: '',
            labelInput: [
                v => !!v || 'Veld is leeg'
            ],
            addOptionInput: [
                v => !!v || 'Veld is leeg',
                v => v && v.length >= 3 || 'Minimaal 3 letters of cijfers',
                v => /^[a-zA-Z0-9 _]+$/.test(v)||'Alleen letters of cijfers toegestaan',
            ],
        }
    },
    methods: {
        loadItems(id){
            this.selected_item_index = id;
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
                response.map(function (item){
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
                //  Error
            })
        },
        add_row(index, action){
            if(action){
                this.selected = index;
                return this.add_task = !this.add_task;
            }
            if(this.$refs.add_new_task.validate()) {
               this.$store.dispatch('add_new_task', [this.selected, this.create_option]).then(() => {
                    this.add_task = false;
                    this.loadData();
               })
           }
        },
        add_new_item(){
            let removedText = this.active_tab.replace(/\D+/g, '');
            if(this.$refs.add_new_item[0].validate()) {
                this.$store.dispatch('add_new_item', [removedText, this.create_option]).then(() => {
                    this.add_item = false;
                    this.loadItems(this.selected_item_index);
                })
            }
        },
        run_Click(index){
            console.log(index);
            this.edit_task = true;
            this.$store.dispatch('grab_single_item', index).then(response => {
                response.forEach( item => item.tasks = JSON.parse(item.tasks))
                response.forEach( item => item.label = JSON.parse(item.label))
                this.selected_item = response;
            })
        },
        toggle_checkbox(index){
            this.selected_item[0].tasks[index].active = !this.selected_item[0].tasks[index].active;
        },
        add_new_label_to_field(){
            if(this.$refs.new_label.validate()) {
                if(!this.selected_item[0].label){
                    this.selected_item[0].label = [];
                }
                this.selected_item[0].label.push({name: this.add_new_task_item_to_field, color: this.label_selected});
                this.add_new_label =  false;
                this.add_new_task_item_to_field = '';
            }
        },
        add_to_task_field(){
            if(this.$refs.new_item.validate()){
                if(!this.selected_item[0].tasks){
                    this.selected_item[0].tasks = [];
                }
                this.selected_item[0].tasks.push({name: this.add_new_task_item_to_field, active: true});
                this.add_new_task = false;
                this.add_new_task_item_to_field = '';
            }
        },
        remove_checkbox(index){
            this.selected_item[0].tasks.splice(index,1);
        },
        create_table(){

        },
        remove_label(index){
            this.selected_item[0].label.splice(index,1);

        },
        updateOption(){
            this.$store.dispatch('update_options', this.selected_item).then(() => {
                this.loadItems(this.selected_item_index);
                this.edit_task = false;
                this.change_title = false;
            })
        }
    },
    mounted() {
        this.loadData();
    },
    watch: {
        selected_item: {
            handler: function (val, oldVal) {
                console.log(oldVal, val);
            },
}
    }
}
</script>

<style scoped>
#properties:hover{
    cursor: pointer;
}
.label_selected:hover{
    color: red;
}

</style>
