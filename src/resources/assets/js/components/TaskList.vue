<template>
    <ul v-if="tasks.length > 0">
        <li v-for="task in tasks">
            Tehtävä {{task.id}}, luotu {{task.created_at}}
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
        }
    }
</script>