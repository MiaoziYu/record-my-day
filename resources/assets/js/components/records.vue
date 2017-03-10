<template>
    <div>
        <h1>Records</h1>

        <div>
            <input type="text" name='name' placeholder="name" v-model="newRecord.name">
            <input type="date" name='started_at' placeholder="started_at" v-model="newRecord.started_at">
            <input type="text" name='score' placeholder="score" v-model="newRecord.score">
            <input type="text" name='duration' placeholder="duration" v-model="newRecord.duration">
            <button v-on:click="createRecord">submit</button>
        </div>

        <ul>
            <li v-for="record in records">
                <p>{{ record.name }}</p>
                <p>Score: {{ record.score }}</p>
            </li>
        </ul>
    </div>
</template>

<script>
import Vue from 'vue';
import api from '../store/api';

export default {
    name: 'records_component',

    data() {
        return {
            records: [],
            newRecord: {},
        }
    },

    beforeMount() {
        this.getRecords()
    },

    methods: {

        getRecords() {
            let date = window.location.hash.split('=').pop();
            api.getRecords(`&started_at=${date}`).then(response => {
                this.records = response.data;
            });
        },

        createRecord() {
            api.createRecord(this.newRecord).then((response) => {
                this.newRecord = {};
                this.getScores();
            })
        }

    }
};
</script>

<style>
</style>