<template>
    <div>
        <ul v-if="tasklists.length > 0">
            <li v-for="tasklist in tasklists">{{ tasklist.description }}</li>
        </ul>
        <div v-else-if="tasklists.length === 0">
            <p>Ei tehtävälistoja</p>
        </div>

        <button class="btn btn-primary" v-on:click="startTasklistCreate">Luo uusi tehtävälista</button>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                tasklists: [],
                tasks: []
            }
        },

        mounted() {
            // Hae tehtävät
            axios.get('/user/tasks').then(resp => this.tasks = resp.data);
            // Hae tehtävälistat
            axios.get('/user/tasklists').then(resp => this.tasklists = resp.data);
        },

        methods: {

            startTasklistCreate() {
                alert('Yay!');
            }

        }
    }
</script>