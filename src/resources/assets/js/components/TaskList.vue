<template>
    <ul v-if="tasks.length > 0">
        <li v-for="task in tasks">
            <a v-bind:href="taskUrl(task.id)">Tehtävä {{task.id}}, luotu {{task.created_at}}</a>
        </li>
    </ul>
    <div v-else-if="tasks.length === 0">
        <p>Ei tehtäviä</p>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                tasks: []
            }
        },

        mounted() {
            axios.get('/user/tasks').then(resp => this.tasks = resp.data);
        },

        methods: {
            taskUrl(id) {
                return '/task/' + id + '/edit';
            }
        }
    }
</script>