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
<!--                 Create Table-->
                 <v-btn @click="create_table_dialog = !create_table_dialog" class="mr-4" depressed>
                     creëren
                 </v-btn>
                 <v-btn depressed>
                     UITLOGGEN
                 </v-btn>
             </v-app-bar>
         </v-col>
       </v-row>
<!--        List fields of user-->
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
                                    <v-list-item-title>Taken: <span v-for="(item,index) in list.task_name" :key="index">| {{item}}</span></v-list-item-title>
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
                        <v-btn @click="remove_field(list.id)" class="red--text" text>
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



<!--        Dialog rename tab-->
        <v-dialog
            v-model="rename_dialog"
            max-width="400"
        >
            <v-card>
                <v-card-title>
                    Wijzig naam van tab
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-model="rename_name"
                            label="Name"
                            required
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="changeCardTitleComplete()" class="primary">
                        Create playlist
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>




<!--        Dialog selected field-->
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
                        <h1 class="black--text pa-3 font-weight-medium">Klik op een regel om te bewerken</h1>
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
                                    <td>{{ item.time }}</td>
                                </tr>
                            </template>

                        </v-data-table>
                        <v-spacer></v-spacer>
                        <v-btn text @click="add_item = !add_item" class="mt-2 float-end" color="primary">
                            Nieuw kaart toevoegen {{active_tab}}
                        </v-btn>
                        <!--                        Change card title-->
                        <v-btn text @click="changeCardTitle(active_tab)" class="mt-2 float-end" color="primary">
                            Naam van deze tab wijzigen
                        </v-btn>
                        <v-btn text @click="removeTab(active_tab)" class="mt-2 float-end" color="primary">
                            Deze tab verwijderen
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




<!--        Dialog add task-->
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





<!--        <v-dialog width="400" v-model="add_task">-->
<!--            <v-card>-->
<!--                <v-form ref="add_new_task">-->
<!--                    <v-card-title>-->
<!--                        Nieuwe taak toevoegen-->
<!--                    </v-card-title>-->
<!--                    <v-card-text>-->
<!--                        <v-text-field-->
<!--                            v-model="create_option"-->
<!--                            :rules="addOptionInput"-->
<!--                            label="Name"-->
<!--                            required-->
<!--                        ></v-text-field>-->
<!--                    </v-card-text>-->
<!--                    <v-card-actions>-->
<!--                        <v-btn-->
<!--                            color="red"-->
<!--                            text-->
<!--                            @click="add_item = false"-->
<!--                        >-->
<!--                            Sluiten-->
<!--                        </v-btn>-->
<!--                        <v-spacer></v-spacer>-->
<!--                        <v-btn v-on:keyup.enter="add_row()" @click="add_row()"-->
<!--                               color="primary"-->
<!--                               text-->
<!--                        >Toevoegen-->
<!--                        </v-btn>-->
<!--                    </v-card-actions>-->
<!--                </v-form>-->
<!--            </v-card>-->
<!--        </v-dialog>-->


<!--        Dialog edit overview task-->
        <v-dialog width="450" v-model="edit_task">
            <v-card v-if="selected_item[0]">
                <v-card-title v-if="!change_title">
                    <v-btn style="margin-left: -19px;" @click="change_title = !change_title" text>
                        <v-icon>mdi-pencil</v-icon>
                    </v-btn>
                    {{selected_item[0].name}}
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
                <v-btn v-if="!change_description" @click="change_description = true" text class="ml-1 d-inline-block">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <span v-if="!change_description" class="d-inline-flex">
                    <p style="width: 375px; font-size: 12px;">{{selected_item[0].description}}</p>
                </span>
                <span style="width: 80%" class="d-block ml-6" v-if="change_description">
                        <v-text-field
                            class="d-inline-block"
                            v-model="selected_item[0].description"
                            label="Description"
                            required
                        ></v-text-field>
                    <v-btn @click="change_description = false" text class="ml-1 d-inline-block">
                        <v-icon>mdi-check-bold</v-icon>
                    </v-btn>
                </span>
                <span v-if="selected_item[0].time && !change_time">
                    <p class="ml-6 d-inline-block">Tijd: {{selected_item[0].time}} minuten</p>
                <v-btn @click="change_time = true" text class="ml-1">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn @click="selected_item[0].time = ''" text class="ml-1">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                </span>
                <v-card-text>
                    <span class="d-inline-block" v-if="change_time">
                    <v-form v-model="valid_time">
                        <v-text-field
                            v-model="selected_item[0].time"
                            label="Time"
                            required
                            :rules="[(v) => /^(?![0])[0-9]+$/.test(v) || 'Alleen cijfers {telt als minuten, hoger dan 0}']"
                        ></v-text-field>
                    </v-form>
                </span>
                    <v-btn v-if="change_time" :disabled="!valid_time" @click="change_time = false" text class="d-inline-block">
                        <v-icon>mdi-check-bold</v-icon>
                    </v-btn>
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
                            <v-btn @click="removeOption(selected_item[0].id)" color="error" class="mt-1 ml-1 d-block">Verwijderen</v-btn>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn color="primary" @click="add_new_label = !add_new_label" text class="ml-1 d-block">Label toevoegen<v-icon>mdi-plus</v-icon>
                        </v-btn>
                        <v-btn v-if="!this.selected_item[0].time" color="primary" @click="add_new_time = !add_new_time" text class="ml-1 d-block">Tijd toevoegen<v-icon>mdi-plus</v-icon></v-btn>
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



<!--        Dialog add time-->
        <v-dialog
            v-model="add_new_time"
            max-width="400"
        >
            <v-card>
                <v-card-title>
                    Tijd toevoegen
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid_time">
                        <v-text-field
                            v-model="add_time"
                            label="Time"
                            required
                            :rules="[(v) => /^(?![0])[0-9]+$/.test(v) || 'Alleen cijfers {telt als minuten, hoger dan 0}']"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn :disabled="!valid_time" @click="add_new_time_to_field()" class="primary">
                        Add time
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>


<!--        Dialog create table-->
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
                    <v-btn @click="add_empty_task_field()"
                           color="primary"
                           text
                    >Toevoegen
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>



<!--        Dialog add new task-->
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



<!--        Dialog add new label-->
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
            valid_time: false,
            selected_item_index: null,
            selected_item: [],
            active_tab: 1,
            change_title: false,
            change_time: false,
            change_description: false,
            label_selected: '',
            selected_rows: [],
            add_new_time: false,
            add_time: '',
            selected: [],
            headers: [
                { text: 'ID', value: 'id' },
                { text: 'Name', value: 'name' },
                { text: 'Status', value: 'status' },
                { text: 'Time', value: 'time'}
            ],

            create_table_dialog: false,
            status_option: ['Actief', 'Inactief'],
            edit_task: false,
            rename_dialog: false,
            add_new_task: false,
            add_new_label: false,
            rename_name: '',
            label_option: ['primary', 'secondary', 'error', 'success', 'info'],
            selected_dialog: false,
            add_new_task_item_to_field: '',
            add_task: false,
            changeTab: null,
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
        //Load all fields from API request
        loadItems(id){
            this.selected_item_index = id;
           if(this.selected_dialog === false){
               this.selected_dialog = !this.selected_dialog;
           }
           this.$store.dispatch('grab_todos_tasks', id).then(response => {
               this.selected = response;
               this.add_new_task_item_to_field = '';
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
        //Add new task to field
        add_row(index, action){
            if(action){
                this.selected = index;
                return this.add_task = !this.add_task;
            }
            if(this.$refs.add_new_task.validate()) {
               this.$store.dispatch('add_new_task', [this.selected, this.create_option]).then(() => {
                    this.add_task = false;
                    this.add_new_task_item_to_field = '';
                    this.create_option = '';
                    this.loadData();
               })
           }
        },
        //Add new item to list
        add_new_item(){
            //Tab to ID only
            let removedText = this.active_tab.replace(/\D+/g, '');
            //Validate if tab is correct
            if(this.$refs.add_new_item[0].validate()) {
                //Add item
                this.$store.dispatch('add_new_item', [removedText, this.create_option]).then(() => {
                    this.add_item = false;
                    this.loadItems(this.selected_item_index);
                    this.add_new_task_item_to_field = '';
                    this.create_option = '';
                })
            }
        },
        //Grab single task option
        run_Click(index){
            this.edit_task = true;
            this.$store.dispatch('grab_single_item', index).then(response => {
                response.forEach( item => item.tasks = JSON.parse(item.tasks))
                response.forEach( item => item.label = JSON.parse(item.label))
                //Save JSON in variable
                this.selected_item = response;
                this.create_option = '';
            })
        },
        //Change title by ID
        changeCardTitle(index){
                this.changeTab = index;
                this.rename_dialog = !this.rename_dialog;
        },
        //Remove tab by ID
        removeTab(index){
            //Remove tab
            this.$store.dispatch('user_remove_tab', index).then(() => {
                this.loadItems(this.selected_item_index);
            })
        },
        //Submit changing card title
        changeCardTitleComplete(){
            this.$store.dispatch('change_card_title_card', [this.changeTab, this.rename_name]).then(() => {
                //Reload items after changing tab
                this.loadItems(this.selected_item_index);
                this.rename_dialog = false;
            })
        },
        toggle_checkbox(index){
            this.selected_item[0].tasks[index].active = !this.selected_item[0].tasks[index].active;
        },
        //Add time to a field
        add_new_time_to_field(){
            this.selected_item[0].time = this.add_time;
            this.add_new_time = false;
        },
        //Add label to field
        add_new_label_to_field(){
            if(this.$refs.new_label.validate()) {
                //Check if label field exists
                if(!this.selected_item[0].label){
                    this.selected_item[0].label = [];
                }
                //Add to label field
                this.selected_item[0].label.push({name: this.add_new_task_item_to_field, color: this.label_selected});
                this.add_new_label =  false;
                this.add_new_task_item_to_field = '';
                this.create_option = '';
            }
        },
        //Add task field
        add_empty_task_field(){
            if(this.$refs.new_veld.validate()) {
                this.$store.dispatch('create_todos_table', this.add_new_task_item_to_field).then(() => {
                    this.create_table_dialog = !this.create_table_dialog;
                    this.add_new_task_item_to_field = '';
                    this.create_option = '';
                    //Reload data
                    this.loadData();
                })
            }
        },
        //Remove field
        remove_field(index){
            this.$store.dispatch('remove_label', index).then(() => {
                //Reload data
                this.loadData();
            })
        },
        //Task to field
        add_to_task_field(){
            if(this.$refs.new_item.validate()){
                //Check if exists
                if(!this.selected_item[0].tasks){
                    this.selected_item[0].tasks = [];
                }
                //Push to JSON object
                this.selected_item[0].tasks.push({name: this.add_new_task_item_to_field, active: true});
                this.add_new_task = false;
                this.add_new_task_item_to_field = '';
                this.create_option = '';
            }
        },
        //Remove selected checkbox
        remove_checkbox(index){
            this.selected_item[0].tasks.splice(index,1);
        },
        //Remove selected label
        remove_label(index){
            this.selected_item[0].label.splice(index,1);

        },
        //Update changes of option
        updateOption(){
            this.$store.dispatch('update_options', this.selected_item).then(() => {
                //Reload items
                this.loadItems(this.selected_item_index);
                this.edit_task = false;
                this.change_title = false;
            })
        },
        //Remove an option from DB
        removeOption(index){
            this.$store.dispatch('remove_options', index).then(() => {
                this.loadItems(this.selected_item_index);
                this.edit_task = false;
            })
        }
    },
    mounted() {
        this.loadData();
    },
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
