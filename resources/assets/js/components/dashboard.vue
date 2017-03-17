<template>
    <div>
        <h1>dashboard</h1>

        <div>
            <input type="text" placeholder="name" v-model="recordToCreate.name">
            <input type="date" v-model="recordToCreate.started_at">
            <select v-model="recordToCreate.score">
                <option value="-20">very negative</option>
                <option value="-10">negative</option>
                <option value="0">neutral</option>
                <option value="10">positive</option>
                <option value="20">very positive</option>
            </select>
            <input type="text" placeholder="duration" v-model="recordToCreate.duration">
            <button @click="createRecord">submit</button>
        </div>

        <ul>
            <li v-for="score in scores"><a :href="getRecordUrl(score)">{{ score.score }}</a></li>
        </ul>

        <div>
            <form @submit.prevent="createTodo">
                <input type="text" placeholder="add todo" v-model="todoToCreate.content">
                <select v-model="todoToCreate.score">
                    <option value="-20">very negative</option>
                    <option value="-10">negative</option>
                    <option value="0">neutral</option>
                    <option value="10">positive</option>
                    <option value="20">very positive</option>
                </select>
                <button type="submit">Add</button>
            </form>

            <ul>
                <li v-for="todo in todos">
                    {{ todo.content }}
                    {{ todo.score }}
                    <input type="checkbox" :checked="todo.is_finished" @click="toggleTodo(todo.id)">
                    <button @click="deleteTodo(todo.id)">×</button>
                </li>
            </ul>

            <button @click="getTodos(false)">unfinished Todos</button>
            <button @click="getTodos(true)">finished Todos</button>
        </div>

    </div>
</template>

<script>
import Vue from 'vue';
import api from '../store/api';

export default {
    name: 'dashboard_component',

    data() {
        return {
            scores: [],
            todos: [],
            todoToCreate: {
                "is_finished": false,
                "score": 0,
            },
            recordToCreate: {
                "score": 0,
            },
        }
    },

    beforeMount() {
        this.getScores();
        this.getTodos(false);
    },

    methods: {
        getScores() {
            api.getScores().then((response) => {
                this.scores = response.data[0];
            });
        },

        getRecordUrl(score) {
            return `/#/records/?date=${score.date}`;
        },

        createRecord() {
            api.createRecord(this.recordToCreate).then(response => {
                this.recordToCreate = {
                    "score": 0,
                };
                this.getScores();
            });
        },

        getTodos(isFinished) {
            isFinished = isFinished ? 1 : 0;
            api.getTodos(isFinished).then(response => {
                this.todos = response.data;
            });
        },

        createTodo() {
            api.createTodo(this.todoToCreate).then(response => {
                this.todoToCreate = {
                    "content": "",
                    "is_finished": false,
                    "score": 0,
                };
                this.getTodos(false);
            });
        },

        toggleTodo(id) {
            let isFinished = event.target.checked;
            api.updateTodo(id, {"is_finished": isFinished}).then(response => {
                this.getTodos(!isFinished);
            });
        },

        deleteTodo(id) {
            api.deleteTodo(id).then(response => {
                this.getTodos(false);
            });
        },
    }
};
</script>

<style>
</style>