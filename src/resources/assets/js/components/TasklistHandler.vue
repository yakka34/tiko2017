<template>
    <div>
        <div>
            <ul v-if="tasklists.length > 0">
                <li v-for="tasklist in tasklists">{{ tasklist.description }}</li>
            </ul>
            <div v-else-if="tasklists.length === 0">
                <p>Ei tehtävälistoja</p>
            </div>

            <div v-if="create" class="panel panel-primary">

                <div class="panel-heading">
                    Uusi tehtävälista
                </div>

                <div class="panel-body">

                    <div class="form-group" v-bind:class="{ 'has-error': errors.has('description') }">
                        <label for="description">Tehtävälistan kuvaus</label>
                        <textarea class="form-control" name="description" id="description" rows="5" v-model="description"></textarea>
                        <p v-if="errors.has('description')" class="help-block" v-text="errors.get('description')"></p>
                    </div>

                    <div>
                        <h4>Valitse tehtävät:</h4>
                        <select title="Tehtävät" @change="taskSelected" v-model="selectedTask">
                            <!--<option selected="selected">Valitse</option>-->
                            <option v-for="task in availableTasks" :value="task.id">Tehtävä {{task.id}}</option>
                        </select>
                    </div>

                    <div class="form-group" v-bind:class="{ 'has-error': errors.has('tasks') }">
                        <h4>Valitut tehtävät:</h4>
                        <ul v-if="selectedTasks.length > 0">
                            <li v-for="task in selectedTasks">
                                Tehtävä {{task.id}}
                                <a v-on:click.stop="removeSelectedTask(task.id)" href="#!" title="Poista valinta">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </a>
                            </li>
                        </ul>
                        <div v-else-if="selectedTasks.length === 0">
                            Valitse tehtäviä ylempää.
                        </div>
                        <p v-if="errors.has('tasks')" class="help-block" v-text="errors.get('tasks')"></p>
                    </div>

                </div>

                <div class="panel-footer">
                    <button class="btn btn-primary" v-on:click="saveTasklist">Tallenna</button>
                </div>

            </div>
        </div>

        <div>
            <button class="btn btn-primary" v-if="!create" v-on:click.stop="startCreate">Luo uusi tehtävälista</button>
        </div>
    </div>
</template>

<script>

    class Errors {
        constructor() {
            this.errors = {};
        }

        has(field) {
            return this.errors.hasOwnProperty(field);
        }

        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors;
        }
    }

    export default {

        data() {
            return {
                tasklists: [],
                tasks: [],

                description: '',
                availableTasks: [],
                selectedTask: 0,
                selectedTasks: [],
                create: false,

                errors: new Errors()
            }
        },

        mounted() {
            // Hae tehtävät
            axios.get('/user/tasks').then(resp => {
                this.tasks = resp.data;
                this.availableTasks = resp.data;
            });
            // Hae tehtävälistat
            axios.get('/user/tasklists').then(resp => this.tasklists = resp.data);
        },

        methods: {

            startCreate() {
                this.create = true;
                window.tinymce.init({ selector:'textarea', plugins:'image',invalid_elements: 'script'});
            },

            taskSelected() {
                if (typeof this.selectedTask === "undefined") {
                    return;
                }
                this.availableTasks = this.availableTasks.filter(task => {
                    // Luo uusi taulukko, joka sisältää tehtävät joiden ID ei ole selectedTask
                    return task.id !== this.selectedTask;
                });
                this.selectedTasks.push(this.tasks.filter(task => task.id === this.selectedTask)[0]);
            },

            removeSelectedTask(id) {
                this.selectedTasks = this.selectedTasks.filter(task => task.id !== id);
                this.availableTasks.push(this.tasks.filter(task => task.id === id)[0]);
            },

            saveTasklist() {
                axios.post('/missioncontrol/tasklist/create', {
                    description: this.description,
                    tasks: _.map(this.selectedTasks, 'id')
                })
                .then(resp => {
                    this.create = false;
                    this.tasklists.push(resp.data.tasklist);
                })
                .catch(err => {
                    this.errors.record(err.response.data);
                });
            }

        }
    }
</script>